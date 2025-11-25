<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $about->title }} - Preview</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #BC450D;
            --secondary: #E7B216;
            --accent: #358BA2;
            --dark: #2D3748;
            --light: #F7FAFC;
            --gray: #718096;
            --border: #E2E8F0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            line-height: 1.6;
            color: var(--dark);
            background-color: #f8f9fa;
        }

        .preview-header {
            background: white;
            border-bottom: 3px solid var(--primary);
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .preview-banner {
            background: linear-gradient(135deg, var(--primary), var(--accent));
            color: white;
            padding: 2rem 0;
            text-align: center;
            margin-bottom: 2rem;
        }

        .preview-banner h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .preview-banner .btn {
            border-radius: 0;
            border: 2px solid white;
            color: white;
            padding: 0.5rem 2rem;
            font-weight: 600;
            margin-top: 1rem;
        }

        .preview-banner .btn:hover {
            background: white;
            color: var(--primary);
        }

        .section {
            padding: 4rem 0;
        }

        .section-alt {
            background: white;
        }

        .section-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 2rem;
            text-align: center;
            position: relative;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background: var(--primary);
        }

        .intro-text {
            font-size: 1.2rem;
            color: var(--gray);
            text-align: center;
            max-width: 800px;
            margin: 0 auto 3rem;
            line-height: 1.8;
        }

        .story-content {
            font-size: 1.1rem;
            line-height: 1.8;
            color: var(--dark);
            white-space: pre-line;
        }

        .mission-card {
            background: white;
            border: 1px solid var(--border);
            padding: 2rem;
            text-align: center;
            height: 100%;
            transition: transform 0.3s ease;
        }

        .mission-card:hover {
            transform: translateY(-5px);
            border-color: var(--primary);
        }

        .mission-icon {
            width: 80px;
            height: 80px;
            background: var(--primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            color: white;
            font-size: 2rem;
        }

        .mission-card h4 {
            color: var(--dark);
            margin-bottom: 1rem;
            font-weight: 600;
        }

        .mission-card p {
            color: var(--gray);
            line-height: 1.6;
        }

        .team-member {
            text-align: center;
            margin-bottom: 2rem;
        }

        .team-member-img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--primary);
            margin: 0 auto 1rem;
        }

        .team-member-placeholder {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background: var(--light);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            border: 3px solid var(--border);
            color: var(--gray);
            font-size: 3rem;
        }

        .team-member h5 {
            color: var(--dark);
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .team-member-position {
            color: var(--primary);
            font-weight: 500;
            margin-bottom: 1rem;
        }

        .team-member-bio {
            color: var(--gray);
            font-size: 0.9rem;
            line-height: 1.6;
        }

        .gallery-item {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .gallery-item img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border: 1px solid var(--border);
        }

        .contact-info {
            background: white;
            padding: 2rem;
            border: 1px solid var(--border);
        }

        .contact-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 1.5rem;
        }

        .contact-icon {
            width: 40px;
            height: 40px;
            background: var(--primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            margin-right: 1rem;
            flex-shrink: 0;
        }

        .contact-details h5 {
            color: var(--dark);
            margin-bottom: 0.25rem;
            font-weight: 600;
        }

        .contact-details p {
            color: var(--gray);
            margin: 0;
        }

        .video-container {
            position: relative;
            padding-bottom: 56.25%;
            height: 0;
            overflow: hidden;
            margin: 2rem 0;
        }

        .video-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: none;
        }

        .preview-footer {
            background: var(--dark);
            color: white;
            padding: 2rem 0;
            text-align: center;
            margin-top: 4rem;
        }

        .admin-bar {
            background: #ffc107;
            color: #000;
            padding: 0.5rem 0;
            text-align: center;
            font-weight: 600;
            position: sticky;
            top: 0;
            z-index: 1001;
        }

        .admin-bar a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 700;
        }

        .admin-bar a:hover {
            text-decoration: underline;
        }

        .values-list {
            list-style: none;
            padding: 0;
        }

        .values-list li {
            padding: 0.5rem 0;
            border-bottom: 1px solid var(--border);
            position: relative;
            padding-left: 1.5rem;
        }

        .values-list li::before {
            content: '✓';
            position: absolute;
            left: 0;
            color: var(--primary);
            font-weight: bold;
        }

        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
            border-radius: 0;
            padding: 0.75rem 2rem;
            font-weight: 600;
        }

        .btn-primary:hover {
            background-color: #a33a0b;
            border-color: #a33a0b;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .preview-banner h1 {
                font-size: 2rem;
            }

            .section {
                padding: 2rem 0;
            }

            .section-title {
                font-size: 1.5rem;
            }

            .mission-card {
                margin-bottom: 1rem;
            }
        }

        .mission-content {
            color: var(--gray);
            line-height: 1.6;
        }

        .mission-content p {
            margin-bottom: 0.5rem;
        }

        .mission-content p:last-child {
            margin-bottom: 0;
        }

        .mission-content strong {
            color: var(--dark);
            font-weight: 600;
        }

        .story-content p {
            margin-bottom: 1rem;
        }

        .story-content p:last-child {
            margin-bottom: 0;
        }

        .story-content strong {
            color: var(--primary);
            font-weight: 600;
        }
    </style>
</head>

