@extends('layouts.app')

@section('content')
<div class="about-page">
    <!-- Success Alert -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container-fluid p-0">
            <div class="hero-image-container">
                @if($about->images && count($about->images) > 0)
                    <img src="{{ asset('storage/' . $about->images[0]) }}" alt="{{ $about->title }}" class="hero-image">
                @else
                    <img src="{{ asset('images/default-about.jpg') }}" alt="{{ $about->title ?? 'About Us' }}" class="hero-image">
                @endif
                <div class="hero-overlay"></div>
                <div class="container">
                    <div class="hero-content">
                        <h1 class="hero-title">{{ $about->title }}</h1>
                        <div class="hero-separator"></div>
                        <p class="hero-subtitle">Farm Fresh Products Directly To Your Table</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Introduction Section -->
    <section class="section py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 offset-lg-1">
                    <div class="section-content">
                        {!! $about->introduction !!}
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Story Section -->
    @if($about->our_story)
    <section class="section py-5 bg-light">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    @if($about->images && count($about->images) > 1)
                        <img src="{{ asset('storage/' . $about->images[1]) }}" alt="Our Story" class="img-fluid rounded">
                    @else
                        <img src="{{ asset('images/default-story.jpg') }}" alt="Our Story" class="img-fluid rounded">
                    @endif
                </div>
                <div class="col-lg-6">
                    <div class="section-header mb-4">
                        <h2 class="section-title">Our Story</h2>
                        <div class="section-separator"></div>
                    </div>
                    <div class="section-content">
                        {!! $about->our_story !!}
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- Mission, Vision, Values Section -->
    <section class="section py-5">
        <div class="container">
            <div class="row">
                @if($about->mission)
                <div class="col-md-4 mb-4">
                    <div class="info-box">
                        <div class="icon-container">
                            <i class="fas fa-bullseye"></i>
                        </div>
                        <h3>Our Mission</h3>
                        <div class="content">
                            {!! $about->mission !!}
                        </div>
                    </div>
                </div>
                @endif
                
                @if($about->vision)
                <div class="col-md-4 mb-4">
                    <div class="info-box">
                        <div class="icon-container">
                            <i class="fas fa-eye"></i>
                        </div>
                        <h3>Our Vision</h3>
                        <div class="content">
                            {!! $about->vision !!}
                        </div>
                    </div>
                </div>
                @endif
                
                @if($about->values)
                <div class="col-md-4 mb-4">
                    <div class="info-box">
                        <div class="icon-container">
                            <i class="fas fa-heart"></i>
                        </div>
                        <h3>Our Values</h3>
                        <div class="content">
                            {!! $about->values !!}
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </section>

    <!-- Team Section -->
    @if(!empty($about->team_members))
    <section class="section py-5 bg-light">
        <div class="container">
            <div class="section-header text-center mb-5">
                <h2 class="section-title">Our Team</h2>
                <div class="section-separator mx-auto"></div>
                @if($about->team_description)
                <div class="section-description mt-3">
                    {!! $about->team_description !!}
                </div>
                @endif
            </div>
            
            <div class="row">
                @foreach($about->team_members as $member)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="team-member">
                        <div class="member-image">
                            @if(!empty($member['image']))
                            <img src="{{ asset('storage/' . $member['image']) }}" alt="{{ $member['name'] }}">
                            @else
                            <img src="{{ asset('images/team-placeholder.jpg') }}" alt="{{ $member['name'] }}">
                            @endif
                        </div>
                        <div class="member-info">
                            <h3>{{ $member['name'] }}</h3>
                            <p class="position">{{ $member['position'] }}</p>
                            @if(!empty($member['bio']))
                            <div class="bio">
                                {{ $member['bio'] }}
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Gallery Section -->
    @if(!empty($about->images) && count($about->images) > 2)
    <section class="section py-5">
        <div class="container">
            <div class="section-header text-center mb-5">
                <h2 class="section-title">Our Gallery</h2>
                <div class="section-separator mx-auto"></div>
            </div>
            
            <div class="row gallery-container">
                @foreach($about->images as $index => $image)
                @if($index > 1) <!-- Skip first two images used in hero and story sections -->
                <div class="col-md-4 mb-4 gallery-item">
                    <a href="{{ asset('storage/' . $image) }}" class="gallery-link" data-lightbox="farm-gallery">
                        <img src="{{ asset('storage/' . $image) }}" alt="Gallery Image {{ $index }}" class="img-fluid rounded">
                    </a>
                </div>
                @endif
                @endforeach
            </div>
            
            @if($about->video_url)
            <div class="row mt-4">
                <div class="col-lg-10 offset-lg-1">
                    <div class="video-container">
                        @php
                        // Extract video ID from YouTube or Vimeo URL
                        $videoId = '';
                        $videoSrc = '';
                        
                        if (strpos($about->video_url, 'youtube.com') !== false) {
                            parse_str(parse_url($about->video_url, PHP_URL_QUERY), $params);
                            $videoId = $params['v'] ?? '';
                            $videoSrc = "https://www.youtube.com/embed/{$videoId}";
                        } elseif (strpos($about->video_url, 'youtu.be') !== false) {
                            $videoId = basename(parse_url($about->video_url, PHP_URL_PATH));
                            $videoSrc = "https://www.youtube.com/embed/{$videoId}";
                        } elseif (strpos($about->video_url, 'vimeo.com') !== false) {
                            $videoId = basename(parse_url($about->video_url, PHP_URL_PATH));
                            $videoSrc = "https://player.vimeo.com/video/{$videoId}";
                        }
                        @endphp
                        
                        @if($videoSrc)
                        <div class="ratio ratio-16x9">
                            <iframe src="{{ $videoSrc }}" title="Video" allowfullscreen></iframe>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>
    </section>
    @endif

    <!-- Contact Section -->
    <section class="section py-5 bg-light">
        <div class="container">
            <div class="section-header text-center mb-5">
                <h2 class="section-title">Get In Touch</h2>
                <div class="section-separator mx-auto"></div>
                <p class="section-subtitle">We'd love to hear from you!</p>
            </div>
            
            <div class="row">
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <div class="contact-info-container">
                        @if($about->address)
                        <div class="contact-info-box mb-4">
                            <div class="icon-container">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <h3>Visit Us</h3>
                            <p>{{ $about->address }}</p>
                        </div>
                        @endif
                        
                        @if($about->phone)
                        <div class="contact-info-box mb-4">
                            <div class="icon-container">
                                <i class="fas fa-phone-alt"></i>
                            </div>
                            <h3>Call Us</h3>
                            <p>
                                <a href="tel:{{ str_replace(' ', '', $about->phone) }}" class="contact-link">
                                    {{ $about->phone }}
                                </a>
                            </p>
                        </div>
                        @endif
                        
                        @if($about->email)
                        <div class="contact-info-box mb-4">
                            <div class="icon-container">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <h3>Email Us</h3>
                            <p>
                                <a href="mailto:{{ $about->email }}" class="contact-link">
                                    {{ $about->email }}
                                </a>
                            </p>
                        </div>
                        @endif
                        
                        <div class="social-media-box">
                            <h3>Follow Us</h3>
                            <div class="social-icons">
                                <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                                <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                                <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                                <a href="#" class="social-icon"><i class="fab fa-linkedin-in"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-8">
                    <div class="contact-form-container">
                        <form id="contactForm" action="{{ route('contact.submit') }}" method="POST" class="contact-form">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="name">Your Name</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                                        @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="email">Your Email</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                                        @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group mb-3">
                                <label for="subject">Subject</label>
                                <input type="text" class="form-control @error('subject') is-invalid @enderror" id="subject" name="subject" value="{{ old('subject') }}">
                                @error('subject')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="form-group mb-4">
                                <label for="message">Message</label>
                                <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message" rows="5" required>{{ old('message') }}</textarea>
                                @error('message')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary px-4 py-2">Send Message</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Map Section -->
    <section class="map-section">
        <div class="container-fluid p-0">
            <div class="map-container">
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d63820.39825790657!2d36.99893538637055!3d-1.0379349219865243!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x182f4e5b27c5d8f1%3A0xb150c73f55ece20!2sThika!5e0!3m2!1sen!2ske!4v1625642690075!5m2!1sen!2ske" 
                    width="100%" 
                    height="450" 
                    style="border:0;" 
                    allowfullscreen="" 
                    loading="lazy">
                </iframe>
            </div>
        </div>
    </section>
