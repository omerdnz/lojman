<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function __construct(
        private readonly UserService $userService,
    ) {}

    public function index(Request $request): View
    {
        abort_unless($request->user()?->can('users.view'), 403);

        $users = $this->userService->paginate($request->query('search'));

        return view('users.index', compact('users'));
    }

    public function create(): View
    {
        abort_unless(auth()->user()?->can('users.create'), 403);

        $roles = $this->userService->roles();
        $rolePresets = $this->buildRolePresets();
        $selectedPermissions = old('permissions', $this->userService->rolePermissionTemplate('viewer'));

        return view('users.create', compact('roles', 'rolePresets', 'selectedPermissions'));
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $this->userService->create($request->validated());

        return redirect()->route('users.index')->with('success', 'Kullanıcı ve yetkileri oluşturuldu.');
    }

    public function edit(User $user): View
    {
        abort_unless(auth()->user()?->can('users.edit'), 403);

        $roles = $this->userService->roles();
        $rolePresets = $this->buildRolePresets();
        $selectedPermissions = old(
            'permissions',
            $user->getDirectPermissions()->pluck('name')->all()
        );
        $isSuperAdmin = $user->hasRole('super_admin');

        return view('users.edit', compact('user', 'roles', 'rolePresets', 'selectedPermissions', 'isSuperAdmin'));
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $this->userService->update($user, $request->validated());

        return redirect()->route('users.index')->with('success', 'Kullanıcı ve yetkileri güncellendi.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        abort_unless($request->user()?->can('users.delete'), 403);

        $this->userService->delete($user, $request->user());

        return redirect()->route('users.index')->with('success', 'Kullanıcı silindi.');
    }

    private function buildRolePresets(): array
    {
        return [
            'hr' => [
                'label' => 'IK Şablonu',
                'permissions' => $this->userService->rolePermissionTemplate('hr'),
            ],
            'dorm_manager' => [
                'label' => 'Lojman Şablonu',
                'permissions' => $this->userService->rolePermissionTemplate('dorm_manager'),
            ],
            'manager' => [
                'label' => 'Yönetici',
                'permissions' => $this->userService->rolePermissionTemplate('manager'),
            ],
            'viewer' => [
                'label' => 'Sadece Görüntüleme',
                'permissions' => $this->userService->rolePermissionTemplate('viewer'),
            ],
        ];
    }
}
