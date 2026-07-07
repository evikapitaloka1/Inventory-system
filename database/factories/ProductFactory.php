<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'kode_barang' => 'BRG-'.$this->faker->unique()->numerify('####'),
            'nama_barang' => $this->faker->words(3, true),
            'category_id' => Category::factory(),
            'stok' => $this->faker->numberBetween(5, 50),
            'stok_minimum' => 5,
            'lokasi_penyimpanan' => 'Gudang '.$this->faker->word(),
            'kondisi_barang' => 'baik',
        ];
    }
}
