<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence();
        return [
            //
            'title' => $title,
            'slug' => \Str::slug($title),
            'body' => fake()->paragraphs(5, true),
            'view_count' => fake()->numberBetween(0,1000),
        ];
    }
}
