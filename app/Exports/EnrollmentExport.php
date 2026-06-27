<?php

namespace App\Exports;

use App\Models\Enrollment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\Cell;

class EnrollmentExport extends DefaultValueBinder implements
    FromCollection,
    WithHeadings,
    WithMapping,
    ShouldAutoSize,
    WithCustomValueBinder
{

    protected $pelatihanId;

    protected $jawabanKeys = [];

    public function __construct($pelatihanId = null)
    {
        $this->pelatihanId = $pelatihanId;
    }

    public function collection()
    {
        $query = Enrollment::with([
            'user.pesertaProfile',
            'user.kecamatan',
            'user.kelurahan',
            'pelatihan.dinas',
        ])->orderBy('created_at', 'desc');

        if ($this->pelatihanId) {
            $query->where('pelatihan_id', $this->pelatihanId);
        }

        $enrollments = $query->get();

        $keys = [];
        foreach ($enrollments as $enrollment) {
            $profile = $enrollment->user?->pesertaProfile;
            if ($profile && $profile->jawaban_pertanyaan) {
                $jawaban = is_array($profile->jawaban_pertanyaan)
                    ? $profile->jawaban_pertanyaan
                    : json_decode($profile->jawaban_pertanyaan, true) ?? [];
                foreach ($jawaban as $key => $value) {
                    if (!in_array($key, $keys)) {
                        $keys[] = $key;
                    }
                }
            }
        }
        $this->jawabanKeys = $keys;

        return $enrollments;
    }

    public function headings(): array
    {
        $headings = [
            'Nama Peserta',
            'NIK',
            'WhatsApp',
            'Email',
            'Jenis Kelamin',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Bulan Lahir',
            'Tahun Lahir',
            'Link Medsos',
            'Alamat KTP',
            'RT',
            'RW',
            'Kelurahan',
            'Kecamatan',
            'Kota',
            'Provinsi',
            'Kodepos',
            'Pendidikan Terakhir',
            'Nama Institusi',
            'Jurusan',
            'Tahun Lulus',
            'Status Pekerjaan',
            'Nama Perusahaan',
            'Nama Pelatihan',
            'Batch',
            'Dinas Penyelenggara',
            'Status Pendaftaran',
            'Tanggal Daftar',
            'Tanggal Approve',
            'Tanggal Ditolak',
            'Tanggal Promosi Cadangan',
            'Catatan',
        ];

        foreach ($this->jawabanKeys as $key) {
            $headings[] = ucwords(str_replace('_', ' ', $key));
        }

        return $headings;
    }

    public function map($enrollment): array
    {
        $user = $enrollment->user;
        $profile = $user?->pesertaProfile;

        $linkMedsos = '-';
        if ($profile && $profile->link_medsos) {
            $medsos = is_array($profile->link_medsos)
                ? $profile->link_medsos
                : json_decode($profile->link_medsos, true) ?? [];
            $strings = [];
            foreach ($medsos as $item) {
                if (!empty($item['platform']) && !empty($item['url'])) {
                    $strings[] = $item['platform'] . ': ' . $item['url'];
                }
            }
            $linkMedsos = !empty($strings) ? implode(', ', $strings) : '-';
        }

        $jawaban = [];
        if ($profile && $profile->jawaban_pertanyaan) {
            $jawabanData = is_array($profile->jawaban_pertanyaan)
                ? $profile->jawaban_pertanyaan
                : json_decode($profile->jawaban_pertanyaan, true) ?? [];
            $jawaban = $jawabanData;
        }

        $row = [
            $user?->name ?? '-',
            $user?->nik ?? '-',
            $user?->whatsapp ?? '-',
            $user?->email ?? '-',
            $profile?->jenis_kelamin ?? '-',
            $profile?->tempat_lahir ?? '-',
            $profile?->tanggal_lahir ?? '-',
            $profile?->bulan_lahir ?? '-',
            $profile?->tahun_lahir ?? '-',
            $linkMedsos,
            $profile?->alamat_ktp ?? '-',
            $profile?->rt ?? '-',
            $profile?->rw ?? '-',
            $profile?->kelurahan ?? '-',
            $profile?->kecamatan ?? '-',
            $profile?->kota ?? '-',
            $profile?->provinsi ?? '-',
            $profile?->kodepos ?? '-',
            $profile?->pendidikan_terakhir ?? '-',
            $profile?->nama_institusi ?? '-',
            $profile?->jurusan ?? '-',
            $profile?->tahun_lulus ?? '-',
            $profile?->status_pekerjaan ?? '-',
            $profile?->nama_perusahaan ?? '-',
            $enrollment->pelatihan?->nama ?? '-',
            $enrollment->pelatihan?->batch ?? '-',
            $enrollment->pelatihan?->dinas?->nama_dinas ?? '-',
            ucfirst($enrollment->status?->value ?? '-'),
            $enrollment->created_at ? $enrollment->created_at->format('d-m-Y H:i') : '-',
            $enrollment->approved_at ? $enrollment->approved_at->format('d-m-Y H:i') : '-',
            $enrollment->rejected_at ? $enrollment->rejected_at->format('d-m-Y H:i') : '-',
            $enrollment->waitlist_promoted_at ? $enrollment->waitlist_promoted_at->format('d-m-Y H:i') : '-',
            $enrollment->notes ?? '-',
        ];

        foreach ($this->jawabanKeys as $key) {
            $row[] = $jawaban[$key] ?? '-';
        }

        return $row;
    }

    public function bindValue(Cell $cell, $value)
    {
        $cell->setValueExplicit($value, DataType::TYPE_STRING);

        return true;
    }
}
