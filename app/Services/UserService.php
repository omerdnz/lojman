<?php

namespace App\Services;

use App\Models\User;
use App\Support\PermissionCatalog;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class UserService
{
    public function paginate(?string $search = null, int $perPage = 20): LengthAwarePaginator
    {
        return User::query()
            ->with(['roles', 'permissions'])
            ->when($search, fn ($q) => $q->where(function ($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            }))
            ->orderBy('username')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function roles(): array
    {
        return Role::query()->where('name', '!=', 'super_admin')->orderBy('name')->pluck('name')->all();
    }

    public function rolePermissionTemplate(string $roleName): array
    {
        $role = Role::findByName($roleName);

        return $role->permissions->pluck('name')->all();
    }

    public function create(array $data): User
    {
        if (User::query()->where('username', $data['username'])->exists()) {
            throw ValidationException::withMessages(['username' => 'Bu kullanıcı adı zaten kullanılıyor.']);
        }

        $user = User::query()->create([
            'username' => $data['username'],
            'name' => $data['name'] ?? $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'is_active' => $data['is_active'] ?? true,
        ]);

        $user->syncRoles([$data['role']]);
        $this->syncUserPermissions($user, $data['permissions'] ?? null, $data['role']);

        return $user;
    }

    public function update(User $user, array $data): User
    {
        if (
            isset($data['username'])
            && $data['username'] !== $user->username
            && User::query()->where('username', $data['username'])->exists()
        ) {
            throw ValidationException::withMessages(['username' => 'Bu kullanıcı adı zaten kullanılıyor.']);
        }

        if ($user->hasRole('super_admin') && ($data['role'] ?? '') !== 'super_admin') {
            throw ValidationException::withMessages(['role' => 'Süper admin rolü değiştirilemez.']);
        }

        $payload = [
            'username' => $data['username'] ?? $user->username,
            'name' => $data['name'] ?? $user->name,
            'email' => $data['email'] ?? $user->email,
            'is_active' => $data['is_active'] ?? $user->is_active,
        ];

        if (! empty($data['password'])) {
            $payload['password'] = Hash::make($data['password']);
        }

        $user->update($payload);

        if (isset($data['role']) && ! $user->hasRole('super_admin')) {
            $user->syncRoles([$data['role']]);
        }

        if (array_key_exists('permissions', $data) && ! $user->hasRole('super_admin')) {
            $this->syncUserPermissions($user, $data['permissions'] ?? [], $data['role'] ?? $user->getRoleNames()->first());
        }

        return $user->fresh(['roles', 'permissions']);
    }

    public function delete(User $user, User $actor): void
    {
        if ($user->id === $actor->id) {
            throw ValidationException::withMessages(['user' => 'Kendi hesabınızı silemezsiniz.']);
        }

        if ($user->hasRole('super_admin')) {
            throw ValidationException::withMessages(['user' => 'Süper admin silinemez.']);
        }

        $user->delete();
    }

    private function syncUserPermissions(User $user, ?array $permissions, ?string $role): void
    {
        if ($permissions === null && $role) {
            $permissions = $this->rolePermissionTemplate($role);
        }

        $allowed = PermissionCatalog::all();
        $filtered = array_values(array_intersect($permissions ?? [], $allowed));

        $user->syncPermissions($filtered);

        app(PermissionRegistrar::class)->forgetCachedPermissions();
        Cache::flush();
    }
}
