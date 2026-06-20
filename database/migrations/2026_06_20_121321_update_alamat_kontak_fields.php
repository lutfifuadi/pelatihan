<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Mengubah label, placeholder, dan urutan (order) field pada section alamat_kontak
     * di tabel form_field_configs.
     */
    public function up(): void
    {
        // ---------------------------------------------------------------
        // 1. Update label & placeholder alamat_ktp
        // ---------------------------------------------------------------
        DB::table('form_field_configs')
            ->where('section', 'alamat_kontak')
            ->where('field_key', 'alamat_ktp')
            ->update([
                'label'       => 'Nama Jalan/Gang',
                'placeholder' => 'NAMA JALAN/GANG',
                'updated_at'  => now(),
            ]);

        // ---------------------------------------------------------------
        // 2. Update urutan (order) semua field alamat_kontak
        //    Urutan baru: dari global ke lokal
        // ---------------------------------------------------------------
        $orderUpdates = [
            'provinsi'      => 1,
            'kota'          => 2,
            'kecamatan_id'  => 3,
            'kelurahan_id'  => 4,
            'rt'            => 5,
            'rw'            => 6,
            'alamat_ktp'    => 7,
            'kodepos'       => 8,
            'whatsapp'      => 9,
            'email'         => 10,
            'link_medsos'   => 11,
        ];

        foreach ($orderUpdates as $fieldKey => $newOrder) {
            DB::table('form_field_configs')
                ->where('section', 'alamat_kontak')
                ->where('field_key', $fieldKey)
                ->update([
                    'order'      => $newOrder,
                    'updated_at' => now(),
                ]);
        }

        // ---------------------------------------------------------------
        // 3. Pastikan validation_rules provinsi & kota tetap 'readonly'
        //    (idempotent — aman dijalankan berulang)
        // ---------------------------------------------------------------
        DB::table('form_field_configs')
            ->where('section', 'alamat_kontak')
            ->where('field_key', 'provinsi')
            ->whereNull('validation_rules')
            ->update([
                'validation_rules' => 'readonly',
                'updated_at'       => now(),
            ]);

        DB::table('form_field_configs')
            ->where('section', 'alamat_kontak')
            ->where('field_key', 'kota')
            ->whereNull('validation_rules')
            ->update([
                'validation_rules' => 'readonly',
                'updated_at'       => now(),
            ]);
    }

    /**
     * Reverse the migrations.
     * Mengembalikan label, placeholder, dan urutan field alamat_kontak
     * ke keadaan semula.
     */
    public function down(): void
    {
        // ---------------------------------------------------------------
        // 1. Kembalikan label & placeholder alamat_ktp
        // ---------------------------------------------------------------
        DB::table('form_field_configs')
            ->where('section', 'alamat_kontak')
            ->where('field_key', 'alamat_ktp')
            ->update([
                'label'       => 'Alamat Lengkap',
                'placeholder' => null,
                'updated_at'  => now(),
            ]);

        // ---------------------------------------------------------------
        // 2. Kembalikan urutan (order) ke keadaan semula
        // ---------------------------------------------------------------
        $originalOrder = [
            'alamat_ktp'    => 1,
            'rt'            => 2,
            'rw'            => 3,
            'kecamatan_id'  => 4,
            'kelurahan_id'  => 5,
            'kota'          => 6,
            'provinsi'      => 7,
            'kodepos'       => 8,
            'whatsapp'      => 9,
            'email'         => 10,
            'link_medsos'   => 11,
        ];

        foreach ($originalOrder as $fieldKey => $originalOrderValue) {
            DB::table('form_field_configs')
                ->where('section', 'alamat_kontak')
                ->where('field_key', $fieldKey)
                ->update([
                    'order'      => $originalOrderValue,
                    'updated_at' => now(),
                ]);
        }
    }
};
