<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterOption;
use Illuminate\Http\Request;

class FormOptionController extends Controller
{
    /**
     * Tampilkan daftar opsi, bisa difilter berdasarkan group_key.
     */
    public function index(Request $request)
    {
        $groupKey = $request->get('group_key', $request->get('group', 'pendidikan_terakhir'));

        $groups = [
            'pendidikan_terakhir' => 'Pendidikan Terakhir',
            'status_pekerjaan'    => 'Status Pekerjaan',
            'platform_medsos'     => 'Platform Medsos',
        ];

        $options = MasterOption::where('group_key', $groupKey)
            ->orderBy('order')
            ->get();

        $activeGroup = $groupKey;

        return view('content.admin.form-options.index', compact('groups', 'groupKey', 'activeGroup', 'options'));
    }

    /**
     * Tampilkan form tambah opsi.
     */
    public function create(Request $request)
    {
        $groupKey = $request->get('group_key', 'pendidikan_terakhir');

        $groupKeys = [
            'pendidikan_terakhir' => 'Pendidikan Terakhir',
            'status_pekerjaan'    => 'Status Pekerjaan',
            'platform_medsos'     => 'Platform Medsos',
        ];

        return view('content.admin.form-options.create', compact('groupKeys', 'groupKey'));
    }

    /**
     * Simpan opsi baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'group_key' => 'required|in:pendidikan_terakhir,status_pekerjaan,platform_medsos',
            'label'     => 'required|string|max:255',
            'value'     => 'required|string|max:255',
            'order'     => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        MasterOption::create($validated);

        return redirect()->route('admin.form-options.index', ['group_key' => $validated['group_key']])
            ->with('success', 'Opsi berhasil ditambahkan.');
    }

    /**
     * Tampilkan form edit opsi.
     */
    public function edit(MasterOption $masterOption)
    {
        $groupKeys = [
            'pendidikan_terakhir' => 'Pendidikan Terakhir',
            'status_pekerjaan'    => 'Status Pekerjaan',
            'platform_medsos'     => 'Platform Medsos',
        ];

        return view('content.admin.form-options.edit', compact('masterOption', 'groupKeys'));
    }

    /**
     * Update opsi.
     */
    public function update(Request $request, MasterOption $masterOption)
    {
        $validated = $request->validate([
            'group_key' => 'required|in:pendidikan_terakhir,status_pekerjaan,platform_medsos',
            'label'     => 'required|string|max:255',
            'value'     => 'required|string|max:255',
            'order'     => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $masterOption->update($validated);

        return redirect()->route('admin.form-options.index', ['group_key' => $masterOption->group_key])
            ->with('success', 'Opsi berhasil diperbarui.');
    }

    /**
     * Hapus opsi (hard delete).
     */
    public function destroy(MasterOption $masterOption)
    {
        $groupKey = $masterOption->group_key;
        $masterOption->delete();

        return redirect()->route('admin.form-options.index', ['group_key' => $groupKey])
            ->with('success', 'Opsi berhasil dihapus.');
    }

    /**
     * Toggle status aktif/nonaktif opsi.
     */
    public function toggleActive(MasterOption $masterOption)
    {
        $masterOption->update([
            'is_active' => !$masterOption->is_active,
        ]);

        $status = $masterOption->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return response()->json([
            'success' => true,
            'message' => "Opsi berhasil {$status}.",
            'is_active' => $masterOption->fresh()->is_active,
        ]);
    }

    /**
     * Update urutan opsi (reorder).
     * Menerima array id => order atau array of {id, order}.
     */
    public function reorder(Request $request)
    {
        $request->validate([
            'items'     => 'required|array',
            'items.*.id'    => 'required|exists:master_options,id',
            'items.*.order' => 'required|integer|min:0',
        ]);

        foreach ($request->items as $item) {
            MasterOption::where('id', $item['id'])->update(['order' => $item['order']]);
        }

        return response()->json(['success' => true, 'message' => 'Urutan berhasil diperbarui.']);
    }
}
