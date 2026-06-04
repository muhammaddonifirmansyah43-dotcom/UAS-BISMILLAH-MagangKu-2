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

    public function show(Internship $internship)
    {
        return response()->json([
            'message' => 'Detail lowongan berhasil diambil',
            'data' => $internship->load('company', 'creator'),
        ]);
    }

    public function store(Request $request)
    {
        $user = $request->user();

        if (!$user || $user->role !== 'admin') {
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
            'registration_url' => 'nullable|string|max:255',
            'status' => 'nullable|in:open,closed',
            'open_date' => 'nullable|date',
            'close_date' => 'nullable|date|after_or_equal:open_date',
        ]);

        $internship = Internship::create([
            'company_id' => $validated['company_id'],
            'created_by' => $user->id,
            'title' => $validated['title'],
            'type' => $validated['type'],
            'description' => $validated['description'],
            'requirements' => $validated['requirements'] ?? null,
            'location' => $validated['location'],
            'registration_url' => $validated['registration_url'] ?? null,
            'status' => $validated['status'] ?? 'open',
            'open_date' => $validated['open_date'] ?? now()->toDateString(),
            'close_date' => $validated['close_date'] ?? null,
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

    public function update(Request $request, Internship $internship)
    {
        $user = $request->user();

        if (!$user || $user->role !== 'admin') {
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
            'registration_url' => 'nullable|string|max:255',
            'status' => 'required|in:open,closed',
            'open_date' => 'nullable|date',
            'close_date' => 'nullable|date|after_or_equal:open_date',
        ]);

        $internship->update([
            'company_id' => $validated['company_id'],
            'title' => $validated['title'],
            'type' => $validated['type'],
            'description' => $validated['description'],
            'requirements' => $validated['requirements'] ?? null,
            'location' => $validated['location'],
            'registration_url' => $validated['registration_url'] ?? null,
            'status' => $validated['status'],
            'open_date' => $validated['open_date'] ?? $internship->open_date,
            'close_date' => $validated['close_date'] ?? null,
        ]);

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

        if (!$user || $user->role !== 'admin') {
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

    public function close(Request $request, Internship $internship)
    {
        $user = $request->user();

        if (!$user || $user->role !== 'admin') {
            return response()->json([
                'message' => 'Hanya admin yang boleh menutup lowongan',
            ], 403);
        }

        $internship->update([
            'status' => 'closed',
        ]);

        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'close_internship',
            'target_type' => 'internships',
            'target_id' => $internship->id,
        ]);

        return response()->json([
            'message' => 'Lowongan berhasil ditutup',
            'data' => $internship->load('company'),
        ]);
    }

    public function open(Request $request, Internship $internship)
    {
        $user = $request->user();

        if (!$user || $user->role !== 'admin') {
            return response()->json([
                'message' => 'Hanya admin yang boleh membuka lowongan',
            ], 403);
        }

        $internship->update([
            'status' => 'open',
        ]);

        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'open_internship',
            'target_type' => 'internships',
            'target_id' => $internship->id,
        ]);

        return response()->json([
            'message' => 'Lowongan berhasil dibuka kembali',
            'data' => $internship->load('company'),
        ]);
    }
}