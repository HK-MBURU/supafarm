@extends('layouts.app')

@section('title', $news->title)

@section('meta_description', $news->excerpt)

@section('content')
<div class="news-single">
    <!-- Article Header -->
    <div class="article-header">
        <div class="container">
            <nav class="breadcrumb">
                <a href="{{ url('/') }}">Home</a>
                <span class="separator">/</span>
                <a href="{{ route('news.index') }}">News</a>
                <span class="separator">/</span>
                <span class="current">{{ Str::limit($news->title, 40) }}</span>
            </nav>

            <div class="article-meta">
                <div class="article-date">
                    <i class="fas fa-calendar-alt"></i>
                    <span>{{ $news->published_date }}</span>
                </div>
                <div class="article-author">
                    <i class="fas fa-user"></i>
                    <span>By {{ $news->author ?: 'Admin' }}</span>
                </div>
                <div class="article-read-time">
                    <i class="fas fa-clock"></i>
                    <span>{{ $news->read_time }} min read</span>
                </div>
                <div class="article-views">
                    <i class="fas fa-eye"></i>
                    <span>{{ $news->views }} views</span>
                </div>
            </div>

            <h1 class="article-title">{{ $news->title }}</h1>

            @if($news->excerpt)
            <p class="article-excerpt">{{ $news->excerpt }}</p>
            @endif
        </div>
    </div>

    <!-- Article Featured Image -->
    @if($news->featured_image_url)
    <div class="article-featured-image">
        <div class="container">
            <img src="{{ $news->featured_image_url }}" alt="{{ $news->title }}" loading="lazy">
        </div>
    </div>
    @endif

    <!-- Article Content -->
    <div class="article-content">
        <div class="container">
            <div class="content-wrapper">
                <div class="main-content">
                    <div class="content-body">
                        {!! $news->content !!}
                    </div>

                    <!-- Tags/Categories (if you add them later) -->
                    @if(false) <!-- Change to true when you implement tags -->
                    <div class="article-tags">
                        <h3>Tags:</h3>
                        <div class="tags-list">
                            <a href="#" class="tag">Farm News</a>
                            <a href="#" class="tag">Updates</a>
                            <a href="#" class="tag">Agriculture</a>
                        </div>
                    </div>
                    @endif

                    <!-- Share Buttons -->
                    <div class="share-section">
                        <h3>Share this article:</h3>
                        <div class="share-buttons">
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
                               target="_blank"
                               class="share-btn facebook">
                                <i class="fab fa-facebook-f"></i>
                                <span>Facebook</span>
                            </a>
                            <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($news->title) }}"
                               target="_blank"
                               class="share-btn twitter">
                                <i class="fab fa-twitter"></i>
                                <span>Twitter</span>
                            </a>
                            <a href="https://wa.me/?text={{ urlencode($news->title . ' ' . url()->current()) }}"
                               target="_blank"
                               class="share-btn whatsapp">
                                <i class="fab fa-whatsapp"></i>
                                <span>WhatsApp</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <aside class="article-sidebar">
                    <!-- Back to News -->
                    <div class="sidebar-section back-to-news">
                        <a href="{{ route('news.index') }}" class="back-btn">
                            <i class="fas fa-arrow-left"></i>
                            <span>Back to All News</span>
                        </a>
                    </div>

                    <!-- Related News -->
                    @if($relatedNews->count() > 0)
                    <div class="sidebar-section related-news">
                        <h3>Related News</h3>
                        <div class="related-list">
                            @foreach($relatedNews as $related)
                            <div class="related-item">
                                @if($related->featured_image_url)
                                <div class="related-image">
                                    <img src="{{ $related->featured_image_url }}" alt="{{ $related->title }}" loading="lazy">
                                </div>
                                @endif
                                <div class="related-content">
                                    <h4>
                                        <a href="{{ route('news.show', $related->slug) }}">
                                            {{ Str::limit($related->title, 50) }}
                                        </a>
                                    </h4>
                                    <div class="related-meta">
                                        <span class="related-date">{{ $related->published_date }}</span>
                                        <span class="related-read-time">{{ $related->read_time }} min</span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Newsletter Signup (optional) -->
                    <div class="sidebar-section newsletter">
                        <h3>Stay Updated</h3>
                        <p>Subscribe to our newsletter for latest updates</p>
                        <form class="newsletter-form">
                            <input type="email" placeholder="Your email address" required>
                            <button type="submit" class="subscribe-btn">
                                <i class="fas fa-paper-plane"></i> Subscribe
                            </button>
                        </form>
                    </div>
                </aside>
            </div>
        </div>
    </div>
