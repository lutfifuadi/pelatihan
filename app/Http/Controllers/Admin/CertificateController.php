<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Enrollment;
use App\Models\Pelatihan;
use App\Services\ActivityLogger;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class CertificateController extends Controller
{
    /**
     * Daftar sertifikat yang sudah digenerate.
     */
    public function index(Request $request)
    {
        $query = Certificate::with(['enrollment.user', 'enrollment.pelatihan']);

        if ($request->filled('pelatihan_id')) {
            $query->whereHas('enrollment', function ($q) use ($request) {
                $q->where('pelatihan_id', $request->pelatihan_id);
            });
        }

        $certificates = $query->orderBy('created_at', 'desc')->paginate(20);
        $pelatihans = Cache::remember('pelatihan.active.list', 3600, function () {
            return Pelatihan::where('is_active', true)->orderBy('nama')->get(['id', 'nama', 'batch']);
        });

        return view('content.admin.certificates.index', compact('certificates', 'pelatihans'));
    }

    /**
     * Form generate sertifikat per pelatihan.
     */
    public function create(Pelatihan $pelatihan)
    {
        $pelatihan->load('dinas');

        // Ambil enrollment yang approved dan belum punya sertifikat
        $enrollments = Enrollment::with('user.pesertaProfile')
            ->where('pelatihan_id', $pelatihan->id)
            ->where('status', 'approved')
            ->whereDoesntHave('certificate')
            ->orderBy('created_at', 'asc')
            ->get();

        // Hitung yang sudah punya sertifikat
        $certified = Enrollment::where('pelatihan_id', $pelatihan->id)
            ->where('status', 'approved')
            ->whereHas('certificate')
            ->count();

        return view('content.admin.certificates.create', compact('pelatihan', 'enrollments', 'certified'));
    }

    /**
     * Generate sertifikat untuk peserta tertentu.
     */
    public function store(Request $request)
    {
        $request->validate([
            'enrollment_id' => 'required|exists:enrollments,id',
        ]);

        $enrollment = Enrollment::with(['user.pesertaProfile', 'pelatihan.dinas'])
            ->findOrFail($request->enrollment_id);

        // Cek apakah sudah punya sertifikat
        if ($enrollment->certificate) {
            return back()->with('error', 'Peserta ini sudah memiliki sertifikat.');
        }

        $certificate = $this->generateCertificate($enrollment);

        ActivityLogger::created($certificate, "Sertifikat {$certificate->certificate_number} untuk {$enrollment->user?->name} berhasil dibuat");

        return redirect()->route('admin.certificates.show', $certificate)
            ->with('success', 'Sertifikat berhasil digenerate.');
    }

    /**
     * Generate sertifikat massal untuk satu pelatihan.
     */
    public function generateBatch(Request $request, Pelatihan $pelatihan)
    {
        $enrollments = Enrollment::with(['user.pesertaProfile', 'pelatihan.dinas'])
            ->where('pelatihan_id', $pelatihan->id)
            ->where('status', 'approved')
            ->whereDoesntHave('certificate')
            ->get();

        if ($enrollments->isEmpty()) {
            return back()->with('error', 'Semua peserta sudah memiliki sertifikat atau tidak ada peserta approved.');
        }

        $generated = 0;
        foreach ($enrollments as $enrollment) {
            $this->generateCertificate($enrollment);
            $generated++;
        }

        ActivityLogger::action('created', 'Certificate', "Batch {$generated} sertifikat untuk pelatihan {$pelatihan->nama} berhasil digenerate", $pelatihan->id, $pelatihan->nama);

        return redirect()->route('admin.certificates.index', ['pelatihan_id' => $pelatihan->id])
            ->with('success', "{$generated} sertifikat berhasil digenerate.");
    }

    /**
     * Detail sertifikat.
     */
    public function show(Certificate $certificate)
    {
        $certificate->load(['enrollment.user.pesertaProfile', 'enrollment.pelatihan.dinas']);
        return view('content.admin.certificates.show', compact('certificate'));
    }

    /**
     * Download PDF sertifikat.
     */
    public function download(Certificate $certificate)
    {
        $certificate->load(['enrollment.user.pesertaProfile', 'enrollment.pelatihan.dinas']);

        $pdf = Pdf::loadView('content.admin.certificates.template', [
            'certificate' => $certificate,
            'participant' => $certificate->enrollment->user,
            'training' => $certificate->enrollment->pelatihan,
        ]);

        ActivityLogger::action('export', 'Certificate', "Sertifikat {$certificate->certificate_number} untuk {$certificate->enrollment->user?->name} di-download", $certificate->id, $certificate->certificate_number);

        $filename = 'sertifikat_' . $certificate->certificate_number . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Halaman verifikasi publik.
     */
    public function verify(Request $request)
    {
        $number = $request->get('nomor');
        $certificate = null;

        if ($number) {
            $certificate = Certificate::with(['enrollment.user.pesertaProfile', 'enrollment.pelatihan'])
                ->where('certificate_number', $number)
                ->first();
        }

        return view('content.public.verify-certificate', compact('certificate', 'number'));
    }

    /**
     * Internal: generate PDF dan simpan.
     */
    private function generateCertificate(Enrollment $enrollment): Certificate
    {
        $certificateNumber = Certificate::generateNumber();

        // Generate PDF
        $pdf = Pdf::loadView('content.admin.certificates.template', [
            'certificate' => null,
            'participant' => $enrollment->user,
            'training' => $enrollment->pelatihan,
            'certificateNumber' => $certificateNumber,
        ]);

        // Simpan PDF ke storage
        $filename = 'certificates/' . $certificateNumber . '.pdf';
        Storage::disk('public')->put($filename, $pdf->output());

        // Buat record di database
        $certificate = Certificate::create([
            'enrollment_id' => $enrollment->id,
            'certificate_number' => $certificateNumber,
            'issued_at' => now(),
            'file_path' => $filename,
        ]);

        // Dispatch notifikasi WA
        \App\Events\SertifikatDiterbitkan::dispatch(
            $enrollment->user,
            $enrollment->pelatihan,
            $certificate
        );

        return $certificate;
    }
}
