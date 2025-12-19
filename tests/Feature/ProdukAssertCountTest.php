<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Produk;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProdukAssertCountTest extends TestCase
{
    use RefreshDatabase;

    public function test_menyimpan_produk_menggunakan_assert_count()
    {
        Produk::factory()->create();

        $this->assertCount(1, Produk::all());
    }

    public function test_menampilkan_produk_menggunakan_assert_count()
    {
        Produk::factory()->count(3)->create();

        $this->assertCount(3, Produk::all());
    }

    public function test_menghapus_produk_menggunakan_assert_count()
    {
        Produk::factory()->count(2)->create();

        $this->assertCount(2, Produk::all());

        Produk::first()->delete();

        $this->assertCount(1, Produk::all());
    }
}
