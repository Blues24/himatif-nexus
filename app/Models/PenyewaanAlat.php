<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PenyewaanAlat extends Model
{
    protected $table = 'penyewaan_alats';

    /** @var array<string> */
    protected $fillable = [
        'inventaris_alat_id',
        'user_id',
        'jumlah_pinjam',
        'tanggal_pinjam',
        'tanggal_kembali',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_pinjam' => 'date',
            'tanggal_kembali' => 'date',
        ];
    }

    /** @return BelongsTo<Inventaris, self> */
    public function inventarisAlat()
    {
        return $this->belongsTo(Inventaris::class, 'inventaris_alat_id', 'inventaris_id');
    }

    /** @return BelongsTo<User, self> */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_uuid');
    }
}
