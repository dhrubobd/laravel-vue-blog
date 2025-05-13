<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    public function run()
    {
        $tags = ['Laravel', 'VueJS', 'JavaScript', 'PHP', 'CSS', 'HTML'];
        foreach ($tags as $tag) {
            Tag::create(['name' => $tag]);
        }
    }
}
