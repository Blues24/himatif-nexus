<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Transaksi extends Model
{
    use HasFactory;

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

    /** @return BelongsToMany<Produk, self> */
    public function produk()
    {
        return $this->belongsToMany(Produk::class, 'shop_details', 'transaksi_id', 'produk_id')
            ->withPivot('quantity', 'harga_satuan')
            ->withTimestamps();
    }
}
