<?php

namespace App\Exports;

use App\Models\Enrollment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class EnrollmentExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
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
        $query = Enrollment::with(['user', 'pelatihan'])
            ->orderBy('created_at', 'desc');

        if ($this->pelatihanId) {
            $query->where('pelatihan_id', $this->pelatihanId);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Nama Peserta',
            'NIK',
            'WhatsApp',
            'Email',
            'Pelatihan',
            'Status',
            'Tanggal Daftar',
            'Tanggal Approve/Reject',
        ];
    }

    public function map($enrollment): array
    {
        $tanggalApproveReject = null;
        if ($enrollment->status === 'approved' && $enrollment->approved_at) {
            $tanggalApproveReject = $enrollment->approved_at->format('d-m-Y H:i');
        } elseif ($enrollment->status === 'rejected' && $enrollment->rejected_at) {
            $tanggalApproveReject = $enrollment->rejected_at->format('d-m-Y H:i');
        } elseif ($enrollment->status === 'waitlist' && $enrollment->waitlist_promoted_at) {
            $tanggalApproveReject = $enrollment->waitlist_promoted_at->format('d-m-Y H:i');
        }

        return [
            $enrollment->user?->name ?? '-',
            $enrollment->user?->nik ?? '-',
            $enrollment->user?->whatsapp ?? '-',
            $enrollment->user?->email ?? '-',
            $enrollment->pelatihan?->nama ?? '-' . ($enrollment->pelatihan?->batch ? ' (Batch: ' . $enrollment->pelatihan->batch . ')' : ''),
            ucfirst($enrollment->status),
            $enrollment->created_at ? $enrollment->created_at->format('d-m-Y H:i') : '-',
            $tanggalApproveReject ?? '-',
        ];
    }
}
