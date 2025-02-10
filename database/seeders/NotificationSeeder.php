<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Notification;

class NotificationSeeder extends Seeder
{
    public function run()
    {
        Notification::create([
            'user_id' => 1,
            'title' => 'Payment Reminder',
            'message' => 'Your rent payment is due in 3 days.',
            'category' => 'general',
            'priority' => 'high',
            'is_read' => false,
        ]);

        // Notification::create([
        //     'admin_id' => 1,
        //     'title' => 'System Update',
        //     'message' => 'The system will undergo maintenance tomorrow at 2:00 AM.',
        //     'category' => 'general',
        //     'priority' => 'normal',
        //     'is_read' => true,
        // ]);
    }
}