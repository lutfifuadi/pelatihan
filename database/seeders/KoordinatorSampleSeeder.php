<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Kecamatan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class KoordinatorSampleSeeder extends Seeder
{
    /**
     * Buat 30 koordinator — 1 per kecamatan.
     * Idempotent — aman dijalankan berulang.
     */
    public function run(): void
    {
        // Ambil data kecamatan dari database
        $kecamatans = Kecamatan::select('id', 'name')->orderBy('id')->get();

        if ($kecamatans->isEmpty()) {
            $this->command->warn('⚠ Tidak ada data kecamatan! Jalankan KecamatanSeeder dulu.');
            return;
        }

        // Data koordinator per kecamatan
        $koordinatorData = [
            ['Andir', 'Asep Kuswara', '3273010101000101'],
            ['Antapani', 'Dedi Mulyadi', '3273010101000102'],
            ['Arcamanik', 'Entin Kartini', '3273010101000103'],
            ['Astanaanyar', 'Fitriani Nur', '3273010101000104'],
            ['Babakan Ciparay', 'Gugun Gunawan', '3273010101000105'],
            ['Bandung Kidul', 'Hendra Lesmana', '3273010101000106'],
            ['Bandung Kulon', 'Iis Ismawati', '3273010101000107'],
            ['Bandung Wetan', 'Jaja Sudarja', '3273010101000108'],
            ['Batununggal', 'Kartika Dewi', '3273010101000109'],
            ['Bojongloa Kaler', 'Lilis Suryani', '3273010101000110'],
            ['Bojongloa Kidul', 'Maman Rohman', '3273010101000111'],
            ['Buahbatu', 'Nani Rahmawati', '3273010101000112'],
            ['Cibeunying Kaler', 'Oman Somantri', '3273010101000113'],
            ['Cibeunying Kidul', 'Popon Puspita', '3273010101000114'],
            ['Cibiru', 'Qori Halimah', '3273010101000115'],
            ['Cicendo', 'Rudi Hermawan', '3273010101000116'],
            ['Cidadap', 'Sari Marlina', '3273010101000117'],
            ['Cinambo', 'Tatang Suherman', '3273010101000118'],
            ['Coblong', 'Ujang Kusnadi', '3273010101000119'],
            ['Gedebage', 'Vivi Virginia', '3273010101000120'],
            ['Kiaracondong', 'Wawan Setiawan', '3273010101000121'],
            ['Lengkong', 'Yuli Yuliani', '3273010101000122'],
            ['Mandalajati', 'Zaenal Arifin', '3273010101000123'],
            ['Panyileukan', 'Ani Rostiani', '3273010101000124'],
            ['Rancasari', 'Budi Rahayu', '3273010101000125'],
            ['Regol', 'Cucu Sumiati', '3273010101000126'],
            ['Sukajadi', 'Deden Rukmana', '3273010101000127'],
            ['Sukasari', 'Euis Sarifah', '3273010101000128'],
            ['Sumur Bandung', 'Faisal Akbar', '3273010101000129'],
            ['Ujungberung', 'Gina Fitriani', '3273010101000130'],
        ];

        $created = 0;
        $updated = 0;

        foreach ($kecamatans as $kec) {
            // Cari data yang cocok dengan nama kecamatan
            $match = null;
            foreach ($koordinatorData as $kd) {
                if ($kd[0] === $kec->name) {
                    $match = $kd;
                    break;
                }
            }

            if (!$match) {
                $this->command->warn("⚠ Kecamatan '{$kec->name}' tidak punya data koordinator, dilewati.");
                continue;
            }

            $nama = $match[1];
            $nik = $match[2];
            $email = 'koor.' . strtolower(str_replace(' ', '', $kec->name)) . '@mail.com';
            $whatsapp = '628' . str_pad(rand(100000000, 999999999), 9, '0', STR_PAD_LEFT);

            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $nama,
                    'nik' => $nik,
                    'whatsapp' => $whatsapp,
                    'password' => Hash::make('password'),
                    'role' => 'koordinator',
                    'kecamatan_id' => $kec->id,
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]
            );

            if ($user->wasRecentlyCreated) {
                $created++;
            } else {
                // Update jika sudah ada
                $user->timestamps = false;
                $user->kecamatan_id = $kec->id;
                $user->is_active = true;
                $user->name = $nama;
                $user->nik = $nik;
                $user->save();
                $user->timestamps = true;
                $updated++;
            }
        }

        $this->command->info("✓ 30 koordinator berhasil diproses! ({$created} baru, {$updated} update)");
    }
}
