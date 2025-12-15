<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Concerns\SetsDeletedBy;

class RasHewan extends Model
{
    use SoftDeletes, SetsDeletedBy;

    protected $table = 'ras_hewan';

    protected $primaryKey = 'idras_hewan';

    public $timestamps = false;

    protected $fillable = [
        'nama_ras',
        'idjenis_hewan',
        'deleted_by',
    ];

    protected $casts = [
        'deleted_at' => 'datetime',
    ];

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by', 'id');
    }

    public function jenisHewan()
    {
        return $this->belongsTo(JenisHewan::class, 'idjenis_hewan', 'idjenis_hewan');
    }
}