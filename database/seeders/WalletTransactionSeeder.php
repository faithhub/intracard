<?php

namespace Database\Seeders;

use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WalletTransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $wallets = Wallet::all();

        foreach ($wallets as $wallet) {
            WalletTransaction::factory()->count(5)->create([
                'user_id' => $wallet->user_id,
                'wallet_id' => $wallet->id,
            ]);
        }
    }
}
