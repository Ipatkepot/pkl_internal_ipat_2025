<?php
// app/Http/Requests/StoreProductRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    /**
     * Tentukan apakah user diizinkan membuat request ini.
     */
    public function authorize(): bool
    {
        // Pastikan login dan role adalah admin
        return auth()->check() && auth()->user()->role === 'admin';
    }

    /**
     * Aturan validasi untuk data yang dikirim.
     */
    public function rules(): array
    {
        return [
            'category_id'    => ['required', 'exists:categories,id'],
            'name'           => ['required', 'string', 'max:255'],
            'description'    => ['nullable', 'string'],

            // Harga minimal 1000 rupiah
            'price'          => ['required', 'numeric', 'min:1000'],

            // Harga diskon (opsional), harus lebih kecil dari harga asli
            'discount_price' => ['nullable', 'numeric', 'min:0', 'lt:price'],

            'stock'          => ['required', 'integer', 'min:0'],
            'weight'         => ['required', 'integer', 'min:1'], // Berat minimal 1 gram

            'is_active'      => ['boolean'],
            'is_featured'    => ['boolean'],

            // Validasi Video Baru
            // max:20480 = 20MB
            'video'          => ['nullable', 'file', 'mimes:mp4,mov,avi', 'max:20480'],

            // Validasi Array Gambar
            'images'         => ['nullable', 'array', 'max:10'],
            'images.*'       => [
                'image',
                'mimes:jpg,png,webp',
                'max:2048', // Maksimal 2MB per file
            ],
        ];
    }

    /**
     * Persiapkan data sebelum validasi dijalankan.
     */
    protected function prepareForValidation(): void
    {
        // Normalisasi checkbox agar selalu bernilai boolean true/false
        $this->merge([
            'is_active'   => $this->boolean('is_active'),
            'is_featured' => $this->boolean('is_featured'),
        ]);
    }

    /**
     * Pesan error kustom (Opsional)
     */
    public function messages(): array
    {
        return [
            'video.mimes' => 'Format video yang didukung hanya mp4, mov, dan avi.',
            'video.max'   => 'Ukuran video terlalu besar, maksimal 20MB.',
            'discount_price.lt' => 'Harga diskon harus lebih rendah dari harga asli.',
        ];
    }
}