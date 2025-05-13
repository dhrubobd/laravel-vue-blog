<?php

namespace Database\Seeders;

use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class LikeSeeder extends Seeder
{
    public function run()
    {
        Post::all()->each(function ($post) {
            $users = User::inRandomOrder()->take(rand(1, 5))->pluck('id');
            foreach ($users as $user) {
                Like::create([
                    'post_id' => $post->id,
                    'user_id' => $user
                ]);
            }
        });
    }
}
