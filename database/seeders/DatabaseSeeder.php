<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory(10)->create();
        $this->call([
            SiteSettingSeeder::class , 
            SupplierSeeder::class , 
            CategoriesSeeder::class , 
            CustomerSeeder::class , 
            UnitSeeder::class , 
            ProductSeeder::class
        ]);

        //  CategorySeeder::class,
        //     TagSeeder::class,
        //     BlogPostSeeder::class,

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
