<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Internship;
use Illuminate\Http\Request;

class InternshipController extends Controller
{
    public function index(Request $request)
    {
        $query = Internship::with('company');

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhereHas('company', function ($companyQuery) use ($search) {
                        $companyQuery->where('name', 'like', '%' . $search . '%');
                    });
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('location')) {
            $query->where('location', $request->location);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        return response()->json([
            'message' => 'Data lowongan berhasil diambil',
            'data' => $query->latest()->get(),
        ]);
    }

    public function store(Request $request)
    {
        $user = $request->user();

        if ($user->role !== 'admin') {
            return response()->json([
                'message' => 'Hanya admin yang boleh menambah lowongan',
            ], 403);
        }

        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'title' => 'required|string|max:150',
            'type' => 'required|in:Magang,PKL',
            'description' => 'required|string',
            'requirements' => 'nullable|string',
            'location' => 'required|string|max:100',
            'registration_url' => 'nullable|string',
            'status' => 'required|in:open,closed',
            'open_date' => 'nullable|date',
            'close_date' => 'nullable|date',
        ]);

        $internship = Internship::create([
            ...$validated,
            'created_by' => $user->id,
        ]);

        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'create_internship',
            'target_type' => 'internships',
            'target_id' => $internship->id,
        ]);

        return response()->json([
            'message' => 'Lowongan berhasil dibuat',
            'data' => $internship->load('company'),
        ], 201);
    }

    public function show(Internship $internship)
    {
        return response()->json([
            'message' => 'Detail lowongan berhasil diambil',
            'data' => $internship->load('company', 'creator'),
        ]);
    }

    public function update(Request $request, Internship $internship)
    {
        $user = $request->user();

        if ($user->role !== 'admin') {
            return response()->json([
                'message' => 'Hanya admin yang boleh mengubah lowongan',
            ], 403);
        }

        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'title' => 'required|string|max:150',
            'type' => 'required|in:Magang,PKL',
            'description' => 'required|string',
            'requirements' => 'nullable|string',
            'location' => 'required|string|max:100',
            'registration_url' => 'nullable|string',
            'status' => 'required|in:open,closed',
            'open_date' => 'nullable|date',
            'close_date' => 'nullable|date',
        ]);

        $internship->update($validated);

        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'update_internship',
            'target_type' => 'internships',
            'target_id' => $internship->id,
        ]);

        return response()->json([
            'message' => 'Lowongan berhasil diperbarui',
            'data' => $internship->load('company'),
        ]);
    }

    public function destroy(Request $request, Internship $internship)
    {
        $user = $request->user();

        if ($user->role !== 'admin') {
            return response()->json([
                'message' => 'Hanya admin yang boleh menghapus lowongan',
            ], 403);
        }

        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'delete_internship',
            'target_type' => 'internships',
            'target_id' => $internship->id,
        ]);

        $internship->delete();

        return response()->json([
            'message' => 'Lowongan berhasil dihapus',
        ]);
    }
}