<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $categories = [
            ['name' => 'Electronics', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Fashion', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Home & Garden', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Sports', 'status' => 0, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Toys', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Automotive', 'status' => 0, 'created_at' => $now, 'updated_at' => $now],
        ];

        DB::table('categories')->insert($categories);
    }
}
