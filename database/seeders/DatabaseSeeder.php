<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed Kecamatan (Kota Bandung)
        $this->call(KecamatanSeeder::class);

        // Seed Kelurahan
        $this->call(KelurahanSeeder::class);

        // Create Admin
        User::create([
            'name' => 'Admin Aplikasi Pelatihan',
            'email' => 'admin@pelatihan.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
            'email_verified_at' => now(),
            'nik' => '1000000000000001',
            'whatsapp' => '81111111111',
        ]);

        // Create Instruktur
        User::create([
            'name' => 'Instruktur 1',
            'email' => 'instruktur@pelatihan.test',
            'password' => Hash::make('password'),
            'role' => 'instruktur',
            'is_active' => true,
            'email_verified_at' => now(),
            'nik' => '1000000000000002',
            'whatsapp' => '82222222222',
        ]);

        // Seed Peserta Demo
        $this->call(PesertaDemoSeeder::class);

        // Seed Dinas
        $this->call(DinasSeeder::class);

        // Seed Pelatihan
        $this->call(PelatihanSeeder::class);

        // Seed FAQs
        $this->call(FaqSeeder::class);

        // Seed Notification Templates
        $this->call(NotificationTemplateSeeder::class);

        // Note: Peserta akan mendaftar sendiri melalui halaman landing page
    }
}
