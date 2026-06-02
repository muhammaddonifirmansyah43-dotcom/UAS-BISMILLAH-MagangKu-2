<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Bookmark;
use App\Models\Internship;
use Illuminate\Http\Request;

class BookmarkController extends Controller
{
    public function index(Request $request)
    {
        $bookmarks = Bookmark::with('internship.company')
            ->where('user_id', $request->user()->id)
            ->latest('created_at')
            ->get();

        return response()->json([
            'message' => 'Lowongan tersimpan berhasil diambil',
            'data' => $bookmarks,
        ]);
    }

    public function store(Request $request, Internship $internship)
    {
        $user = $request->user();

        $bookmark = Bookmark::firstOrCreate([
            'user_id' => $user->id,
            'internship_id' => $internship->id,
        ]);

        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'save_internship',
            'target_type' => 'internships',
            'target_id' => $internship->id,
        ]);

        return response()->json([
            'message' => 'Lowongan berhasil disimpan',
            'data' => $bookmark,
        ], 201);
    }

    public function destroy(Request $request, Internship $internship)
    {
        $user = $request->user();

        Bookmark::where('user_id', $user->id)
            ->where('internship_id', $internship->id)
            ->delete();

        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'remove_bookmark',
            'target_type' => 'internships',
            'target_id' => $internship->id,
        ]);

        return response()->json([
            'message' => 'Lowongan berhasil dihapus dari simpanan',
        ]);
    }
}