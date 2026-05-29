<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $table = 'units';

    protected $fillable = [
        'parent_id',
        'kode',
        'nama',
        'tipe',
    ];

    public function parent()
    {
        return $this->belongsTo(Unit::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Unit::class, 'parent_id');
    }

    public function mahasiswas()
    {
        return $this->hasMany(Mahasiswa::class, 'unit_id');
    }

    public function scopeFakultas(Builder $query): Builder
    {
        return $query->where('tipe', 'fakultas');
    }

    public function scopeProdi(Builder $query): Builder
    {
        return $query->where('tipe', 'prodi');
    }
}
