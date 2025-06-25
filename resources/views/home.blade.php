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
    <h2>About Supa Farm</h2>
    <p>We are dedicated to producing high-quality farm products using sustainable methods. Our family-owned farm has been operating since 2010, providing the freshest produce to our community.</p>
    <a href="/about" class="btn-secondary">Learn More</a>
</div>
@endsection