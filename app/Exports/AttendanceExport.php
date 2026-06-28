<?php

namespace App\Exports;

use App\Models\Attendance;
use App\Models\Enrollment;
use App\Models\Pelatihan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AttendanceExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $pelatihan;
    protected $pertemuans;
    protected $totalPertemuan;

    public function __construct(Pelatihan $pelatihan)
    {
        $this->pelatihan = $pelatihan;

        $this->totalPertemuan = Attendance::whereHas('enrollment', function ($q) use ($pelatihan) {
            $q->where('pelatihan_id', $pelatihan->id);
        })->max('pertemuan_ke') ?? 0;

        $this->pertemuans = range(1, $this->totalPertemuan);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Enrollment::with(['user', 'attendances'])
            ->where('pelatihan_id', $this->pelatihan->id)
            ->where('status', 'confirmed')
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function headings(): array
    {
        $headings = ['Nama Peserta', 'NIK'];

        foreach ($this->pertemuans as $p) {
            $headings[] = 'P' . $p;
        }

        $headings[] = 'Total Hadir';
        $headings[] = 'Total Sakit';
        $headings[] = 'Total Izin';
        $headings[] = 'Total Alpa';
        $headings[] = 'Persentase Kehadiran';

        return $headings;
    }

    public function map($enrollment): array
    {
        $row = [
            $enrollment->user?->name ?? '-',
            $enrollment->user?->nik ?? '-',
        ];

        $totalHadir = 0;
        $totalSakit = 0;
        $totalIzin = 0;
        $totalAlpa = 0;

        foreach ($this->pertemuans as $p) {
            $att = $enrollment->attendances->firstWhere('pertemuan_ke', $p);
            $status = $att ? $att->status : null;

            switch ($status) {
                case 'hadir':
                    $totalHadir++;
                    break;
                case 'sakit':
                    $totalSakit++;
                    break;
                case 'izin':
                    $totalIzin++;
                    break;
                case 'alpa':
                    $totalAlpa++;
                    break;
            }

            $row[] = $status ? ucfirst($status) : '-';
        }

        $row[] = $totalHadir;
        $row[] = $totalSakit;
        $row[] = $totalIzin;
        $row[] = $totalAlpa;

        $persen = $this->totalPertemuan > 0 ? round(($totalHadir / $this->totalPertemuan) * 100, 2) : 0;
        $row[] = $persen . '%';

        return $row;
    }
}
