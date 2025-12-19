<?php

namespace Tests\Feature\Customer\Cart;

use Tests\TestCase;
use App\Models\User;
use App\Models\Cart;
use App\Models\Produk;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CartAssertEmptyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Cart kosong ketika user belum menambahkan produk
     * Menggunakan assertEmpty()
     */
    public function test_cart_kosong_menggunakan_assert_empty()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $carts = Cart::where('user_id', $user->id)->get();

        $this->assertEmpty($carts);
    }

    /**
     * Cart tidak kosong setelah produk ditambahkan
     * Menggunakan assertNotEmpty()
     */
    public function test_cart_tidak_kosong_setelah_tambah_produk()
    {
        $user = User::factory()->create();

        $produk = Produk::factory()->create([
            'stok' => 10,
            'status' => true
        ]);

        $this->actingAs($user);

        Cart::create([
            'user_id' => $user->id,
            'produk_id' => $produk->id,
            'quantity' => 2
        ]);

        $carts = Cart::where('user_id', $user->id)->get();

        $this->assertNotEmpty($carts);
    }

    /**
     * Cart kembali kosong setelah dikosongkan
     * Menggunakan assertEmpty()
     */
    public function test_cart_kosong_setelah_clear()
    {
        $user = User::factory()->create();

        $produk = Produk::factory()->create([
            'stok' => 5,
            'status' => true
        ]);

        $this->actingAs($user);

        Cart::create([
            'user_id' => $user->id,
            'produk_id' => $produk->id,
            'quantity' => 1
        ]);

        // Pastikan cart tidak kosong terlebih dahulu
        $this->assertNotEmpty(
            Cart::where('user_id', $user->id)->get()
        );

        // Kosongkan cart
        Cart::where('user_id', $user->id)->delete();

        // Cart harus kosong
        $this->assertEmpty(
            Cart::where('user_id', $user->id)->get()
        );
    }
}
