<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    /**
     * Display news index page with all news
     */
    public function index()
    {
        $news = News::published()
                    ->orderBy('published_at', 'desc')
                    ->orderBy('created_at', 'desc')
                    ->paginate(12);

        return view('news.index', compact('news'));
    }

    /**
     * Display single news article
     */
    public function show($slug)
    {
        $news = News::where('slug', $slug)->firstOrFail();

        // Increment views
        if ($news->isPublished()) {
            $news->incrementViews();
        }

        // Get related news (same category or recent)
        $relatedNews = News::published()
                          ->where('id', '!=', $news->id)
                          ->orderBy('published_at', 'desc')
                          ->limit(3)
                          ->get();

        return view('news.show', compact('news', 'relatedNews'));
    }

    /**
     * Get latest news for homepage partial
     */
    public function getLatestNews()
    {
        $latestNews = News::published()
                         ->orderBy('published_at', 'desc')
                         ->orderBy('created_at', 'desc')
                         ->take(4) // Get 4 for homepage grid
                         ->get();

        return $latestNews;
    }

    /**
     * Get featured news for homepage
     */
    public function getFeaturedNews()
    {
        $featuredNews = News::published()
                           ->featured()
                           ->orderBy('published_at', 'desc')
                           ->take(2) // Get 2 for featured section
                           ->get();

        return $featuredNews;
    }
}
