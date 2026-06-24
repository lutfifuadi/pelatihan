<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\WhatsAppNumberRequest;
use App\Models\WhatsappNumber;
use Illuminate\Http\Request;

class WhatsAppSupportController extends Controller
{
    public function index()
    {
        $numbers = WhatsappNumber::sorted()->get();
        return response()->json($numbers);
    }

    public function store(WhatsAppNumberRequest $request)
    {
        $number = WhatsappNumber::create($request->validated());
        return response()->json(['message' => 'Nomor berhasil ditambahkan', 'data' => $number]);
    }

    public function update(WhatsAppNumberRequest $request, $id)
    {
        $number = WhatsappNumber::findOrFail($id);
        $number->update($request->validated());
        return response()->json(['message' => 'Nomor berhasil diupdate', 'data' => $number]);
    }

    public function destroy($id)
    {
        $number = WhatsappNumber::findOrFail($id);
        $number->delete();
        return response()->json(['message' => 'Nomor berhasil dihapus']);
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:whatsapp_numbers,id',
        ]);

        foreach ($request->ids as $index => $id) {
            WhatsappNumber::where('id', $id)->update(['sort_order' => $index]);
        }

        return response()->json(['message' => 'Urutan berhasil diupdate']);
    }

    public function toggleActive($id)
    {
        $number = WhatsappNumber::findOrFail($id);
        $number->update(['is_active' => !$number->is_active]);
        return response()->json(['message' => 'Status berhasil diubah', 'is_active' => $number->fresh()->is_active]);
    }
}
