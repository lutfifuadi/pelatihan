<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePengumumanRequest;
use App\Http\Requests\UpdatePengumumanRequest;
use App\Models\Pelatihan;
use App\Models\PengumumanPelatihan;
use Illuminate\Http\Request;

class PengumumanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pengumumans = PengumumanPelatihan::with('pelatihan')
            ->orderBy('is_pinned', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.pengumuman.index', compact('pengumumans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pelatihans = Pelatihan::all();
        return view('admin.pengumuman.create', compact('pelatihans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePengumumanRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();
        $data['is_private'] = $request->has('is_private') ? (bool) $request->input('is_private') : false;
        $data['is_pinned'] = $request->has('is_pinned') ? (bool) $request->input('is_pinned') : false;

        PengumumanPelatihan::create($data);

        return redirect()->route('admin.pengumuman.index')
            ->with('success', 'Pengumuman berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pengumuman = PengumumanPelatihan::findOrFail($id);
        $pelatihans = Pelatihan::all();
        return view('admin.pengumuman.edit', compact('pengumuman', 'pelatihans'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePengumumanRequest $request, string $id)
    {
        $pengumuman = PengumumanPelatihan::findOrFail($id);
        $data = $request->validated();
        $data['is_private'] = $request->has('is_private') ? (bool) $request->input('is_private') : false;
        $data['is_pinned'] = $request->has('is_pinned') ? (bool) $request->input('is_pinned') : false;

        $pengumuman->update($data);

        return redirect()->route('admin.pengumuman.index')
            ->with('success', 'Pengumuman berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $pengumuman = PengumumanPelatihan::findOrFail($id);
        $pengumuman->delete();

        return redirect()->route('admin.pengumuman.index')
            ->with('success', 'Pengumuman berhasil dihapus.');
    }
}
