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
        <div class="product-card">
            <img src="{{ asset('images/honey.jpg') }}" alt="Organic Honey">
            <h3>Organic Honey</h3>
            <p>Pure, natural honey from our farm</p>
            <a href="/products/honey" class="btn-secondary">View Details</a>
        </div>
        
        <div class="product-card">
            <img src="{{ asset('images/eggs.jpg') }}" alt="Farm Fresh Eggs">
            <h3>Farm Fresh Eggs</h3>
            <p>Free-range eggs from happy hens</p>
            <a href="/products/eggs" class="btn-secondary">View Details</a>
        </div>
        
        <div class="product-card">
            <img src="{{ asset('images/coffee.jpg') }}" alt="Premium Coffee">
            <h3>Premium Coffee</h3>
            <p>Freshly roasted coffee beans</p>
            <a href="/products/coffee" class="btn-secondary">View Details</a>
        </div>
    </div>
</div>

<div class="about-section">
    <h2>About Supa Farm</h2>
    <p>We are dedicated to producing high-quality farm products using sustainable methods. Our family-owned farm has been operating since 2010, providing the freshest produce to our community.</p>
    <a href="/about" class="btn-secondary">Learn More</a>
</div>
@endsection