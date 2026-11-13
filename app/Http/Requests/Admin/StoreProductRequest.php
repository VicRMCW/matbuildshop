<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id' => ['required', 'exists:categories,id'],
            'name'        => ['required', 'string', 'max:150'],
            'description' => ['required', 'string'],
            'price'       => ['required', 'integer', 'min:1'],
            'stock'       => ['required', 'integer', 'min:0'],
            'image'       => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'is_active'   => ['nullable', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        // Checkbox HTML tidak mengirim data jika tidak dicentang, normalisasikan ke boolean.
        $this->merge([
            'is_active' => $this->boolean('is_active'),
        ]);
    }
}
