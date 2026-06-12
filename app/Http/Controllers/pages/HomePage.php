<?php

namespace App\Http\Controllers\pages;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;

class HomePage extends Controller
{
  public function index()
  {
    $faqs = Faq::active()->ordered()->get();

    seo()->staticPage('home')
         ->addJsonLd(seo()->faqPageSchema($faqs));

    $pageConfigs = ['myLayout' => 'blank'];
    return view('content.landing.beranda', ['pageConfigs' => $pageConfigs]);
  }
}
