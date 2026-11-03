<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FormatifStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'judul' => ['required', 'string', 'max:255'],
            'kategori' => ['required', 'string', 'max:255'],
            'text_konten' => ['required', 'string'],
            'thumbnail_path' => ['nullable', 'image', 'max:2048'],
            'status' => ['required', 'string', 'in:draft,publish'],
        ];
    }

    public function messages(): array
    {
        return [
            'judul.required' => 'Judul wajib diisi.',
            'kategori.required' => 'Kategori wajib diisi.',
            'text_konten.required' => 'Konten wajib diisi.',
            'thumbnail_path.image' => 'File harus berupa gambar.',
            'thumbnail_path.max' => 'Ukuran gambar maksimal 2 MB.',
            'status.in' => 'Status harus draft atau publish.',
        ];
    }
}
