<?php

namespace App\Http\Middleware;

use App\Models\Session;
use Closure;
use Illuminate\Http\Request;

class TokenAuth
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json([
                'message' => 'Token tidak ditemukan',
            ], 401);
        }

        $session = Session::with('user')
            ->where('token', hash('sha256', $token))
            ->where('expires_at', '>', now())
            ->first();

        if (!$session) {
            return response()->json([
                'message' => 'Token tidak valid atau sudah expired',
            ], 401);
        }

        $request->setUserResolver(function () use ($session) {
            return $session->user;
        });

        return $next($request);
    }
}