@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Hero Banner Section -->
    <div class="hero-banner mb-4">
        <div class="row">
            <div class="col-12">
                <div class="banner-content p-4 p-md-5 text-white" style="background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('{{ asset('images/farm-banner.jpg') }}') no-repeat center center; background-size: cover; border-radius: 10px;">
                    <h1>Quality Farm Supplies for Your Success</h1>
                    <p class="lead">Everything you need to grow your farm business in one place</p>
                    <div class="mt-4">
                        <a href="{{ route('products') }}" class="btn btn-primary btn-lg">Shop Now</a>
                        <a href="{{ route('about') }}" class="btn btn-outline-light btn-lg ms-2">Learn More</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Categories Section -->
    <div class="category-section mb-5">
        <div class="section-header d-flex justify-content-between align-items-center mb-4">
            <h2>Shop by Category</h2>
            <a href="{{ route('categories') }}" class="text-decoration-none">View All <i class="fas fa-arrow-right"></i></a>
        </div>
        <div class="row">
            <div class="col-6 col-md-3 mb-3">
                <div class="category-card text-center p-3 h-100" style="background-color: #fff; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                    <div class="category-icon mb-3">
                        <i class="fas fa-seedling fa-3x text-success"></i>
                    </div>
                    <h5>Seeds & Plants</h5>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-3">
                <div class="category-card text-center p-3 h-100" style="background-color: #fff; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                    <div class="category-icon mb-3">
                        <i class="fas fa-tractor fa-3x text-warning"></i>
                    </div>
                    <h5>Farm Equipment</h5>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-3">
                <div class="category-card text-center p-3 h-100" style="background-color: #fff; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                    <div class="category-icon mb-3">
                        <i class="fas fa-flask fa-3x text-danger"></i>
                    </div>
                    <h5>Fertilizers</h5>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-3">
                <div class="category-card text-center p-3 h-100" style="background-color: #fff; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                    <div class="category-icon mb-3">
                        <i class="fas fa-bug fa-3x text-primary"></i>
                    </div>
                    <h5>Pest Control</h5>
                </div>
            </div>
        </div>
    </div>

    <!-- Featured Products Section -->
    <div class="featured-products mb-5">
        <div class="section-header d-flex justify-content-between align-items-center mb-4">
            <h2>Featured Products</h2>
            <a href="{{ route('products') }}" class="text-decoration-none">View All <i class="fas fa-arrow-right"></i></a>
        </div>
        <div class="row">
            @for ($i = 1; $i <= 4; $i++)
            <div class="col-12 col-md-6 col-lg-3">
                <div class="product-card">
                    <div class="product-image">
                        <img src="{{ asset('images/product-' . $i . '.jpg') }}" alt="Product {{ $i }}">
                    </div>
                    <div class="product-info">
                        <h5 class="product-title">Premium Farm Product {{ $i }}</h5>
                        <p class="product-price">$49.99</p>
                        <div class="d-flex">
                            <button class="btn btn-primary me-2">Add to Cart</button>
                            <button class="btn btn-outline-secondary"><i class="far fa-heart"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            @endfor
        </div>
    </div>

    <!-- Testimonials Section -->
    <div class="testimonials-section mb-5 p-4" style="background-color: #f5f5f5; border-radius: 10px;">
        <h2 class="text-center mb-4">What Our Customers Say</h2>
        <div class="row">
            <div class="col-md-4 mb-3 mb-md-0">
                <div class="testimonial-card p-3 h-100" style="background-color: #fff; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                    <div class="testimonial-content">
                        <p class="testimonial-text">"Supa Farm has the best quality seeds I've ever used. My crops yield has increased dramatically!"</p>
                        <div class="testimonial-author d-flex align-items-center mt-3">
                            <div class="author-avatar me-3">
                                <img src="{{ asset('images/avatar-1.jpg') }}" alt="Customer" class="rounded-circle" width="50" height="50">
                            </div>
                            <div class="author-info">
                                <h6 class="mb-0">John Kimani</h6>
                                <small class="text-muted">Wheat Farmer</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3 mb-md-0">
                <div class="testimonial-card p-3 h-100" style="background-color: #fff; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                    <div class="testimonial-content">
                        <p class="testimonial-text">"Their customer service is exceptional. They helped me choose the right equipment for my small farm."</p>
                        <div class="testimonial-author d-flex align-items-center mt-3">
                            <div class="author-avatar me-3">
                                <img src="{{ asset('images/avatar-2.jpg') }}" alt="Customer" class="rounded-circle" width="50" height="50">
                            </div>
                            <div class="author-info">
                                <h6 class="mb-0">Sarah Mwangi</h6>
                                <small class="text-muted">Dairy Farmer</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="testimonial-card p-3 h-100" style="background-color: #fff; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                    <div class="testimonial-content">
                        <p class="testimonial-text">"Fast delivery and competitive prices! Supa Farm is now my go-to supplier for all farm needs."</p>
                        <div class="testimonial-author d-flex align-items-center mt-3">
                            <div class="author-avatar me-3">
                                <img src="{{ asset('images/avatar-3.jpg') }}" alt="Customer" class="rounded-circle" width="50" height="50">
                            </div>
                            <div class="author-info">
                                <h6 class="mb-0">David Ochieng</h6>
                                <small class="text-muted">Vegetable Grower</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Call to Action Section -->
    <div class="cta-section p-4 p-md-5 text-center text-white mb-5" style="background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('{{ asset('images/farm-field.jpg') }}') no-repeat center center; background-size: cover; border-radius: 10px;">
        <h2>Ready to Boost Your Farm Productivity?</h2>
        <p class="lead mb-4">Join thousands of satisfied farmers who trust Supa Farm Supplies</p>
        <a href="{{ route('register') }}" class="btn btn-light btn-lg">Create an Account</a>
        <a href="{{ route('products') }}" class="btn btn-outline-light btn-lg ms-3">Shop Now</a>
    </div>
</div>
@endsection