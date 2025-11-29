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
            [
                'name' => 'Elektronik & Gadget',
                'description' => 'Perangkat elektronik terkini, smartphone, laptop, dan aksesori teknologi modern 2025'
            ],
            [
                'name' => 'Fashion & Pakaian',
                'description' => 'Koleksi pakaian, sepatu, dan aksesori fashion terlengkap untuk semua gaya'
            ],
            [
                'name' => 'Rumah & Taman',
                'description' => 'Peralatan rumah tangga, dekorasi interior, dan perlengkapan taman berkualitas'
            ],
            [
                'name' => 'Kecantikan & Perawatan',
                'description' => 'Produk kecantikan, skincare premium, dan perawatan pribadi terpercaya'
            ],
            [
                'name' => 'Makanan & Minuman',
                'description' => 'Makanan, minuman, dan bahan makanan berkualitas premium pilihan'
            ],
            [
                'name' => 'Olahraga & Outdoor',
                'description' => 'Perlengkapan olahraga profesional dan gear outdoor untuk petualangan'
            ],
            [
                'name' => 'Buku & Alat Tulis',
                'description' => 'Buku terlengkap, alat tulis, dan perlengkapan sekolah/kantor berkualitas'
            ],
            [
                'name' => 'Mainan & Hobi',
                'description' => 'Mainan edukatif anak, action figure, dan perlengkapan hobi lengkap'
            ],
            [
                'name' => 'Otomotif & Aksesori',
                'description' => 'Aksesori kendaraan premium dan suku cadang berkualitas original'
            ],
            [
                'name' => 'Peralatan Dapur',
                'description' => 'Alat masak modern, peralatan dapur profesional, dan aksesori dapur inovatif'
            ],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['name' => $category['name']],
                ['description' => $category['description']]
            );
        }
    }
}

