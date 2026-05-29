<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class MahasiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $mahasiswa = Mahasiswa::all();
        return response()->json([
            'success' => true,
            'data' => $mahasiswa
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nim' => 'required|string|max:20|unique:mahasiswas,nim',
            'nama' => 'required|string|max:100',
            'program_studi' => 'required|string|max:100',
            'fakultas' => 'required|string|max:100',
        ]);

        $mahasiswa = Mahasiswa::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Data mahasiswa berhasil ditambahkan',
            'data' => $mahasiswa
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $nim): JsonResponse
    {
        $mahasiswa = Mahasiswa::find($nim);

        if (!$mahasiswa) {
            return response()->json([
                'success' => false,
                'message' => 'Data mahasiswa tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $mahasiswa
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $nim): JsonResponse
    {
        $mahasiswa = Mahasiswa::find($nim);

        if (!$mahasiswa) {
            return response()->json([
                'success' => false,
                'message' => 'Data mahasiswa tidak ditemukan'
            ], 404);
        }

        $validated = $request->validate([
            'nama' => 'sometimes|required|string|max:100',
            'program_studi' => 'sometimes|required|string|max:100',
            'fakultas' => 'sometimes|required|string|max:100',
        ]);

        $mahasiswa->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Data mahasiswa berhasil diupdate',
            'data' => $mahasiswa
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $nim): JsonResponse
    {
        $mahasiswa = Mahasiswa::find($nim);

        if (!$mahasiswa) {
            return response()->json([
                'success' => false,
                'message' => 'Data mahasiswa tidak ditemukan'
            ], 404);
        }

        $mahasiswa->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data mahasiswa berhasil dihapus'
        ]);
    }
}
