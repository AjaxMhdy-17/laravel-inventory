<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $now = Carbon::now();

        $customers = [];
        for ($i = 0; $i < 20; $i++) {
            $customers[] = [
                'name' => $faker->name,
                'phone' => $faker->phoneNumber,
                'email' => $faker->email,
                'address' => $faker->address,
                'photo' => 'backend/assets/dist/img/avatar5.png', // Or a placeholder
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('customers')->insert($customers);
    }
}
