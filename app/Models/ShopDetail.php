<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShopDetail extends Model
{
    protected $table = 'shop_details';

    protected $primaryKey = 'shop_detail_id';

    public $timestamps = false;

    protected $fillable = [
        'transaksi_id',
        'produk_id',
        'quantity',
        'harga_satuan',
    ];

    /** @return BelongsTo<Transaksi, self> */
    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'transaksi_id', 'transaksi_id');
    }

    /** @return BelongsTo<Produk, self> */
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id', 'produk_id');
    }
}
