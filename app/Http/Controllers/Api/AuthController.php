<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Session;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'phone' => 'nullable|string|max:30',
            'avatar_url' => 'nullable|string',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password_hash' => Hash::make($validated['password']),
            'role' => 'student',
            'phone' => $validated['phone'] ?? null,
            'avatar_url' => $validated['avatar_url'] ?? null,
        ]);

        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'register',
            'target_type' => 'users',
            'target_id' => $user->id,
        ]);

        return response()->json([
            'message' => 'Registrasi berhasil',
            'data' => $user,
        ], 201);
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (!$user || !Hash::check($validated['password'], $user->password_hash)) {
            return response()->json([
                'message' => 'Email atau password salah',
            ], 401);
        }

        $plainToken = Str::random(80);

        $session = Session::create([
            'user_id' => $user->id,
            'token' => hash('sha256', $plainToken),
            'expires_at' => now()->addDays(7),
        ]);

        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'login',
            'target_type' => 'sessions',
            'target_id' => $session->id,
        ]);

        return response()->json([
            'message' => 'Login berhasil',
            'token' => $plainToken,
            'token_type' => 'Bearer',
            'user' => $user,
        ]);
    }

    public function logout(Request $request)
    {
        $token = $request->bearerToken();

        if ($token) {
            Session::where('token', hash('sha256', $token))->delete();
        }

        return response()->json([
            'message' => 'Logout berhasil',
        ]);
    }
}