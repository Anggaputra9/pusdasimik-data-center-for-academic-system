<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class DosenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $dosen = Dosen::all();
        return response()->json([
            'success' => true,
            'data' => $dosen
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nip' => 'required|string|max:20|unique:dosens,nip',
            'nama' => 'required|string|max:100',
            'jabatan' => 'required|string|max:100',
        ]);

        $dosen = Dosen::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Data dosen berhasil ditambahkan',
            'data' => $dosen
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $nip): JsonResponse
    {
        $dosen = Dosen::where('nip', $nip)->first();

        if (!$dosen) {
            return response()->json([
                'success' => false,
                'message' => 'Data dosen tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $dosen
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $nip): JsonResponse
    {
        $dosen = Dosen::where('nip', $nip)->first();

        if (!$dosen) {
            return response()->json([
                'success' => false,
                'message' => 'Data dosen tidak ditemukan'
            ], 404);
        }

        $validated = $request->validate([
            'nama' => 'sometimes|required|string|max:100',
            'jabatan' => 'sometimes|required|string|max:100',
        ]);

        $dosen->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Data dosen berhasil diupdate',
            'data' => $dosen
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $nip): JsonResponse
    {
        $dosen = Dosen::where('nip', $nip)->first();

        if (!$dosen) {
            return response()->json([
                'success' => false,
                'message' => 'Data dosen tidak ditemukan'
            ], 404);
        }

        $dosen->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data dosen berhasil dihapus'
        ]);
    }
}
