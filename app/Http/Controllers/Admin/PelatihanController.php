<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dinas;
use App\Models\Kecamatan;
use App\Models\Pelatihan;
use App\Services\ActivityLogger;
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

        ActivityLogger::created($pelatihan, "Pelatihan {$pelatihan->nama} batch {$pelatihan->batch} berhasil dibuat");

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

        $oldValues = $pelatihan->getOriginal();
        $pelatihan->update($request->all());
        $pelatihan->kecamatans()->sync($request->kecamatan_ids ?? []);

        $freshPelatihan = $pelatihan->fresh();
        ActivityLogger::updated($freshPelatihan, $oldValues, $freshPelatihan->getAttributes(), "Pelatihan {$freshPelatihan->nama} batch {$freshPelatihan->batch} berhasil diperbarui");

        return redirect()->route('admin.pelatihan.index')
            ->with('success', 'Pelatihan berhasil diperbarui.');
    }

    public function show(Pelatihan $pelatihan)
    {
        // Optimasi: Load + count dalam 1 query, hindari N+1
        $pelatihan->load([
            'dinas',
            'kecamatans',
        ]);

        $peserta = $pelatihan->pesertaProfiles()
            ->with(['user', 'kelurahan'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Optimasi: Gunakan withCount agar tidak perlu query terpisah
        $pelatihan->loadCount([
            'pesertaProfiles as total_peserta',
            'pesertaProfiles as completed_count' => function ($q) {
                $q->where('is_completed', true);
            },
        ]);

        return view('content.admin.pelatihan.show', compact('pelatihan', 'peserta'));
    }

    public function destroy(Pelatihan $pelatihan)
    {
        $oldData = $pelatihan->getAttributes();
        $nama = $pelatihan->nama;
        $batch = $pelatihan->batch;
        $pelatihan->delete();

        ActivityLogger::log(
            action: 'deleted',
            subjectType: 'Pelatihan',
            subjectId: $pelatihan->id,
            subjectName: $nama,
            description: "Pelatihan {$nama} batch {$batch} berhasil dihapus",
            oldValues: $oldData,
        );

        return redirect()->route('admin.pelatihan.index')
            ->with('success', 'Pelatihan berhasil dihapus.');
    }
}
