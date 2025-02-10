<?php

namespace App\Helpers;

class TableColumnsConfig
{
    public static function getColumnsToShow()
    {
        return [
            'admins' => [
                'columns' => ['first_name', 'last_name', 'email', 'phone', 'created_at'],
                'relations' => []
            ],
            'users' => [
                'columns' => ['first_name', 'last_name', 'email', 'phone', 'account_goal', 'status', 'created_at'],
                'relations' => []
            ],
            'addresses' => [
                'columns' => ['name', 'amount', 'province', 'city', 'street_name', 'postal_code', 'created_at'],
                'relations' => [
                    'user' => ['first_name as user_first_name', 'last_name as user_last_name', 'email as user_email']
                ]
            ],
            'card_transactions' => [
                'columns' => ['uuid', 'amount', 'charge', 'status', 'type', 'created_at'],
                'relations' => [
                    'user' => ['first_name as user_first_name', 'last_name as user_last_name', 'email as user_email'],
                    'card' => ['name as card_name', 'card_number']
                ]
            ],
            'wallet_transactions' => [
                'columns' => ['uuid', 'amount', 'charge', 'status', 'type', 'details', 'created_at'],
                'relations' => [
                    'user' => ['first_name as user_first_name', 'last_name as user_last_name', 'email as user_email'],
                    'wallet' => ['uuid as wallet_uuid', 'balance as wallet_balance']
                ]
            ],
            'wallets' => [
                'columns' => ['uuid', 'balance', 'created_at'],
                'relations' => [
                    'user' => ['first_name as user_first_name', 'last_name as user_last_name', 'email as user_email']
                ]
            ],
            'teams' => [
                'columns' => ['uuid', 'name', 'members', 'created_at'],
                'relations' => [
                    'user' => ['first_name as user_first_name', 'last_name as user_last_name', 'email as user_email']
                ]
            ],
        ];
    }
}
