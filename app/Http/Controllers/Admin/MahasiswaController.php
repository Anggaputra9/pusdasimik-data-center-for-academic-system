<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\Unit;
use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $mahasiswas = Mahasiswa::with('unit.parent')->latest()->paginate(10);
        $fakultas = Unit::whereNull('parent_id')->orderBy('nama')->get();
        return view('admin.mahasiswa.index', compact('mahasiswas', 'fakultas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $fakultas = Unit::with(['children' => fn ($query) => $query->orderBy('nama')])
            ->fakultas()
            ->orderBy('nama')
            ->get();

        return view('admin.mahasiswa.create', compact('fakultas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nim' => 'required|string|max:20|unique:mahasiswas,nim',
            'nama' => 'required|string|max:100',
            'unit_id' => 'required|exists:units,id',
            'tahun_akademik' => 'required|string|max:20',
            'status' => 'required|in:' . implode(',', Mahasiswa::STATUSES),
        ]);

        Mahasiswa::create($validated);

        return redirect()->route('admin.mahasiswa.index')
            ->with('success', 'Data mahasiswa berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $nim)
    {
        $mahasiswa = Mahasiswa::findOrFail($nim);
        return view('admin.mahasiswa.show', compact('mahasiswa'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $nim)
    {
        $mahasiswa = Mahasiswa::with('unit.parent')->findOrFail($nim);
        $fakultas = Unit::with(['children' => fn ($query) => $query->orderBy('nama')])
            ->fakultas()
            ->orderBy('nama')
            ->get();

        return view('admin.mahasiswa.edit', compact('mahasiswa', 'fakultas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $nim)
    {
        $mahasiswa = Mahasiswa::findOrFail($nim);

        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'unit_id' => 'required|exists:units,id',
            'tahun_akademik' => 'required|string|max:20',
            'status' => 'required|in:' . implode(',', Mahasiswa::STATUSES),
        ]);

        $mahasiswa->update($validated);

        return redirect()->route('admin.mahasiswa.index')
            ->with('success', 'Data mahasiswa berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $nim)
    {
        $mahasiswa = Mahasiswa::findOrFail($nim);
        $mahasiswa->delete();

        return redirect()->route('admin.mahasiswa.index')
            ->with('success', 'Data mahasiswa berhasil dihapus!');
    }
}
