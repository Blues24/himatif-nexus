<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produk';

    protected $primaryKey = 'produk_id';

    public $incrementing = true;

    protected $keyType = 'int';

    /** @var array<string> */
    protected $fillable = [
        'nama_produk',
        'deskripsi_produk',
        'harga_produk',
        'stok_produk',
        'gambar_produk',
    ];

    protected function casts(): array
    {
        return [
            'harga_produk' => 'decimal:2',
        ];
    }

    /** @return HasMany<ShopDetail,self> */
    public function shopDetails()
    {
        return $this->hasMany(ShopDetail::class, 'produk_id', 'produk_id');
    }
}
