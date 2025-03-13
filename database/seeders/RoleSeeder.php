<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run()
    {
        Role::insert([
            ['name' => 'Superadmin', 'description' => 'Has full access to the system'],
            ['name' => 'Wakil Ketua Bidang 2', 'description' => 'Persetujuan Anggaran Kegiatan'],
            ['name' => 'Wakil Ketua Bidang 3', 'description' => 'Persetujuan Rencana Kegiatan'],
            ['name' => 'Ketua Senat Mahasiswa', 'description' => 'Mengetahui dan Menyetujui Rencana Kegiatan'],
            ['name' => 'Sekretaris', 'description' => 'Administrasi Kegiatan'],
            ['name' => 'Bendahara', 'description' => 'Administrasi Keuangan'],
            ['name' => 'User', 'description' => 'Pengguna Anggota Organisasi}'],
        ]);
    }
}
