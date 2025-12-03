@php
    // Get active images for scrolling gallery
    $galleryImages = App\Models\Media::active()
        ->images()
        ->ordered()
        ->take(15) // Get more images for smooth scrolling
        ->get();
@endphp

@if($galleryImages->count() > 0)
<section class="gallery-scroll-section">
    <div class="section-header">
        <h2 class="section-title">Our Gallery</h2>
        <p class="section-subtitle">A glimpse of our farm and products</p>
    </div>

    <!-- Gallery Container -->
    <div class="gallery-scroll-container">
        <div class="gallery-scroll-track">
            <!-- First set of images -->
            @foreach($galleryImages as $image)
            <div class="gallery-item">
                <div class="gallery-image-wrapper">
                    <img src="{{ $image->file_url }}"
                         alt="{{ $image->title }}"
                         loading="lazy"
                         class="gallery-image">

                    @if($image->title)
                    <div class="image-caption">
                        <span class="caption-text">{{ Str::limit($image->title, 25) }}</span>
                    </div>
                    @endif

                    <div class="image-overlay">
                        <div class="overlay-content">
                            @if($image->title)
                            <h3>{{ $image->title }}</h3>
                            @endif
                            @if($image->description)
                            <p>{{ Str::limit($image->description, 80) }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach

            <!-- Duplicate set for seamless scrolling -->
            @foreach($galleryImages as $image)
            <div class="gallery-item">
                <div class="gallery-image-wrapper">
                    <img src="{{ $image->file_url }}"
                         alt="{{ $image->title }}"
                         loading="lazy"
                         class="gallery-image">

                    @if($image->title)
                    <div class="image-caption">
                        <span class="caption-text">{{ Str::limit($image->title, 25) }}</span>
                    </div>
                    @endif

                    <div class="image-overlay">
                        <div class="overlay-content">
                            @if($image->title)
                            <h3>{{ $image->title }}</h3>
                            @endif
                            @if($image->description)
                            <p>{{ Str::limit($image->description, 80) }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- View All Gallery Button -->
    <div class="view-all-container">
        <a href="{{ route('gallery.index') }}" class="btn-view-gallery">
            <span>View Full Gallery</span>
            <i class="fas fa-images"></i>
        </a>
    </div>
</section>
@endif

<style>
    /* Gallery Scroll Section - Modern Flat Design */
    .gallery-scroll-section {
        padding: 40px 0;
        background-color: #ffffff;
        margin: 40px 0;
        border-top: 1px solid #e8e8e8;
        border-bottom: 1px solid #e8e8e8;
        overflow: hidden;
    }

    .section-header {
        text-align: center;
        margin-bottom: 40px;
        padding: 0 20px;
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

    /* Gallery Scroll Container */
    .gallery-scroll-container {
        width: 100%;
        overflow: hidden;
        position: relative;
        margin-bottom: 40px;
    }

    .gallery-scroll-track {
        display: flex;
        animation: scrollGallery 40s linear infinite;
        padding: 10px 0;
        will-change: transform;
    }

    /* Pause animation on hover */
    .gallery-scroll-container:hover .gallery-scroll-track {
        animation-play-state: paused;
    }

    /* Gallery Item */
    .gallery-item {
        flex: 0 0 280px;
        margin: 0 15px;
        height: 350px;
        border-radius: 4px;
        overflow: hidden;
        position: relative;
        border: 1px solid #e8e8e8;
        background-color: #f9f9f9;
    }

    .gallery-image-wrapper {
        width: 100%;
        height: 100%;
        position: relative;
        overflow: hidden;
    }

    .gallery-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .gallery-item:hover .gallery-image {
        transform: scale(1.05);
    }

    /* Image Caption */
    .image-caption {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background-color: rgba(84, 25, 7, 0.85);
        color: white;
        padding: 10px 15px;
        font-size: 0.9rem;
        font-weight: 500;
        text-align: center;
    }

    .caption-text {
        display: inline-block;
        max-width: 100%;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    /* Image Overlay */
    .image-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(188, 69, 13, 0.9);
        color: white;
        opacity: 0;
        transition: opacity 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        text-align: center;
    }

    .gallery-item:hover .image-overlay {
        opacity: 1;
    }

    .overlay-content h3 {
        font-size: 1.2rem;
        font-weight: 600;
        margin-bottom: 10px;
    }

    .overlay-content p {
        font-size: 0.9rem;
        line-height: 1.5;
        opacity: 0.9;
    }

    /* View All Gallery Button */
    .view-all-container {
        text-align: center;
        padding: 0 20px;
    }

    .btn-view-gallery {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        background-color: transparent;
        color: var(--primary-color);
        border: 2px solid var(--primary-color);
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

    .btn-view-gallery span {
        flex-grow: 1;
        text-align: center;
    }

    .btn-view-gallery i {
        font-size: 0.9rem;
    }

    .btn-view-gallery:hover {
        background-color: var(--primary-color);
        color: white;
    }

    /* Animation for scrolling */
    @keyframes scrollGallery {
        0% {
            transform: translateX(0);
        }
        100% {
            transform: translateX(-50%);
        }
    }

    /* Responsive Styles */
    @media (min-width: 768px) {
        .gallery-scroll-section {
            padding: 50px 0;
            margin: 50px 0;
        }

        .section-title {
            font-size: 2.2rem;
        }

        .section-subtitle {
            font-size: 1.2rem;
        }

        .gallery-item {
            flex: 0 0 320px;
            height: 400px;
        }

        .btn-view-gallery {
            padding: 13px 32px;
            font-size: 1rem;
            height: 50px;
        }
    }

    @media (min-width: 1024px) {
        .gallery-scroll-section {
            padding: 60px 0;
            margin: 60px 0;
        }

        .section-title {
            font-size: 2.5rem;
        }

        .gallery-item {
            flex: 0 0 350px;
            height: 420px;
        }

        .gallery-scroll-track {
            animation-duration: 50s; /* Slower animation on larger screens */
        }
    }

    /* Mobile adjustments */
    @media (max-width: 767px) {
        .gallery-item {
            flex: 0 0 240px;
            height: 300px;
            margin: 0 10px;
        }

        .image-caption {
            font-size: 0.8rem;
            padding: 8px 10px;
        }

        .overlay-content h3 {
            font-size: 1rem;
        }

        .overlay-content p {
            font-size: 0.85rem;
        }

        .gallery-scroll-track {
            animation-duration: 30s; /* Faster on mobile */
        }
    }

    /* Support for reduced motion */
    @media (prefers-reduced-motion: reduce) {
        .gallery-scroll-track {
            animation: none;
            overflow-x: auto;
            scroll-behavior: smooth;
        }

        .gallery-scroll-track::-webkit-scrollbar {
            height: 8px;
        }

        .gallery-scroll-track::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        .gallery-scroll-track::-webkit-scrollbar-thumb {
            background: var(--primary-color);
            border-radius: 4px;
        }

        .gallery-scroll-track::-webkit-scrollbar-thumb:hover {
            background: var(--dark-color);
        }
    }
</style>

<script>
// Fallback for browsers that don't support CSS animations
document.addEventListener('DOMContentLoaded', function() {
    const galleryTrack = document.querySelector('.gallery-scroll-track');
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    if (prefersReducedMotion && galleryTrack) {
        // Add scroll buttons for manual scrolling
        const galleryContainer = galleryTrack.closest('.gallery-scroll-container');

        // Create navigation buttons
        const navHTML = `
            <div class="gallery-scroll-nav">
                <button class="scroll-btn prev-btn" aria-label="Scroll left">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="scroll-btn next-btn" aria-label="Scroll right">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        `;

        galleryContainer.insertAdjacentHTML('beforeend', navHTML);

        // Style for navigation buttons
        const style = document.createElement('style');
        style.textContent = `
            .gallery-scroll-nav {
                position: absolute;
                top: 50%;
                left: 0;
                right: 0;
                transform: translateY(-50%);
                display: flex;
                justify-content: space-between;
                padding: 0 10px;
                pointer-events: none;
                z-index: 2;
            }

            .scroll-btn {
                background-color: rgba(188, 69, 13, 0.9);
                color: white;
                border: none;
                width: 40px;
                height: 40px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                pointer-events: all;
                transition: background-color 0.3s ease;
            }

            .scroll-btn:hover {
                background-color: var(--dark-color);
            }

            @media (max-width: 767px) {
                .gallery-scroll-nav {
                    display: none;
                }
            }
        `;
        document.head.appendChild(style);

        // Add scroll functionality
        const scrollAmount = 300;
        const prevBtn = galleryContainer.querySelector('.prev-btn');
        const nextBtn = galleryContainer.querySelector('.next-btn');

        if (prevBtn) {
            prevBtn.addEventListener('click', () => {
                galleryTrack.scrollBy({
                    left: -scrollAmount,
                    behavior: 'smooth'
                });
            });
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', () => {
                galleryTrack.scrollBy({
                    left: scrollAmount,
                    behavior: 'smooth'
                });
            });
        }
    }
});
</script>
