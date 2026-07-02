<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PengumumanPelatihan;
use App\Http\Requests\StorePengumumanRequest;
use App\Http\Requests\UpdatePengumumanRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class PengumumanPelatihanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        Gate::authorize('create', PengumumanPelatihan::class);

        $pengumuman = PengumumanPelatihan::with('pelatihan')->latest()->paginate(15);

        return response()->json([
            'success' => true,
            'message' => 'Daftar semua pengumuman pelatihan berhasil diambil.',
            'data' => $pengumuman
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePengumumanRequest $request): JsonResponse
    {
        Gate::authorize('create', PengumumanPelatihan::class);

        $validated = $request->validated();
        $validated['user_id'] = auth()->id();
        $validated['is_private'] = $request->boolean('is_private', false);

        $pengumuman = PengumumanPelatihan::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Pengumuman pelatihan berhasil dibuat.',
            'data' => $pengumuman->load('pelatihan')
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(PengumumanPelatihan $pengumuman): JsonResponse
    {
        Gate::authorize('update', $pengumuman);

        return response()->json([
            'success' => true,
            'message' => 'Detail pengumuman pelatihan berhasil diambil.',
            'data' => $pengumuman->load('pelatihan')
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePengumumanRequest $request, PengumumanPelatihan $pengumuman): JsonResponse
    {
        Gate::authorize('update', $pengumuman);

        $validated = $request->validated();
        if ($request->has('is_private')) {
            $validated['is_private'] = $request->boolean('is_private');
        }

        $pengumuman->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Pengumuman pelatihan berhasil diperbarui.',
            'data' => $pengumuman->load('pelatihan')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PengumumanPelatihan $pengumuman): JsonResponse
    {
        Gate::authorize('delete', $pengumuman);

        $pengumuman->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pengumuman pelatihan berhasil dihapus.'
        ]);
    }
}
