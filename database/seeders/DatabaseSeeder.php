<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Item;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Buat User Dummy
        $sarah = User::create([
            'name' => 'Sarah M.',
            'email' => 'sarah@example.com',
            'password' => Hash::make('password'),
            // 'location' => '0.8 miles away', // buka ini jika kamu menambahkan kolom location kemarin
        ]);

        $rafi = User::create([
            'name' => 'Rafi Aryandono',
            'email' => 'rafi@example.com',
            'password' => Hash::make('password'),
        ]);

        $kamu = User::create([
            'name' => 'Rafsyah Fachri', // Nama kamu untuk login nanti
            'email' => 'rafsyah@example.com',
            'password' => Hash::make('password'),
        ]);


        // 2. Buat Barang Milik Sarah (Target Barter)
        Item::create([
            'user_id' => $sarah->id,
            'title' => 'Vintage Espresso Machine',
            'description' => 'Used for 2 years, works perfectly. Makes great crema. I\'m moving to a smaller place and sadly need to part with it.',
            'category' => 'Electronics',
            'image_path' => 'espresso.jpg', // Nanti kita siapkan gambarnya
            'status' => 'available',
        ]);

        // 3. Buat Barang Milik Kamu (Untuk di Inventory Bawah)
        Item::create([
            'user_id' => $kamu->id,
            'title' => 'Acoustic Guitar',
            'description' => 'Gitar akustik mulus, suara garing.',
            'category' => 'Musical',
            'image_path' => 'guitar.jpg',
            'status' => 'available',
        ]);

        Item::create([
            'user_id' => $kamu->id,
            'title' => 'Potted Monstera',
            'description' => 'Tanaman hias monstera subur, daun segar.',
            'category' => 'Home & Garden',
            'image_path' => 'monstera.jpg',
            'status' => 'available',
        ]);

        Item::create([
            'user_id' => $kamu->id,
            'title' => 'Vintage Camera',
            'description' => 'Kamera analog jadul, fungsional.',
            'category' => 'Electronics',
            'image_path' => 'camera.jpg',
            'status' => 'available',
        ]);
    }
}