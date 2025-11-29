<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserAdminSeeder extends Seeder
{
    public function run()
    {
        // Check jika admin sudah ada
        if (User::where('email', 'admin@example.com')->exists()) {
            $this->command->info('Admin user sudah ada, skip seeding.');
            return;
        }

        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '08000000000'
        ]);
    }
}
