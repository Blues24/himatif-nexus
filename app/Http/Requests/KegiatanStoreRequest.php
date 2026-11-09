<?php

namespace App\Http\Requests;

use App\Models\Kegiatan;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class KegiatanStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', Kegiatan::class);
    }

    /** @return array<string, ValidationRule|array<mixed>|string> */
    public function rules(): array
    {
        return [
            'nama_kegiatan' => ['required', 'string', 'max:255'],
            'deskripsi_kegiatan' => ['required', 'string'],
            'tanggal_pelaksanaan' => ['required', 'date'],
            'status_kegiatan' => ['required', 'string', 'in:rencana,berjalan,selesai'],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'nama_kegiatan.required' => 'Nama kegiatan wajib diisi.',
            'deskripsi_kegiatan.required' => 'Deskripsi kegiatan wajib diisi.',
            'tanggal_pelaksanaan.required' => 'Tanggal pelaksanaan wajib diisi.',
            'tanggal_pelaksanaan.date' => 'Format tanggal pelaksanaan tidak valid.',
            'status_kegiatan.in' => 'Status kegiatan harus rencana, berjalan, atau selesai.',
        ];
    }
}
