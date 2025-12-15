<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Concerns\SetsDeletedBy;

class Role extends Model
{
    use SoftDeletes, SetsDeletedBy;

    protected $table = 'role';

    protected $primaryKey = 'idrole';

    public $timestamps = false;

    protected $fillable = [
        'nama_role',
        'deleted_by',
    ];

    protected $casts = [
        'deleted_at' => 'datetime',
    ];

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by', 'id');
    }

    public function user()
    {
        return $this->belongsToMany(User::class, 'role_user', 'idrole', 'iduser');
    }
}