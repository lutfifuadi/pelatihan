<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePengumumanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Otorisasi admin diserahkan ke policy/middleware, return true di request class ini
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'pelatihan_id' => 'nullable|exists:pelatihan,id',
            'judul' => 'required|string|max:255',
            'konten' => 'required|string',
            'is_private' => 'boolean|nullable',
            'is_pinned' => 'boolean|nullable',
        ];
    }
}
