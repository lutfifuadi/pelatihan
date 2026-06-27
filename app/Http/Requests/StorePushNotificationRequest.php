<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePushNotificationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()?->role === 'admin';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:100'],
            'body' => ['required', 'string', 'max:255'],
            'link_url' => ['nullable', 'url', 'max:500'],
            'target_type' => ['required', Rule::in(['all', 'filtered'])],
            'target_filters' => ['nullable', 'array'],
            'target_filters.status' => ['nullable', 'array'],
            'target_filters.status.*' => ['string', Rule::in([
                'pending',
                'approved',
                'waiting_wa_confirmation',
                'waiting_newbimma_check',
                'confirmed',
                'rejected',
                'waitlist',
            ])],
            'target_filters.daerah' => ['nullable', 'array'],
            'target_filters.daerah.*' => ['integer', 'exists:kelurahan,id'],
            'target_filters.pelatihan' => ['nullable', 'array'],
            'target_filters.pelatihan.*' => ['integer', 'exists:pelatihan,id'],
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('target_filters') && is_string($this->input('target_filters'))) {
            $this->merge([
                'target_filters' => json_decode($this->input('target_filters'), true),
            ]);
        }
    }
}
