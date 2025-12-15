<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Concerns\SetsDeletedBy;

class JenisHewan extends Model
{
    use SoftDeletes, SetsDeletedBy;

    protected $table = 'jenis_hewan';

    protected $primaryKey = 'idjenis_hewan';

    public $timestamps = false;

    protected $fillable = [
        'nama_jenis_hewan',
        'deleted_by',
    ];

    protected $casts = [
        'deleted_at' => 'datetime',
    ];

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by', 'id');
    }

    public function rasHewan()
    {
        return $this->hasMany(RasHewan::class, 'idjenis_hewan', 'idjenis_hewan');
    }
}