<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Elektronik', 'description' => 'Perangkat elektronik dan gadget kantor'],
            ['name' => 'Furniture', 'description' => 'Meja, kursi, dan perabot kantor'],
            ['name' => 'Alat Tulis Kantor', 'description' => 'ATK dan perlengkapan administrasi'],
            ['name' => 'Peralatan Jaringan', 'description' => 'Router, switch, kabel, dan aksesori jaringan'],
            ['name' => 'Kendaraan Operasional', 'description' => 'Kendaraan dinas dan operasional'],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(['name' => $category['name']], $category);
        }
    }
}
