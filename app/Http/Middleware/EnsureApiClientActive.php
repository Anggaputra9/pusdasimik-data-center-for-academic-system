<?php

namespace App\Http\Middleware;

use App\Models\ApiClient;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureApiClientActive
{
    public function handle(Request $request, Closure $next): Response
    {
        $client = $request->user('sanctum');

        if (! $client instanceof ApiClient) {
            return response()->json(['message' => 'Token tidak valid.'], 401);
        }

        if (! $client->is_active) {
            return response()->json(['message' => 'Klien API dinonaktifkan.'], 403);
        }

        return $next($request);
    }
}
