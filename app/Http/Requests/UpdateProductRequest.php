<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
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
        // Ambil ID produk dari route dengan aman
        $product   = $this->route('product');
        $productId = is_object($product) ? $product->id : $product;

        return [
            // Field utama produk
            'name'            => ['required', 'string', 'max:255', Rule::unique('products')->ignore($productId)],
            'description'     => ['nullable', 'string'],
            'price'           => ['required', 'numeric', 'min:0'],
            'discount_price'  => ['nullable', 'numeric', 'min:0', 'lt:price'],
            'stock'           => ['required', 'integer', 'min:0'],
            'weight'          => ['required', 'integer', 'min:1'],
            'category_id'     => ['required', 'exists:categories,id'],

            // PERBAIKAN VALIDASI VIDEO: Menggunakan mimetypes agar lebih akurat
            'video'           => [
                'nullable',
                'file',
                'mimetypes:video/mp4,video/quicktime,video/x-msvideo,video/x-matroska',
                'max:51200', // Dinaikkan ke 50MB agar lebih aman
            ],
            'delete_video'    => ['nullable', 'boolean'],

            // Gambar baru (opsional)
            'images'          => ['nullable', 'array'],
            'images.*'        => ['image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'],

            // Gambar yang akan dihapus
            'delete_images'   => ['nullable', 'array'],
            'delete_images.*' => ['exists:product_images,id'],

            // Set gambar utama
            'primary_image'   => ['nullable', 'exists:product_images,id'],

            // Status
            'is_active'       => ['nullable', 'boolean'],
            'is_featured'     => ['nullable', 'boolean'],
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'is_active'    => $this->has('is_active'),
            'is_featured'  => $this->has('is_featured'),
            'delete_video' => $this->has('delete_video'),
        ]);
    }

    /**
     * Custom message
     */
    public function messages(): array
    {
        return [
            'name.required'        => 'Nama produk wajib diisi.',
            'name.unique'          => 'Nama produk sudah digunakan.',
            'price.required'       => 'Harga wajib diisi.',
            'category_id.required' => 'Pilih kategori produk.',
            'weight.required'      => 'Berat produk wajib diisi untuk ongkir.',
            'video.mimetypes'      => 'Format video tidak didukung. Gunakan MP4, MOV, atau AVI.',
            'video.max'            => 'Ukuran video terlalu besar (Maksimal 50MB).',
            'discount_price.lt'    => 'Harga diskon harus lebih rendah dari harga normal.',
        ];
    }
}
