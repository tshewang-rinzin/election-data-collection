<?php

use App\Domains\Auth\Http\Controllers\Backend\User\DeactivatedUserController;
use App\Domains\Auth\Http\Controllers\Backend\User\DeletedUserController;
use App\Domains\Auth\Http\Controllers\Backend\User\UserController;
use App\Domains\Auth\Http\Controllers\Backend\User\UserPasswordController;
use App\Domains\Auth\Http\Controllers\Backend\User\UserSessionController;
use App\Http\Controllers\Backend\ElectionResultController;
use App\Domains\Auth\Models\Role;
use App\Domains\Auth\Models\User;
use Tabuna\Breadcrumbs\Trail;

// All route names are prefixed with 'admin.auth'.
Route::group([
    'prefix' => 'election-result',
    'as' => 'election-result.',
    'middleware' => 'auth',
], function () {
    Route::get('/', [ElectionResultController::class, 'index'])
        ->name('index')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.dashboard')
                ->push(__('Election Result'), route('admin.dashboard'));
        });

    Route::get('create', [ElectionResultController::class, 'create'])
        ->name('create')
        ->breadcrumbs(function (Trail $trail) {
            $trail->parent('admin.auth.role.index')
                ->push(__('Create Role'), route('admin.auth.role.create'));
        });

    Route::post('/', [ElectionResultController::class, 'store'])->name('store');

    Route::group(['prefix' => '{role}'], function () {
        Route::get('edit', [ElectionResultController::class, 'edit'])
            ->name('edit')
            ->breadcrumbs(function (Trail $trail, Role $role) {
                $trail->parent('admin.auth.role.index')
                    ->push(__('Editing :role', ['role' => $role->name]), route('admin.auth.role.edit', $role));
            });

        Route::patch('/', [ElectionResultController::class, 'update'])->name('update');
        Route::delete('/', [ElectionResultController::class, 'destroy'])->name('destroy');
    });
});

