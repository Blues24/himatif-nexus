<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    use HasFactory;
    protected $table = 'roles';

    protected $primaryKey = 'role_id';

    public $timestamps = false;

    /** @var array<string> */
    protected $fillable = ['nama_role'];

    /** @return HasMany<User,self> */
    public function users()
    {
        return $this->hasMany(User::class, 'role_id', 'role_id');
    }
}
