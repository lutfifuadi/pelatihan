<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class WhatsAppNumberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('id');

        return [
            'label' => 'required|string|max:100',
            'number' => [
                'required',
                'string',
                'regex:/^[0-9]+$/',
                'min:10',
                'max:15',
                Rule::unique('whatsapp_numbers')->ignore($id),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'label.required' => 'Label wajib diisi',
            'number.required' => 'Nomor WhatsApp wajib diisi',
            'number.regex' => 'Nomor hanya boleh berisi angka',
            'number.min' => 'Nomor minimal 10 digit',
            'number.max' => 'Nomor maksimal 15 digit',
            'number.unique' => 'Nomor sudah terdaftar',
        ];
    }
}
