@extends('layouts.app')

@section('content')
<div class="hero-section">
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <h1>Supa Farm Supplies</h1>
        <p>Fresh farm products straight from nature to your table</p>
        <a href="/products" class="btn-primary btn-sm">Shop Now</a>
    </div>
</div>

<div class="featured-products">
    <h2>Our Products</h2>

    <div class="product-cards">
        @foreach($categories as $category)
        <div class="product-card">
            @if($category->image_url)
            <img src="{{ $category->image_url }}" alt="{{ $category->name }}">
            @else
            <img src="{{ asset('images/no-image-available.jpg') }}" alt="No image available" class="img-fluid">
            @endif
            <h3>{{$category->name}}</h3>
            <p>{{$category->description}}</p>
            <a href="{{ route('products.category', $category->id) }}" class="btn-secondary">View Details</a>
        </div>
        @endforeach


    </div>
</div>

<div class="about-section">
    @if($about)
    <h2>{{ $about->title ?? 'About Supa Farm' }}</h2>

    @if($about->introduction)
    <div class="introduction">{!! strip_tags($about->introduction, '<p><br><strong><em>
                    <ul>
                        <ol>
                            <li>') !!}</div>
    @endif

    @if($about->our_story)
    <div class="our-story">
        <h3>Our Story</h3>
        <div class="story-content">{!! Str::limit(strip_tags($about->our_story, '<p><br><strong><em>'), 200) !!}</div>
    </div>
    @endif

    @if($about->mission)
    <div class="mission">
        <h4>Our Mission</h4>
        <div class="mission-content">{!! strip_tags($about->mission, '<p><br><strong><em>
                        <ul>
                            <ol>
                                <li>') !!}</div>
    </div>
    @endif

    @if($about->main_image_url)
    <div class="about-image-container">
        <div class="about-image">
            <img src="{{ $about->main_image_url }}" alt="{{ $about->title }}" loading="lazy">
            <div class="image-overlay"></div>
        </div>
    </div>
    @endif

    <div class="about-cta">
        <a href="/about" class="btn-secondary">Learn More About Us</a>
    </div>
    @else
    <!-- Fallback content -->
    <h2>About Supa Farm</h2>
    <p>We are dedicated to producing high-quality farm products using sustainable methods. Our family-owned farm has been operating since 2010, providing the freshest produce to our community.</p>
    <div class="about-cta">
        <a href="/about" class="btn-secondary">Learn More</a>
    </div>
    @endif
</div>

<style>
    /* About Section Professional Styling */
.about-section {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    padding: 60px 30px;
    margin: 40px 0;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    position: relative;
    overflow: hidden;
}

.about-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--primary-color), #2A6D80, var(--primary-color));
}

.about-section h2 {
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--dark-color);
    margin-bottom: 30px;
    text-align: center;
    position: relative;
}

.about-section h2::after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 50%;
    transform: translateX(-50%);
    width: 80px;
    height: 3px;
    background: var(--primary-color);
    border-radius: 2px;
}

.introduction {
    font-size: 1.2rem;
    line-height: 1.8;
    color: #555;
    margin-bottom: 30px;
    text-align: center;
    max-width: 800px;
    margin-left: auto;
    margin-right: auto;
}

.our-story, .mission {
    margin: 30px 0;
    padding: 25px;
    background: rgba(255, 255, 255, 0.7);
    border-radius: 10px;
    border-left: 4px solid var(--primary-color);
}

.our-story h3, .mission h4 {
    color: var(--primary-color);
    margin-bottom: 15px;
    font-weight: 600;
}

.story-content, .mission-content {
    color: #666;
    line-height: 1.7;
}

/* Professional Image Styling */
.about-image-container {
    margin: 40px 0;
    display: flex;
    justify-content: center;
}

.about-image {
    position: relative;
    max-width: 600px;
    width: 100%;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
    transform: perspective(1000px) rotateX(2deg);
    transition: all 0.3s ease;
}

.about-image:hover {
    transform: perspective(1000px) rotateX(0deg) translateY(-5px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.25);
}

.about-image img {
    width: 100%;
    height: 350px;
    object-fit: cover;
    display: block;
    transition: transform 0.3s ease;
}

.about-image:hover img {
    transform: scale(1.05);
}

.image-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(
        45deg,
        rgba(var(--primary-color-rgb), 0.1) 0%,
        transparent 50%,
        rgba(var(--primary-color-rgb), 0.1) 100%
    );
    opacity: 0;
    transition: opacity 0.3s ease;
}

.about-image:hover .image-overlay {
    opacity: 1;
}

/* Call to Action Button */
.about-cta {
    text-align: center;
    margin-top: 40px;
}









/* Responsive Design */
@media (max-width: 768px) {
    .about-section {
        padding: 40px 20px;
        margin: 20px 0;
    }
    
    .about-section h2 {
        font-size: 2rem;
    }
    
    .introduction {
        font-size: 1.1rem;
    }
    
    .our-story, .mission {
        padding: 20px;
    }
    
    .about-image {
        transform: none;
    }
    
    .about-image img {
        height: 250px;
    }
}

@media (max-width: 480px) {
    .about-section h2 {
        font-size: 1.8rem;
    }
    
    .btn-secondary {
        padding: 12px 25px;
        font-size: 1rem;
    }
}
</style>
@endsection