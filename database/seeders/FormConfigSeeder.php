<?php

namespace Database\Seeders;

use App\Models\MasterOption;
use App\Models\FormFieldConfig;
use Illuminate\Database\Seeder;

class FormConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->seedMasterOptions();
        $this->seedFormFieldConfigs();
    }

    /**
     * Seed master options (dropdown/radio values).
     */
    private function seedMasterOptions(): void
    {
        $options = [
            // Pendidikan Terakhir
            ...$this->buildOptions('pendidikan_terakhir', [
                ['label' => 'SD', 'value' => 'SD'],
                ['label' => 'SMP', 'value' => 'SMP'],
                ['label' => 'SMA', 'value' => 'SMA'],
                ['label' => 'D1', 'value' => 'D1'],
                ['label' => 'D2', 'value' => 'D2'],
                ['label' => 'D3', 'value' => 'D3'],
                ['label' => 'S1', 'value' => 'S1'],
                ['label' => 'S2', 'value' => 'S2'],
                ['label' => 'S3', 'value' => 'S3'],
            ]),

            // Status Pekerjaan
            ...$this->buildOptions('status_pekerjaan', [
                ['label' => 'Bekerja', 'value' => 'BEKERJA'],
                ['label' => 'Belum Bekerja', 'value' => 'BELUM BEKERJA'],
                ['label' => 'Pelajar/Mahasiswa', 'value' => 'PELAJAR/MAHASISWA'],
                ['label' => 'Wirausaha', 'value' => 'WIRAUSAHA'],
            ]),

            // Platform Media Sosial
            ...$this->buildOptions('platform_medsos', [
                ['label' => 'Instagram', 'value' => 'Instagram'],
                ['label' => 'Facebook', 'value' => 'Facebook'],
                ['label' => 'LinkedIn', 'value' => 'LinkedIn'],
                ['label' => 'Twitter', 'value' => 'Twitter'],
                ['label' => 'TikTok', 'value' => 'TikTok'],
                ['label' => 'YouTube', 'value' => 'YouTube'],
                ['label' => 'Website', 'value' => 'Website'],
                ['label' => 'Lainnya', 'value' => 'Lainnya'],
            ]),

            // Punya Usaha
            ...$this->buildOptions('punya_usaha', [
                ['label' => 'Sudah', 'value' => 'Sudah'],
                ['label' => 'Belum', 'value' => 'Belum'],
            ]),

            // Jenis Usaha
            ...$this->buildOptions('jenis_usaha', [
                ['label' => 'Belum Pernah', 'value' => 'Belum Pernah'],
                ['label' => 'Fashion', 'value' => 'Fashion'],
                ['label' => 'Kuliner', 'value' => 'Kuliner'],
                ['label' => 'Jasa', 'value' => 'Jasa'],
            ]),

            // Usaha Dimiliki
            ...$this->buildOptions('usaha_dimiliki', [
                ['label' => 'Belum Pernah', 'value' => 'Belum Pernah'],
            ]),

            // Nama Usaha
            ...$this->buildOptions('nama_usaha', [
                ['label' => 'Belum Pernah', 'value' => 'Belum Pernah'],
            ]),
        ];

        foreach ($options as $option) {
            MasterOption::create($option);
        }
    }

    /**
     * Helper to build option arrays with ordering.
     */
    private function buildOptions(string $groupKey, array $items): array
    {
        return array_map(function ($item, $index) use ($groupKey) {
            return [
                'group_key' => $groupKey,
                'label' => $item['label'],
                'value' => $item['value'],
                'order' => $index,
                'is_active' => true,
            ];
        }, $items, array_keys($items));
    }

    /**
     * Seed form field configurations.
     */
    private function seedFormFieldConfigs(): void
    {
        $fields = [
            // ============================================
            // Section: Data Pribadi
            // ============================================
            // 1. nama_lengkap
            [
                'section' => 'data_pribadi',
                'field_key' => 'nama_lengkap',
                'label' => 'Nama Lengkap Sesuai KTP',
                'placeholder' => null,
                'type' => 'text',
                'is_required' => true,
                'is_active' => true,
                'order' => 1,
                'width' => 'full',
                'options_group' => null,
                'validation_rules' => 'uppercase',
                'show_if' => null,
            ],
            // 2. nik
            [
                'section' => 'data_pribadi',
                'field_key' => 'nik',
                'label' => 'NIK KTP',
                'placeholder' => null,
                'type' => 'text',
                'is_required' => true,
                'is_active' => true,
                'order' => 2,
                'width' => 'half',
                'options_group' => null,
                'validation_rules' => 'digits_between:15,16',
                'show_if' => null,
            ],
            // 3. jenis_kelamin
            [
                'section' => 'data_pribadi',
                'field_key' => 'jenis_kelamin',
                'label' => 'Jenis Kelamin',
                'placeholder' => null,
                'type' => 'radio',
                'is_required' => true,
                'is_active' => true,
                'order' => 3,
                'width' => 'full',
                'options_group' => null,
                'validation_rules' => 'in:L,P',
                'show_if' => null,
            ],
            // 4. tempat_lahir
            [
                'section' => 'data_pribadi',
                'field_key' => 'tempat_lahir',
                'label' => 'Tempat Lahir',
                'placeholder' => null,
                'type' => 'text',
                'is_required' => true,
                'is_active' => true,
                'order' => 4,
                'width' => 'half',
                'options_group' => null,
                'validation_rules' => 'uppercase',
                'show_if' => null,
            ],
            // 5. tanggal_lahir
            [
                'section' => 'data_pribadi',
                'field_key' => 'tanggal_lahir',
                'label' => 'Tanggal Lahir',
                'placeholder' => null,
                'type' => 'select',
                'is_required' => true,
                'is_active' => true,
                'order' => 5,
                'width' => 'third',
                'options_group' => null,
                'validation_rules' => null,
                'show_if' => null,
            ],
            // 6. bulan_lahir
            [
                'section' => 'data_pribadi',
                'field_key' => 'bulan_lahir',
                'label' => 'Bulan Lahir',
                'placeholder' => null,
                'type' => 'select2',
                'is_required' => true,
                'is_active' => true,
                'order' => 6,
                'width' => 'third',
                'options_group' => null,
                'validation_rules' => null,
                'show_if' => null,
            ],
            // 7. tahun_lahir
            [
                'section' => 'data_pribadi',
                'field_key' => 'tahun_lahir',
                'label' => 'Tahun Lahir',
                'placeholder' => null,
                'type' => 'select2',
                'is_required' => true,
                'is_active' => true,
                'order' => 7,
                'width' => 'third',
                'options_group' => null,
                'validation_rules' => null,
                'show_if' => null,
            ],

            // ============================================
            // Section: Alamat & Kontak
            // ============================================
            // 1. alamat_ktp
            [
                'section' => 'alamat_kontak',
                'field_key' => 'alamat_ktp',
                'label' => 'Alamat Lengkap',
                'placeholder' => null,
                'type' => 'text',
                'is_required' => true,
                'is_active' => true,
                'order' => 1,
                'width' => 'full',
                'options_group' => null,
                'validation_rules' => 'uppercase',
                'show_if' => null,
            ],
            // 2. rt
            [
                'section' => 'alamat_kontak',
                'field_key' => 'rt',
                'label' => 'RT',
                'placeholder' => null,
                'type' => 'text',
                'is_required' => true,
                'is_active' => true,
                'order' => 2,
                'width' => 'third',
                'options_group' => null,
                'validation_rules' => 'digits',
                'show_if' => null,
            ],
            // 3. rw
            [
                'section' => 'alamat_kontak',
                'field_key' => 'rw',
                'label' => 'RW',
                'placeholder' => null,
                'type' => 'text',
                'is_required' => true,
                'is_active' => true,
                'order' => 3,
                'width' => 'third',
                'options_group' => null,
                'validation_rules' => 'digits',
                'show_if' => null,
            ],
            // 4. kecamatan_id
            [
                'section' => 'alamat_kontak',
                'field_key' => 'kecamatan_id',
                'label' => 'Kecamatan',
                'placeholder' => null,
                'type' => 'select2',
                'is_required' => true,
                'is_active' => true,
                'order' => 4,
                'width' => 'third',
                'options_group' => null,
                'validation_rules' => null,
                'show_if' => null,
            ],
            // 5. kelurahan_id
            [
                'section' => 'alamat_kontak',
                'field_key' => 'kelurahan_id',
                'label' => 'Kelurahan',
                'placeholder' => null,
                'type' => 'select2',
                'is_required' => true,
                'is_active' => true,
                'order' => 5,
                'width' => 'third',
                'options_group' => null,
                'validation_rules' => null,
                'show_if' => null,
            ],
            // 6. kota
            [
                'section' => 'alamat_kontak',
                'field_key' => 'kota',
                'label' => 'Kota/Kabupaten',
                'placeholder' => null,
                'type' => 'text',
                'is_required' => true,
                'is_active' => true,
                'order' => 6,
                'width' => 'third',
                'options_group' => null,
                'validation_rules' => 'readonly',
                'show_if' => null,
            ],
            // 7. provinsi
            [
                'section' => 'alamat_kontak',
                'field_key' => 'provinsi',
                'label' => 'Provinsi',
                'placeholder' => null,
                'type' => 'text',
                'is_required' => true,
                'is_active' => true,
                'order' => 7,
                'width' => 'third',
                'options_group' => null,
                'validation_rules' => 'readonly',
                'show_if' => null,
            ],
            // 8. kodepos
            [
                'section' => 'alamat_kontak',
                'field_key' => 'kodepos',
                'label' => 'Kode Pos',
                'placeholder' => null,
                'type' => 'text',
                'is_required' => true,
                'is_active' => true,
                'order' => 8,
                'width' => 'third',
                'options_group' => null,
                'validation_rules' => 'digits:5',
                'show_if' => null,
            ],
            // 9. whatsapp
            [
                'section' => 'alamat_kontak',
                'field_key' => 'whatsapp',
                'label' => 'Nomor WhatsApp',
                'placeholder' => null,
                'type' => 'tel',
                'is_required' => true,
                'is_active' => true,
                'order' => 9,
                'width' => 'half',
                'options_group' => null,
                'validation_rules' => null,
                'show_if' => null,
            ],
            // 10. email
            [
                'section' => 'alamat_kontak',
                'field_key' => 'email',
                'label' => 'Email',
                'placeholder' => null,
                'type' => 'email',
                'is_required' => true,
                'is_active' => true,
                'order' => 10,
                'width' => 'half',
                'options_group' => null,
                'validation_rules' => null,
                'show_if' => null,
            ],
            // 11. link_medsos
            [
                'section' => 'alamat_kontak',
                'field_key' => 'link_medsos',
                'label' => 'Link Media Sosial',
                'placeholder' => null,
                'type' => 'text',
                'is_required' => false,
                'is_active' => true,
                'order' => 11,
                'width' => 'full',
                'options_group' => null,
                'validation_rules' => null,
                'show_if' => null,
            ],

            // ============================================
            // Section: Pendidikan
            // ============================================
            // 1. pendidikan_terakhir
            [
                'section' => 'pendidikan',
                'field_key' => 'pendidikan_terakhir',
                'label' => 'Pendidikan Terakhir',
                'placeholder' => null,
                'type' => 'select2',
                'is_required' => true,
                'is_active' => true,
                'order' => 1,
                'width' => 'half',
                'options_group' => 'pendidikan_terakhir',
                'validation_rules' => null,
                'show_if' => null,
            ],
            // 2. tahun_lulus
            [
                'section' => 'pendidikan',
                'field_key' => 'tahun_lulus',
                'label' => 'Tahun Lulus',
                'placeholder' => null,
                'type' => 'select2',
                'is_required' => true,
                'is_active' => true,
                'order' => 2,
                'width' => 'half',
                'options_group' => null,
                'validation_rules' => null,
                'show_if' => null,
            ],
            // 3. nama_institusi
            [
                'section' => 'pendidikan',
                'field_key' => 'nama_institusi',
                'label' => 'Nama Institusi',
                'placeholder' => null,
                'type' => 'text',
                'is_required' => true,
                'is_active' => true,
                'order' => 3,
                'width' => 'half',
                'options_group' => null,
                'validation_rules' => 'uppercase',
                'show_if' => null,
            ],
            // 4. jurusan
            [
                'section' => 'pendidikan',
                'field_key' => 'jurusan',
                'label' => 'Jurusan',
                'placeholder' => null,
                'type' => 'text',
                'is_required' => false,
                'is_active' => true,
                'order' => 4,
                'width' => 'half',
                'options_group' => null,
                'validation_rules' => 'uppercase',
                'show_if' => null,
            ],
            // 5. status_pekerjaan
            [
                'section' => 'pendidikan',
                'field_key' => 'status_pekerjaan',
                'label' => 'Status Pekerjaan',
                'placeholder' => null,
                'type' => 'select2',
                'is_required' => true,
                'is_active' => true,
                'order' => 5,
                'width' => 'half',
                'options_group' => 'status_pekerjaan',
                'validation_rules' => null,
                'show_if' => null,
            ],
            // 6. nama_perusahaan
            [
                'section' => 'pendidikan',
                'field_key' => 'nama_perusahaan',
                'label' => 'Nama Perusahaan',
                'placeholder' => null,
                'type' => 'text',
                'is_required' => false,
                'is_active' => true,
                'order' => 6,
                'width' => 'half',
                'options_group' => null,
                'validation_rules' => 'uppercase',
                'show_if' => json_encode(['field' => 'status_pekerjaan', 'value' => 'BEKERJA']),
            ],

            // ============================================
            // Section: Minat
            // ============================================
            // 1. batch_pelatihan
            [
                'section' => 'minat',
                'field_key' => 'batch_pelatihan',
                'label' => 'Pilih Pelatihan (Batch)',
                'placeholder' => null,
                'type' => 'card_select',
                'is_required' => true,
                'is_active' => true,
                'order' => 1,
                'width' => 'full',
                'options_group' => null,
                'validation_rules' => null,
                'show_if' => null,
            ],

            // ============================================
            // Section: Dokumen — Pertanyaan Umum
            // ============================================
            // 1. pengetahuan_asep
            [
                'section' => 'dokumen',
                'field_key' => 'pengetahuan_asep',
                'label' => 'Apa yang kamu ketahui tentang Bapak H. Asep Mulyadi, S.H.?',
                'placeholder' => 'Tulis jawaban anda...',
                'type' => 'textarea',
                'is_required' => true,
                'is_active' => true,
                'order' => 1,
                'width' => 'full',
                'options_group' => null,
                'validation_rules' => null,
                'show_if' => null,
            ],
            // 2. alasan_pelatihan
            [
                'section' => 'dokumen',
                'field_key' => 'alasan_pelatihan',
                'label' => 'Sebutkan alasan mengikuti pelatihan tersebut.',
                'placeholder' => 'Tulis jawaban anda...',
                'type' => 'textarea',
                'is_required' => true,
                'is_active' => true,
                'order' => 2,
                'width' => 'full',
                'options_group' => null,
                'validation_rules' => null,
                'show_if' => null,
            ],
            // 3. pengalaman_bisnis
            [
                'section' => 'dokumen',
                'field_key' => 'pengalaman_bisnis',
                'label' => 'Ceritakan pengalaman bisnis anda dalam bidang pelatihan tersebut.',
                'placeholder' => 'Tulis jawaban anda...',
                'type' => 'textarea',
                'is_required' => true,
                'is_active' => true,
                'order' => 3,
                'width' => 'full',
                'options_group' => null,
                'validation_rules' => null,
                'show_if' => null,
            ],
            // ============================================
            // Section: Dokumen — Minat & Usaha
            // ============================================
            // 4. rencana_setelah_pelatihan
            [
                'section' => 'dokumen',
                'field_key' => 'rencana_setelah_pelatihan',
                'label' => 'Apa minat/rencana Anda kedepannya setelah mengikuti pelatihan tersebut?',
                'placeholder' => 'Tulis jawaban anda...',
                'type' => 'textarea',
                'is_required' => true,
                'is_active' => true,
                'order' => 4,
                'width' => 'full',
                'options_group' => null,
                'validation_rules' => null,
                'show_if' => null,
            ],
            // 5. punya_usaha
            [
                'section' => 'dokumen',
                'field_key' => 'punya_usaha',
                'label' => 'Apakah anda sudah memiliki usaha yang sedang dijalankan?',
                'placeholder' => null,
                'type' => 'radio',
                'is_required' => true,
                'is_active' => true,
                'order' => 5,
                'width' => 'half',
                'options_group' => 'punya_usaha',
                'validation_rules' => null,
                'show_if' => null,
            ],
            // 6. jenis_usaha
            [
                'section' => 'dokumen',
                'field_key' => 'jenis_usaha',
                'label' => 'Jenis usaha yang sedang dijalankan saat ini?',
                'placeholder' => null,
                'type' => 'radio',
                'is_required' => true,
                'is_active' => true,
                'order' => 6,
                'width' => 'half',
                'options_group' => 'jenis_usaha',
                'validation_rules' => null,
                'show_if' => null,
            ],
            // ============================================
            // Section: Dokumen — Usaha & Kendala
            // ============================================
            // 7. usaha_dimiliki
            [
                'section' => 'dokumen',
                'field_key' => 'usaha_dimiliki',
                'label' => 'Usaha yang dimiliki?',
                'placeholder' => 'Contoh: Hijab & Pakaian, Sate, Desainer dan lain lain',
                'type' => 'radio_other',
                'is_required' => true,
                'is_active' => true,
                'order' => 7,
                'width' => 'half',
                'options_group' => 'usaha_dimiliki',
                'validation_rules' => null,
                'show_if' => null,
            ],
            // 8. nama_usaha
            [
                'section' => 'dokumen',
                'field_key' => 'nama_usaha',
                'label' => 'Nama usaha yang sedang dijalankan?',
                'placeholder' => 'Contoh: Warung sate pak budi dan lain lain',
                'type' => 'radio_other',
                'is_required' => true,
                'is_active' => true,
                'order' => 8,
                'width' => 'half',
                'options_group' => 'nama_usaha',
                'validation_rules' => null,
                'show_if' => null,
            ],
            // 9. kendala_usaha
            [
                'section' => 'dokumen',
                'field_key' => 'kendala_usaha',
                'label' => 'Apa kendala yang dialami dalam menjalankan usaha anda?',
                'placeholder' => 'Contoh: Sulit mendapatkan konsumen baru',
                'type' => 'textarea',
                'is_required' => false,
                'is_active' => true,
                'order' => 9,
                'width' => 'full',
                'options_group' => null,
                'validation_rules' => null,
                'show_if' => null,
            ],
            // 10. konfirmasi
            [
                'section' => 'dokumen',
                'field_key' => 'konfirmasi',
                'label' => 'Saya menyatakan bahwa data yang diisi adalah benar',
                'placeholder' => null,
                'type' => 'checkbox',
                'is_required' => true,
                'is_active' => true,
                'order' => 10,
                'width' => 'full',
                'options_group' => null,
                'validation_rules' => null,
                'show_if' => null,
            ],
        ];

        foreach ($fields as $field) {
            FormFieldConfig::updateOrCreate(
                ['section' => $field['section'], 'field_key' => $field['field_key']],
                $field
            );
        }
    }
}
