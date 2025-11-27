<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Elektronik', 'description' => 'Perangkat elektronik dan gadget'],
            ['name' => 'Fashion', 'description' => 'Pakaian, sepatu, dan aksesori'],
            ['name' => 'Rumah Tangga', 'description' => 'Barang-barang kebutuhan rumah tangga'],
            ['name' => 'Makanan', 'description' => 'Makanan dan minuman'],
            ['name' => 'Kesehatan', 'description' => 'Produk kesehatan dan kecantikan'],
            ['name' => 'Olahraga', 'description' => 'Perlengkapan olahraga dan fitness'],
            ['name' => 'Buku', 'description' => 'Buku dan literatur'],
            ['name' => 'Mainan', 'description' => 'Mainan anak-anak dan permainan'],
            ['name' => 'Otomotif', 'description' => 'Suku cadang dan aksesori kendaraan'],
            ['name' => 'Hobi', 'description' => 'Barang hobi dan koleksi'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}

