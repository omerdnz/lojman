<?php

namespace Database\Seeders;

use App\Support\PermissionCatalog;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        foreach (PermissionCatalog::all() as $permission) {
            Permission::findOrCreate($permission);
        }

        $roles = [
            'super_admin' => PermissionCatalog::all(),
            'hr' => [
                'dashboard.view',
                'employees.view', 'employees.create', 'employees.edit', 'employees.import',
                'departments.manage',
                'placements.view', 'placements.assign', 'placements.remove', 'placements.transfer',
                'transfers.view',
                'documents.view', 'documents.manage',
                'reports.view', 'reports.export',
                'notifications.view',
            ],
            'dorm_manager' => [
                'dashboard.view',
                'employees.view',
                'rooms.view', 'rooms.create', 'rooms.edit',
                'blocks.manage', 'floors.manage',
                'placements.view', 'placements.assign', 'placements.remove', 'placements.transfer', 'placements.bulk',
                'transfers.view',
                'maintenance.view', 'maintenance.manage',
                'reports.view', 'reports.export',
                'notifications.view',
            ],
            'manager' => [
                'dashboard.view',
                'employees.view',
                'rooms.view',
                'placements.view',
                'transfers.view',
                'maintenance.view', 'maintenance.manage',
                'reports.view', 'reports.export',
                'notifications.view',
            ],
            'viewer' => [
                'dashboard.view',
                'employees.view',
                'rooms.view',
                'transfers.view',
                'reports.view',
                'notifications.view',
            ],
        ];

        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::findOrCreate($roleName);
            $role->syncPermissions($rolePermissions);
        }
    }
}
