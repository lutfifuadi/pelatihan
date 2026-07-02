<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePengumumanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'pelatihan_id' => 'nullable|exists:pelatihan,id',
            'judul' => 'sometimes|required|string|max:255',
            'konten' => 'sometimes|required|string',
            'is_private' => 'boolean|nullable',
            'is_pinned' => 'boolean|nullable',
        ];
    }
}
