<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $units = Unit::with('parent')->orderBy('tipe')->orderBy('nama')->get();
        return view('admin.unit.index', compact('units'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'parent_id' => 'nullable|exists:units,id',
            'kode' => 'required|string|max:10|unique:units,kode',
            'nama' => 'required|string|max:100',
            'tipe' => 'required|in:fakultas,prodi',
        ]);

        Unit::create($validated);

        return redirect()->route('admin.unit.index')
            ->with('success', 'Unit berhasil ditambahkan!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Unit $unit)
    {
        $validated = $request->validate([
            'parent_id' => 'nullable|exists:units,id',
            'kode' => 'required|string|max:10|unique:units,kode,' . $unit->id,
            'nama' => 'required|string|max:100',
            'tipe' => 'required|in:fakultas,prodi',
        ]);

        $unit->update($validated);

        return redirect()->route('admin.unit.index')
            ->with('success', 'Unit berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Unit $unit)
    {
        // Check if unit has children (prodi)
        if ($unit->children()->count() > 0) {
            return redirect()->route('admin.unit.index')
                ->with('error', 'Unit tidak dapat dihapus karena masih memiliki unit anak (prodi)!');
        }

        // Check if unit has mahasiswa
        if ($unit->mahasiswas()->count() > 0) {
            return redirect()->route('admin.unit.index')
                ->with('error', 'Unit tidak dapat dihapus karena masih memiliki mahasiswa!');
        }

        $unit->delete();

        return redirect()->route('admin.unit.index')
            ->with('success', 'Unit berhasil dihapus!');
    }
}
