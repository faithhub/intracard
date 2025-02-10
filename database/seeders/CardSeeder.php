<?php

namespace Database\Seeders;

use App\Models\Card;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // Dummy card data
         Card::create([
            'name_on_card' => 'Visa Gold',
            'user_id' => 5, // Link to User 1
            'token' => "6011000000000004", // Link to User 2
            'card_number' => '4111111111111111', // Dummy card number
            'expiry_month' => '03',
            'expiry_year' => '2027',
            'cvv' => '123',
            'type' => 'credit', // Or 'debit'
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Card::create([
            'name_on_card' => 'MasterCard Platinum',
            'user_id' => 5, // Link to User 1
            'token' => "6011000000000004", // Link to User 2
            'card_number' => '5500000000000004', // Dummy card number
            'expiry_month' => '03',
            'expiry_year' => '2027',
            'cvv' => '456',
            'type' => 'credit',
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Card::create([
            'name_on_card' => 'American Express',
            'user_id' => 2, // Link to User 2
            'token' => "6011000000000004", // Link to User 2
            'card_number' => '340000000000009', // Dummy card number
            'expiry_month' => '03',
            'expiry_year' => '2027',
            'cvv' => '789',
            'type' => 'debit',
            'status' => 'inactive',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Card::create([
            'name_on_card' => 'Discover Card',
            'user_id' => 2, // Link to User 2
            'token' => "6011000000000004", // Link to User 2
            'card_number' => '6011000000000004', // Dummy card number
            'expiry_month' => '03',
            'expiry_year' => '2027',
            'cvv' => '321',
            'type' => 'debit',
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
