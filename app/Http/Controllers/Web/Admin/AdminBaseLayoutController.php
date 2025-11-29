<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\BaseLayout;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class AdminBaseLayoutController extends Controller
{
    public function index(): Response
    {
        $this->authorize('viewAny', BaseLayout::class);

        $baseLayouts = BaseLayout::with(['user', 'categories', 'tags'])
            ->orderBy('id', 'desc')
            ->paginate(15);

        return Inertia::render('Admin/BaseLayouts/Index', [
            'baseLayouts' => $baseLayouts,
        ]);
    }

    public function show(BaseLayout $baseLayout): Response
    {
        $this->authorize('view', $baseLayout);

        $baseLayout->load(['user', 'categories', 'tags']);

        return Inertia::render('Admin/BaseLayouts/Show', [
            'baseLayout' => $baseLayout,
        ]);
    }

    public function destroy(BaseLayout $baseLayout): RedirectResponse
    {
        $this->authorize('delete', $baseLayout);

        $baseLayout->categories()->detach();
        $baseLayout->tags()->detach();
        $baseLayout->delete();

        return redirect()->route('admin.base-layouts.index')
            ->with('success', 'Base layout deleted successfully.');
    }
}
