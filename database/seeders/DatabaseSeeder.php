<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Package;
use App\Models\ContactInformation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seeding Users (Admin & Customer)
        User::create([
            'name' => 'Admin Gili Diving',
            'email' => 'admin@gilidiving.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@gmail.com',
            'password' => Hash::make('budi123'),
            'role' => 'customer',
        ]);

        // Seeding Packages
        Package::create([
            'nama_paket' => 'Paket Beginner (Pemula)',
            'kategori' => 'beginner',
            'deskripsi' => 'Paket menyelam yang didesain khusus bagi pemula yang belum pernah mencoba diving sama sekali. Anda akan mendapatkan pelatihan singkat dan simulasi di kolam/pantai dangkal sebelum menyelam bersama instruktur.',
            'fasilitas' => "Instruktur Bersertifikat PADI\nPeralatan Diving Lengkap\nPelatihan Dasar Sebelum Menyelam\nDokumentasi Foto Underwater\nSertifikat Pengalaman",
            'harga' => 750000.00,
            'durasi' => '2 Jam (1x Dive)',
            'gambar' => 'beginner.jpg',
        ]);

        Package::create([
            'nama_paket' => 'Paket Intermediate (Menengah)',
            'kategori' => 'intermediate',
            'deskripsi' => 'Ditujukan bagi customer yang telah memiliki pengalaman dasar diving atau sertifikat Open Water. Kita akan mengunjungi beberapa spot terumbu karang eksotis dengan arus sedang.',
            'fasilitas' => "Pemandu Divemaster Berpengalaman\nPeralatan Diving Lengkap\nPerjalanan Boat Bersama\n2 Spot Diving (Turtle Point & Halik Reef)\nSnack & Air Mineral",
            'harga' => 1200000.00,
            'durasi' => '4 Jam (2x Dive)',
            'gambar' => 'intermediate.jpg',
        ]);

        Package::create([
            'nama_paket' => 'Paket Professional',
            'kategori' => 'professional',
            'deskripsi' => 'Khusus bagi penyelam berlisensi (Advanced Open Water ke atas) yang ingin menjelajahi kedalaman ekstrim dan spot premium Gili Trawangan untuk melihat hiu, penyu raksasa, dan terumbu karang dalam.',
            'fasilitas' => "Private Divemaster\nPeralatan Diving Premium & Opsi Nitrox\nPrivate Speedboat\n3 Spot Diving Premium (Shark Point, Deep Turbo, Sunset Reef)\nSnack Box & Minuman Dingin\nDokumentasi Video GoPro Hero 12",
            'harga' => 1800000.00,
            'durasi' => '6 Jam (3x Dive)',
            'gambar' => 'professional.jpg',
        ]);

        // Seeding Contact Information
        ContactInformation::create([
            'alamat' => 'Gili Trawangan, Lombok Utara, Nusa Tenggara Barat, Indonesia',
            'nomor_hp' => '081234567890',
            'jam_buka' => '08.00 - 20.00 WITA',
            'email' => 'info@gilidiving.com',
        ]);
    }
}
