@php
    // Get latest news - you can adjust the query as needed
    $latestNews = App\Models\News::published()
        ->orderBy('published_at', 'desc')
        ->orderBy('created_at', 'desc')
        ->take(2)
        ->get();
@endphp

@if($latestNews->count() > 0)
<section class="latest-news">
    <div class="section-header">
        <h2 class="section-title">Latest News & Updates</h2>
        <p class="section-subtitle">Stay informed with our farm's latest stories</p>
    </div>

    <div class="news-grid">
        @foreach($latestNews as $news)
        <div class="news-card">
            <div class="news-image">
                @if($news->featured_image_url)
                    <img src="{{ $news->featured_image_url }}" alt="{{ $news->title }}" loading="lazy">
                @endif
                <div class="news-date">
                    <span class="date-day">{{ $news->published_at ? $news->published_at->format('d') : $news->created_at->format('d') }}</span>
                    <span class="date-month">{{ $news->published_at ? $news->published_at->format('M') : $news->created_at->format('M') }}</span>
                </div>
            </div>

            <div class="news-content">


                <h3 class="news-title">{{ Str::limit($news->title, 60) }}</h3>

                <p class="news-excerpt">{{ Str::limit($news->excerpt, 120) }}</p>

                <a href="{{ route('news.show', $news->slug) }}" class="btn-read">
                    Read More <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
        @endforeach
    </div>

    <!-- View All News Button -->
    <div class="view-all-container">
        <a href="{{ route('news.index') }}" class="btn-view-all-news">
            <span>View All News & Updates</span>

        </a>
    </div>
</section>
@endif

<style>
    /* Latest News Section - Modern Flat Design */
    .latest-news {
        padding: 40px 20px;
        background-color: #f9f9f9;
        margin: 40px 0;
        border-top: 1px solid #e8e8e8;
        border-bottom: 1px solid #e8e8e8;
    }

    .section-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .section-title {
        color: var(--dark-color);
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .section-subtitle {
        color: #666;
        font-size: 1.1rem;
    }

    /* News Grid */
    .news-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 30px;
        max-width: 1200px;
        margin: 0 auto 40px auto;
    }

    /* News Card */
    .news-card {
        background: #ffffff;
        border: 1px solid #e0e0e0;
        border-radius: 4px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    /* News Image with Date Badge */
    .news-image {
        position: relative;
        height: 250px;
        background-color: #f5f5f5;
        overflow: hidden;
    }

    .news-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .news-date {
        position: absolute;
        top: 20px;
        left: 20px;
        background-color: var(--primary-color);
        color: white;
        width: 60px;
        height: 60px;
        border-radius: 4px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        line-height: 1;
        text-align: center;
    }

    .date-day {
        font-size: 1.5rem;
        font-weight: 700;
        display: block;
    }

    .date-month {
        font-size: 0.9rem;
        font-weight: 500;
        display: block;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* News Content */
    .news-content {
        padding: 25px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .news-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 15px;
        font-size: 0.85rem;
        color: #666;
    }

    .news-author,
    .news-read-time {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .news-author i,
    .news-read-time i {
        color: var(--primary-color);
        font-size: 0.8rem;
    }

    .news-title {
        color: var(--dark-color);
        font-size: 1.4rem;
        font-weight: 700;
        margin-bottom: 15px;
        line-height: 1.3;
    }

    .news-excerpt {
        color: #555;
        font-size: 1rem;
        line-height: 1.6;
        margin-bottom: 20px;
        flex-grow: 1;
    }

    /* Read More Button */
    .btn-read {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        background-color: transparent;
        color: var(--primary-color);
        border: 2px solid var(--primary-color);
        border-radius: 4px;
        padding: 10px 20px;
        font-size: 0.95rem;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        transition: none;
        align-self: flex-start;
        height: 44px;
    }

    .btn-read i {
        font-size: 0.9rem;
        transition: none;
    }

    .btn-read:hover {
        background-color: var(--primary-color);
        color: white;
    }

    /* View All News Button Container */
    .view-all-container {
        text-align: center;
    }

    /* View All News Button */
    .btn-view-all-news {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        background-color: var(--dark-color);
        color: white;
        border: 2px solid var(--dark-color);
        border-radius: 4px;
        padding: 12px 28px;
        font-size: 0.95rem;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        transition: none;
        letter-spacing: 0.3px;
        line-height: 1.2;
        height: 48px;
    }

    .btn-view-all-news span {
        flex-grow: 1;
        text-align: center;
    }

    .btn-view-all-news i {
        font-size: 0.9rem;
    }

    .btn-view-all-news:hover {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }

    /* Desktop Styles */
    @media (min-width: 768px) {
        .latest-news {
            padding: 50px 30px;
            margin: 50px 0;
        }

        .section-title {
            font-size: 2.2rem;
        }

        .section-subtitle {
            font-size: 1.2rem;
        }

        .news-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 40px;
            margin-bottom: 50px;
        }

        .news-image {
            height: 280px;
        }

        .news-content {
            padding: 30px;
        }

        .news-title {
            font-size: 1.5rem;
        }

        .btn-read {
            padding: 11px 24px;
            font-size: 1rem;
            height: 46px;
        }

        .btn-view-all-news {
            padding: 13px 32px;
            font-size: 1rem;
            height: 50px;
        }
    }

    @media (min-width: 1024px) {
        .latest-news {
            padding: 60px 40px;
            margin: 60px 0;
        }

        .section-title {
            font-size: 2.5rem;
        }

        .news-grid {
            gap: 50px;
        }

        .news-image {
            height: 300px;
        }

        .news-title {
            font-size: 1.6rem;
        }

        .news-excerpt {
            font-size: 1.05rem;
        }
    }
</style>
