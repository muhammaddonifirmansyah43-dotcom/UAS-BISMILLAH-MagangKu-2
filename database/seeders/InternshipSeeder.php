<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Internship;
use App\Models\User;
use Illuminate\Database\Seeder;

class InternshipSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@magangku.com')->first();

        $kimia = Company::where('name', 'PT Kimia Farma')->first();
        $kusuma = Company::where('name', 'PT Kusuma Satria Dinasasri Wisatajaya')->first();
        $bnn = Company::where('name', 'PT BNN')->first();

        Internship::create([
            'company_id' => $kimia->id,
            'created_by' => $admin->id,
            'title' => 'Admin Klinik',
            'type' => 'PKL',
            'description' => 'Membantu administrasi klinik, input data pasien, dan pengelolaan dokumen.',
            'requirements' => 'Teliti, disiplin, mampu mengoperasikan Microsoft Office, dan komunikatif.',
            'location' => 'Malang',
            'registration_url' => 'https://kimiafarma.co.id/karir',
            'status' => 'open',
            'open_date' => now()->toDateString(),
            'close_date' => now()->addMonth()->toDateString(),
        ]);

        Internship::create([
            'company_id' => $kusuma->id,
            'created_by' => $admin->id,
            'title' => 'Product Marketing',
            'type' => 'Magang',
            'description' => 'Membantu proses pemasaran produk dan promosi perusahaan.',
            'requirements' => 'Siswa/mahasiswa aktif, mampu berkomunikasi, kreatif, dan percaya diri.',
            'location' => 'Batu',
            'registration_url' => 'https://kusumawisatajaya.id/karir',
            'status' => 'open',
            'open_date' => now()->toDateString(),
            'close_date' => now()->addMonth()->toDateString(),
        ]);

        Internship::create([
            'company_id' => $bnn->id,
            'created_by' => $admin->id,
            'title' => 'Teknisi',
            'type' => 'PKL',
            'description' => 'Membantu kegiatan teknis dan pemeliharaan perangkat kerja.',
            'requirements' => 'Memahami dasar teknisi, bertanggung jawab, dan siap bekerja dalam tim.',
            'location' => 'Malang',
            'registration_url' => 'https://bnn.go.id',
            'status' => 'closed',
            'open_date' => now()->subMonth()->toDateString(),
            'close_date' => now()->subDay()->toDateString(),
        ]);
    }
}