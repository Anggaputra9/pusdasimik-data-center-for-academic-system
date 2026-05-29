<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramStudi extends Model
{
    use HasFactory;

    protected $fillable = [
        'fakultas_id',
        'kode',
        'nama',
    ];

    /**
     * Get the fakultas that owns the program studi.
     */
    public function fakultas()
    {
        return $this->belongsTo(Fakultas::class);
    }

    /**
     * Get the mahasiswas for the program studi.
     */
    public function mahasiswas()
    {
        return $this->hasMany(Mahasiswa::class, 'prodi_id');
    }
}
