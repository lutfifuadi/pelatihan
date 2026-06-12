<?php

namespace App\Http\Controllers\pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomePage extends Controller
{
  public function index()
  {
    seo()->staticPage('home');
    $pageConfigs = ['myLayout' => 'blank'];
    return view('content.landing.beranda', ['pageConfigs' => $pageConfigs]);
  }
}
