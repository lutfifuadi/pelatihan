<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kecamatan;
use Illuminate\Http\Request;

class KecamatanController extends Controller
{
    /**
     * Display a listing of kecamatan.
     */
    public function index(Request $request)
    {
        $sortBy = $request->get('sort_by', 'name');
        $sortDir = $request->get('sort_dir', 'asc');
        $search = $request->get('search');

        // Whitelist kolom yang bisa di-sort
        $allowedSort = ['name'];
        if (!in_array($sortBy, $allowedSort)) {
            $sortBy = 'name';
        }
        $sortDir = in_array($sortDir, ['asc', 'desc']) ? $sortDir : 'asc';

        $kecamatans = Kecamatan::with(['users' => function ($q) {
            $q->where('role', 'koordinator');
        }])->when($search, function ($q, $search) {
            $q->where('name', 'like', '%' . $search . '%');
        })->orderBy($sortBy, $sortDir)->paginate(10)->withQueryString();

        if ($request->ajax()) {
            $rows = view('content.admin.kecamatan._table_content', compact('kecamatans', 'sortBy', 'sortDir', 'search'))->render();
            $pagination = $kecamatans->hasPages() ? $kecamatans->links()->render() : '';
            return response()->json(['rows' => $rows, 'pagination' => $pagination]);
        }

        return view('content.admin.kecamatan.index', compact('kecamatans', 'sortBy', 'sortDir', 'search'));
    }

    /**
     * Show the form for creating a new kecamatan.
     */
    public function create()
    {
        return view('content.admin.kecamatan.create');
    }

    /**
     * Store a newly created kecamatan.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:kecamatans,name',
        ]);

        Kecamatan::create(['name' => $request->name]);

        return redirect()->route('admin.kecamatan.index')
            ->with('success', 'Kecamatan berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified kecamatan.
     */
    public function edit(Kecamatan $kecamatan)
    {
        return view('content.admin.kecamatan.edit', compact('kecamatan'));
    }

    /**
     * Update the specified kecamatan.
     */
    public function update(Request $request, Kecamatan $kecamatan)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:kecamatans,name,' . $kecamatan->id,
        ]);

        $kecamatan->update(['name' => $request->name]);

        return redirect()->route('admin.kecamatan.index')
            ->with('success', 'Kecamatan berhasil diperbarui.');
    }

    /**
     * Remove the specified kecamatan.
     */
    public function destroy(Kecamatan $kecamatan)
    {
        // Cek apakah ada user yang terikat
        if ($kecamatan->users()->where('role', 'koordinator')->count() > 0) {
            return redirect()->route('admin.kecamatan.index')
                ->with('error', 'Kecamatan tidak bisa dihapus karena masih memiliki koordinator terdaftar.');
        }

        $kecamatan->delete();

        return redirect()->route('admin.kecamatan.index')
            ->with('success', 'Kecamatan berhasil dihapus.');
    }
}
