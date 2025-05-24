<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SiteSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            'logo' => 'uploads/logos/default_logo.png',
            'name' => 'MySite',
            'description' => 'This is a sample site description.',
            'email' => 'info@mysite.com',
            'phone' => '+1234567890',
            'street' => '123 Main St',
            'city' => 'Sample City',
            'country' => 'Sample Country',
            'x' => 'https://x.com/mysite',
            'facebook' => 'https://facebook.com/mysite',
            'linkedin' => 'https://linkedin.com/company/mysite',
            'youtube' => 'https://youtube.com/mysite',
            'instagram' => 'https://instagram.com/mysite',
            'created_at' => '2025-05-21 20:49:00',
            'updated_at' => '2025-05-21 20:49:00',
        ];
        DB::table('site_settings')->insert($data);
    }
}
