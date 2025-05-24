<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BlogPost>
 */
class BlogPostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // $title = $this->faker->sentence();
        // return [
        //     'user_id' => User::inRandomOrder()->first()->id,
        //     'category_id' => Category::inRandomOrder()->first()->id,
        //     'title' => $title,
        //     'slug' => Str::slug($title) . '-' . rand(1000, 9999),
        //     'photo' => "backend/assets/dist/img/photo1.png",
        //     'content' => fake()->paragraphs(5, true),
        //     'status' => fake()->boolean(),
        // ];
    }
}
