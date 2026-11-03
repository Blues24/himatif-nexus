<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ProdukStoreRequest extends FormRequest
{
    /** @return array<string, ValidationRule|array<mixed>|string> */
    public function rules(): array
    {
        return [
            'nama_produk' => ['required', 'string', 'max:255'],
            'deskripsi_produk' => ['nullable', 'string'],
            'harga_produk' => ['required', 'numeric', 'min:0', 'max:999999999.99'],
            'stok_produk' => ['required', 'integer', 'min:0'],
            'gambar_produk' => ['nullable', 'image', 'max:2048'], // max 2MB
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'nama_produk.required' => 'Nama produk wajib diisi.',
            'harga_produk.numeric' => 'Harga harus berupa angka.',
            'harga_produk.min' => 'Harga tidak boleh negatif.',
            'stok_produk.integer' => 'Jumlah stok harus berupa angka.',
            'stok_produk.min' => 'Stok tidak boleh negatif.',
            'gambar_produk.image' => 'File harus berupa gambar.',
            'gambar_produk.max' => 'Ukuran gambar maksimal 2 MB.',
        ];
    }
}
