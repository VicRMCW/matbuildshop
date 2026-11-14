<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Akses sudah dibatasi middleware auth dan role:customer di route.
        return true;
    }

    protected function prepareForValidation(): void
    {
        // Pembeli sering mengetik spasi atau tanda hubung di nomor telepon.
        $this->merge([
            'phone' => preg_replace('/[\s\-]/', '', (string) $this->input('phone')),
        ]);
    }

    public function rules(): array
    {
        return [
            'recipient_name' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'regex:/^(\+62|62|0)[0-9]{8,13}$/'],
            'shipping_address' => ['required', 'string', 'min:10', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'recipient_name.required' => 'Nama penerima wajib diisi.',
            'recipient_name.max' => 'Nama penerima maksimal 100 karakter.',
            'phone.required' => 'Nomor telepon wajib diisi.',
            'phone.regex' => 'Nomor telepon tidak valid. Contoh: 08123456789.',
            'shipping_address.required' => 'Alamat pengiriman wajib diisi.',
            'shipping_address.min' => 'Alamat pengiriman terlalu singkat. Tulis jalan, nomor, dan kota.',
            'shipping_address.max' => 'Alamat pengiriman maksimal 500 karakter.',
        ];
    }
}