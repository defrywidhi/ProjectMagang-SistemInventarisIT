<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cache roles/permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // === BUAT DAFTAR KUNCI (PERMISSIONS) ===
        // Sesuai dokumen mentormu 

        // Kunci untuk Barang
        Permission::create(['name' => 'kelola barang']); // Untuk Admin (CRUD)

        // Kunci untuk RAB
        Permission::create(['name' => 'buat rab']);         // Untuk Manajer/Admin
        Permission::create(['name' => 'setujui rab']);      // HANYA untuk Manajer 

        // Kunci untuk Transaksi
        Permission::create(['name' => 'input barang masuk']);   // Untuk Admin 
        Permission::create(['name' => 'input barang keluar']);  // Untuk Teknisi 

        // Kunci untuk Audit
        Permission::create(['name' => 'lihat laporan']);        // Untuk Manajer, Auditor 
        Permission::create(['name' => 'lakukan stok opname']); // Untuk Auditor 

        // Kunci Super Admin
        Permission::create(['name' => 'kelola user']); // HANYA untuk Admin

        // === BUAT GANTUNGAN KUNCI (ROLES) & PASANG KUNCINYA ===

        // Role 1: Teknisi 
        $roleTeknisi = Role::create(['name' => 'teknisi']);
        $roleTeknisi->givePermissionTo('input barang keluar');

        // Role 2: Auditor 
        $roleAuditor = Role::create(['name' => 'auditor']);
        $roleAuditor->givePermissionTo([
            'lihat laporan',
            'lakukan stok opname'
        ]);

        // Role 3: Manajer 
        $roleManajer = Role::create(['name' => 'manager']);
        $roleManajer->givePermissionTo([
            'setujui rab',
            'lihat laporan'
        ]);

        // Role 4: Admin 
        // $roleAdmin = Role::create(['name' => 'admin']);
        // Admin bisa melakukan semua yang bisa dilakukan Manajer dan Teknisi
        // $roleAdmin->givePermissionTo(Permission::all());
        // Beri izin kelola user (yang tidak dimiliki Manajer)
        // $roleAdmin->givePermissionTo('kelola user'); // Ini sudah termasuk di Permission::all()

        // Role admin baru
        $roleAdmin = Role::create(['name' => 'admin']);
        // Beri Admin semua izin KECUALI 'setujui rab'
        $permissions_admin = Permission::where('name', '!=', 'setujui rab')->get();
        $roleAdmin->givePermissionTo($permissions_admin);
    }
}
