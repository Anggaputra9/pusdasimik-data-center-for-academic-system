<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fakultas extends Model
{
    use HasFactory;

    protected $table = 'fakultas';

    protected $fillable = [
        'kode',
        'nama',
    ];

    /**
     * Get the program studis for the fakultas.
     */
    public function programStudis()
    {
        return $this->hasMany(ProgramStudi::class);
    }

    /**
     * Get all mahasiswas through program studis.
     */
    public function mahasiswas()
    {
        return $this->hasManyThrough(Mahasiswa::class, ProgramStudi::class, 'fakultas_id', 'prodi_id');
    }
}
