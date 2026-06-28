<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pelatihan;
use App\Models\User;
use App\Models\Enrollment;
use App\Models\Attendance;
use App\Models\Certificate;
use App\Exports\PesertaExport;
use App\Exports\EnrollmentExport;
use App\Exports\AttendanceExport;
use App\Exports\CertificateExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    /**
     * Menampilkan halaman indeks export dengan semua opsi.
     */
    public function index()
    {
        $pelatihans = Pelatihan::where('is_active', true)
            ->orderBy('nama')
            ->get(['id', 'nama']);

        return view('content.admin.exports.index', compact('pelatihans'));
    }

    /**
     * Export PDF daftar peserta.
     */
    public function exportPesertaPdf(Request $request)
    {
        $pesertas = User::where('role', 'peserta')
            ->with('kecamatan', 'kelurahan')
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->search;
                $q->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                      ->orWhere('nik', 'like', '%' . $search . '%')
                      ->orWhere('whatsapp', 'like', '%' . $search . '%');
                });
            })
            ->orderBy('name', 'asc')
            ->get();

        $pdf = Pdf::loadView('content.admin.exports.peserta-pdf', compact('pesertas'));

        return $pdf->download('data-peserta-' . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Export Excel daftar peserta.
     */
    public function exportPesertaExcel(Request $request)
    {
        $filters = $request->only(['search']);
        return Excel::download(
            new PesertaExport($filters),
            'data-peserta-' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    /**
     * Export PDF data enrollments (pendaftaran).
     */
    public function exportEnrollmentsPdf(Request $request, ?Pelatihan $pelatihan = null)
    {
        $query = Enrollment::with(['user', 'pelatihan']);

        if ($pelatihan && $pelatihan->exists) {
            $query->where('pelatihan_id', $pelatihan->id);
        } elseif ($request->filled('pelatihan_id')) {
            $query->where('pelatihan_id', $request->pelatihan_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $enrollments = $query->orderBy('created_at', 'desc')->get();

        $pdf = Pdf::loadView('content.admin.exports.enrollments-pdf', compact('enrollments', 'pelatihan'));

        $filename = 'data-pendaftaran';
        if ($pelatihan && $pelatihan->exists) {
            $filename .= '-' . \Illuminate\Support\Str::slug($pelatihan->nama);
        }
        $filename .= '-' . now()->format('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Export Excel data enrollments (pendaftaran).
     */
    public function exportEnrollmentsExcel(Request $request, ?Pelatihan $pelatihan = null)
    {
        $pelatihanId = null;
        if ($pelatihan && $pelatihan->exists) {
            $pelatihanId = $pelatihan->id;
        } elseif ($request->filled('pelatihan_id')) {
            $pelatihanId = $request->pelatihan_id;
        }

        $filename = 'data-pendaftaran';
        if ($pelatihanId) {
            $p = Pelatihan::find($pelatihanId);
            if ($p) {
                $filename .= '-' . \Illuminate\Support\Str::slug($p->nama);
            }
        }
        $filename .= '-' . now()->format('Y-m-d') . '.xlsx';

        return Excel::download(new EnrollmentExport($pelatihanId), $filename);
    }

    /**
     * Export PDF rekapitulasi absensi.
     */
    public function exportAttendancePdf(Pelatihan $pelatihan)
    {
        $pelatihan->load('dinas');

        $enrollments = Enrollment::with(['user', 'attendances'])
            ->where('pelatihan_id', $pelatihan->id)
            ->where('status', 'confirmed')
            ->orderBy('created_at', 'asc')
            ->get();

        $totalPertemuan = Attendance::whereHas('enrollment', function ($q) use ($pelatihan) {
            $q->where('pelatihan_id', $pelatihan->id);
        })->max('pertemuan_ke') ?? 0;

        $pertemuans = range(1, $totalPertemuan);

        $pdf = Pdf::loadView('content.admin.exports.attendance-pdf', compact(
            'pelatihan', 'enrollments', 'totalPertemuan', 'pertemuans'
        ));

        $filename = 'rekap-absensi-' . \Illuminate\Support\Str::slug($pelatihan->nama) . '-' . now()->format('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Export Excel rekapitulasi absensi.
     */
    public function exportAttendanceExcel(Pelatihan $pelatihan)
    {
        $filename = 'rekap-absensi-' . \Illuminate\Support\Str::slug($pelatihan->nama) . '-' . now()->format('Y-m-d') . '.xlsx';

        return Excel::download(new AttendanceExport($pelatihan), $filename);
    }

    /**
     * Export PDF data sertifikat.
     */
    public function exportCertificatesPdf(Request $request)
    {
        $query = Certificate::with(['enrollment.user', 'enrollment.pelatihan']);

        $pelatihan = null;
        if ($request->filled('pelatihan_id')) {
            $query->whereHas('enrollment', function ($q) use ($request) {
                $q->where('pelatihan_id', $request->pelatihan_id);
            });
            $pelatihan = Pelatihan::find($request->pelatihan_id);
        }

        $certificates = $query->orderBy('created_at', 'desc')->get();

        $pdf = Pdf::loadView('content.admin.exports.certificates-pdf', compact('certificates', 'pelatihan'));

        $filename = 'data-sertifikat';
        if ($pelatihan) {
            $filename .= '-' . \Illuminate\Support\Str::slug($pelatihan->nama);
        }
        $filename .= '-' . now()->format('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Export Excel data sertifikat.
     */
    public function exportCertificatesExcel(Request $request)
    {
        $pelatihanId = null;
        $pelatihan = null;

        if ($request->filled('pelatihan_id')) {
            $pelatihanId = $request->pelatihan_id;
            $pelatihan = Pelatihan::find($pelatihanId);
        }

        $filename = 'data-sertifikat';
        if ($pelatihan) {
            $filename .= '-' . \Illuminate\Support\Str::slug($pelatihan->nama);
        }
        $filename .= '-' . now()->format('Y-m-d') . '.xlsx';

        return Excel::download(new CertificateExport($pelatihanId), $filename);
    }
}
