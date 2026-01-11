<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
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
            // Nama: wajib, string, max 255 karakter
            'name' => ['required', 'string', 'max:255'],

            // Email: wajib, unik kecuali milik sendiri
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],

            // Phone: regex Indonesia (08... / 628... / +628...)
            'phone' => [
                'nullable',
                'string',
                'max:20',
                'regex:/^(\+62|62|0)8[1-9][0-9]{6,10}$/',
            ],

            // Address: opsional
            'address' => [
                'nullable',
                'string',
                'max:500',
            ],

            // Avatar: Max 2MB, Dimensi aman
            'avatar' => [
                'nullable',
                'image',
                'mimes:jpeg,jpg,png,webp',
                'max:2048',
                'dimensions:min_width=100,min_height=100,max_width=2500,max_height=2500',
            ],

            // Banner (Background): BARU! Max 3MB karena biasanya ukuran landscape lebih besar
            'banner' => [
                'nullable',
                'image',
                'mimes:jpeg,jpg,png,webp',
                'max:3072', // 3MB
            ],
        ];
    }

    /**
     * Pesan error custom dalam Bahasa Indonesia.
     */
    public function messages(): array
    {
        return [
            'name.required'      => 'Nama lengkap tidak boleh kosong.',
            'email.required'     => 'Alamat email tidak boleh kosong.',
            'email.unique'       => 'Email ini sudah digunakan oleh pengguna lain.',
            'phone.regex'        => 'Format nomor telepon tidak valid. Gunakan format 08xx atau +628xx.',
            'avatar.max'         => 'Ukuran foto profil maksimal 2MB.',
            'avatar.image'       => 'File yang diupload harus berupa gambar.',
            'avatar.dimensions'  => 'Dimensi foto profil tidak didukung (minimal 100x100px).',
            'banner.max'         => 'Ukuran background maksimal 3MB.',
            'banner.image'       => 'Background harus berupa file gambar.',
            'banner.mimes'       => 'Format background harus jpeg, jpg, png, atau webp.',
        ];
    }

    /**
     * Penamaan atribut agar pesan error lebih enak dibaca.
     */
    public function attributes(): array
    {
        return [
            'name'    => 'nama lengkap',
            'email'   => 'alamat email',
            'phone'   => 'nomor telepon',
            'address' => 'alamat domisili',
            'avatar'  => 'foto profil',
            'banner'  => 'background profil',
        ];
    }
}