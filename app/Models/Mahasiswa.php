<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    public const STATUSES = ['aktif', 'cuti', 'lulus', 'do'];

    public const STATUS_LABELS = [
        'aktif' => 'Aktif',
        'cuti' => 'Cuti',
        'lulus' => 'Lulus',
        'do' => 'Drop Out',
    ];

    protected $table = 'mahasiswas';

    protected $primaryKey = 'nim';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'nim',
        'nama',
        'unit_id',
        'tahun_akademik',
        'status',
    ];

    public function getRouteKeyName()
    {
        return 'nim';
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_LABELS[$this->status] ?? ucfirst((string) $this->status);
    }
}
