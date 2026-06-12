<?php

namespace App\Http\Controllers;

use App\Models\Pelatihan;

class PelatihanController extends Controller
{
    public function show(Pelatihan $pelatihan)
    {
        seo()->fromModel($pelatihan)
             ->addJsonLd(seo()->courseSchema($pelatihan));

        return view('content.pelatihan.show', compact('pelatihan'));
    }
}