</div>
@endsection

@push('styles')
<style>
    /* Clean, Professional About Page Styles */
    .about-page {
        color: #333;
        font-family: 'Roboto', 'Segoe UI', sans-serif;
        line-height: 1.6;
    }
    
    /* Hero Section */
    .hero-section {
        position: relative;
        margin-bottom: 40px;
    }
    
    .hero-image-container {
        position: relative;
        height: 450px;
        overflow: hidden;
    }
    
    .hero-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
    }
    
    .hero-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.4);
    }
    
    .hero-content {
        position: absolute;
        top: 50%;
        left: 0;
        width: 100%;
        transform: translateY(-50%);
        color: white;
        text-align: center;
        z-index: 2;
        padding: 20px;
    }
    
    .hero-title {
        font-size: 3rem;
        font-weight: 700;
        margin-bottom: 15px;
    }
    
    .hero-separator {
        width: 70px;
        height: 3px;
        background-color: #28a745;
        margin: 0 auto 15px;
    }
    
    .hero-subtitle {
        font-size: 1.3rem;
        font-weight: 300;
        max-width: 700px;
        margin: 0 auto;
    }
    
    /* Section Styles */
    .section {
        padding: 40px 0;
    }
    
    .section-title {
        font-size: 2.2rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 15px;
    }
    
    .section-separator {
        width: 50px;
        height: 3px;
        background-color: #28a745;
        margin-bottom: 20px;
    }
    
    .section-content {
        font-size: 1.05rem;
        line-height: 1.7;
    }
    
    .section-subtitle {
        font-size: 1.1rem;
        color: #666;
        margin-top: 10px;
    }
    
    /* Info Boxes */
    .info-box {
        background-color: white;
        padding: 25px;
        border-radius: 4px;
        border: 1px solid #eee;
        height: 100%;
        text-align: center;
    }
    
    .icon-container {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 60px;
        height: 60px;
        background-color: #f8f9fa;
        border-radius: 50%;
        margin: 0 auto 15px;
        color: #28a745;
        font-size: 1.6rem;
    }
    
    /* Team Section */
    .team-member {
        background-color: white;
        border-radius: 4px;
        overflow: hidden;
        border: 1px solid #eee;
        height: 100%;
    }
    
    .member-image {
        height: 250px;
        overflow: hidden;
    }
    
    .member-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .member-info {
        padding: 20px;
        text-align: center;
    }
    
    .member-info h3 {
        font-size: 1.4rem;
        margin-bottom: 5px;
        color: #333;
    }
    
    .member-info .position {
        color: #28a745;
        font-weight: 600;
        margin-bottom: 10px;
        font-size: 1rem;
    }
    
    .member-info .bio {
        color: #666;
        font-size: 0.95rem;
    }
    
    /* Gallery */
    .gallery-item {
        margin-bottom: 20px;
    }
    
    .gallery-link {
        display: block;
    }
    
    .video-container {
        margin-top: 20px;
        border-radius: 4px;
        overflow: hidden;
        border: 1px solid #eee;
    }
    
    /* Contact Section */
    .contact-info-container {
        height: 100%;
    }
    
    .contact-info-box {
        text-align: center;
    }
    
    .contact-info-box h3 {
        font-size: 1.2rem;
        margin: 15px 0 5px;
    }
    
    .contact-info-box p {
        color: #666;
        margin: 0;
    }
    
    .contact-link {
        color: #28a745;
        text-decoration: none;
    }
    
    .contact-link:hover {
        text-decoration: underline;
    }
    
    .social-media-box {
        text-align: center;
        margin-top: 20px;
    }
    
    .social-icons {
        margin-top: 10px;
    }
    
    .social-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        background-color: #f8f9fa;
        border-radius: 50%;
        margin-right: 8px;
        color: #28a745;
        font-size: 1rem;
        text-decoration: none;
    }
    
    .social-icon:hover {
        background-color: #28a745;
        color: white;
    }
    
    .contact-form-container {
        background-color: white;
        padding: 25px;
        border-radius: 4px;
        border: 1px solid #eee;
    }
    
    .form-control {
        padding: 10px 12px;
        border-radius: 4px;
        border: 1px solid #ced4da;
    }
    
    .btn-primary {
        background-color: #28a745;
        border-color: #28a745;
    }
    
    .btn-primary:hover {
        background-color: #218838;
        border-color: #1e7e34;
    }
    
    /* Map Section */
    .map-container {
        width: 100%;
        height: 400px;
    }
    
    .map-container iframe {
        width: 100%;
        height: 100%;
    }
    
    /* Responsive Adjustments */
    @media (max-width: 1199px) {
        .hero-title {
            font-size: 2.5rem;
        }
    }
    
    @media (max-width: 991px) {
        .hero-image-container {
            height: 350px;
        }
        
        .hero-title {
            font-size: 2.2rem;
        }
        
        .hero-subtitle {
            font-size: 1.2rem;
        }
        
        .section-title {
            font-size: 1.8rem;
        }
    }
    
    @media (max-width: 767px) {
        .hero-image-container {
            height: 300px;
        }
        
        .hero-title {
            font-size: 2rem;
        }
        
        .hero-subtitle {
            font-size: 1.1rem;
        }
    }
    
    @media (max-width: 575px) {
        .hero-image-container {
            height: 250px;
        }
        
        .hero-title {
            font-size: 1.7rem;
        }
        
        .hero-subtitle {
            font-size: 1rem;
        }
        
        .section-title {
            font-size: 1.6rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Initialize lightbox for gallery if it exists
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof lightbox !== 'undefined') {
            lightbox.option({
                'resizeDuration': 200,
                'wrapAround': true,
                'albumLabel': 'Image %1 of %2'
            });
        }
    });
</script>
@endpush