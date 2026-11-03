<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class InventarisStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, ValidationRule|array<mixed>|string> */
    public function rules(): array
    {
        return [
            'nama_barang' => ['required', 'string', 'max:255'],
            'stok' => ['required', 'integer', 'min:0'],
            'kondisi' => ['required', 'string', 'in:baik,rusak,hilang'],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'nama_barang.required' => 'Nama barang wajib diisi.',
            'stok.integer' => 'Jumlah stok harus berupa angka.',
            'stok.min' => 'Stok tidak boleh negatif.',
            'kondisi.in' => 'Kondisi harus baik, rusak, atau hilang.',
        ];
    }
}
