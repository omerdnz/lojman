<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class UserPermissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_assign_granular_permissions_to_user(): void
    {
        $this->seed();

        $admin = User::query()->where('username', 'admin')->first();
        $hr = User::query()->where('username', 'IK')->first();

        $response = $this->actingAs($admin)->put(route('users.update', $hr), [
            'username' => 'IK',
            'name' => $hr->name,
            'email' => $hr->email,
            'role' => 'hr',
            'is_active' => true,
            'permissions' => ['employees.view', 'employees.edit'],
        ]);

        $response->assertRedirect(route('users.index'));

        $hr->refresh();
        $this->assertTrue($hr->hasDirectPermission('employees.view'));
        $this->assertTrue($hr->hasDirectPermission('employees.edit'));
        $this->assertFalse($hr->hasDirectPermission('employees.delete'));
    }

    public function test_user_without_edit_permission_cannot_access_employee_edit(): void
    {
        $this->seed();

        Permission::findOrCreate('employees.view');

        $user = User::factory()->create(['username' => 'viewer1']);
        $user->syncRoles(['viewer']);
        $user->syncPermissions(['employees.view']);

        $response = $this->actingAs($user)->get(route('employees.create'));

        $response->assertForbidden();
    }
}
