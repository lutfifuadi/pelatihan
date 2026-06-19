<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // ---------------------------------------------------------------
        // 1. Nonaktifkan field lama (jaga data file tetap ada di storage)
        // ---------------------------------------------------------------
        DB::table('form_field_configs')
            ->where('section', 'dokumen')
            ->whereIn('field_key', ['foto_profil', 'scan_ktp'])
            ->update(['is_active' => false]);

        // ---------------------------------------------------------------
        // 2. Hapus field config lama (foto_profil & scan_ktp)
        // ---------------------------------------------------------------
        DB::table('form_field_configs')
            ->where('section', 'dokumen')
            ->whereIn('field_key', ['foto_profil', 'scan_ktp'])
            ->delete();

        // ---------------------------------------------------------------
        // 3. Insert / update field-field baru
        // ---------------------------------------------------------------
        $fields = [
            // --- PERTANYAAN UMUM (sub_bagian: pertanyaan_umum) ---
            [
                'section'          => 'dokumen',
                'field_key'        => 'pengetahuan_asep',
                'label'            => 'Apa yang kamu ketahui tentang Bapak H. Asep Mulyadi, S.H.?',
                'placeholder'      => 'Tulis jawaban anda...',
                'type'             => 'textarea',
                'is_required'      => true,
                'is_active'        => true,
                'order'            => 1,
                'width'            => 'full',
                'options_group'    => null,
                'validation_rules' => null,
                'show_if'          => null,
            ],
            [
                'section'          => 'dokumen',
                'field_key'        => 'alasan_pelatihan',
                'label'            => 'Sebutkan alasan mengikuti pelatihan tersebut.',
                'placeholder'      => 'Tulis jawaban anda...',
                'type'             => 'textarea',
                'is_required'      => true,
                'is_active'        => true,
                'order'            => 2,
                'width'            => 'full',
                'options_group'    => null,
                'validation_rules' => null,
                'show_if'          => null,
            ],
            [
                'section'          => 'dokumen',
                'field_key'        => 'pengalaman_bisnis',
                'label'            => 'Ceritakan pengalaman bisnis anda dalam bidang pelatihan tersebut.',
                'placeholder'      => 'Tulis jawaban anda...',
                'type'             => 'textarea',
                'is_required'      => true,
                'is_active'        => true,
                'order'            => 3,
                'width'            => 'full',
                'options_group'    => null,
                'validation_rules' => null,
                'show_if'          => null,
            ],

            // --- PERTANYAAN MINAT & USAHA (sub_bagian: minat_usaha) ---
            [
                'section'          => 'dokumen',
                'field_key'        => 'rencana_setelah_pelatihan',
                'label'            => 'Apa minat/rencana Anda kedepannya setelah mengikuti pelatihan tersebut?',
                'placeholder'      => 'Tulis jawaban anda...',
                'type'             => 'textarea',
                'is_required'      => true,
                'is_active'        => true,
                'order'            => 4,
                'width'            => 'full',
                'options_group'    => null,
                'validation_rules' => null,
                'show_if'          => null,
            ],
            [
                'section'          => 'dokumen',
                'field_key'        => 'punya_usaha',
                'label'            => 'Apakah anda sudah memiliki usaha yang sedang dijalankan?',
                'placeholder'      => null,
                'type'             => 'radio',
                'is_required'      => true,
                'is_active'        => true,
                'order'            => 5,
                'width'            => 'half',
                'options_group'    => 'punya_usaha',
                'validation_rules' => null,
                'show_if'          => null,
            ],
            [
                'section'          => 'dokumen',
                'field_key'        => 'jenis_usaha',
                'label'            => 'Jenis usaha yang sedang dijalankan saat ini?',
                'placeholder'      => null,
                'type'             => 'radio',
                'is_required'      => true,
                'is_active'        => true,
                'order'            => 6,
                'width'            => 'half',
                'options_group'    => 'jenis_usaha',
                'validation_rules' => null,
                'show_if'          => null,
            ],

            // --- PERTANYAAN USAHA & KENDALA (sub_bagian: usaha_kendala) ---
            [
                'section'          => 'dokumen',
                'field_key'        => 'usaha_dimiliki',
                'label'            => 'Usaha yang dimiliki?',
                'placeholder'      => 'Contoh: Hijab & Pakaian, Sate, Desainer dan lain lain',
                'type'             => 'radio_other',
                'is_required'      => true,
                'is_active'        => true,
                'order'            => 7,
                'width'            => 'half',
                'options_group'    => 'usaha_dimiliki',
                'validation_rules' => null,
                'show_if'          => null,
            ],
            [
                'section'          => 'dokumen',
                'field_key'        => 'nama_usaha',
                'label'            => 'Nama usaha yang sedang dijalankan?',
                'placeholder'      => 'Contoh: Warung sate pak budi dan lain lain',
                'type'             => 'radio_other',
                'is_required'      => true,
                'is_active'        => true,
                'order'            => 8,
                'width'            => 'half',
                'options_group'    => 'nama_usaha',
                'validation_rules' => null,
                'show_if'          => null,
            ],
            [
                'section'          => 'dokumen',
                'field_key'        => 'kendala_usaha',
                'label'            => 'Apa kendala yang dialami dalam menjalankan usaha anda?',
                'placeholder'      => 'Contoh: Sulit mendapatkan konsumen baru',
                'type'             => 'textarea',
                'is_required'      => false,
                'is_active'        => true,
                'order'            => 9,
                'width'            => 'full',
                'options_group'    => null,
                'validation_rules' => null,
                'show_if'          => null,
            ],

            // --- KONFIRMASI ---
            [
                'section'          => 'dokumen',
                'field_key'        => 'konfirmasi',
                'label'            => 'Saya menyatakan bahwa data yang diisi adalah benar',
                'placeholder'      => null,
                'type'             => 'checkbox',
                'is_required'      => true,
                'is_active'        => true,
                'order'            => 10,
                'width'            => 'full',
                'options_group'    => null,
                'validation_rules' => null,
                'show_if'          => null,
            ],
        ];

        foreach ($fields as $field) {
            DB::table('form_field_configs')->updateOrInsert(
                ['section' => $field['section'], 'field_key' => $field['field_key']],
                $field
            );
        }

        // ---------------------------------------------------------------
        // 4. Insert opsi baru ke master_options
        // ---------------------------------------------------------------
        $options = [
            // Group: punya_usaha
            ['group_key' => 'punya_usaha', 'label' => 'Sudah',   'value' => 'Sudah',   'order' => 1, 'is_active' => true],
            ['group_key' => 'punya_usaha', 'label' => 'Belum',   'value' => 'Belum',   'order' => 2, 'is_active' => true],

            // Group: jenis_usaha
            ['group_key' => 'jenis_usaha', 'label' => 'Belum Pernah', 'value' => 'Belum Pernah', 'order' => 1, 'is_active' => true],
            ['group_key' => 'jenis_usaha', 'label' => 'Fashion',      'value' => 'Fashion',      'order' => 2, 'is_active' => true],
            ['group_key' => 'jenis_usaha', 'label' => 'Kuliner',      'value' => 'Kuliner',      'order' => 3, 'is_active' => true],
            ['group_key' => 'jenis_usaha', 'label' => 'Jasa',         'value' => 'Jasa',         'order' => 4, 'is_active' => true],

            // Group: usaha_dimiliki
            ['group_key' => 'usaha_dimiliki', 'label' => 'Belum Pernah', 'value' => 'Belum Pernah', 'order' => 1, 'is_active' => true],

            // Group: nama_usaha
            ['group_key' => 'nama_usaha', 'label' => 'Belum Pernah', 'value' => 'Belum Pernah', 'order' => 1, 'is_active' => true],
        ];

        foreach ($options as $option) {
            DB::table('master_options')->updateOrInsert(
                ['group_key' => $option['group_key'], 'value' => $option['value']],
                $option
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // ---------------------------------------------------------------
        // Kembalikan field lama foto_profil & scan_ktp
        // ---------------------------------------------------------------
        DB::table('form_field_configs')->updateOrInsert(
            ['section' => 'dokumen', 'field_key' => 'foto_profil'],
            [
                'label'            => 'Foto Profil',
                'placeholder'      => null,
                'type'             => 'file',
                'is_required'      => true,
                'is_active'        => true,
                'order'            => 1,
                'width'            => 'half',
                'options_group'    => null,
                'validation_rules' => 'max:2048',
                'show_if'          => null,
                'updated_at'       => now(),
            ]
        );

        DB::table('form_field_configs')->updateOrInsert(
            ['section' => 'dokumen', 'field_key' => 'scan_ktp'],
            [
                'label'            => 'Scan KTP',
                'placeholder'      => null,
                'type'             => 'file',
                'is_required'      => true,
                'is_active'        => true,
                'order'            => 2,
                'width'            => 'half',
                'options_group'    => null,
                'validation_rules' => 'max:5120',
                'show_if'          => null,
                'updated_at'       => now(),
            ]
        );

        // ---------------------------------------------------------------
        // Kembalikan konfirmasi ke nilai semula (sebelum diubah)
        // ---------------------------------------------------------------
        DB::table('form_field_configs')
            ->where('section', 'dokumen')
            ->where('field_key', 'konfirmasi')
            ->update([
                'label'       => 'Pernyataan Data Benar',
                'placeholder' => null,
                'type'        => 'checkbox',
                'is_required' => true,
                'is_active'   => true,
                'order'       => 3,
                'width'       => 'full',
                'updated_at'  => now(),
            ]);

        // ---------------------------------------------------------------
        // Hapus field-field baru (selain konfirmasi yg sudah dikembalikan)
        // ---------------------------------------------------------------
        DB::table('form_field_configs')
            ->where('section', 'dokumen')
            ->whereIn('field_key', [
                'pengetahuan_asep',
                'alasan_pelatihan',
                'pengalaman_bisnis',
                'rencana_setelah_pelatihan',
                'punya_usaha',
                'jenis_usaha',
                'usaha_dimiliki',
                'nama_usaha',
                'kendala_usaha',
            ])
            ->delete();

        // ---------------------------------------------------------------
        // Hapus opsi-opsi baru
        // ---------------------------------------------------------------
        DB::table('master_options')
            ->whereIn('group_key', ['punya_usaha', 'jenis_usaha', 'usaha_dimiliki', 'nama_usaha'])
            ->delete();
    }
};
