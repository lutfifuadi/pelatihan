<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PesertaExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return User::where('role', 'peserta')
            ->with('kecamatan', 'kelurahan')
            ->when($this->filters['search'] ?? null, function ($q, $search) {
                $q->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                      ->orWhere('nik', 'like', '%' . $search . '%')
                      ->orWhere('whatsapp', 'like', '%' . $search . '%');
                });
            })
            ->orderBy('name', 'asc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Nama',
            'NIK',
            'WhatsApp',
            'Email',
            'Kecamatan',
            'Kelurahan',
            'Tanggal Daftar',
            'Status Aktif',
        ];
    }

    public function map($user): array
    {
        return [
            $user->name,
            $user->nik ?? '-',
            $user->whatsapp ?? '-',
            $user->email ?? '-',
            $user->kecamatan?->name ?? '-',
            $user->kelurahan?->name ?? '-',
            $user->created_at ? $user->created_at->format('d-m-Y H:i') : '-',
            $user->is_active ? 'Aktif' : 'Tidak Aktif',
        ];
    }
}
