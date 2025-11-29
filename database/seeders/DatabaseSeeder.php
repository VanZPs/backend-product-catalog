<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            CategorySeeder::class,
            UserAdminSeeder::class,
            CustomerSeeder::class,
            SellerSeeder::class,
            ProductSeeder::class,
            ReviewSeeder::class,
        ]);
    }
}
