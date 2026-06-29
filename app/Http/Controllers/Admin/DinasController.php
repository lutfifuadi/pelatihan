<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dinas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            'logo' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048',
            'is_active' => 'boolean',
        ]);

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('dinas/logos', 'public');
        }

        Dinas::create([
            'nama_dinas' => $request->nama_dinas,
            'singkatan' => $request->singkatan,
            'logo' => $logoPath,
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
            'logo' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048',
            'is_active' => 'boolean',
        ]);

        $logoPath = $dinas->logo;
        if ($request->hasFile('logo')) {
            if ($dinas->logo && Storage::disk('public')->exists($dinas->logo)) {
                Storage::disk('public')->delete($dinas->logo);
            }
            $logoPath = $request->file('logo')->store('dinas/logos', 'public');
        }

        $dinas->update([
            'nama_dinas' => $request->nama_dinas,
            'singkatan' => $request->singkatan,
            'logo' => $logoPath,
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

        if ($dinas->logo && Storage::disk('public')->exists($dinas->logo)) {
            Storage::disk('public')->delete($dinas->logo);
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
