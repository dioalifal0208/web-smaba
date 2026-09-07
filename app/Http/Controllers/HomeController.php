<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Article;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $news = Article::query()
            ->published()
            ->latest('published_at')
            ->limit(4)
            ->get(['title', 'slug', 'excerpt', 'published_at'])
            ->map(fn (Article $article): array => [
                'title' => $article->title,
                'excerpt' => $article->excerpt,
                'published_at' => $article->published_at,
                'url' => route('articles.show', ['slug' => $article->slug]),
            ]);

        $announcements = Announcement::query()
            ->published()
            ->active()
            ->where('is_important', true)
            ->latest('published_at')
            ->limit(3)
            ->get(['title', 'slug', 'excerpt', 'published_at', 'is_important'])
            ->map(fn (Announcement $announcement): array => [
                'title' => $announcement->title,
                'excerpt' => $announcement->excerpt,
                'date' => $announcement->published_at,
                'is_important' => $announcement->is_important,
                'url' => route('announcements.show', ['slug' => $announcement->slug]),
            ]);

        return view('pages.home', compact('announcements', 'news'));
    }
}
