<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role; // <-- TAMBAHKAN INI

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Jalankan pabrik Roles & Permissions DULU
        $this->call(RolesAndPermissionsSeeder::class);

        // Buat User Admin pertama kita
        $admin = User::create([
            'name' => 'Admin Gudang',
            'email' => 'admin@test.com',
            'password' => bcrypt('1234'),
        ]);
        $admin->assignRole('admin');

        // Buat Akun MANAJER (Untuk Approval Tahap 1)
        $manager = User::create([
            'name' => 'Bapak Manajer',
            'email' => 'manager@test.com',
            'password' => bcrypt('1234'),
        ]);
        $manager->assignRole('manager');

        // Buat Akun DIREKTUR (Untuk Approval Tahap 2 - Final)
        $direktur = User::create([
            'name' => 'Bapak Direktur',
            'email' => 'direktur@test.com',
            'password' => bcrypt('1234'),
        ]);
        $direktur->assignRole('direktur');
        
        // Akun Teknisi
        $teknisi = User::create([
            'name' => 'Teknisi Lapangan',
            'email' => 'teknisi@test.com',
            'password' => bcrypt('1234'),
        ]);
        $teknisi->assignRole('teknisi');
    }
}