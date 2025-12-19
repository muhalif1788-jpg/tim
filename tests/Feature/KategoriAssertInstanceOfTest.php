<?php

namespace Tests\Feature\Admin\Kategori;

use Tests\TestCase;
use App\Models\Kategori;
use Illuminate\Foundation\Testing\RefreshDatabase;

class KategoriAssertInstanceOfTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Menguji bahwa data kategori yang dibuat
     * merupakan instance dari model Kategori
     */
    public function test_menyimpan_kategori_instance_of_kategori()
    {
        $kategori = Kategori::create([
            'nama_kategori' => 'Elektronik'
        ]);

        $this->assertInstanceOf(Kategori::class, $kategori);
    }

    /**
     * Menguji bahwa data kategori yang diambil dari database
     * merupakan instance dari model Kategori
     */
    public function test_mengambil_kategori_instance_of_kategori()
    {
        Kategori::create([
            'nama_kategori' => 'Pakaian'
        ]);

        $kategori = Kategori::first();

        $this->assertInstanceOf(Kategori::class, $kategori);
    }

    /**
     * Menguji bahwa hasil pencarian kategori berdasarkan ID
     * merupakan instance dari model Kategori
     */
    public function test_find_kategori_by_id_instance_of_kategori()
    {
        $kategori = Kategori::create([
            'nama_kategori' => 'Aksesoris'
        ]);

        $foundKategori = Kategori::find($kategori->id);

        $this->assertInstanceOf(Kategori::class, $foundKategori);
    }
}
