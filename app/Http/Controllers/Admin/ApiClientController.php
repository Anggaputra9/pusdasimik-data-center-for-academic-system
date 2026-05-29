<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApiClient;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ApiClientController extends Controller
{
    public function index(Request $request)
    {
        $clients = ApiClient::with(['tokens' => fn ($q) => $q->latest()])->orderBy('nama')->get();

        $plainToken = $request->session()->pull('plain_token');
        $plainTokenClient = $request->session()->pull('plain_token_client');

        return view('admin.api_client.index', compact('clients', 'plainToken', 'plainTokenClient'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'slug' => 'nullable|string|max:50|alpha_dash|unique:api_clients,slug',
            'deskripsi' => 'nullable|string|max:255',
        ]);

        $validated['slug'] = $validated['slug'] ?? Str::slug($validated['nama']);
        $validated['is_active'] = true;

        ApiClient::create($validated);

        return redirect()->route('admin.api-client.index')
            ->with('success', 'Klien API berhasil ditambahkan.');
    }

    public function update(Request $request, ApiClient $apiClient)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'deskripsi' => 'nullable|string|max:255',
            'is_active' => 'sometimes|boolean',
        ]);

        $validated['is_active'] = (bool) ($request->input('is_active', false));

        $apiClient->update($validated);

        return redirect()->route('admin.api-client.index')
            ->with('success', 'Data klien diperbarui.');
    }

    public function destroy(ApiClient $apiClient)
    {
        $apiClient->tokens()->delete();
        $apiClient->delete();

        return redirect()->route('admin.api-client.index')
            ->with('success', 'Klien API beserta seluruh tokennya dihapus.');
    }

    public function issueToken(Request $request, ApiClient $apiClient)
    {
        $validated = $request->validate([
            'token_name' => 'required|string|max:50',
        ]);

        $token = $apiClient->createToken($validated['token_name']);

        return redirect()->route('admin.api-client.index')
            ->with('plain_token', $token->plainTextToken)
            ->with('plain_token_client', $apiClient->nama)
            ->with('success', 'Token baru diterbitkan. Salin sekarang — token plaintext tidak akan ditampilkan lagi.');
    }

    public function revokeToken(ApiClient $apiClient, int $tokenId)
    {
        $apiClient->tokens()->where('id', $tokenId)->delete();

        return redirect()->route('admin.api-client.index')
            ->with('success', 'Token dicabut.');
    }
}
