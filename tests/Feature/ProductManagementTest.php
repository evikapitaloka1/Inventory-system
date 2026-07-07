<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_product_list(): void
    {
        $admin = User::factory()->admin()->create();
        Product::factory()->count(3)->create();

        $this->actingAs($admin)
            ->get(route('products.index'))
            ->assertStatus(200);
    }

    public function test_admin_can_create_a_product(): void
    {
        $admin = User::factory()->admin()->create();
        $category = Category::factory()->create();

        $response = $this->actingAs($admin)->post(route('products.store'), [
            'kode_barang' => 'TST-001',
            'nama_barang' => 'Barang Uji Coba',
            'category_id' => $category->id,
            'stok' => 10,
            'stok_minimum' => 2,
            'lokasi_penyimpanan' => 'Gudang Uji',
            'kondisi_barang' => 'baik',
        ]);

        $response->assertRedirect(route('products.index'));
        $this->assertDatabaseHas('products', ['kode_barang' => 'TST-001']);
    }

    public function test_staff_can_create_a_product(): void
    {
        $staff = User::factory()->staff()->create();
        $category = Category::factory()->create();

        $response = $this->actingAs($staff)->post(route('products.store'), [
            'kode_barang' => 'TST-002',
            'nama_barang' => 'Barang Staff',
            'category_id' => $category->id,
            'stok' => 5,
            'stok_minimum' => 1,
            'kondisi_barang' => 'baik',
        ]);

        $response->assertRedirect(route('products.index'));
        $this->assertDatabaseHas('products', ['kode_barang' => 'TST-002']);
    }

    public function test_manager_cannot_create_a_product(): void
    {
        $manager = User::factory()->manager()->create();
        $category = Category::factory()->create();

        $response = $this->actingAs($manager)->post(route('products.store'), [
            'kode_barang' => 'TST-003',
            'nama_barang' => 'Barang Manager',
            'category_id' => $category->id,
            'stok' => 5,
            'stok_minimum' => 1,
            'kondisi_barang' => 'baik',
        ]);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('products', ['kode_barang' => 'TST-003']);
    }

    public function test_manager_can_still_view_products(): void
    {
        $manager = User::factory()->manager()->create();
        Product::factory()->count(2)->create();

        $this->actingAs($manager)
            ->get(route('products.index'))
            ->assertStatus(200);
    }

    public function test_product_search_filters_results(): void
    {
        $admin = User::factory()->admin()->create();
        Product::factory()->create(['nama_barang' => 'Laptop Dell Special']);
        Product::factory()->create(['nama_barang' => 'Kursi Kantor']);

        $response = $this->actingAs($admin)->get(route('products.index', ['q' => 'Dell']));

        $response->assertSee('Laptop Dell Special');
        $response->assertDontSee('Kursi Kantor');
    }
}
