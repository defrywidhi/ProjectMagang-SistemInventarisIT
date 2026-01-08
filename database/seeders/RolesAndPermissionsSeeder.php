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


        $roleTeknisi = Role::create(['name' => 'teknisi']);
        $roleTeknisi->givePermissionTo('input barang keluar');

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

        $roleDirektur = Role::create(['name' => 'direktur']);
        $roleDirektur->givePermissionTo([
            'setujui rab', 
            'lihat laporan'
        ]);

        $roleAdmin = Role::create(['name' => 'admin']);
        $permissions_admin = Permission::where('name', '!=', 'setujui rab')->get();
        $roleAdmin->givePermissionTo($permissions_admin);
    }
}