<body>
    <!-- Admin Bar -->
    <div class="admin-bar">
        <div class="container">
            <i class="fas fa-eye me-2"></i>
            PREVIEW MODE -
            <a href="{{ route('admin.about.edit') }}" class="mx-2">Edit Page</a> |
            <a href="{{ route('admin.about.index') }}" class="mx-2">Back to Admin</a> |
            <span class="ms-2">Last updated: {{ $about->updated_at->format('M d, Y \a\t h:i A') }}</span>
        </div>
    </div>

    <!-- Header -->
    <header class="preview-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h3 class="mb-0 text-primary fw-bold">SupaFarm</h3>
                </div>
                <div class="col-md-6 text-md-end">
                    <nav>
                        <a href="#" class="text-dark text-decoration-none me-3">Home</a>
                        <a href="#" class="text-dark text-decoration-none me-3">Products</a>
                        <a href="#" class="text-dark text-decoration-none me-3">About</a>
                        <a href="#" class="text-dark text-decoration-none">Contact</a>
                    </nav>
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Banner -->
    <section class="preview-banner">
        <div class="container">
            <h1>{{ $about->title }}</h1>
            <div class="lead mb-0">{!! Str::limit(strip_tags($about->introduction), 150) !!}</div>
            <a href="#contact" class="btn">Get in Touch</a>
        </div>
    </section>

    <!-- Introduction Section -->
    <section class="section">
        <div class="container">
            <h2 class="section-title">Our Story</h2>
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="story-content">
                        {!! $about->our_story !!}
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Mission, Vision & Values -->
    <section class="section section-alt">
        <div class="container">
            <h2 class="section-title">Who We Are</h2>
            <div class="row">
                <!-- Mission -->
                <div class="col-lg-4 mb-4">
                    <div class="mission-card">
                        <div class="mission-icon">
                            <i class="fas fa-bullseye"></i>
                        </div>
                        <h4>Our Mission</h4>
                        <div class="mission-content">
                            {!! $about->mission !!}
                        </div>
                    </div>
                </div>

                <!-- Vision -->
                <div class="col-lg-4 mb-4">
                    <div class="mission-card">
                        <div class="mission-icon">
                            <i class="fas fa-eye"></i>
                        </div>
                        <h4>Our Vision</h4>
                        <div class="mission-content">
                            {!! $about->vision !!}
                        </div>
                    </div>
                </div>

                <!-- Values -->
                <div class="col-lg-4 mb-4">
                    <div class="mission-card">
                        <div class="mission-icon">
                            <i class="fas fa-heart"></i>
                        </div>
                        <h4>Our Values</h4>
                        <div class="mission-content">
                            {!! $about->values !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    @if (!empty($about->team_members) && count($about->team_members) > 0)
        <section class="section">
            <div class="container">
                <h2 class="section-title">Meet Our Team</h2>
                @if ($about->team_description)
                    <p class="intro-text">{{ $about->team_description }}</p>
                @endif

                <div class="row">
                    @foreach ($about->team_members as $member)
                        <div class="col-xl-3 col-lg-4 col-md-6">
                            <div class="team-member">
                                @if (isset($member['image']))
                                    <img src="{{ asset('storage/' . $member['image']) }}" alt="{{ $member['name'] }}"
                                        class="team-member-img">
                                @else
                                    <div class="team-member-placeholder">
                                        <i class="fas fa-user"></i>
                                    </div>
                                @endif

                                <h5>{{ $member['name'] }}</h5>
                                <div class="team-member-position">{{ $member['position'] ?? 'Team Member' }}</div>
                                @if (isset($member['bio']))
                                    <p class="team-member-bio">{{ $member['bio'] }}</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Media Gallery -->
    @if (count($about->image_urls) > 0 || $about->video_url)
        <section class="section section-alt">
            <div class="container">
                <h2 class="section-title">Gallery</h2>

                <!-- Images -->
                @if (count($about->image_urls) > 0)
                    <div class="row">
                        @foreach ($about->image_urls as $imageUrl)
                            <div class="col-xl-3 col-lg-4 col-md-6">
                                <div class="gallery-item">
                                    <img src="{{ $imageUrl }}" alt="About us image {{ $loop->iteration }}">
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                <!-- Video -->
                @if ($about->video_url)
                    <div class="row">
                        <div class="col-lg-8 mx-auto">
                            <div class="video-container">
                                @if (str_contains($about->video_url, 'youtube.com') || str_contains($about->video_url, 'youtu.be'))
                                    <iframe
                                        src="https://www.youtube.com/embed/{{ \App\Helpers\VideoHelpers::getYouTubeId($about->video_url) }}"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                        allowfullscreen>
                                    </iframe>
                                @elseif(str_contains($about->video_url, 'vimeo.com'))
                                    <iframe
                                        src="https://player.vimeo.com/video/{{ \App\Helpers\VideoHelpers::getVimeoId($about->video_url) }}"
                                        allow="autoplay; fullscreen; picture-in-picture" allowfullscreen>
                                    </iframe>
                                @else
                                    <video controls style="width: 100%; height: 100%;">
                                        <source src="{{ $about->video_url }}" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </section>
    @endif

    <!-- Contact Section -->
    <section id="contact" class="section">
        <div class="container">
            <h2 class="section-title">Get In Touch</h2>
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="contact-info">
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="contact-details">
                                <h5>Address</h5>
                                <p>{{ $about->address }}</p>
                            </div>
                        </div>

                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div class="contact-details">
                                <h5>Phone</h5>
                                <p>{{ $about->phone }}</p>
                            </div>
                        </div>

                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="contact-details">
                                <h5>Email</h5>
                                <p>{{ $about->email }}</p>
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <a href="mailto:{{ $about->email }}" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-2"></i>Send Message
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="preview-footer">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="mb-3">SupaFarm</h5>
                    <p class="mb-0">Providing fresh, organic products since 2024</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-1">&copy; 2024 SupaFarm. All rights reserved.</p>
                    <p class="mb-0">
                        <a href="#" class="text-white text-decoration-none me-3">Privacy Policy</a>
                        <a href="#" class="text-white text-decoration-none">Terms of Service</a>
                    </p>
                </div>
            </div>
        </div>
    </footer>
</body>

</html>
