<?php

namespace Database\Seeders;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    public function run()
    {
        User::all()->each(function ($user) {
            Notification::create([
                'user_id' => $user->id,
                'type' => 'like',
                'message' => 'Someone liked your post.',
            ]);

            Notification::create([
                'user_id' => $user->id,
                'type' => 'comment',
                'message' => 'Someone commented on your post.',
            ]);

            Notification::create([
                'user_id' => $user->id,
                'type' => 'reply',
                'message' => 'Someone replied to your comment.',
            ]);
        });
    }
}
