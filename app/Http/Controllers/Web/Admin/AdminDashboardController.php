<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\BaseLayout;
use Inertia\Inertia;
use Inertia\Response;

class AdminDashboardController extends Controller
{
    public function index(): Response
    {
        $stats = [
            'totalUsers' => User::count(),
            'totalBaseLayouts' => BaseLayout::count(),
            'totalViews' => BaseLayout::sum('views'),
            'totalLikes' => BaseLayout::sum('likedCount'),
        ];

        return Inertia::render('Admin/Dashboard', [
            'stats' => $stats,
        ]);
    }
}
