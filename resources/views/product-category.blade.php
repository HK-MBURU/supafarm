@extends('layouts.app')

@section('title', ucfirst($category))

@section('content')
    <section class="category-header">
        <h2>{{ ucfirst($category) }}</h2>
        <p>Browse our selection of high-quality {{ $category }}</p>
    </section>
    
    <section class="product-listing">
        @if($category == 'honey')
            <div class="product-grid">
                <div class="product-card">
                    <img src="{{ asset('images/honey-1.jpg') }}" alt="Raw Honey">
                    <h3>Raw Honey</h3>
                    <p>Unprocessed, straight from the hive</p>
                    <p class="price">$12.99</p>
                    <a href="#" class="btn">Add to Cart</a>
                </div>
                
                <div class="product-card">
                    <img src="{{ asset('images/honey-2.jpg') }}" alt="Manuka Honey">
                    <h3>Manuka Honey</h3>
                    <p>Premium healing honey</p>
                    <p class="price">$24.99</p>
                    <a href="#" class="btn">Add to Cart</a>
                </div>
                
                <div class="product-card">
                    <img src="{{ asset('images/honey-3.jpg') }}" alt="Wildflower Honey">
                    <h3>Wildflower Honey</h3>
                    <p>Rich in flavor and nutrients</p>
                    <p class="price">$14.99</p>
                    <a href="#" class="btn">Add to Cart</a>
                </div>
            </div>
        @elseif($category == 'eggs')
            <div class="product-grid">
                <div class="product-card">
                    <img src="{{ asset('images/eggs-1.jpg') }}" alt="Free-Range Eggs">
                    <h3>Free-Range Eggs</h3>
                    <p>Fresh from happy hens</p>
                    <p class="price">$6.99/dozen</p>
                    <a href="#" class="btn">Add to Cart</a>
                </div>
                
                <div class="product-card">
                    <img src="{{ asset('images/eggs-2.jpg') }}" alt="Organic Eggs">
                    <h3>Organic Eggs</h3>
                    <p>Certified organic feed, no antibiotics</p>
                    <p class="price">$8.99/dozen</p>
                    <a href="#" class="btn">Add to Cart</a>
                </div>
                
                <div class="product-card">
                    <img src="{{ asset('images/eggs-3.jpg') }}" alt="Duck Eggs">
                    <h3>Duck Eggs</h3>
                    <p>Larger and richer than chicken eggs</p>
                    <p class="price">$10.99/dozen</p>
                    <a href="#" class="btn">Add to Cart</a>
                </div>
            </div>
        @elseif($category == 'coffee')
            <div class="product-grid">
                <div class="product-card">
                    <img src="{{ asset('images/coffee-1.jpg') }}" alt="Light Roast Coffee">
                    <h3>Light Roast Coffee</h3>
                    <p>Bright and fruity notes</p>
                    <p class="price">$14.99/bag</p>
                    <a href="#" class="btn">Add to Cart</a>
                </div>
                
                <div class="product-card">
                    <img src="{{ asset('images/coffee-2.jpg') }}" alt="Medium Roast Coffee">
                    <h3>Medium Roast Coffee</h3>
                    <p>Balanced flavor with chocolate notes</p>
                    <p class="price">$15.99/bag</p>
                    <a href="#" class="btn">Add to Cart</a>
                </div>
                
                <div class="product-card">
                    <img src="{{ asset('images/coffee-3.jpg') }}" alt="Dark Roast Coffee">
                    <h3>Dark Roast Coffee</h3>
                    <p>Bold, rich flavor with low acidity</p>
                    <p class="price">$16.99/bag</p>
                    <a href="#" class="btn">Add to Cart</a>
                </div>
            </div>
        @endif
    </section>
@endsection