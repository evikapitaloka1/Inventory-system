<?php

namespace Database\Seeders;

use App\Models\Borrowing;
use App\Models\BorrowingDetail;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class BorrowingSeeder extends Seeder
{
    public function run(): void
    {
        $staff = User::where('email', 'staff@inventaris.test')->first();
        $admin = User::where('email', 'admin@inventaris.test')->first();

        $laptop = Product::where('kode_barang', 'ELK-001')->first();
        $proyektor = Product::where('kode_barang', 'ELK-002')->first();

        $b1 = Borrowing::updateOrCreate(
            ['kode_peminjaman' => 'PJM-0001'],
            [
                'nama_peminjam' => 'Budi Staff Gudang',
                'user_id' => $staff->id,
                'tanggal_pinjam' => now()->subDays(10),
                'tanggal_kembali_rencana' => now()->subDays(3),
                'tanggal_kembali' => now()->subDays(3),
                'status' => 'dikembalikan',
                'approved_by' => $admin->id,
            ]
        );
        BorrowingDetail::updateOrCreate(
            ['borrowing_id' => $b1->id, 'product_id' => $laptop->id],
            ['jumlah' => 1, 'kondisi_saat_kembali' => 'baik']
        );

        $b2 = Borrowing::updateOrCreate(
            ['kode_peminjaman' => 'PJM-0002'],
            [
                'nama_peminjam' => 'Budi Staff Gudang',
                'user_id' => $staff->id,
                'tanggal_pinjam' => now()->subDays(2),
                'tanggal_kembali_rencana' => now()->addDays(5),
                'status' => 'dipinjam',
                'approved_by' => $admin->id,
            ]
        );
        BorrowingDetail::updateOrCreate(
            ['borrowing_id' => $b2->id, 'product_id' => $proyektor->id],
            ['jumlah' => 1]
        );

        Borrowing::updateOrCreate(
            ['kode_peminjaman' => 'PJM-0003'],
            [
                'nama_peminjam' => 'Budi Staff Gudang',
                'user_id' => $staff->id,
                'tanggal_pinjam' => now(),
                'tanggal_kembali_rencana' => now()->addDays(3),
                'status' => 'pending',
            ]
        );
    }
}
