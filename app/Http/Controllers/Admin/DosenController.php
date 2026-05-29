<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use Illuminate\Http\Request;

class DosenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dosens = Dosen::latest()->paginate(10);
        return view('admin.dosen.index', compact('dosens'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.dosen.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nip' => 'required|string|max:20|unique:dosens,nip',
            'nama' => 'required|string|max:100',
        ]);

        Dosen::create($validated);

        return redirect()->route('admin.dosen.index')
            ->with('success', 'Data dosen berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $nip)
    {
        $dosen = Dosen::where('nip', $nip)->firstOrFail();
        return view('admin.dosen.show', compact('dosen'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $nip)
    {
        $dosen = Dosen::where('nip', $nip)->firstOrFail();
        return view('admin.dosen.edit', compact('dosen'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $nip)
    {
        $dosen = Dosen::where('nip', $nip)->firstOrFail();

        $validated = $request->validate([
            'nama' => 'required|string|max:100',
        ]);

        $dosen->update($validated);

        return redirect()->route('admin.dosen.index')
            ->with('success', 'Data dosen berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $nip)
    {
        $dosen = Dosen::where('nip', $nip)->firstOrFail();
        $dosen->delete();

        return redirect()->route('admin.dosen.index')
            ->with('success', 'Data dosen berhasil dihapus!');
    }
}
