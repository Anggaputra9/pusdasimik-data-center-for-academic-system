<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    /**
     * Get all fakultas (units with parent_id = null)
     */
    public function getFakultas()
    {
        $fakultas = Unit::whereNull('parent_id')
            ->orderBy('nama')
            ->get(['id', 'nama']);

        return response()->json($fakultas);
    }

    /**
     * Get prodi by fakultas_id
     */
    public function getProdiByFakultas($fakultasId)
    {
        $prodi = Unit::where('parent_id', $fakultasId)
            ->orderBy('nama')
            ->get(['id', 'nama']);

        return response()->json($prodi);
    }

    /**
     * Get all units
     */
    public function index()
    {
        $units = Unit::with('parent')
            ->orderBy('nama')
            ->get();

        return response()->json($units);
    }
}
