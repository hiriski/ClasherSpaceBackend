<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class AdminUserController extends Controller
{
    public function index(): Response
    {
        $this->authorize('viewAny', User::class);

        $users = User::with('authProvider')
            ->orderBy('id', 'desc')
            ->paginate(15);

        return Inertia::render('Admin/Users/Index', [
            'users' => $users,
        ]);
    }

    public function show(User $user): Response
    {
        $this->authorize('view', $user);

        $user->load(['baseLayouts', 'authProvider', 'player']);

        return Inertia::render('Admin/Users/Show', [
            'user' => $user,
            'baseLayoutsCount' => $user->baseLayouts()->count(),
        ]);
    }

    public function destroy(User $user): RedirectResponse
    {
        $this->authorize('delete', $user);

        $user->baseLayouts()->each(function ($layout) {
            $layout->categories()->detach();
            $layout->tags()->detach();
            $layout->delete();
        });

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }
}
