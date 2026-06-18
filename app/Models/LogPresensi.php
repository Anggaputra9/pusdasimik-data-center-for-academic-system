<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogPresensi extends Model
{
    protected $table = 'log_presensi';

    protected $fillable = [
        'nim_mahasiswa',
        'nama_mahasiswa',
        'kode_kelas',
        'nama_mata_kuliah',
        'status_kehadiran',
        'waktu',
        'sistem_asal',
    ];

    protected $casts = [
        'waktu' => 'datetime',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'nim_mahasiswa', 'nim');
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status_kehadiran) {
            'hadir' => 'bg-green-100 text-green-800',
            'terlambat' => 'bg-yellow-100 text-yellow-800',
            'izin' => 'bg-blue-100 text-blue-800',
            'sakit' => 'bg-purple-100 text-purple-800',
            'alpha' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status_kehadiran) {
            'hadir' => 'Hadir',
            'terlambat' => 'Terlambat',
            'izin' => 'Izin',
            'sakit' => 'Sakit',
            'alpha' => 'Alpha',
            default => ucfirst($this->status_kehadiran),
        };
    }
}
