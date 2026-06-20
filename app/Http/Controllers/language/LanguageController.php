<?php

namespace App\Http\Controllers\language;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class LanguageController extends Controller
{
  public function swap(Request $request, $locale)
  {
    // Force only Indonesian locale
    $locale = 'id';
    $request->session()->put('locale', $locale);
    App::setLocale($locale);
    return redirect()->back();
  }
}