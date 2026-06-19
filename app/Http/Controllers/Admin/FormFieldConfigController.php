<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FormFieldConfig;
use Illuminate\Http\Request;

class FormFieldConfigController extends Controller
{
    /**
     * Tampilkan daftar field config, difilter berdasarkan section.
     */
    public function index(Request $request)
    {
        $section = $request->get('section', 'data_pribadi');

        $sections = [
            'data_pribadi'  => 'Data Pribadi',
            'alamat_kontak' => 'Alamat & Kontak',
            'pendidikan'    => 'Pendidikan & Pekerjaan',
            'minat'         => 'Minat Pelatihan',
            'dokumen'       => 'Dokumen',
        ];

        $fields = FormFieldConfig::where('section', $section)
            ->orderBy('order')
            ->get();

        return view('content.admin.form-config.index', compact('sections', 'section', 'fields'));
    }

    /**
     * Tampilkan form edit field config (untuk modal/sidebar).
     */
    public function edit(FormFieldConfig $formFieldConfig)
    {
        $widthOptions = [
            'full'  => 'Full (100%)',
            'half'  => 'Half (50%)',
            'third' => 'Third (33%)',
        ];

        return view('content.admin.form-config.edit', compact('formFieldConfig', 'widthOptions'));
    }

    /**
     * Update konfigurasi field.
     */
    public function update(Request $request, FormFieldConfig $formFieldConfig)
    {
        $validated = $request->validate([
            'label'       => 'required|string|max:255',
            'placeholder' => 'nullable|string|max:255',
            'is_required' => 'nullable|boolean',
            'is_active'   => 'nullable|boolean',
            'width'       => 'nullable|in:full,half,third',
            'order'       => 'nullable|integer|min:0',
        ]);

        $formFieldConfig->update($validated);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Field berhasil diperbarui.']);
        }

        return redirect()->route('admin.form-config.index', ['section' => $formFieldConfig->section])
            ->with('success', 'Field berhasil diperbarui.');
    }

    /**
     * Toggle status aktif/nonaktif field.
     */
    public function toggleActive(FormFieldConfig $formFieldConfig)
    {
        $formFieldConfig->update([
            'is_active' => !$formFieldConfig->is_active,
        ]);

        $status = $formFieldConfig->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return response()->json([
            'success' => true,
            'message' => "Field berhasil {$status}.",
            'is_active' => $formFieldConfig->fresh()->is_active,
        ]);
    }

    /**
     * Toggle status required/optional field.
     */
    public function toggleRequired(FormFieldConfig $formFieldConfig)
    {
        $formFieldConfig->update([
            'is_required' => !$formFieldConfig->is_required,
        ]);

        $status = $formFieldConfig->is_required ? 'required' : 'optional';

        return response()->json([
            'success' => true,
            'message' => "Field sekarang {$status}.",
            'is_required' => $formFieldConfig->fresh()->is_required,
        ]);
    }

    /**
     * Update urutan field dalam section.
     * Menerima array items dengan id dan order.
     */
    public function reorder(Request $request)
    {
        $request->validate([
            'items'      => 'required|array',
            'items.*.id'    => 'required|exists:form_field_configs,id',
            'items.*.order' => 'required|integer|min:0',
        ]);

        foreach ($request->items as $item) {
            FormFieldConfig::where('id', $item['id'])->update(['order' => $item['order']]);
        }

        return response()->json(['success' => true, 'message' => 'Urutan field berhasil diperbarui.']);
    }
}
