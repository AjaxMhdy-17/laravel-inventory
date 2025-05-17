<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BlogPostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BlogPost::factory(100)->create()->each(function ($post) {
            $tagIds = Tag::inRandomOrder()->take(rand(1, 3))->pluck('id');
            $post->tags()->attach($tagIds);
        });
    }
}
