<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index()
    {
        return response()->json([
            'message' => 'Data perusahaan berhasil diambil',
            'data' => Company::latest()->get(),
        ]);
    }

    public function store(Request $request)
    {
        $user = $request->user();

        if ($user->role !== 'admin') {
            return response()->json([
                'message' => 'Hanya admin yang boleh menambah perusahaan',
            ], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:30',
            'address' => 'nullable|string',
            'logo_url' => 'nullable|string',
            'website' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $company = Company::create($validated);

        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'create_company',
            'target_type' => 'companies',
            'target_id' => $company->id,
        ]);

        return response()->json([
            'message' => 'Perusahaan berhasil dibuat',
            'data' => $company,
        ], 201);
    }

    public function show(Company $company)
    {
        return response()->json([
            'message' => 'Detail perusahaan berhasil diambil',
            'data' => $company->load('internships'),
        ]);
    }

    public function update(Request $request, Company $company)
    {
        $user = $request->user();

        if ($user->role !== 'admin') {
            return response()->json([
                'message' => 'Hanya admin yang boleh mengubah perusahaan',
            ], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:30',
            'address' => 'nullable|string',
            'logo_url' => 'nullable|string',
            'website' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $company->update($validated);

        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'update_company',
            'target_type' => 'companies',
            'target_id' => $company->id,
        ]);

        return response()->json([
            'message' => 'Perusahaan berhasil diperbarui',
            'data' => $company,
        ]);
    }

    public function destroy(Request $request, Company $company)
    {
        $user = $request->user();

        if ($user->role !== 'admin') {
            return response()->json([
                'message' => 'Hanya admin yang boleh menghapus perusahaan',
            ], 403);
        }

        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'delete_company',
            'target_type' => 'companies',
            'target_id' => $company->id,
        ]);

        $company->delete();

        return response()->json([
            'message' => 'Perusahaan berhasil dihapus',
        ]);
    }
}