<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Inventaris extends Model
{
    protected $table = 'inventaris_alat';

    protected $primaryKey = 'inventaris_id';

    public $incrementing = true;

    protected $keyType = 'int';

    /** @var array<string> */
    protected $fillable = [
        'nama_barang',
        'stok',
        'kondisi',
    ];

    /** @return HasMany<PenyewaanAlat,self> */
    public function penyewaanAlats()
    {
        return $this->hasMany(PenyewaanAlat::class, 'inventaris_alat_id', 'inventaris_id');
    }
}
