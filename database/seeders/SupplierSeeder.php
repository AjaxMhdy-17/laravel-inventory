<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $suppliers = [
            [
                'name' => 'Alpha Supplies',
                'phone' => '+12345678901',
                'email' => 'contact@alphasupplies.com',
                'address' => '123 Alpha Street, Cityville',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Beta Traders',
                'phone' => '+19876543210',
                'email' => 'info@betatraders.com',
                'address' => '456 Beta Avenue, Townsville',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Gamma Wholesale',
                'phone' => '+11223344556',
                'email' => 'sales@gammawholesale.com',
                'address' => '789 Gamma Blvd, Villagecity',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Delta Distributors',
                'phone' => '+10987654321',
                'email' => 'support@deltadistributors.com',
                'address' => '321 Delta Road, Metrocity',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Epsilon Enterprises',
                'phone' => '+10123456789',
                'email' => 'hello@epsilonenterprises.com',
                'address' => '654 Epsilon Lane, Capitaltown',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        DB::table('suppliers')->insert($suppliers);
    }
}
