<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    public function run(): void
    {
        Company::create([
            'name' => 'PT Kimia Farma',
            'email' => 'recruitment@kimiafarma.co.id',
            'phone' => '021-123456',
            'address' => 'Malang',
            'logo_url' => '/images/kimia-farma.png',
            'website' => 'https://www.kimiafarma.co.id',
            'description' => 'Perusahaan farmasi yang menyediakan layanan kesehatan dan klinik.',
        ]);

        Company::create([
            'name' => 'PT Kusuma Satria Dinasasri Wisatajaya',
            'email' => 'recruitment@kusumawisatajaya.id',
            'phone' => '0341-123456',
            'address' => 'Batu',
            'logo_url' => '/images/kusuma-agrowisata.png',
            'website' => 'https://kusumawisatajaya.id',
            'description' => 'Perusahaan yang bergerak di bidang wisata dan pengembangan sumber daya manusia.',
        ]);

        Company::create([
            'name' => 'PT BNN',
            'email' => 'info@bnn.go.id',
            'phone' => '021-80880011',
            'address' => 'Malang',
            'logo_url' => '/images/bnn.png',
            'website' => 'https://bnn.go.id',
            'description' => 'Instansi yang menyediakan kesempatan praktik kerja lapangan.',
        ]);
    }
}