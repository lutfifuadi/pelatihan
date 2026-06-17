<?php

namespace App\Exports;

use App\Models\Certificate;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class CertificateExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $pelatihanId;

    public function __construct($pelatihanId = null)
    {
        $this->pelatihanId = $pelatihanId;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $query = Certificate::with(['enrollment.user', 'enrollment.pelatihan'])
            ->orderBy('created_at', 'desc');

        if ($this->pelatihanId) {
            $query->whereHas('enrollment', function ($q) {
                $q->where('pelatihan_id', $this->pelatihanId);
            });
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Nama Peserta',
            'NIK',
            'Pelatihan',
            'Nomor Sertifikat',
            'Tanggal Terbit',
        ];
    }

    public function map($certificate): array
    {
        return [
            $certificate->enrollment?->user?->name ?? '-',
            $certificate->enrollment?->user?->nik ?? '-',
            $certificate->enrollment?->pelatihan?->nama ?? '-',
            $certificate->certificate_number ?? '-',
            $certificate->issued_at ? $certificate->issued_at->format('d-m-Y') : '-',
        ];
    }
}
