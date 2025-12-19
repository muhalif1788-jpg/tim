<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_can_create_user()
    {
        // Login sebagai admin
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        // Data user baru
        $response = $this->post(route('admin.user.store'), [
            'name' => 'Budi',
            'email' => 'budi@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'customer',
            'phone' => '08123456789',
            'address' => 'Makassar'
        ]);

        // Ambil user dari database
        $user = User::firstWhere('email', 'budi@test.com');

        // ASSERT EQUALS
        $this->assertEquals('Budi', $user->name);
        $this->assertEquals('budi@test.com', $user->email);
        $this->assertEquals('customer', $user->role);
        $this->assertEquals('08123456789', $user->phone);
        $this->assertEquals('Makassar', $user->address);

        // Pastikan password ter-hash
        $this->assertTrue(Hash::check('password123', $user->password));
    }

    /** @test */
    public function admin_can_update_user()
    {
    $admin = User::factory()->create(['role' => 'admin']);
    $this->actingAs($admin);

    $user = User::factory()->create([
        'name' => 'Lama',
        'email' => 'lama@test.com',
        'role' => 'customer'
    ]);

    $this->put(route('admin.user.update', $user->id), [
        'name' => 'Nama Baru',
        'email' => 'baru@test.com',
        'role' => 'admin',
        'phone' => '089999999',
        'address' => 'Parepare'
    ]);

    $user->refresh();

    // ASSERT EQUALS
    $this->assertEquals('Nama Baru', $user->name);
    $this->assertEquals('baru@test.com', $user->email);
    $this->assertEquals('admin', $user->role);
    $this->assertEquals('089999999', $user->phone);
    $this->assertEquals('Parepare', $user->address);
}

/** @test */
public function admin_can_delete_user()
{
    $admin = User::factory()->create(['role' => 'admin']);
    $this->actingAs($admin);

    $user = User::factory()->create();

    $this->delete(route('admin.user.destroy', $user->id));

    // ASSERT EQUALS
    $this->assertEquals(0, User::where('id', $user->id)->count());
}


}