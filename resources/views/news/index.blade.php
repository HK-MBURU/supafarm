@extends('layouts.app')

@section('title', 'News & Updates')

@section('content')
<div class="news-page">
    <div class="page-header">
        <h1>News & Updates</h1>
        <p>Stay updated with the latest from our farm</p>
    </div>

    @if($news->count() > 0)
    <div class="news-archive">
        @foreach($news as $item)
        <div class="news-archive-item">
            <div class="news-archive-image">
                <img src="{{ $item->featured_image_url }}" alt="{{ $item->title }}" loading="lazy">
                <div class="news-archive-date">
                    {{ $item->published_date }}
                </div>
            </div>
            <div class="news-archive-content">
                <h2>{{ $item->title }}</h2>
                <div class="news-archive-meta">
                    <span><i class="fas fa-user"></i> {{ $item->author ?: 'Admin' }}</span>
                    <span><i class="fas fa-clock"></i> {{ $item->read_time }} min read</span>
                    <span><i class="fas fa-eye"></i> {{ $item->views }} views</span>
                </div>
                <p>{{ $item->excerpt }}</p>
                <a href="{{ route('news.show', $item->slug) }}" class="btn-read">Read Full Story</a>
            </div>
        </div>
        @endforeach
    </div>

    {{ $news->links() }}
    @else
    <div class="no-news">
        <p>No news articles available at the moment.</p>
    </div>
    @endif
</div>

<style>
    .news-page {
        max-width: 1200px;
        margin: 0 auto;
        padding: 30px 20px;
    }

    .page-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .page-header h1 {
        color: var(--dark-color);
        font-size: 2.5rem;
        margin-bottom: 10px;
    }

    .news-archive {
        display: grid;
        grid-template-columns: 1fr;
        gap: 30px;
    }

    .news-archive-item {
        background: white;
        border: 1px solid #e0e0e0;
        border-radius: 4px;
        overflow: hidden;
    }

    .news-archive-image {
        position: relative;
        height: 200px;
    }

    .news-archive-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .news-archive-date {
        position: absolute;
        top: 15px;
        left: 15px;
        background: var(--primary-color);
        color: white;
        padding: 5px 10px;
        border-radius: 4px;
        font-size: 0.9rem;
    }

    .news-archive-content {
        padding: 25px;
    }

    .news-archive-content h2 {
        color: var(--dark-color);
        font-size: 1.5rem;
        margin-bottom: 10px;
    }

    .news-archive-meta {
        display: flex;
        gap: 15px;
        margin-bottom: 15px;
        font-size: 0.9rem;
        color: #666;
    }

    .news-archive-meta i {
        color: var(--primary-color);
        margin-right: 5px;
    }

    .btn-read {
        display: inline-block;
        background: transparent;
        color: var(--primary-color);
        border: 2px solid var(--primary-color);
        padding: 8px 20px;
        border-radius: 4px;
        text-decoration: none;
        font-weight: 600;
        margin-top: 15px;
    }

    .no-news {
        text-align: center;
        padding: 50px;
        font-size: 1.1rem;
        color: #666;
    }

    @media (min-width: 768px) {
        .news-archive {
            grid-template-columns: repeat(2, 1fr);
        }

        .news-page {
            padding: 40px;
        }
    }

    @media (min-width: 1024px) {
        .news-archive {
            grid-template-columns: repeat(3, 1fr);
        }
    }
</style>
@endsection
