<?php

use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PanelController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\TransferHistoryController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('login'));

Route::prefix('rapor')->name('reports.')->middleware(['auth', 'permission:reports.view'])->group(function () {
    Route::get('/', [ReportController::class, 'index'])->name('index');
    Route::get('/excel', [ReportController::class, 'excel'])->middleware('permission:reports.export')->name('excel');
    Route::get('/pdf', [ReportController::class, 'pdf'])->middleware('permission:reports.export')->name('pdf');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/panel', [PanelController::class, 'index'])->name('panel.index');

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard.index')
        ->middleware('permission:dashboard.view');

    Route::get('/employees', [EmployeeController::class, 'index'])
        ->name('employees.index')
        ->middleware('permission:employees.view');
    Route::get('/employees/create', [EmployeeController::class, 'create'])
        ->name('employees.create')
        ->middleware('permission:employees.create');
    Route::post('/employees', [EmployeeController::class, 'store'])
        ->name('employees.store')
        ->middleware('permission:employees.create');
    Route::get('/employees/{employee}', [EmployeeController::class, 'show'])
        ->name('employees.show')
        ->middleware('permission:employees.view');
    Route::get('/employees/{employee}/edit', [EmployeeController::class, 'edit'])
        ->name('employees.edit')
        ->middleware('permission:employees.edit');
    Route::put('/employees/{employee}', [EmployeeController::class, 'update'])
        ->name('employees.update')
        ->middleware('permission:employees.edit');
    Route::delete('/employees/{employee}', [EmployeeController::class, 'destroy'])
        ->name('employees.destroy')
        ->middleware('permission:employees.delete');

    Route::get('/rooms', [RoomController::class, 'index'])
        ->name('rooms.index')
        ->middleware('permission:rooms.view');
    Route::middleware('permission:rooms.create')->group(function () {
        Route::get('/rooms/create', [RoomController::class, 'create'])->name('rooms.create');
        Route::post('/rooms', [RoomController::class, 'store'])->name('rooms.store');
    });
    Route::middleware('permission:rooms.edit')->group(function () {
        Route::get('/rooms/{room}/edit', [RoomController::class, 'edit'])->name('rooms.edit');
        Route::put('/rooms/{room}', [RoomController::class, 'update'])->name('rooms.update');
    });
    Route::delete('/rooms/{room}', [RoomController::class, 'destroy'])
        ->name('rooms.destroy')
        ->middleware('permission:rooms.delete');

    Route::middleware('permission:placements.view|placements.assign')->group(function () {
        Route::get('/assignments', [AssignmentController::class, 'index'])->name('assignments.index');
    });
    Route::post('/assignments', [AssignmentController::class, 'store'])
        ->name('assignments.store')
        ->middleware('permission:placements.assign|rooms.edit');
    Route::post('/assignments/remove-by-employee', [AssignmentController::class, 'destroyByEmployee'])
        ->name('assignments.remove-by-employee')
        ->middleware('permission:placements.remove|rooms.edit');
    Route::post('/assignments/bulk-assign', [AssignmentController::class, 'bulkAssign'])
        ->name('assignments.bulk-assign')
        ->middleware('permission:placements.bulk');
    Route::post('/assignments/bulk-remove', [AssignmentController::class, 'bulkRemove'])
        ->name('assignments.bulk-remove')
        ->middleware('permission:placements.bulk');
    Route::post('/assignments/transfer', [AssignmentController::class, 'transfer'])
        ->name('assignments.transfer')
        ->middleware('permission:placements.transfer');

    Route::get('/transfers', [TransferHistoryController::class, 'index'])
        ->name('transfers.index')
        ->middleware('permission:transfers.view');

    Route::middleware('permission:employees.import')->group(function () {
        Route::get('/imports', [ImportController::class, 'index'])->name('imports.index');
        Route::post('/imports', [ImportController::class, 'store'])->name('imports.store');
    });

    Route::get('/users', [UserController::class, 'index'])->name('users.index')->middleware('permission:users.view');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create')->middleware('permission:users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store')->middleware('permission:users.create');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit')->middleware('permission:users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update')->middleware('permission:users.edit');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy')->middleware('permission:users.delete');

    Route::get('/notifications', [NotificationController::class, 'index'])
        ->name('notifications.index')
        ->middleware('permission:notifications.view');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');

    Route::get('/settings/capacity', [SettingsController::class, 'capacity'])
        ->name('settings.capacity')
        ->middleware('permission:settings.manage');
    Route::put('/settings/capacity', [SettingsController::class, 'updateCapacity'])
        ->name('settings.capacity.update')
        ->middleware('permission:settings.manage');

    Route::middleware('permission:permissions.manage')->group(function () {
        Route::get('/settings/permissions', [RolePermissionController::class, 'index'])->name('settings.permissions');
        Route::put('/settings/permissions', [RolePermissionController::class, 'update'])->name('settings.permissions.update');
    });
});
