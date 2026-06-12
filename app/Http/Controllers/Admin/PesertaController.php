<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class PesertaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $sortBy = $request->get('sort_by', 'name');
        $sortDir = $request->get('sort_dir', 'asc');

        $allowedSort = ['name', 'nik', 'whatsapp', 'created_at'];
        if (!in_array($sortBy, $allowedSort)) {
            $sortBy = 'name';
        }
        $sortDir = in_array($sortDir, ['asc', 'desc']) ? $sortDir : 'asc';

        $pesertas = User::where('role', 'peserta')
            ->with('kecamatan', 'kelurahan')
            ->when($search, function ($q, $search) {
                $q->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                      ->orWhere('nik', 'like', '%' . $search . '%')
                      ->orWhere('whatsapp', 'like', '%' . $search . '%');
                });
            })
            ->orderBy($sortBy, $sortDir)
            ->paginate(10)
            ->withQueryString();

        if ($request->ajax()) {
            $rows = view('content.admin.peserta._table_rows', compact('pesertas', 'sortBy', 'sortDir', 'search'))->render();
            $pagination = $pesertas->hasPages() ? $pesertas->links()->render() : '';
            return response()->json(['rows' => $rows, 'pagination' => $pagination]);
        }

        return view('content.admin.peserta.index', compact('pesertas', 'sortBy', 'sortDir', 'search'));
    }

    public function show(User $peserta)
    {
        if ($peserta->role !== 'peserta') {
            abort(404);
        }
        $peserta->load('kecamatan', 'kelurahan', 'pesertaProfile');
        return view('content.admin.peserta.show', compact('peserta'));
    }

    public function destroy(User $peserta)
    {
        if ($peserta->role !== 'peserta') {
            abort(404);
        }
        $peserta->delete();
        return redirect()->route('admin.peserta.index')
            ->with('success', 'Peserta berhasil dihapus.');
    }
}
