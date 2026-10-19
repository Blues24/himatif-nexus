<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaksi extends Model
{
    protected $table = 'transaksi';

    protected $primaryKey = 'transaksi_id';

    public $incrementing = true;

    protected $keyType = 'int';

    /** @var array<string> */
    protected $fillable = [
        'user_id',
        'total_bayar',
        'status_transaksi',
    ];

    protected function casts(): array
    {
        return [
            'total_bayar' => 'decimal:2',
        ];
    }

    /** @return BelongsTo<User, self> */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_uuid');
    }
}
