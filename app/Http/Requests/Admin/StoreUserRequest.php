<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    /**
     * Tentukan apakah user ini diizinkan membuat request ini.
     * Hanya admin yang boleh.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }

    /**
     * Aturan validasi untuk membuat user baru.
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:255'],

            'email' => ['required', 'email', 'unique:users,email'],

            'whatsapp' => [
                'required',
                'string',
                'min:9',
                'max:20',
                'unique:users,whatsapp',
                // Hanya boleh angka, boleh diawali 08 atau 62
                'regex:/^(08|628|62)[0-9]{7,15}$/',
            ],

            'role' => ['required', 'in:admin,koordinator,instruktur,peserta'],

            'is_active' => ['required', 'in:0,1'],

            'nik' => ['nullable', 'string', 'min:16', 'max:16', 'regex:/^[0-9]{16}$/'],
        ];
    }

    /**
     * Pesan validasi dalam Bahasa Indonesia.
     */
    public function messages(): array
    {
        return [
            'name.required'      => 'Nama wajib diisi.',
            'name.string'        => 'Nama harus berupa teks.',
            'name.min'           => 'Nama minimal :min karakter.',
            'name.max'           => 'Nama maksimal :max karakter.',

            'email.required'     => 'Email wajib diisi.',
            'email.email'        => 'Format email tidak valid.',
            'email.unique'       => 'Email sudah terdaftar di sistem.',

            'whatsapp.required'  => 'Nomor WhatsApp wajib diisi.',
            'whatsapp.string'    => 'Nomor WhatsApp harus berupa teks.',
            'whatsapp.min'       => 'Nomor WhatsApp minimal :min karakter.',
            'whatsapp.max'       => 'Nomor WhatsApp maksimal :max karakter.',
            'whatsapp.unique'    => 'Nomor WhatsApp sudah terdaftar di sistem.',
            'whatsapp.regex'     => 'Format nomor WhatsApp tidak valid. Gunakan format 08xxx atau 628xxx.',

            'role.required'      => 'Role wajib dipilih.',
            'role.in'            => 'Role yang dipilih tidak valid. Pilih salah satu: admin, koordinator, instruktur, peserta.',

            'is_active.required' => 'Status aktif wajib dipilih.',
            'is_active.in'       => 'Status aktif tidak valid.',

            'nik.string'         => 'NIK harus berupa teks.',
            'nik.min'            => 'NIK harus tepat 16 digit.',
            'nik.max'            => 'NIK harus tepat 16 digit.',
            'nik.regex'          => 'NIK hanya boleh terdiri dari 16 angka.',
        ];
    }

    /**
     * Label nama field dalam Bahasa Indonesia.
     */
    public function attributes(): array
    {
        return [
            'name'      => 'Nama',
            'email'     => 'Email',
            'whatsapp'  => 'Nomor WhatsApp',
            'role'      => 'Role',
            'is_active' => 'Status Aktif',
            'nik'       => 'NIK',
        ];
    }
}
