<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelurahan;
use App\Models\Kecamatan;
use Illuminate\Http\Request;

class KelurahanController extends Controller
{
    public function index(Request $request)
    {
        $sortBy = $request->get('sort_by', 'name');
        $sortDir = $request->get('sort_dir', 'asc');
        $search = $request->get('search');

        $allowedSort = ['name'];
        if (!in_array($sortBy, $allowedSort)) {
            $sortBy = 'name';
        }
        $sortDir = in_array($sortDir, ['asc', 'desc']) ? $sortDir : 'asc';

        $kelurahans = Kelurahan::with(['kecamatan', 'users' => function ($q) {
            $q->where('role', 'koordinator');
        }])->when($search, function ($q, $search) {
            $q->where('name', 'like', '%' . $search . '%');
        })->orderBy($sortBy, $sortDir)->paginate(10)->withQueryString();

        if ($request->ajax()) {
            $rows = view('content.admin.kelurahan._table_rows', compact('kelurahans', 'sortBy', 'sortDir', 'search'))->render();
            $pagination = $kelurahans->hasPages() ? $kelurahans->links()->render() : '';
            return response()->json(['rows' => $rows, 'pagination' => $pagination]);
        }

        return view('content.admin.kelurahan.index', compact('kelurahans', 'sortBy', 'sortDir', 'search'));
    }

    public function create()
    {
        $kecamatans = Kecamatan::orderBy('name')->get();
        return view('content.admin.kelurahan.create', compact('kecamatans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:200',
            'kecamatan_id' => 'required|exists:kecamatans,id',
            'is_active' => 'boolean',
        ]);

        Kelurahan::create([
            'name' => strtoupper($request->name),
            'kecamatan_id' => $request->kecamatan_id,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.kelurahan.index')
            ->with('success', 'Kelurahan berhasil ditambahkan.');
    }

    public function edit(Kelurahan $kelurahan)
    {
        $kecamatans = Kecamatan::orderBy('name')->get();
        return view('content.admin.kelurahan.edit', compact('kelurahan', 'kecamatans'));
    }

    public function update(Request $request, Kelurahan $kelurahan)
    {
        $request->validate([
            'name' => 'required|string|max:200',
            'kecamatan_id' => 'required|exists:kecamatans,id',
            'is_active' => 'boolean',
        ]);

        $kelurahan->update([
            'name' => strtoupper($request->name),
            'kecamatan_id' => $request->kecamatan_id,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.kelurahan.index')
            ->with('success', 'Kelurahan berhasil diperbarui.');
    }

    public function destroy(Kelurahan $kelurahan)
    {
        if ($kelurahan->users()->where('role', 'koordinator')->count() > 0) {
            return redirect()->route('admin.kelurahan.index')
                ->with('error', 'Kelurahan tidak bisa dihapus karena masih memiliki koordinator terdaftar.');
        }

        $kelurahan->delete();

        return redirect()->route('admin.kelurahan.index')
            ->with('success', 'Kelurahan berhasil dihapus.');
    }
}
