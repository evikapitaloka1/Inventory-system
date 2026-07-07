<?php

namespace Tests\Feature;

use App\Models\Borrowing;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BorrowingFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_staff_can_submit_a_borrowing_request(): void
    {
        $staff = User::factory()->staff()->create();
        $product = Product::factory()->create(['stok' => 10]);

        $response = $this->actingAs($staff)->post(route('borrowings.store'), [
            'nama_peminjam' => $staff->name,
            'tanggal_pinjam' => now()->format('Y-m-d'),
            'tanggal_kembali_rencana' => now()->addDays(3)->format('Y-m-d'),
            'products' => [$product->id],
            'quantities' => [2],
        ]);

        $response->assertRedirect(route('borrowings.index'));
        $this->assertDatabaseHas('borrowings', ['nama_peminjam' => $staff->name, 'status' => 'pending']);

        // Stok belum berkurang sebelum disetujui.
        $this->assertSame(10, $product->fresh()->stok);
    }

    public function test_admin_approving_a_borrowing_reduces_stock(): void
    {
        $admin = User::factory()->admin()->create();
        $staff = User::factory()->staff()->create();
        $product = Product::factory()->create(['stok' => 10]);

        $borrowing = Borrowing::create([
            'kode_peminjaman' => 'PJM-TEST-1',
            'nama_peminjam' => $staff->name,
            'user_id' => $staff->id,
            'tanggal_pinjam' => now(),
            'tanggal_kembali_rencana' => now()->addDays(3),
            'status' => 'pending',
        ]);
        $borrowing->details()->create(['product_id' => $product->id, 'jumlah' => 3]);

        $response = $this->actingAs($admin)->post(route('borrowings.approve', $borrowing));

        $response->assertRedirect();
        $this->assertSame('dipinjam', $borrowing->fresh()->status);
        $this->assertSame(7, $product->fresh()->stok);
    }

    public function test_staff_cannot_approve_a_borrowing(): void
    {
        $staff = User::factory()->staff()->create();
        $product = Product::factory()->create(['stok' => 10]);

        $borrowing = Borrowing::create([
            'kode_peminjaman' => 'PJM-TEST-2',
            'nama_peminjam' => $staff->name,
            'user_id' => $staff->id,
            'tanggal_pinjam' => now(),
            'tanggal_kembali_rencana' => now()->addDays(3),
            'status' => 'pending',
        ]);
        $borrowing->details()->create(['product_id' => $product->id, 'jumlah' => 1]);

        $this->actingAs($staff)->post(route('borrowings.approve', $borrowing))->assertStatus(403);
        $this->assertSame('pending', $borrowing->fresh()->status);
    }

    public function test_returning_a_borrowed_item_restocks_the_product(): void
    {
        $admin = User::factory()->admin()->create();
        $staff = User::factory()->staff()->create();
        $product = Product::factory()->create(['stok' => 5]);

        $borrowing = Borrowing::create([
            'kode_peminjaman' => 'PJM-TEST-3',
            'nama_peminjam' => $staff->name,
            'user_id' => $staff->id,
            'tanggal_pinjam' => now(),
            'tanggal_kembali_rencana' => now()->addDays(3),
            'status' => 'dipinjam',
        ]);
        $borrowing->details()->create(['product_id' => $product->id, 'jumlah' => 2]);

        // Ubah menjadi seperti ini:
$response = $this->actingAs($admin)->post(route('borrowings.return', $borrowing), [
    'kondisi_saat_kembali' => 'baik'
]);

        $response->assertRedirect();
        $this->assertSame('dikembalikan', $borrowing->fresh()->status);
        $this->assertSame(7, $product->fresh()->stok);
    }

 public function test_admin_can_reject_a_pending_borrowing(): void
    {
        $admin = User::factory()->admin()->create();
        $staff = User::factory()->staff()->create();
        $product = Product::factory()->create(['stok' => 5]);

        $borrowing = Borrowing::create([
            'kode_peminjaman' => 'PJM-TEST-4',
            'nama_peminjam' => $staff->name,
            'user_id' => $staff->id,
            'tanggal_pinjam' => now(),
            'tanggal_kembali_rencana' => now()->addDays(3),
            'status' => 'pending',
        ]);
        $borrowing->details()->create(['product_id' => $product->id, 'jumlah' => 1]);

        // --- BAGIAN INI YANG DIUBAH ---
        // Sisipkan array ['alasan_penolakan' => '...'] di dalam method post()
        $this->actingAs($admin)->post(route('borrowings.reject', $borrowing), [
            'alasan_penolakan' => 'Maaf, barang sedang dalam perbaikan dan tidak bisa dipinjam.'
        ])->assertRedirect();
        // ------------------------------

        $this->assertSame('ditolak', $borrowing->fresh()->status);
        $this->assertSame(5, $product->fresh()->stok);
    }
}
