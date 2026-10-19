<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Formatif extends Model
{
    protected $table = 'formatif';

    protected $primaryKey = 'formatif_id';

    public $incrementing = true;

    protected $keyType = 'int';

    /** @var array<string> */
    protected $fillable = [
        'user_id',
        'judul',
        'slug',
        'kategori',
        'text_konten',
        'thumbnail_path',
        'status',
    ];

    /** @return BelongsTo<User, self> */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_uuid');
    }
}
