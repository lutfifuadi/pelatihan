<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Dashboard Admin
     */
    public function admin()
    {
        return view('content.dashboard.admin');
    }

    /**
     * Dashboard Instruktur
     */
    public function instruktur()
    {
        return view('content.dashboard.instruktur');
    }

    /**
     * Dashboard Koordinator
     */
    public function koordinator()
    {
        return view('content.dashboard.koordinator');
    }

    /**
     * Dashboard Peserta
     */
    public function peserta()
    {
        $data = [
            'totalPelatihan' => 3,
            'tugasSelesai' => 12,
            'sertifikat' => 1,
            'jamBelajar' => 47,
            'nilaiRata' => '85.5',
        ];

        return view('content.dashboard.peserta', compact('data'));
    }
}
