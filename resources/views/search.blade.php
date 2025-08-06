@extends('layouts.app')

@section('content')
<div class="search-results-container">
    <div class="search-header">
        <h1>Search Results</h1>
        @if($query)
            <p>Showing results for: "<strong>{{ $query }}</strong>"</p>
        @else
            <p>Please enter a search term</p>
        @endif
    </div>

    @if(isset($products) && $products->count() > 0)
        <div class="search-results">
            <p class="results-count">Found {{ $products->count() }} product(s)</p>
            
            <div class="product-list">
                @foreach($products as $product)
                <div class="product-card">
                    <div class="product-image">
                        @if($product->image_url)
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" loading="lazy">
                        @else
                            <img src="{{ asset('images/no-product-image.jpg') }}" alt="No image available" loading="lazy">
                        @endif
                    </div>
                    
                    <div class="product-details">
                        <h3>{{ $product->name }}</h3>
                        <p class="price">KSh {{ number_format($product->price, 2) }}</p>
                        <p class="description">{{ Str::limit($product->description, 100) }}</p>
                        
                        <div class="product-actions">
                            <form action="/cart/add" method="POST" class="cart-form">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="btn-add-to-cart">
                                    <i class="fas fa-cart-plus"></i> Add to Cart
                                </button>
                            </form>
                            
                            <a href="{{ route('products.view', $product->id) }}" class="btn-view-details">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    @else
        <div class="no-results">
            <i class="fas fa-search"></i>
            <h3>No products found</h3>
            @if($query)
                <p>Sorry, we couldn't find any products matching "{{ $query }}"</p>
            @endif
            <p>Try different keywords or browse our categories</p>
            <a href="/" class="btn-primary">Continue Shopping</a>
        </div>
    @endif
</div>

@push('styles')
<style>
    .search-results-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    .search-header {
        margin-bottom: 30px;
        text-align: center;
    }

    .search-header h1 {
        color: var(--primary-color);
        margin-bottom: 10px;
    }

    .results-count {
        margin-bottom: 20px;
        font-weight: bold;
        color: #666;
    }

    .product-list {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 20px;
    }

    .product-card {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        overflow: hidden;
        transition: transform 0.3s ease;
    }

    .product-card:hover {
        transform: translateY(-5px);
    }

    .product-image {
        height: 200px;
        overflow: hidden;
    }

    .product-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .product-details {
        padding: 15px;
    }

    .product-details h3 {
        margin-bottom: 10px;
        color: var(--dark-color);
    }

    .price {
        font-size: 1.2rem;
        font-weight: bold;
        color: var(--primary-color);
        margin-bottom: 10px;
    }

    .description {
        color: #666;
        margin-bottom: 15px;
        line-height: 1.4;
    }

    .product-actions {
        display: flex;
        gap: 10px;
        flex-direction: column;
    }

    .btn-add-to-cart, .btn-view-details {
        padding: 10px 15px;
        border: none;
        border-radius: 4px;
        text-decoration: none;
        text-align: center;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .btn-add-to-cart {
        background-color: var(--primary-color);
        color: white;
        width: 100%;
    }

    .btn-add-to-cart:hover {
        background-color: var(--dark-color);
    }

    .btn-view-details {
        background-color: transparent;
        color: var(--primary-color);
        border: 1px solid var(--primary-color);
    }

    .btn-view-details:hover {
        background-color: var(--primary-color);
        color: white;
    }

    .no-results {
        text-align: center;
        padding: 60px 20px;
    }

    .no-results i {
        font-size: 4rem;
        color: #ccc;
        margin-bottom: 20px;
    }

    .no-results h3 {
        margin-bottom: 15px;
        color: var(--dark-color);
    }

    .no-results p {
        color: #666;
        margin-bottom: 15px;
    }

    .btn-primary {
        display: inline-block;
        padding: 12px 24px;
        background-color: var(--primary-color);
        color: white;
        text-decoration: none;
        border-radius: 4px;
        margin-top: 20px;
    }

    .btn-primary:hover {
        background-color: var(--dark-color);
    }
</style>
@endpush
@endsection