<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Seller;
use Illuminate\Support\Facades\Hash;

class SampleSellerSeeder extends Seeder
{
    public function run()
    {
        // Seller user
        $user = User::create([
            'name' => 'Sample Seller',
            'email' => 'seller@example.com',
            'password' => Hash::make('password'),
            'role' => 'seller',
            'phone' => '081234567890'
        ]);

        Seller::create([
            'user_id' => $user->id,
            'store_name' => 'Toko Contoh',
            'store_description' => 'Toko Contoh untuk demo.',
            'province_id' => '11',
            'city_id' => '1101',
            'district_id' => '1101010',
            'village_id' => '1101010001',
            'address' => 'Jl. Mawar No. 1',
            'rt' => '001',
            'rw' => '002',
            'ktp_number' => '1234567890123456',
            'ktp_file_path' => 'documents/ktp/sample.png',
            'pic_file_path' => 'images/pic/sample.png',
            'status' => 'approved',
            'is_active' => true,
            'verified_at' => now(),
        ]);
    }
}