</div>
@endsection

<style>
    /* News Single Page Styles */
    .news-single {
        background-color: #ffffff;
    }

    /* Article Header */
    .article-header {
        background-color: #f9f9f9;
        padding: 30px 0;
        border-bottom: 1px solid #e8e8e8;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }

    .breadcrumb {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 20px;
        font-size: 0.9rem;
        color: #666;
    }

    .breadcrumb a {
        color: var(--primary-color);
        text-decoration: none;
    }

    .breadcrumb a:hover {
        text-decoration: underline;
    }

    .breadcrumb .separator {
        color: #999;
    }

    .breadcrumb .current {
        color: var(--dark-color);
        font-weight: 500;
    }

    .article-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        margin-bottom: 20px;
        font-size: 0.9rem;
        color: #666;
    }

    .article-meta > div {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .article-meta i {
        color: var(--primary-color);
        font-size: 0.9rem;
    }

    .article-title {
        color: var(--dark-color);
        font-size: 2rem;
        font-weight: 700;
        line-height: 1.3;
        margin-bottom: 15px;
    }

    .article-excerpt {
        color: #555;
        font-size: 1.1rem;
        line-height: 1.6;
        max-width: 800px;
    }

    /* Article Featured Image */
    .article-featured-image {
        padding: 30px 0;
    }

    .article-featured-image img {
        width: 100%;
        height: 400px;
        object-fit: cover;
        border-radius: 4px;
    }

    /* Article Content */
    .article-content {
        padding: 40px 0;
    }

    .content-wrapper {
        display: grid;
        grid-template-columns: 1fr;
        gap: 40px;
    }

    .main-content {
        max-width: 800px;
    }

    .content-body {
        font-size: 1.1rem;
        line-height: 1.8;
        color: #333;
    }

    .content-body h2 {
        color: var(--dark-color);
        font-size: 1.8rem;
        margin: 30px 0 15px;
        font-weight: 600;
    }

    .content-body h3 {
        color: var(--dark-color);
        font-size: 1.5rem;
        margin: 25px 0 12px;
        font-weight: 600;
    }

    .content-body p {
        margin-bottom: 20px;
    }

    .content-body ul,
    .content-body ol {
        margin-bottom: 20px;
        padding-left: 20px;
    }

    .content-body li {
        margin-bottom: 8px;
    }

    .content-body img {
        max-width: 100%;
        height: auto;
        margin: 20px 0;
        border-radius: 4px;
    }

    .content-body blockquote {
        border-left: 3px solid var(--primary-color);
        padding-left: 20px;
        margin: 25px 0;
        font-style: italic;
        color: #555;
        background-color: #f9f9f9;
        padding: 20px;
        border-radius: 0 4px 4px 0;
    }

    /* Tags */
    .article-tags {
        margin-top: 40px;
        padding-top: 30px;
        border-top: 1px solid #e8e8e8;
    }

    .article-tags h3 {
        color: var(--dark-color);
        font-size: 1.2rem;
        margin-bottom: 15px;
    }

    .tags-list {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .tag {
        background-color: #f5f5f5;
        color: #666;
        padding: 6px 12px;
        border-radius: 4px;
        font-size: 0.9rem;
        text-decoration: none;
        border: 1px solid #e0e0e0;
    }

    .tag:hover {
        background-color: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
    }

    /* Share Section */
    .share-section {
        margin-top: 40px;
        padding-top: 30px;
        border-top: 1px solid #e8e8e8;
    }

    .share-section h3 {
        color: var(--dark-color);
        font-size: 1.2rem;
        margin-bottom: 15px;
    }

    .share-buttons {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .share-btn {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px 15px;
        border-radius: 4px;
        text-decoration: none;
        font-weight: 500;
        font-size: 0.9rem;
        border: 1px solid transparent;
    }

    .share-btn i {
        font-size: 1rem;
    }

    .share-btn.facebook {
        background-color: #1877f2;
        color: white;
    }

    .share-btn.twitter {
        background-color: #1da1f2;
        color: white;
    }

    .share-btn.whatsapp {
        background-color: #25d366;
        color: white;
    }

    .share-btn:hover {
        opacity: 0.9;
    }

    /* Sidebar */
    .article-sidebar {
        display: flex;
        flex-direction: column;
        gap: 30px;
    }

    .sidebar-section {
        background-color: #f9f9f9;
        border: 1px solid #e8e8e8;
        border-radius: 4px;
        padding: 25px;
    }

    .sidebar-section h3 {
        color: var(--dark-color);
        font-size: 1.3rem;
        margin-bottom: 15px;
        font-weight: 600;
    }

    /* Back to News Button */
    .back-btn {
        display: flex;
        align-items: center;
        gap: 10px;
        background-color: var(--primary-color);
        color: white;
        padding: 12px 20px;
        border-radius: 4px;
        text-decoration: none;
        font-weight: 500;
        text-align: center;
        justify-content: center;
    }

    .back-btn:hover {
        background-color: var(--dark-color);
    }

    /* Related News */
    .related-list {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .related-item {
        display: flex;
        gap: 15px;
        padding-bottom: 15px;
        border-bottom: 1px solid #e8e8e8;
    }

    .related-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .related-image {
        flex-shrink: 0;
        width: 80px;
        height: 80px;
    }

    .related-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 4px;
    }

    .related-content {
        flex: 1;
    }

    .related-content h4 {
        margin-bottom: 8px;
    }

    .related-content h4 a {
        color: var(--dark-color);
        font-size: 1rem;
        font-weight: 600;
        text-decoration: none;
        line-height: 1.3;
    }

    .related-content h4 a:hover {
        color: var(--primary-color);
    }

    .related-meta {
        display: flex;
        gap: 10px;
        font-size: 0.8rem;
        color: #666;
    }

    /* Newsletter */
    .newsletter p {
        color: #666;
        margin-bottom: 15px;
        font-size: 0.95rem;
        line-height: 1.5;
    }

    .newsletter-form {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .newsletter-form input {
        padding: 12px 15px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 0.95rem;
    }

    .newsletter-form input:focus {
        outline: none;
        border-color: var(--primary-color);
    }

    .subscribe-btn {
        background-color: var(--dark-color);
        color: white;
        border: none;
        padding: 12px 15px;
        border-radius: 4px;
        font-size: 0.95rem;
        font-weight: 500;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .subscribe-btn:hover {
        background-color: var(--primary-color);
    }

    /* Responsive Styles */
    @media (min-width: 768px) {
        .article-header {
            padding: 40px 0;
        }

        .article-title {
            font-size: 2.5rem;
        }

        .article-featured-image {
            padding: 40px 0;
        }

        .article-featured-image img {
            height: 500px;
        }

        .article-content {
            padding: 50px 0;
        }

        .content-body {
            font-size: 1.15rem;
        }
    }

    @media (min-width: 992px) {
        .content-wrapper {
            grid-template-columns: 1fr 350px;
            gap: 50px;
        }

        .article-header {
            padding: 50px 0;
        }

        .article-title {
            font-size: 2.8rem;
        }
    }

    @media (max-width: 767px) {
        .article-meta {
            flex-direction: column;
            gap: 10px;
        }

        .article-featured-image img {
            height: 250px;
        }

        .share-buttons {
            flex-direction: column;
        }

        .share-btn {
            justify-content: center;
        }
    }
</style>
