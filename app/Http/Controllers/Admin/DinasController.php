<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dinas;
use Illuminate\Http\Request;

class DinasController extends Controller
{
    public function index()
    {
        $dinas = Dinas::orderBy('nama_dinas')->paginate(15);
        return view('content.admin.dinas.index', compact('dinas'));
    }

    public function create()
    {
        return view('content.admin.dinas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_dinas' => 'required|string|max:200|unique:dinas,nama_dinas',
            'singkatan' => 'nullable|string|max:50',
            'is_active' => 'boolean',
        ]);

        Dinas::create([
            'nama_dinas' => $request->nama_dinas,
            'singkatan' => $request->singkatan,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.dinas.index')
            ->with('success', 'Dinas berhasil ditambahkan.');
    }

    public function edit(Dinas $dinas)
    {
        return view('content.admin.dinas.edit', compact('dinas'));
    }

    public function update(Request $request, Dinas $dinas)
    {
        $request->validate([
            'nama_dinas' => 'required|string|max:200|unique:dinas,nama_dinas,' . $dinas->id,
            'singkatan' => 'nullable|string|max:50',
            'is_active' => 'boolean',
        ]);

        $dinas->update([
            'nama_dinas' => $request->nama_dinas,
            'singkatan' => $request->singkatan,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.dinas.index')
            ->with('success', 'Dinas berhasil diperbarui.');
    }

    public function destroy(Dinas $dinas)
    {
        if ($dinas->pelatihans()->count() > 0) {
            return redirect()->route('admin.dinas.index')
                ->with('error', 'Dinas tidak bisa dihapus karena masih memiliki pelatihan terkait.');
        }

        $dinas->delete();

        return redirect()->route('admin.dinas.index')
            ->with('success', 'Dinas berhasil dihapus.');
    }

    public function show(Dinas $dinas)
    {
        return redirect()->route('admin.dinas.index');
    }
}
