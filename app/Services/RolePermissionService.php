<?php

namespace App\Services;

use App\Support\PermissionCatalog;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionService
{
    /** @var array<string, string> */
    public const MANAGEABLE_ROLES = [
        'hr' => 'IK Kullanıcısı',
        'dorm_manager' => 'Lojman Kullanıcısı',
    ];

    public function rolePermissions(string $roleName): array
    {
        return Role::findByName($roleName)->permissions->pluck('name')->all();
    }

    public function syncRole(string $roleName, array $permissionNames): void
    {
        if (! in_array($roleName, ['hr', 'dorm_manager'], true)) {
            abort(422, 'Bu rol yönetilemez.');
        }

        $allowed = PermissionCatalog::assignableToRoles();
        $filtered = array_values(array_intersect($permissionNames, $allowed));

        Role::findByName($roleName)->syncPermissions($filtered);

        app(PermissionRegistrar::class)->forgetCachedPermissions();
        Cache::flush();
    }

    public function groups(): array
    {
        return collect(PermissionCatalog::groups())
            ->map(function (array $group, string $key) {
                $group['permissions'] = collect($group['permissions'])
                    ->only(PermissionCatalog::assignableToRoles())
                    ->all();

                return $group;
            })
            ->filter(fn (array $g) => ! empty($g['permissions']))
            ->all();
    }
}
