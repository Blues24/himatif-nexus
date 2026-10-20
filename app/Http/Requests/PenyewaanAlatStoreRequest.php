<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class PenyewaanAlatStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, ValidationRule|array<mixed>|string> */
    public function rules(): array
    {
        return [
            'inventaris_alat_id' => ['required', 'integer', 'exists:inventaris_alat,inventaris_id'],
            'jumlah_pinjam' => ['required', 'integer', 'min:1'],
            'tanggal_pinjam' => ['required', 'date'],
            'tanggal_kembali' => ['required', 'date', 'after:tanggal_pinjam'],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'inventaris_alat_id.exists' => 'Alat inventaris tidak ditemukan.',
            'jumlah_pinjam.min' => 'Jumlah pinjam minimal 1.',
            'tanggal_kembali.after' => 'Tanggal kembali harus setelah tanggal pinjam.',
        ];
    }
}
