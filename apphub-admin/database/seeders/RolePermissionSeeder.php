<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Define guard name (default 'web')
        $guardName = 'web';

        // === 1. Create Roles (if not exists) === //
        $roles = [
            'superadmin', // Full access
            'admin',      // Admin management
            'user'        // Basic user
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(
                ['name' => $role, 'guard_name' => $guardName]
            );
        }

        // === 2. Create Permissions (if not exists) === //
        $permissions = [
            'create-post',
            'edit-post',
            'delete-post',
            'manage-users',  // Tambahan, jika ingin mengelola user
            'manage-roles',  // Tambahan, jika ingin mengelola role
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission, 'guard_name' => $guardName]
            );
        }

        // Fetch role instances
        $roleSuperAdmin = Role::where('name', 'superadmin')->first();
        $roleAdmin = Role::where('name', 'admin')->first();
        $roleUser = Role::where('name', 'user')->first();

        // === 3. Assign Permissions === //

        // Superadmin: all permissions
        $roleSuperAdmin->syncPermissions(Permission::all());

        // Admin: only selected permissions
        $roleAdmin->syncPermissions([
            'create-post',
            'edit-post',
            // Tambahkan jika mau: 'manage-users'
        ]);

        // User: no specific permission (basic role)
        $roleUser->syncPermissions([]); // Bisa dikosongkan atau tambahkan minimal permission

        // === 4. Optional: Assign Role to Default User (Testing) === //
        // Jika mau assign role ke user id 1, misalnya admin:
        // $defaultAdmin = \App\Models\User::find(1);
        // if ($defaultAdmin) {
        //     $defaultAdmin->assignRole('admin');
        // }

        // Output hasil di console
        $this->command->info('Roles and Permissions have been seeded successfully!');
    }
}
