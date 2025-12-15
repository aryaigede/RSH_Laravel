<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\SetsDeletedBy;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetailRekamMedis extends Model
{
    use HasFactory, SoftDeletes, SetsDeletedBy;

    protected $table = 'detail_rekam_medis';

    protected $primaryKey = 'iddetail_rekam_medis';

    public $timestamps = false;

    protected $fillable = [
        'idrekam_medis',
        'idkode_tindakan_terapi',
        'detail',
        'deleted_by',
    ];

    protected $casts = [
        'deleted_at' => 'datetime',
    ];

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by', 'id');
    }

    // Relasi ke Rekam Medis
    public function rekamMedis()
    {
        return $this->belongsTo(RekamMedis::class, 'idrekam_medis', 'idrekam_medis');
    }

    // Relasi ke Kode Tindakan Terapi
    public function kodeTindakanTerapi()
    {
        return $this->belongsTo(KodeTindakanTerapi::class, 'idkode_tindakan_terapi', 'idkode_tindakan_terapi');
    }
}
