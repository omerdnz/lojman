<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['username' => 'admin', 'name' => 'Süper Admin', 'email' => 'admin@lojman.local', 'password' => '123456', 'role' => 'super_admin'],
            ['username' => 'IK', 'name' => 'İnsan Kaynakları', 'email' => 'ik@lojman.local', 'password' => '123456', 'role' => 'hr'],
            ['username' => 'LOJMAN', 'name' => 'Lojman Sorumlusu', 'email' => 'lojman@lojman.local', 'password' => '123', 'role' => 'dorm_manager'],
        ];

        foreach ($users as $data) {
            $user = User::query()->updateOrCreate(
                ['email' => $data['email']],
                [
                    'username' => $data['username'],
                    'name' => $data['name'],
                    'password' => Hash::make($data['password']),
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]
            );

            $user->syncRoles([$data['role']]);

            $template = \Spatie\Permission\Models\Role::findByName($data['role']);
            $user->syncPermissions($template->permissions->pluck('name')->all());
        }
    }
}
