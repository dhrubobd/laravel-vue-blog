<?php

namespace Database\Seeders;

use App\Models\Bookmark;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class BookmarkSeeder extends Seeder
{
    public function run()
    {
        User::all()->each(function ($user) {
            $posts = Post::inRandomOrder()->take(rand(1, 5))->pluck('id');
            foreach ($posts as $post) {
                Bookmark::create([
                    'post_id' => $post,
                    'user_id' => $user->id
                ]);
            }
        });
    }
}
