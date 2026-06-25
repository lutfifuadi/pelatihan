<?php

namespace App\Http\Controllers;

use App\Models\KtaMember;
use Illuminate\Http\Request;

class KtaMemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ktaMembers = KtaMember::latest()->paginate(10);
        return view('admin.kta-members.index', compact('ktaMembers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.kta-members.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required|string|size:16|unique:kta_members,nik',
            'nama_lengkap' => 'required|string|max:255',
            'status_kta' => 'required|in:Aktif,Tidak Aktif',
            'wilayah' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        KtaMember::create($request->all());

        return redirect()->route('admin.kta-members.index')
                         ->with('success', 'Anggota KTA berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(KtaMember $ktaMember)
    {
        return view('admin.kta-members.show', compact('ktaMember'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(KtaMember $ktaMember)
    {
        return view('admin.kta-members.edit', compact('ktaMember'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, KtaMember $ktaMember)
    {
        $request->validate([
            'nik' => 'required|string|size:16|unique:kta_members,nik,' . $ktaMember->id,
            'nama_lengkap' => 'required|string|max:255',
            'status_kta' => 'required|in:Aktif,Tidak Aktif',
            'wilayah' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        $ktaMember->update($request->all());

        return redirect()->route('admin.kta-members.index')
                         ->with('success', 'Data Anggota KTA berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(KtaMember $ktaMember)
    {
        $ktaMember->delete();

        return redirect()->route('admin.kta-members.index')
                         ->with('success', 'Anggota KTA berhasil dihapus.');
    }
}

