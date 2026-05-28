<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    protected $primaryKey = 'nim';
    public $incrementing = false;
    protected $keyType = 'string';
    
    protected $fillable = [
        'nim',
        'nama',
        'unit_id',
    ];

    /**
     * Get the unit (prodi) that the mahasiswa belongs to.
     */
    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    /**
     * Get the prodi unit (alias for unit).
     */
    public function prodi()
    {
        return $this->unit();
    }

    /**
     * Get the fakultas name (accessor).
     */
    public function getFakultasAttribute()
    {
        return $this->unit && $this->unit->parent ? $this->unit->parent->nama : '-';
    }

    /**
     * Get the prodi name (accessor).
     */
    public function getProdiAttribute()
    {
        return $this->unit ? $this->unit->nama : '-';
    }
}
