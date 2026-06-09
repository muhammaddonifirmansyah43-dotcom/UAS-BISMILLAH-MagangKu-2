<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('id', 'desc')->get();

        return response()->json([
            'message' => 'Data pengguna berhasil diambil',
            'data' => $users,
        ]);
    }

    public function resetPassword(Request $request, User $user)
    {
        $validated = $request->validate([
            'password' => 'required|string|min:6',
        ]);

        $user->update([
            'password_hash' => Hash::make($validated['password']),
        ]);

        return response()->json([
            'message' => 'Password pengguna berhasil direset',
            'data' => $user,
        ]);
    }
}