<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    /**
     * Menampilkan halaman index Laporan & Statistik.
     */
    public function index()
    {
        return view('content.admin.laporan.index');
    }
}
