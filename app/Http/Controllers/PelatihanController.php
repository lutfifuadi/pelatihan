<?php

namespace App\Http\Controllers;

use App\Models\Pelatihan;
use App\Services\EnrollmentCooldownService;

class PelatihanController extends Controller
{
    public function __construct(
        private EnrollmentCooldownService $enrollmentCooldownService
    ) {}

    public function index()
    {
        app()->setLocale('id');

        $pelatihans = Pelatihan::with(['dinas', 'kecamatans'])
            ->withCount(['approvedEnrollments'])
            ->where('is_active', true)
            ->orderBy('tanggal_mulai', 'asc')
            ->orderBy('batch', 'asc')
            ->get()
            ->map(function ($p) {
                $p->is_ditutup = $p->isPendaftaranDitutup();
                return $p;
            });

        seo()->staticPage('pelatihan.index');

        $pageConfigs = ['myLayout' => 'blank'];
        return view('content.landing.pelatihan-index', [
            'pageConfigs' => $pageConfigs,
            'pelatihans' => $pelatihans,
        ]);
    }

    public function show(Pelatihan $pelatihan)
    {
        seo()->fromModel($pelatihan)
             ->addJsonLd(seo()->courseSchema($pelatihan))
             ->addJsonLd(seo()->breadcrumbSchema([
                 ['label' => 'Beranda', 'url' => url('/')],
                 ['label' => 'Pelatihan', 'url' => url('/pelatihan')],
                 ['label' => $pelatihan->nama, 'url' => url('/pelatihan/' . ($pelatihan->slug ?? $pelatihan->id))],
             ]));

        $is_ditutup = $pelatihan->isPendaftaranDitutup();

        $enrollmentCooldown = null;
        if (auth()->check()) {
            $enrollmentCooldown = $this->enrollmentCooldownService->getEnrollmentStatus(auth()->user(), $pelatihan);
        }

        return view('content.pelatihan.show', compact('pelatihan', 'is_ditutup', 'enrollmentCooldown'));
    }
}
