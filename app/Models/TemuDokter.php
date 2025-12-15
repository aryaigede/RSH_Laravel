<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TemuDokter extends Model
{
    use SoftDeletes;

    public const STATUS_NEW = 'N';
    public const STATUS_FINISHED = 'F';

    protected $table = 'temu_dokter';

    protected $primaryKey = 'idreservasi_dokter';

    public $timestamps = false;

    protected $fillable = [
        'no_urut',
        'waktu_daftar',
        'status',
        'idpet',
        'idrole_user',
        'idrekam_medis',
        'deleted_by',
    ];

    protected $casts = [
        'waktu_daftar' => 'datetime',
    ];

    // Human-readable status label
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_NEW => 'New',
            self::STATUS_FINISHED => 'Finished',
            default => $this->status ?? '-',
        };
    }

    public function pet()
    {
        return $this->belongsTo(Pet::class, 'idpet', 'idpet');
    }

    public function roleUser()
    {
        return $this->belongsTo(RoleUser::class, 'idrole_user', 'idrole_user');
    }

    public function rekamMedis()
    {
        return $this->belongsTo(RekamMedis::class, 'idrekam_medis', 'idrekam_medis');
    }
}
