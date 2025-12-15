<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\SetsDeletedBy;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pemilik extends Model
{
    use HasFactory, SoftDeletes, SetsDeletedBy;

    protected $table = 'pemilik';

    protected $primaryKey = 'idpemilik';

    public $timestamps = false;

    protected $fillable = [
        'no_wa',
        'alamat',
        'iduser',
        'deleted_by',
    ];

    protected $casts = [
        'deleted_at' => 'datetime',
    ];

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by', 'id');
    }

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'iduser', 'id');
    }

    // Relasi ke Pet (Hewan)
    public function pets()
    {
        return $this->hasMany(Pet::class, 'idpemilik', 'idpemilik');
    }
}