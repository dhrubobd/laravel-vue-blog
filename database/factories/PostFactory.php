<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'title' => $this->faker->sentence,
            'content' => $this->faker->paragraphs(rand(3, 7), true),
            'image' => $this->faker->boolean(70) ? $this->faker->imageUrl(800, 600, 'nature', true) : null,
            'visibility' => $this->faker->randomElement(['public', 'private']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
