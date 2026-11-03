<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Demo data. Sign in with admin@example.com / Admin#12345.
     */
    public function run(): void
    {
        $this->call(RolePermissionSeeder::class);

        $people = [
            ['Ayu', 'Pratama', 'admin@example.com', 'superadmin'],
            ['Budi', 'Santoso', 'editor@example.com', 'admin'],
            ['Citra', 'Dewi', 'viewer@example.com', 'user'],
        ];

        foreach ($people as [$name, $last, $email, $role]) {
            $user = User::forceCreate([
                'name' => $name,
                'last_name' => $last,
                'email' => $email,
                'password' => Hash::make('Admin#12345'),
                'email_verified_at' => now(),
                'password_changed_at' => now(),
                'role' => $role === 'user' ? 'user' : 'admin',
                'status' => 'active',
                'auth_type' => 'local',
            ]);
            $user->assignRole($role);
        }

        $this->call(DemoCatalogueSeeder::class);
    }
}
