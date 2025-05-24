<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Carbon\Carbon;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $now = Carbon::now();

        // Fetch existing IDs from related tables
        $userIds = DB::table('users')->pluck('id')->toArray();
        $supplierIds = DB::table('suppliers')->pluck('id')->toArray();
        $unitIds = DB::table('units')->pluck('id')->toArray();
        $categoryIds = DB::table('categories')->pluck('id')->toArray();

        // Check if related tables have data
        if (empty($userIds) || empty($supplierIds) || empty($unitIds) || empty($categoryIds)) {
            $this->command->error('Please seed users, suppliers, units, and categories tables first!');
            return;
        }

        $products = [];

        for ($i = 0; $i < 50; $i++) {
            $products[] = [
                'user_id' => $faker->randomElement($userIds),
                'supplier_id' => $faker->randomElement($supplierIds),
                'unit_id' => $faker->randomElement($unitIds),
                'category_id' => $faker->randomElement($categoryIds),
                'name' => ucfirst($faker->words($nb = 3, $asText = true)),
                'quantity' => $faker->numberBetween(1, 100) . ' ' . $faker->randomElement(['pcs', 'kg', 'liters']),
                'photo' => 'backend/assets/dist/img/avatar5.png', // placeholder photo path
                'status' => $faker->boolean(80), // 80% chance active
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('products')->insert($products);
    }
}
