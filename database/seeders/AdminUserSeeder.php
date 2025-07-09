<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Buat role 'bidan' jika belum ada
        $role = Role::firstOrCreate(['name' => 'admin']);

        // Buat user baru
        $user = User::create([
            'name' => 'Mindon Cantik',
            'email' => 'mindon@example.com',
            'password' => Hash::make('password'), // Ganti password sesuai kebutuhan
        ]);

        // Assign role ke user
        $user->assignRole($role);
    }
}