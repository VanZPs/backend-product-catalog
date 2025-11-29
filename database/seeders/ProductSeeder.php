<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Seller;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Nama produk realistis Indonesia 2025
     */
    private array $productNames = [
        // Elektronik & Gadget
        'Smartphone Samsung Galaxy A55',
        'Laptop ASUS VivoBook 15',
        'Earbuds Wireless Beats Solo',
        'Power Bank 30000mAh Fast Charging',
        'Smartwatch Xiaomi Band 8',
        'Kamera Mirrorless Canon EOS R50',
        'Tablet iPad 2024',
        'Monitor Gaming 144Hz Curved',

        // Fashion
        'Kemeja Batik Modern Pria',
        'Celana Jeans Premium Panjang',
        'Sepatu Sneaker Casual',
        'Jaket Kulit Asli',
        'Dress Casual Wanita Modern',
        'Kaos Polos Katun Premium',
        'Hoodie Tebal Musim Dingin',
        'Sandal Jepit Kenyamanan Maksimal',

        // Rumah & Taman
        'Lampu LED Smart RGB',
        'Karpet Lantai Anti Slip',
        'Lemari Gantung Dinding',
        'Pot Tanaman Keramik Artistik',
        'Gorden Blackout Tahan Panas',
        'Rak Dinding Minimalis Modern',
        'Meja Makan Kayu Jati',
        'Kursi Gaming Ergonomis',

        // Kecantikan & Perawatan
        'Facial Wash Pagi Malam',
        'Serum Vitamin C Whitening',
        'Masker Wajah Sheet Mask',
        'Sunscreen SPF 50+ PA+++',
        'Toner Hydrating dan Menyegarkan',
        'Body Lotion Pelembab Kulit',
        'Shampoo Anti Ketombe Herbal',
        'Lipstik Tahan Lama 24 Jam',

        // Makanan & Minuman
        'Kopi Robusta Premium Indonesia',
        'Teh Hijau Organik Murni',
        'Snack Keripik Singkong Rasa',
        'Cokelat Hitam Organik',
        'Minyak Zaitun Extra Virgin',
        'Susu Kental Manis Maxi',
        'Kacang Almond Panggang Garlic',
        'Tepung Terigu Cakra Kembar',

        // Olahraga & Outdoor
        'Sepatu Lari Nike Running',
        'Yoga Mat Non-Slip Tebal',
        'Dumbbell Set Stainless Steel',
        'Tenda Camping Waterproof',
        'Tas Hiking 60L Outdoor',
        'Botol Minum Stainless Steel',
        'Raket Badminton Professional',
        'Bola Volley Official Size',

        // Buku & Alat Tulis
        'Buku Fiksi Fantasi Terlaris',
        'Novel Misteri Indonesia',
        'Buku Resep Masakan Rumahan',
        'Pena Gel Premium Hitam',
        'Buku Tulis Sekolah 80 Halaman',
        'Penggaris Panjang 30cm Plastik',
        'Stiker dan Stempel Dekoratif',
        'Buku Catatan Jurnal Leather',

        // Mainan & Hobi
        'Action Figure Superhero Lengkap',
        'Lego Sets Konstruksi Anak',
        'Board Game Keluarga Seru',
        'Puzzle 1000 Pieces Pemandangan',
        'Boneka Karakter Lucu Imut',
        'Mobil Remote Control Offroad',
        'Drone Mini Kamera 4K',
        'Trading Card Game Original',

        // Otomotif
        'Oli Motor Synthetic Premium',
        'Kampas Rem Motor Kualitas OEM',
        'Accu Mobil 12V 100Ah',
        'Lampu LED Mobil Terang',
        'Karpet Mobil Anti Slip',
        'Pembersih Interior Mobil',
        'Pelindung Jok Mobil Premium',
        'Pengharum Mobil Aroma Bunga',

        // Peralatan Dapur
        'Panci Set Teflond Berkualitas',
        'Spatula Silikon Heat Resistant',
        'Pisau Chef Stainless Steel',
        'Talenan Kayu Cutting Board',
        'Mixer Elektrik Powerful',
        'Rice Cooker Stainless Steel',
        'Blender Plastik Tahan Lama',
        'Microwave Dapur Compact',
    ];

    private array $descriptions = [
        'Produk berkualitas tinggi dengan desain modern dan fitur lengkap',
        'Terbuat dari bahan premium, tahan lama, dan ramah lingkungan',
        'Telah teruji dan terpercaya oleh jutaan pengguna di Indonesia',
        'Garansi resmi dari distributor, layanan purna jual terbaik',
        'Harga terjangkau untuk kualitas yang sangat memuaskan',
        'Cocok untuk penggunaan sehari-hari maupun kebutuhan khusus',
        'Desain ergonomis untuk kenyamanan maksimal pengguna',
        'Warna menarik dan pilihan ukuran yang lengkap tersedia',
    ];

    /**
     * Valid sample locations (province, city, district, village)
     * Format: province_code => [city_code, district_code, [village_codes...]]
     */
    private array $validLocations = [
        '11' => ['1101', '110101', ['1101012001', '1101012002', '1101012003']],
        '12' => ['1201', '120101', ['1201012001', '1201012002', '1201012003']],
        '13' => ['1301', '130101', ['1301012001', '1301012002', '1301012003']],
    ];

    private array $sampleImages = [
        'products/sample1.png',
        'products/sample2.png',
        'products/sample3.png',
    ];

    public function run(): void
    {
        $sellers = Seller::where('status', 'approved')->get();
        $categories = Category::all();

        if ($sellers->isEmpty() || $categories->isEmpty()) {
            $this->command->warn('Pastikan CategorySeeder dan SellerSeeder sudah dijalankan terlebih dahulu!');
            return;
        }

        // Generate 25 produk (lebih dari 20)
        $productCount = 0;
        foreach ($sellers as $seller) {
            // Setiap seller membuat 1-2 produk
            $numProducts = rand(1, 2);
            
            for ($i = 0; $i < $numProducts; $i++) {
                if ($productCount >= 25) break;

                $category = $categories->random();
                $productName = $this->productNames[$productCount % count($this->productNames)];
                $sampleImage = $this->sampleImages[$productCount % count($this->sampleImages)];
                
                Product::create([
                    'seller_id' => $seller->seller_id,
                    'name' => $productName,
                    'slug' => Str::slug($productName) . '-' . $productCount,
                    'description' => $this->descriptions[array_rand($this->descriptions)],
                    'category_id' => $category->category_id,
                    'price' => $this->generatePrice(),
                    'stock' => rand(5, 100),
                    'images' => json_encode([$sampleImage]),
                    'primary_image' => $sampleImage,
                    'visitor' => rand(10, 500),
                    'is_active' => rand(0, 1) ? true : false,
                ]);

                $productCount++;
            }

            if ($productCount >= 25) break;
        }

        // Update sellers dengan valid location codes
        $this->updateSellersWithValidLocations();

        $this->command->info('ProductSeeder selesai! Total produk: ' . $productCount);
    }

    /**
     * Update sellers dengan valid location codes (province, city, district, village)
     */
    private function updateSellersWithValidLocations(): void
    {
        $locationIndex = 0;
        $locations = array_values($this->validLocations);
        
        Seller::all()->each(function ($seller) use (&$locationIndex, $locations) {
            $location = $locations[$locationIndex % count($locations)];
            [$provinceCode, $cityCode, $districtCode, $villageCode] = [
                array_key_first($this->validLocations),
                $location[0],
                $location[1],
                $location[2][0],
            ];

            $seller->update([
                'province_id' => $provinceCode,
                'city_id' => $cityCode,
                'district_id' => $districtCode,
                'village_id' => $villageCode,
            ]);

            $locationIndex++;
        });

        $this->command->info('Sellers location codes updated dengan valid format!');
    }

    private function generatePrice(): float
    {
        $prices = [
            49999,   // ~50K
            99999,   // ~100K
            149999,  // ~150K
            199999,  // ~200K
            299999,  // ~300K
            499999,  // ~500K
            749999,  // ~750K
            999999,  // ~1JT
            1499999, // ~1.5JT
            1999999, // ~2JT
        ];

        return $prices[array_rand($prices)];
    }
}
