<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PasswordResetLinkController;
use App\Http\Controllers\NewPasswordController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\UserPermissionController;

Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.form');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register.form');
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
});

Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.store');

Route::middleware(['log.requests'])->group(function () {
    Route::middleware(['auth.custom', 'active'])->group(function () {
        Route::get('/posts/create', [PostController::class, 'create'])
            ->name('posts.create')
            ->middleware('permission:create-posts');

        Route::post('/posts', [PostController::class, 'store'])
            ->name('posts.store')
            ->middleware('permission:create-posts');

        Route::get('/posts/{post}/edit', [PostController::class, 'edit'])
            ->name('posts.edit')
            ->middleware('permission:edit-posts|own');

        Route::put('/posts/{post}', [PostController::class, 'update'])
            ->name('posts.update')
            ->middleware('permission:edit-posts|own');

        Route::delete('/posts/{post}', [PostController::class, 'destroy'])
            ->name('posts.destroy')
            ->middleware('permission:delete-posts|own');

        Route::get('/my-posts', [PostController::class, 'myPosts'])->name('posts.my');
        Route::get('/my-drafts', [PostController::class, 'myDrafts'])->name('posts.drafts');
        Route::get('/pending', [PostController::class, 'pending'])->name('posts.pending');

        Route::post('/posts/{post}/approve', [PostController::class, 'approve'])
            ->name('posts.approve')
            ->middleware(['permission:publish-posts']);
    });

    Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
    Route::get('/posts/{post:slug}', [PostController::class, 'show'])->name('posts.show');
});

Route::middleware(['auth.custom', 'active'])->group(function () {
    Route::post('/comments', [CommentController::class, 'store'])->name('comments.store')->middleware(['permission:create-comments']);
    Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update')->middleware(['permission:edit-comments|own']);
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy')->middleware(['permission:delete-comments|own']);

    Route::get('/dashboard', [PostController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/dashboard/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/dashboard/password', [ProfileController::class, 'editPassword'])->name('password.edit');
    Route::put('/dashboard/password', [ProfileController::class, 'updatePassword'])->name('password.update');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::middleware(['auth.custom'])->group(function () {
    Route::middleware(['permission:manage-users'])->group(function () {
        Route::get('/admin/users', [UserManagementController::class, 'index'])->name('admin.users');
        Route::patch('/admin/users/{user}/toggle-status', [UserManagementController::class, 'toggleStatus'])->name('admin.users.toggle-status');
    });

    Route::middleware(['permission:manage-user-permissions'])->group(function () {
        Route::get('/admin/users/permissions', [UserPermissionController::class, 'index'])->name('admin.users.permissions.index');
        Route::get('/admin/users/{user}/permissions', [UserPermissionController::class, 'edit'])
            ->name('admin.users.permissions.edit');
        Route::put('/admin/users/{user}/permissions', [UserPermissionController::class, 'update'])
            ->name('admin.users.permissions.update');
    });

    Route::middleware(['permission:manage-role-permissions'])->group(function () {
        Route::get('/admin/roles/permissions', [RolePermissionController::class, 'index'])
            ->name('admin.roles.permissions.index');
        Route::put('/admin/roles/{role}/permissions', [RolePermissionController::class, 'update'])
            ->name('admin.roles.permissions.update');
    });
});

Route::get('/', function () {
    return view('welcome');
});

Route::get('/suspended', function () {return view('suspended');})->name('suspended');

Route::get('/lifecycle-test', function () {
    return response()->json([
        'PHP version' => PHP_VERSION,
        'time' => now()->toIso8601String(),
    ]);
});
