<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Concerns\SetsDeletedBy;

class KategoriKlinis extends Model
{
    use SoftDeletes, SetsDeletedBy;

    protected $table = 'kategori_klinis';

    protected $primaryKey = 'idkategori_klinis';

    public $timestamps = false;

    protected $fillable = [
        'nama_kategori_klinis',
        'deleted_by',
    ];

    protected $casts = [
        'deleted_at' => 'datetime',
    ];

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by', 'id');
    }

    public function kodeTindakanTerapi()
    {
        return $this->hasMany(KodeTindakanTerapi::class, 'idkategori_klinis', 'idkategori_klinis');
    }
}