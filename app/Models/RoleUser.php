<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Concerns\SetsDeletedBy;

class RoleUser extends Model
{
    use SoftDeletes, SetsDeletedBy;

    protected $table = 'role_user';

    protected $primaryKey = 'idrole_user';

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'iduser',
        'idrole',
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
        return $this->belongsTo(User::class, 'iduser', 'id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'idrole', 'idrole');
    }
}