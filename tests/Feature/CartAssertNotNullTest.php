<?php

namespace Tests\Feature\Customer;

use Tests\TestCase;
use App\Models\User;
use App\Models\Cart;
use App\Models\Produk;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;

class CartAssertNotNullTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_add_product_to_cart()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $produk = Produk::factory()->create([
            'status' => true,
            'stok' => 10,
        ]);

        $response = $this->post(route('cart.store'), [
            'produk_id' => $produk->id,
            'quantity' => 2
        ]);

        $response->assertRedirect();

        // Ambil cart
        $cart = Cart::where('user_id', $user->id)
                    ->where('produk_id', $produk->id)
                    ->first();

        // ASSERT NOT NULL -> Cart harus ada
        $this->assertNotNull($cart, 'Cart seharusnya ada setelah produk ditambahkan');

        // ASSERT NULL -> Produk yang tidak ada di cart
        $missingCart = Cart::where('user_id', $user->id)
                           ->where('produk_id', 9999)
                           ->first();
        $this->assertNull($missingCart, 'Cart tidak boleh ada untuk produk yang belum ditambahkan');

        // Assert quantity sesuai
        $this->assertEquals(2, $cart->quantity);
    }

    /** @test */
    public function user_can_remove_product_from_cart()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $produk = Produk::factory()->create([
            'status' => true,
            'stok' => 5,
        ]);

        $cart = Cart::create([
            'user_id' => $user->id,
            'produk_id' => $produk->id,
            'quantity' => 1,
        ]);

        $response = $this->delete(route('cart.destroy', $cart->id));
        $response->assertRedirect();

        // ASSERT NULL -> Cart harus sudah dihapus
        $deletedCart = Cart::find($cart->id);
        $this->assertNull($deletedCart, 'Cart harus null setelah dihapus');
    }

    /** @test */
    public function user_cart_index_contains_items()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $produk = Produk::factory()->create(['status' => true]);
        Cart::create([
            'user_id' => $user->id,
            'produk_id' => $produk->id,
            'quantity' => 3
        ]);

        $response = $this->get(route('cart.index'));
        $response->assertOk();

        // Ambil cart dari view
        $carts = $response->viewData('carts');

        // ASSERT NOT NULL -> Cart tidak boleh kosong
        $this->assertNotNull($carts);
        $this->assertCount(1, $carts);
    }
}