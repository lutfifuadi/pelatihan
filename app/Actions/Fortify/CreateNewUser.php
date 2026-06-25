<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'nik' => ['required', 'string', 'digits_between:15,16', 'unique:users,nik'],
            'whatsapp' => ['required', 'string', 'max:20'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'sumber_informasi' => ['required', 'string', 'in:koordinator,sosmed,lainnya'],
            'sumber_informasi_detail' => ['nullable', 'string', 'max:255'],
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ], [
            'nik.unique' => 'NIK ini sudah terdaftar. Silakan login saja.',
            'nik.digits_between' => 'NIK harus 15 atau 16 digit.',
            'whatsapp.required' => 'Nomor WhatsApp wajib diisi.',
            'sumber_informasi.required' => 'Silakan pilih sumber informasi pelatihan.',
        ])->validate();

        // Auto-convert WA number: 08xxx -> 628xxx
        $whatsapp = $input['whatsapp'];
        $whatsapp = ltrim($whatsapp, '+');
        if (str_starts_with($whatsapp, '0')) {
            $whatsapp = '62' . substr($whatsapp, 1);
        } elseif (!str_starts_with($whatsapp, '62')) {
            $whatsapp = '62' . $whatsapp;
        }

        $user = User::create([
            'name' => $input['name'],
            'nik' => $input['nik'],
            'whatsapp' => $whatsapp,
            'email' => $input['email'],
            'sumber_informasi' => $input['sumber_informasi'],
            'sumber_informasi_detail' => $input['sumber_informasi_detail'] ?? null,
            'password' => Hash::make('pelatihanku2026'),
            'role' => 'peserta',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        session()->flash('new_registration', true);

        return $user;
    }
}
