<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Nama-nama umum pelanggan Indonesia
     */
    private array $firstNames = [
        'Ahmad', 'Budi', 'Citra', 'Dewi', 'Eka', 'Fajar', 'Gita', 'Hendra',
        'Indah', 'Joko', 'Karina', 'Lina', 'Mira', 'Nanda', 'Oka', 'Putri',
        'Qianna', 'Rina', 'Siti', 'Toni', 'Udin', 'Vita', 'Wahyu', 'Xenia',
        'Yudi', 'Zara', 'Anita', 'Bambang', 'Cahyono', 'Dani', 'Erika',
        'Faisal', 'Galuh', 'Handoko', 'Irma', 'Jaka', 'Kusuma', 'Lusiana',
        'Magdalena', 'Novi', 'Oscar', 'Puspita', 'Rama', 'Sinta', 'Tara',
        'Umar', 'Vika', 'Wulandari', 'Yanuar', 'Zahra'
    ];

    private array $lastNames = [
        'Wijaya', 'Santoso', 'Kurniawan', 'Hidayat', 'Rahman', 'Setiawan',
        'Hartono', 'Winarno', 'Suryanto', 'Permata', 'Kusuma', 'Pratama',
        'Hermawan', 'Gunawan', 'Handayani', 'Suharto', 'Prabowo', 'Yudhoyono',
        'Soeharto', 'Soekarno', 'Haryanto', 'Supriyanto', 'Riyanto', 'Prihatno',
        'Ardianto', 'Budiman', 'Cahyono', 'Darwanto', 'Eka', 'Farros', 'Gunawan',
        'Hadi', 'Ismail', 'Jamaludin', 'Kemal', 'Lukas', 'Mahmud', 'Nurdin',
        'Osman', 'Parman', 'Qanita', 'Ridho', 'Syaiful', 'Taufik', 'Ulfah'
    ];

    private array $provinces = [
        '11', '12', '13', '14', '15', '16', '17', '18', '19', '21',
        '31', '32', '33', '34', '35', '36', '51', '52', '53', '61',
        '62', '63', '64', '65', '71', '72', '73', '74', '75', '76'
    ];

    public function run(): void
    {
        for ($i = 0; $i < 20; $i++) {
            User::create([
                'name' => $this->firstNames[array_rand($this->firstNames)] . ' ' . 
                          $this->lastNames[array_rand($this->lastNames)],
                'email' => 'customer' . ($i + 1) . '@example.com',
                'phone' => '08' . rand(10, 99) . rand(10000000, 99999999),
                'password' => bcrypt('password'),
                'role' => 'customer',
            ]);
        }
    }
}
