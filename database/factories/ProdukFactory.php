<?php

namespace Database\Factories;

use App\Models\Produk;
use App\Models\Kategori;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProdukFactory extends Factory
{
    protected $model = Produk::class;

    public function definition()
    {
        return [
            'nama_produk' => $this->faker->word(),
            'kategori_id' => Kategori::factory(),
            'harga' => $this->faker->numberBetween(10000, 1000000),
            'stok' => $this->faker->numberBetween(1, 50),
            'status' => true,
        ];
    }
}
