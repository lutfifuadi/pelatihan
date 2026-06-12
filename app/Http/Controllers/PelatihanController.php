<?php

namespace App\Http\Controllers;

use App\Models\Pelatihan;

class PelatihanController extends Controller
{
    public function show(Pelatihan $pelatihan)
    {
        seo()->fromModel($pelatihan)
             ->addJsonLd(seo()->courseSchema($pelatihan))
             ->addJsonLd(seo()->breadcrumbSchema([
                 ['label' => 'Beranda', 'url' => url('/')],
                 ['label' => 'Pelatihan', 'url' => url('/pelatihan')],
                 ['label' => $pelatihan->nama, 'url' => url('/pelatihan/' . ($pelatihan->slug ?? $pelatihan->id))],
             ]));

        return view('content.pelatihan.show', compact('pelatihan'));
    }
}
