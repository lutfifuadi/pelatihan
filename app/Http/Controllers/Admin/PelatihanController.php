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
    public function index(Request $request)
    {
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        // Whitelist kolom yang diizinkan untuk sorting (cegah SQL injection)
        $allowedSort = [
            'tanggal_mulai', 'nama', 'dinas', 'kuota', 'is_active', 'created_at'
        ];

        if (!in_array($sortBy, $allowedSort)) {
            $sortBy = 'created_at';
        }

        if (!in_array($sortOrder, ['asc', 'desc'])) {
            $sortOrder = 'desc';
        }

        $query = Pelatihan::with('dinas');

        // Handle khusus untuk relasi 'dinas'
        if ($sortBy === 'dinas') {
            $query = $query
                ->select('pelatihans.*')
                ->leftJoin('dinas', 'pelatihans.dinas_id', '=', 'dinas.id')
                ->orderBy('dinas.nama_dinas', $sortOrder);
        } else {
            $query = $query->orderBy($sortBy, $sortOrder);
        }

        $pelatihans = $query->paginate(15);

        return view('content.admin.pelatihan.index', compact('pelatihans', 'sortBy', 'sortOrder'));
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

    public function show(Request $request, Pelatihan $pelatihan)
    {
        $pelatihan->load([
            'dinas',
            'kecamatans',
        ]);

        $statusFilter = $request->query('status');
        $search = $request->query('search');

        $query = $pelatihan->enrollments()
            ->with(['user.pesertaProfile.kelurahan.kecamatan'])
            ->orderBy('created_at', 'desc');

        if ($statusFilter && $statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($uq) use ($search) {
                    $uq->where('name', 'like', "%{$search}%")
                       ->orWhere('nik', 'like', "%{$search}%")
                       ->orWhere('whatsapp', 'like', "%{$search}%")
                       ->orWhere('phone', 'like', "%{$search}%")
                       ->orWhere('email', 'like', "%{$search}%");
                })->orWhereHas('user.pesertaProfile', function ($pq) use ($search) {
                    $pq->where('nama_lengkap', 'like', "%{$search}%")
                       ->orWhere('nik', 'like', "%{$search}%");
                });
            });
        }

        $enrollments = $query->paginate(20)->withQueryString();

        // Statistik
        $totalPeserta = $pelatihan->enrollments()->count();
        $confirmedCount = $pelatihan->enrollments()->where('status', 'confirmed')->count();
        $pendingCount = $pelatihan->enrollments()->where('status', 'pending')->count();
        $rejectedCount = $pelatihan->enrollments()->where('status', 'rejected')->count();

        // Seluruh nomor WA untuk tombol salin cepat
        $allWaNumbers = $pelatihan->enrollments()
            ->with('user.pesertaProfile')
            ->get()
            ->map(function ($enr) {
                return $enr->user?->whatsapp ?: ($enr->user?->phone ?: $enr->user?->pesertaProfile?->no_wa);
            })
            ->filter()
            ->unique()
            ->implode(', ');

        return view('content.admin.pelatihan.show', compact(
            'pelatihan',
            'enrollments',
            'totalPeserta',
            'confirmedCount',
            'pendingCount',
            'rejectedCount',
            'allWaNumbers',
            'statusFilter',
            'search'
        ));
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
