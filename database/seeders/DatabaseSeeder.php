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
        // 1. Jalankan pabrik Roles & Permissions DULU
        $this->call(RolesAndPermissionsSeeder::class);

        // 2. Buat User Admin pertama kita
        $adminUser = User::factory()->create([
            'name' => 'Admin Inventory', // Ganti namanya jadi lebih keren
            'email' => 'admin@rumahsakit.com', // Email untuk login
            'password' => bcrypt('1234'), // Ganti passwordnya (nanti bisa kamu ganti sendiri)
        ]);

        // 3. "Kasih" dia gantungan kunci 'admin'
        // Ini adalah "jurus" dari Spatie
        $adminUser->assignRole('admin'); 

        // (Opsional) Buat beberapa user teknisi palsu untuk tes
        User::factory()->create([
            'name' => 'Teknisi A',
            'email' => 'teknisi.a@rumahsakit.com',
            'password' => bcrypt('1234'),
        ])->assignRole('teknisi');

        User::factory()->create([
            'name' => 'Manajer IT',
            'email' => 'manajer.it@rumahsakit.com',
            'password' => bcrypt('1234'),
        ])->assignRole('manager');
    }
}