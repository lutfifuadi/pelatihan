<?php

namespace App\Http\Controllers\pages;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\Pelatihan;
use Illuminate\Http\Request;

class HomePage extends Controller
{
  public function index()
  {
    // Pastikan tampilan landing page menggunakan locale Indonesia
    app()->setLocale('id');

    $faqs = Faq::active()->ordered()->get();

    // Ambil pelatihan aktif untuk ditampilkan di section publik
    $pelatihans = Pelatihan::with(['dinas', 'kecamatans'])
      ->withCount(['approvedEnrollments'])
      ->where('is_active', true)
      ->orderBy('tanggal_mulai', 'asc')
      ->orderBy('batch', 'asc')
      ->get();

    seo()->staticPage('home')
         ->addJsonLd(seo()->faqPageSchema($faqs));

    $pageConfigs = ['myLayout' => 'blank'];
    return view('content.landing.beranda', [
      'pageConfigs' => $pageConfigs,
      'pelatihans' => $pelatihans,
    ]);
  }
}
