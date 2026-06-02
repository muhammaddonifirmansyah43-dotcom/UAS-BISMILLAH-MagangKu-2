<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        return response()->json([
            'message' => 'Profil berhasil diambil',
            'data' => $request->user(),
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'phone' => 'nullable|string|max:30',
            'avatar_url' => 'nullable|string',
        ]);

        $user->update($validated);

        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'update_profile',
            'target_type' => 'users',
            'target_id' => $user->id,
        ]);

        return response()->json([
            'message' => 'Profil berhasil diperbarui',
            'data' => $user,
        ]);
    }

    public function changePassword(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'old_password' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        if (!Hash::check($validated['old_password'], $user->password_hash)) {
            return response()->json([
                'message' => 'Password lama salah',
            ], 422);
        }

        $user->update([
            'password_hash' => Hash::make($validated['new_password']),
        ]);

        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'change_password',
            'target_type' => 'users',
            'target_id' => $user->id,
        ]);

        return response()->json([
            'message' => 'Password berhasil diperbarui',
        ]);
    }
}