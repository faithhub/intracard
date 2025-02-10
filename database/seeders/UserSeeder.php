<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'middle_name' => 'A.',
                'email' => 'john.doe@example.com',
                'password' => 'Oluwadara+1',
                'phone' => '1234567890',
                'status' => 'active',
                'account_details' => json_encode([
                    'goal' => 'rent',
                    'plan' => 'pay_rent',
                ]),
                'address_details' => json_encode([
                    'address' => '123 Rent Street',
                    'city' => 'Rentville',
                    'province' => 'Ontario',
                    'postalCode' => 'R1N T11',
                ]),
                'account_type' => 'rent',
                'landlord_or_finance_details' => json_encode([
                    'paymentMethod' => 'interac',
                    'email' => 'landlord@example.com',
                ]),
            ],
            [
                'first_name' => 'Jane',
                'last_name' => 'Smith',
                'middle_name' => 'B.',
                'email' => 'jane.smith@example.com',
                'password' => 'Oluwadara+1',
                'phone' => '0987654321',
                'status' => 'active',
                'account_details' => json_encode([
                    'goal' => 'rent',
                    'plan' => 'pay_rent_build',
                ]),
                'address_details' => json_encode([
                    'address' => '456 Build Avenue',
                    'city' => 'Buildtown',
                    'province' => 'Alberta',
                    'postalCode' => 'B1U L23',
                ]),
                'account_type' => 'rent',
                'landlord_or_finance_details' => json_encode([
                    'paymentMethod' => 'cheque',
                    'email' => 'landlord@example.com',
                ]),
            ],
            [
                'first_name' => 'Alice',
                'last_name' => 'Johnson',
                'middle_name' => 'C.',
                'email' => 'alice.johnson@example.com',
                'password' => 'Oluwadara+1',
                'phone' => '1122334455',
                'status' => 'active',
                'account_details' => json_encode([
                    'goal' => 'mortgage',
                    'plan' => 'pay_mortgage',
                ]),
                'address_details' => json_encode([
                    'address' => '789 Mortgage Lane',
                    'city' => 'Mortgagetown',
                    'province' => 'British Columbia',
                    'postalCode' => 'M1R T33',
                ]),
                'account_type' => 'mortgage',
                'landlord_or_finance_details' => json_encode([
                    'paymentMethod' => 'EFT',
                    'email' => 'mortgagecompany@example.com',
                ]),
            ],
            [
                'first_name' => 'Bob',
                'last_name' => 'Williams',
                'middle_name' => 'D.',
                'email' => 'bob.williams@example.com',
                'password' => 'Oluwadara+1',
                'phone' => '6677889900',
                'status' => 'active',
                'account_details' => json_encode([
                    'goal' => 'mortgage',
                    'plan' => 'pay_mortgage_build',
                ]),
                'address_details' => json_encode([
                    'address' => '101 Buildmort Lane',
                    'city' => 'Creditville',
                    'province' => 'Quebec',
                    'postalCode' => 'C1R E44',
                ]),
                'account_type' => 'mortgage',
                'landlord_or_finance_details' => json_encode([
                    'paymentMethod' => 'ACH',
                    'email' => 'mortgagecompany@example.com',
                ]),
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
