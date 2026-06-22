<?php

namespace Tests\Feature;

use App\Models\User;
use App\Support\PermissionCatalog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RolePermissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_can_access_permissions_page(): void
    {
        $this->seed();

        $admin = User::query()->where('username', 'admin')->first();

        $response = $this->actingAs($admin)->get(route('settings.permissions'));

        $response->assertOk();
        $response->assertSee('IK Kullanıcısı');
        $response->assertSee('Lojman Kullanıcısı');
    }

    public function test_hr_user_cannot_access_permissions_page(): void
    {
        $this->seed();

        $hr = User::query()->where('username', 'IK')->first();

        $response = $this->actingAs($hr)->get(route('settings.permissions'));

        $response->assertForbidden();
    }

    public function test_admin_can_update_hr_role_template(): void
    {
        $this->seed();

        $admin = User::query()->where('username', 'admin')->first();

        $response = $this->actingAs($admin)->put(route('settings.permissions.update'), [
            'permissions' => [
                'hr' => ['employees.view', 'employees.create'],
                'dorm_manager' => ['rooms.view', 'placements.assign'],
            ],
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
    }

    public function test_permission_catalog_has_granular_actions(): void
    {
        $all = PermissionCatalog::all();
        $this->assertContains('employees.edit', $all);
        $this->assertContains('employees.create', $all);
        $this->assertContains('rooms.edit', $all);
        $this->assertContains('users.permissions', $all);
    }
}
