<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dinas;
use App\Models\Kecamatan;
use App\Models\Pelatihan;
use Illuminate\Http\Request;

class PelatihanController extends Controller
{
    public function index()
    {
        $pelatihans = Pelatihan::with('dinas')->orderBy('created_at', 'desc')->paginate(15);
        return view('content.admin.pelatihan.index', compact('pelatihans'));
    }

    public function create()
    {
        $dinas = Dinas::where('is_active', true)->orderBy('nama_dinas')->get();
        $kecamatans = Kecamatan::orderBy('name')->get();
        return view('content.admin.pelatihan.create', compact('dinas', 'kecamatans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:200',
            'batch' => 'required|string|max:50|unique:pelatihan,batch',
            'deskripsi' => 'nullable|string',
            'batas_pendaftaran' => 'nullable|date',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'kuota' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
            'dinas_id' => 'nullable|exists:dinas,id',
            'kecamatan_ids' => 'nullable|array',
            'kecamatan_ids.*' => 'exists:kecamatans,id',
        ]);

        $pelatihan = Pelatihan::create($request->all());
        $pelatihan->kecamatans()->sync($request->kecamatan_ids ?? []);

        return redirect()->route('admin.pelatihan.index')
            ->with('success', 'Pelatihan berhasil ditambahkan.');
    }

    public function edit(Pelatihan $pelatihan)
    {
        $dinas = Dinas::where('is_active', true)->orderBy('nama_dinas')->get();
        $kecamatans = Kecamatan::orderBy('name')->get();
        $pelatihan->load('kecamatans');
        return view('content.admin.pelatihan.edit', compact('pelatihan', 'dinas', 'kecamatans'));
    }

    public function update(Request $request, Pelatihan $pelatihan)
    {
        $request->validate([
            'nama' => 'required|string|max:200',
            'batch' => 'required|string|max:50|unique:pelatihan,batch,' . $pelatihan->id,
            'deskripsi' => 'nullable|string',
            'batas_pendaftaran' => 'nullable|date',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'kuota' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
            'dinas_id' => 'nullable|exists:dinas,id',
            'kecamatan_ids' => 'nullable|array',
            'kecamatan_ids.*' => 'exists:kecamatans,id',
        ]);

        $pelatihan->update($request->all());
        $pelatihan->kecamatans()->sync($request->kecamatan_ids ?? []);

        return redirect()->route('admin.pelatihan.index')
            ->with('success', 'Pelatihan berhasil diperbarui.');
    }

    public function show(Pelatihan $pelatihan)
    {
        $pelatihan->load([
            'dinas',
            'kecamatans',
            'pesertaProfiles.user',
            'pesertaProfiles.kelurahan',
        ]);

        $peserta = $pelatihan->pesertaProfiles()
            ->with(['user', 'kelurahan'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $totalPeserta = $pelatihan->pesertaProfiles()->count();
        $completedCount = $pelatihan->pesertaProfiles()->where('is_completed', true)->count();

        return view('content.admin.pelatihan.show', compact('pelatihan', 'peserta', 'totalPeserta', 'completedCount'));
    }

    public function destroy(Pelatihan $pelatihan)
    {
        $pelatihan->delete();

        return redirect()->route('admin.pelatihan.index')
            ->with('success', 'Pelatihan berhasil dihapus.');
    }
}
