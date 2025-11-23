<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            UserAdminSeeder::class,
            SampleUserSeeder::class,
            SampleSellerSeeder::class,
            SampleProductSeeder::class,
        ]);
    }
}
