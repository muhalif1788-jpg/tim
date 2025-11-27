<?php
// database/seeders/UserSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@abonsapi.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '081234567890',
            'address' => 'Kantor Pusat Abon Sapi',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Customer Demo', 
            'email' => 'customer@example.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'phone' => '081298765432',
            'address' => 'Alamat Customer Demo',
            'email_verified_at' => now(),
        ]);

        $this->command->info('✅ Users created successfully!');
        $this->command->info('👤 Admin: admin@abonsapi.com / password');
        $this->command->info('👤 Customer: customer@example.com / password');
    }
}