<?php

namespace Database\Factories;

use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransaksiFactory extends Factory
{
    protected $model = Transaksi::class;

    public function definition(): array
    {
        return [
            'order_id'          => 'ORD-' . $this->faker->unique()->numberBetween(1000, 9999),
            'user_id'           => User::factory(),
            'nama_penerima'     => $this->faker->name(),
            'telepon_penerima'  => $this->faker->phoneNumber(),
            'alamat_pengiriman' => $this->faker->address(),
            'status'            => 'pending',
            'total_harga'       => 100000,
        ];
    }

    public function success()
    {
        return $this->state(fn () => [
            'status' => 'success',
        ]);
    }

    public function failed()
    {
        return $this->state(fn () => [
            'status' => 'failed',
        ]);
    }
}
