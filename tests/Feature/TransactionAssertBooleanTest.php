<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\User;
use App\Models\Transaksi;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TransactionAssertBooleanTest extends TestCase
{
    use RefreshDatabase;

    /**
     * assertTrue()
     * Mengecek bahwa status transaksi success
     */
    public function test_assert_true_transaction_success_status()
    {
        // Membuat admin
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        // Membuat transaksi dengan status success
        $transaction = Transaksi::factory()->create([
            'status' => 'success'
        ]);

        // ASSERT: status transaksi bernilai success
        $this->assertTrue($transaction->status === 'success');
    }

    /**
     * assertFalse()
     * Mengecek bahwa status transaksi bukan pending
     */
    public function test_assert_false_transaction_pending_status()
    {
        // Membuat transaksi dengan status failed
        $transaction = Transaksi::factory()->create([
            'status' => 'failed'
        ]);

        // ASSERT: status transaksi bukan pending
        $this->assertFalse($transaction->status === 'pending');
    }
}