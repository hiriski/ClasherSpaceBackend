<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Web\Admin\AdminDashboardController;
use App\Http\Controllers\Web\Admin\AdminUserController;
use App\Http\Controllers\Web\Admin\AdminBaseLayoutController;
use App\Http\Controllers\Web\UserDashboardController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Admin Routes
    Route::prefix('admin')->middleware('role:admin')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

        // Users Management
        Route::get('/users', [AdminUserController::class, 'index'])->name('admin.users.index');
        Route::get('/users/{user}', [AdminUserController::class, 'show'])->name('admin.users.show');
        Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy');

        // Base Layouts Management
        Route::get('/base-layouts', [AdminBaseLayoutController::class, 'index'])->name('admin.base-layouts.index');
        Route::get('/base-layouts/{baseLayout}', [AdminBaseLayoutController::class, 'show'])->name('admin.base-layouts.show');
        Route::delete('/base-layouts/{baseLayout}', [AdminBaseLayoutController::class, 'destroy'])->name('admin.base-layouts.destroy');

        // Categories Management (placeholder routes - implement controllers as needed)
        Route::get('/categories', function () {
            return Inertia::render('Admin/Categories/Index', [
                'categories' => \App\Models\BaseLayoutCategory::paginate(15)
            ]);
        })->name('admin.categories.index');

        // Tags Management (placeholder routes - implement controllers as needed)
        Route::get('/tags', function () {
            return Inertia::render('Admin/Tags/Index', [
                'tags' => \App\Models\BaseLayoutTag::paginate(15)
            ]);
        })->name('admin.tags.index');
    });

    // General User Routes
    Route::middleware('role:general_user')->group(function () {
        Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');

        // Base Layouts CRUD (placeholder routes - implement controller as needed)
        Route::get('/base-layouts', function () {
            $userId = auth()->id();
            return Inertia::render('BaseLayouts/Index', [
                'baseLayouts' => \App\Models\BaseLayout::with(['categories', 'tags'])
                    ->where('userId', $userId)
                    ->orderBy('id', 'desc')
                    ->paginate(15)
            ]);
        })->name('base-layouts.index');

        Route::get('/base-layouts/create', function () {
            return Inertia::render('BaseLayouts/Form', [
                'categories' => \App\Models\BaseLayoutCategory::all(),
                'tags' => \App\Models\BaseLayoutTag::all(),
            ]);
        })->name('base-layouts.create');

        Route::get('/base-layouts/{baseLayout}', function (\App\Models\BaseLayout $baseLayout) {
            $baseLayout->load(['user', 'categories', 'tags']);
            return Inertia::render('BaseLayouts/Show', [
                'baseLayout' => $baseLayout
            ]);
        })->name('base-layouts.show');
    });

    // Profile Routes (for all authenticated users)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
