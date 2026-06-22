<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateRolePermissionsRequest;
use App\Services\RolePermissionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RolePermissionController extends Controller
{
    public function __construct(
        private readonly RolePermissionService $rolePermissionService,
    ) {}

    public function index(): View
    {
        $roles = RolePermissionService::MANAGEABLE_ROLES;
        $groups = $this->rolePermissionService->groups();
        $assigned = [];

        foreach (array_keys($roles) as $roleName) {
            $assigned[$roleName] = $this->rolePermissionService->rolePermissions($roleName);
        }

        return view('settings.permissions', compact('roles', 'groups', 'assigned'));
    }

    public function update(UpdateRolePermissionsRequest $request): RedirectResponse
    {
        foreach (array_keys(RolePermissionService::MANAGEABLE_ROLES) as $roleName) {
            $this->rolePermissionService->syncRole(
                $roleName,
                $request->input("permissions.{$roleName}", []),
            );
        }

        return back()->with('success', 'IK ve Lojman rol şablonları güncellendi. Yeni kullanıcılara şablon uygulanabilir.');
    }
}
