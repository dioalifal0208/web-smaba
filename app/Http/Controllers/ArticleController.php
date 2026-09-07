<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Contracts\View\View;

class ArticleController extends Controller
{
    public function index(): View
    {
        $articles = Article::query()->published()->orderByDesc('published_at')->orderByDesc('id')
            ->select(['id', 'title', 'slug', 'excerpt', 'published_at'])->paginate(10);

        return view('pages.articles.index', compact('articles'));
    }

    public function show(string $slug): View
    {
        $article = Article::query()->published()->where('slug', $slug)
            ->select(['title', 'slug', 'excerpt', 'body', 'published_at'])->firstOrFail();

        return view('pages.articles.show', compact('article'));
    }
}
