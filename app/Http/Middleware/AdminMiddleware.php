<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user()) {
            return response()->json([
                'message' => 'Silakan login terlebih dahulu',
            ], 401);
        }

        if ($request->user()->role !== 'admin') {
            return response()->json([
                'message' => 'Akses ditolak. Hanya admin yang boleh mengakses fitur ini',
            ], 403);
        }

        return $next($request);
    }
}