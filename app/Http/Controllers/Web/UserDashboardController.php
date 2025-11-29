<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\BaseLayout;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class UserDashboardController extends Controller
{
    public function index(): Response
    {
        $userId = Auth::id();

        $stats = [
            'totalBaseLayouts' => BaseLayout::where('userId', $userId)->count(),
            'totalViews' => BaseLayout::where('userId', $userId)->sum('views'),
            'totalLikes' => BaseLayout::where('userId', $userId)->sum('likedCount'),
        ];

        return Inertia::render('User/Dashboard', [
            'stats' => $stats,
        ]);
    }
}
