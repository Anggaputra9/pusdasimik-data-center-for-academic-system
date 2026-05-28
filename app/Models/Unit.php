<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'kode',
        'nama',
        'tipe',
    ];

    /**
     * Get the parent unit (for prodi, this is fakultas).
     */
    public function parent()
    {
        return $this->belongsTo(Unit::class, 'parent_id');
    }

    /**
     * Get the children units (for fakultas, these are prodis).
     */
    public function children()
    {
        return $this->hasMany(Unit::class, 'parent_id');
    }

    /**
     * Get all mahasiswas for this unit (only for prodi).
     */
    public function mahasiswas()
    {
        return $this->hasMany(Mahasiswa::class, 'unit_id');
    }

    /**
     * Scope to get only fakultas.
     */
    public function scopeFakultas($query)
    {
        return $query->where('tipe', 'fakultas');
    }

    /**
     * Scope to get only prodi.
     */
    public function scopeProdi($query)
    {
        return $query->where('tipe', 'prodi');
    }
}
