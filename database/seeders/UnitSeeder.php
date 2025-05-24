<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $now = Carbon::now();

        $units = [];

        for ($i = 0; $i < 5; $i++) {
            $units[] = [
                'name' => ucfirst($faker->unique()->word), // e.g., Kg, Liter, Piece
                'status' => $faker->boolean, // randomly true or false
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('units')->insert($units);
    }
}
