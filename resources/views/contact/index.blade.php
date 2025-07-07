@extends('layouts.app')

@section('content')
<div class="contact-page">
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container-fluid p-0">
            <div class="hero-image-container">
                <img src="{{ asset('images/contact-hero.jpg') }}" alt="Contact Us" class="hero-image">
                <div class="hero-overlay"></div>
                <div class="container">
                    <div class="hero-content">
                        <h1 class="hero-title">Contact Us</h1>
                        <div class="hero-separator"></div>
                        <p class="hero-subtitle">We'd Love to Hear From You</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact-section py-5">
        <div class="container">
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <div class="row">
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <div class="contact-info-container">
                        <div class="contact-info-box">
                            <div class="icon-container">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <h3>Visit Us</h3>
                            <p>Thika, Kenya</p>
                        </div>

                        <div class="contact-info-box">
                            <div class="icon-container">
                                <i class="fas fa-phone-alt"></i>
                            </div>
                            <h3>Call Us</h3>
                            <p>
                                <a href="tel:+254726619243" class="contact-link">
                                    +254 726 619243
                                </a>
                            </p>
                        </div>

                        <div class="contact-info-box">
                            <div class="icon-container">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <h3>Email Us</h3>
                            <p>
                                <a href="mailto:info@supafarmsupplies.com" class="contact-link">
                                    info@supafarmsupplies.com
                                </a>
                            </p>
                        </div>

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
                        <h2>Send Us a Message</h2>
                        <p class="mb-4">Fill out the form below and we'll get back to you as soon as possible.</p>

                        <form action="{{ route('contact.submit') }}" method="POST" class="contact-form">
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
    /* Contact Section */
    .contact-info-container {
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .contact-info-box {
        margin-bottom: 30px;
    }

    .contact-info-box h3 {
        font-size: 1.3rem;
        margin: 15px 0 5px;
    }

    .contact-info-box p {
        color: #666;
        margin: 0;
    }

    .contact-link {
        color: #28a745;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .contact-link:hover {
        color: #218838;
        text-decoration: underline;
    }

    .social-icons {
        margin-top: 10px;
    }

    .social-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        background-color: #f8f9fa;
        border-radius: 50%;
        margin-right: 10px;
        color: #28a745;
        font-size: 1rem;
        transition: all 0.3s ease;
        text-decoration: none;
        box-shadow: 0 3px 5px rgba(0, 0, 0, 0.1);
    }

    .social-icon:hover {
        background-color: #28a745;
        color: white;
        transform: translateY(-3px);
    }

    .contact-form-container {
        background-color: white;
        padding: 40px;
        border-radius: 8px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    }

    .form-control {
        padding: 12px 15px;
        border-radius: 4px;
        border: 1px solid #ced4da;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }

    .form-control:focus {
        border-color: #28a745;
        box-shadow: 0 0 0 0.25rem rgba(40, 167, 69, 0.25);
    }

    .btn-primary {
        background-color: #28a745;
        border-color: #28a745;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        background-color: #218838;
        border-color: #1e7e34;
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }
</style>
@endpush