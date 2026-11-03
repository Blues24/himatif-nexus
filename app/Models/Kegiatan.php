<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kegiatan extends Model
{
    use HasFactory;
    protected $table = 'kegiatan';

    protected $primaryKey = 'kegiatan_id';

    public $incrementing = true;

    protected $keyType = 'int';



    /** @var array<string> */
    protected $fillable = [
        'nama_kegiatan',
        'deskripsi_kegiatan',
        'tanggal_pelaksanaan',
        'status_kegiatan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_pelaksanaan' => 'date',
        ];
    }
}
