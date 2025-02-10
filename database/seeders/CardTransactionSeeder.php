<?php

namespace Database\Seeders;

use App\Models\CardTransaction;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CardTransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // Create dummy transactions for User 1
         CardTransaction::create([
            'user_id' => 5,
            'card_id' => 2, // Assume card_id 1 exists
            'uuid' => \Str::uuid(),
            'amount' => 100.50,
            'charge' => 2.00,
            'status' => 'completed',
            'type' => 'debit',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        CardTransaction::create([
            'user_id' => 5,
            'card_id' => 2, // Assume card_id 2 exists
            'uuid' => \Str::uuid(),
            'amount' => 250.00,
            'charge' => 5.00,
            'status' => 'pending',
            'type' => 'credit',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create dummy transactions for User 2
        CardTransaction::create([
            'user_id' => 2,
            'card_id' => 3, // Assume card_id 3 exists
            'uuid' => \Str::uuid(),
            'amount' => 75.00,
            'charge' => 1.50,
            'status' => 'failed',
            'type' => 'debit',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        CardTransaction::create([
            'user_id' => 2,
            'card_id' => 4, // Assume card_id 4 exists
            'uuid' => \Str::uuid(),
            'amount' => 500.00,
            'charge' => 10.00,
            'status' => 'completed',
            'type' => 'credit',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
