<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;

class PesertaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $sortBy = $request->get('sort_by', 'name');
        $sortDir = $request->get('sort_dir', 'asc');
        $filterPelatihan = $request->get('filter_pelatihan', 'all');

        $allowedSort = ['name', 'nik', 'whatsapp', 'created_at'];
        if (!in_array($sortBy, $allowedSort)) {
            $sortBy = 'name';
        }
        $sortDir = in_array($sortDir, ['asc', 'desc']) ? $sortDir : 'asc';

        $pesertas = User::where('role', 'peserta')
            ->with('kecamatan', 'kelurahan', 'pesertaProfile.pelatihan')
            ->when($search, function ($q, $search) {
                $q->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                      ->orWhere('nik', 'like', '%' . $search . '%')
                      ->orWhere('whatsapp', 'like', '%' . $search . '%');
                });
            })
            ->when($filterPelatihan && $filterPelatihan !== 'all', function ($q) use ($filterPelatihan) {
                if ($filterPelatihan === 'sudah') {
                    $q->whereHas('pesertaProfile', fn($q) => $q->whereNotNull('pelatihan_id'));
                } elseif ($filterPelatihan === 'belum') {
                    $q->whereDoesntHave('pesertaProfile', fn($q) => $q->whereNotNull('pelatihan_id'));
                }
            })
            ->orderBy($sortBy, $sortDir)
            ->paginate(10)
            ->withQueryString();

        if ($request->ajax()) {
            $rows = view('content.admin.peserta._table_rows', compact('pesertas', 'sortBy', 'sortDir', 'search', 'filterPelatihan'))->render();
            $pagination = $pesertas->hasPages() ? $pesertas->links()->render() : '';
            return response()->json(['rows' => $rows, 'pagination' => $pagination]);
        }

        return view('content.admin.peserta.index', compact('pesertas', 'sortBy', 'sortDir', 'search', 'filterPelatihan'));
    }

    public function show(User $peserta)
    {
        if ($peserta->role !== 'peserta') {
            abort(404);
        }
        $peserta->load('kecamatan', 'kelurahan', 'pesertaProfile', 'enrollments.pelatihan');
        return view('content.admin.peserta.show', compact('peserta'));
    }

    public function destroy(User $peserta)
    {
        if ($peserta->role !== 'peserta') {
            abort(404);
        }

        $oldData = $peserta->getAttributes();
        $nama = $peserta->name;
        $peserta->delete();

        ActivityLogger::log(
            action: 'deleted',
            subjectType: 'Peserta',
            subjectId: $peserta->id,
            subjectName: $nama,
            description: "Peserta {$nama} berhasil dihapus",
            oldValues: $oldData,
        );

        return redirect()->route('admin.peserta.index')
            ->with('success', 'Peserta berhasil dihapus.');
    }
}
