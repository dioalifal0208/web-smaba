<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Contracts\View\View;

class AnnouncementController extends Controller
{
    public function index(): View
    {
        $announcements = Announcement::query()->published()->active()->orderByDesc('is_important')
            ->orderByDesc('published_at')->orderByDesc('id')
            ->select(['id', 'title', 'slug', 'excerpt', 'published_at', 'is_important'])->paginate(10);

        return view('pages.announcements.index', compact('announcements'));
    }

    public function show(string $slug): View
    {
        $announcement = Announcement::query()->published()->active()->where('slug', $slug)
            ->select(['title', 'slug', 'excerpt', 'body', 'published_at', 'expires_at', 'is_important'])->firstOrFail();

        return view('pages.announcements.show', compact('announcement'));
    }
}
