@extends('layouts.app')

@section('title', 'Gallery')

@section('content')
<div class="gallery-page">
    <div class="page-header">
        <h1>Our Gallery</h1>
        <p>Explore our farm, products, and activities through photos and videos</p>

        <!-- Filter Tabs -->
        <div class="gallery-filters">
            <button class="filter-btn active" data-filter="all">All Media</button>
            <button class="filter-btn" data-filter="image">Photos</button>
            <button class="filter-btn" data-filter="video">Videos</button>
        </div>
    </div>

    @if($images->count() > 0 || $videos->count() > 0)
    <div class="gallery-grid">
        <!-- Images -->
        @foreach($images as $item)
        <div class="gallery-item" data-type="image">
            <div class="gallery-item-inner">
                <img src="{{ $item->file_url }}" alt="{{ $item->title }}" loading="lazy">
                <div class="gallery-item-overlay">
                    <h3>{{ $item->title }}</h3>
                    @if($item->description)
                    <p>{{ Str::limit($item->description, 100) }}</p>
                    @endif
                    <div class="gallery-item-actions">
                        <a href="{{ $item->file_url }}"
                           class="view-btn"
                           target="_blank"
                           rel="noopener">
                            <i class="fas fa-expand"></i>
                        </a>
                        <a href="{{ route('gallery.show', $item->id) }}" class="details-btn">
                            <i class="fas fa-info-circle"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach

        <!-- Videos -->
        @foreach($videos as $item)
        <div class="gallery-item" data-type="video">
            <div class="gallery-item-inner">
                @if($item->type === 'youtube' && $item->embed_url)
                <div class="video-wrapper">
                    <iframe src="{{ $item->embed_url }}"
                            frameborder="0"
                            allowfullscreen
                            loading="lazy"></iframe>
                </div>
                @elseif($item->type === 'video' && $item->file_url)
                <video controls poster="{{ $item->thumbnail_url }}">
                    <source src="{{ $item->file_url }}" type="video/mp4">
                </video>
                @else
                <div class="video-placeholder">
                    <i class="fas fa-video"></i>
                </div>
                @endif
                <div class="gallery-item-overlay">
                    <h3>{{ $item->title }}</h3>
                    <div class="gallery-item-actions">
                        <a href="{{ route('gallery.show', $item->id) }}" class="play-btn">
                            <i class="fas fa-play"></i> Watch
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="no-gallery">
        <p>No gallery items available at the moment.</p>
    </div>
    @endif
</div>

<style>
    .gallery-page {
        max-width: 1400px;
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

    .gallery-filters {
        display: flex;
        justify-content: center;
        gap: 10px;
        margin-top: 20px;
        flex-wrap: wrap;
    }

    .filter-btn {
        background: transparent;
        color: var(--dark-color);
        border: 2px solid var(--dark-color);
        padding: 8px 20px;
        border-radius: 4px;
        cursor: pointer;
        font-weight: 500;
    }

    .filter-btn.active {
        background: var(--dark-color);
        color: white;
    }

    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 20px;
    }

    .gallery-item {
        background: white;
        border: 1px solid #e8e8e8;
        border-radius: 4px;
        overflow: hidden;
        height: 250px;
    }

    .gallery-item-inner {
        width: 100%;
        height: 100%;
        position: relative;
    }

    .gallery-item img,
    .gallery-item video {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .video-wrapper {
        width: 100%;
        height: 100%;
    }

    .video-wrapper iframe {
        width: 100%;
        height: 100%;
    }

    .video-placeholder {
        width: 100%;
        height: 100%;
        background: #f5f5f5;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        color: var(--primary-color);
    }

    .gallery-item-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(84, 25, 7, 0.9);
        color: white;
        padding: 20px;
        opacity: 0;
        transition: opacity 0.3s;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
    }

    .gallery-item:hover .gallery-item-overlay {
        opacity: 1;
    }

    .gallery-item-overlay h3 {
        font-size: 1.1rem;
        margin-bottom: 10px;
    }

    .gallery-item-overlay p {
        font-size: 0.9rem;
        opacity: 0.9;
    }

    .gallery-item-actions {
        display: flex;
        gap: 10px;
        margin-top: 15px;
    }

    .view-btn,
    .details-btn,
    .play-btn {
        background: var(--primary-color);
        color: white;
        border: none;
        padding: 8px 12px;
        border-radius: 4px;
        text-decoration: none;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .no-gallery {
        text-align: center;
        padding: 50px;
        font-size: 1.1rem;
        color: #666;
    }

    @media (min-width: 768px) {
        .gallery-page {
            padding: 40px;
        }

        .gallery-grid {
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 25px;
        }

        .gallery-item {
            height: 280px;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterButtons = document.querySelectorAll('.filter-btn');
    const galleryItems = document.querySelectorAll('.gallery-item');

    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            const filter = this.dataset.filter;

            // Update active button
            filterButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');

            // Filter items
            galleryItems.forEach(item => {
                if (filter === 'all') {
                    item.style.display = 'block';
                } else {
                    if (item.dataset.type === filter) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                }
            });
        });
    });
});
</script>
@endsection
