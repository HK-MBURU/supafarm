@extends('layouts.app')

@section('content')
<div class="category-header">
    <h1>{{ $category->name }}</h1>
    <p>{{ $category->description }}</p>
    <div class="category-meta">
        <span>{{ $totalProducts }} products available</span>
        <span class="user-info">Last updated by: {{ $userInfo['login'] }} at {{ $userInfo['timestamp'] }}</span>
    </div>
</div>

@if($category->image_url)
<div class="category-banner">
    <img src="{{ $category->image_url }}" alt="{{ $category->name }}" class="banner-image">
</div>
@endif

<div class="product-filters">
    <div class="filter-container">
        <select name="sort" id="sort-products" class="filter-select">
            <option value="newest">Newest First</option>
            <option value="price-low">Price: Low to High</option>
            <option value="price-high">Price: High to Low</option>
        </select>
    </div>
</div>

<div class="product-list">
    @forelse($products as $product)
    <div class="product-item">
        <div class="product-image">
            @if($product->image_url)
                <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
            @else
                <img src="{{ asset('images/no-product-image.jpg') }}" alt="No image available">
            @endif
            
            @if($product->is_featured)
                <span class="featured-badge">Featured</span>
            @endif
            
            @if($product->sale_price && $product->sale_price < $product->price)
                <span class="sale-badge">Sale</span>
            @endif
        </div>
        
        <div class="product-details">
            <h2>{{ $product->name }}</h2>
            
            <div class="price-container">
                @if($product->sale_price && $product->sale_price < $product->price)
                    <p class="price sale">
                        <span class="original">KSh {{ number_format($product->price, 2) }}</span>
                        <span class="current">KSh {{ number_format($product->sale_price, 2) }}</span>
                    </p>
                @else
                    <p class="price">KSh {{ number_format($product->price, 2) }}</p>
                @endif
            </div>
            
            <p class="product-description">{{ Str::limit($product->description, 100) }}</p>
            
            <div class="stock-info">
                @if($product->stock > 0)
                    <span class="in-stock">In Stock ({{ $product->stock }})</span>
                @else
                    <span class="out-of-stock">Out of Stock</span>
                @endif
            </div>
            
            <form action="{{ route('cart.add') }}" method="POST" class="add-to-cart-form">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}" class="quantity-input">
                <button type="submit" class="btn-primary" {{ $product->stock <= 0 ? 'disabled' : '' }}>
                    Add to Cart
                </button>
            </form>
            
            <a href="{{ route('products.show', $product->id) }}" class="btn-secondary">View Details</a>
        </div>
    </div>
    @empty
    <div class="no-products">
        <p>No products found in this category.</p>
    </div>
    @endforelse
</div>

<div class="pagination-container">
    {{ $products->links() }}
</div>

<div class="category-footer">
    <h3>About {{ $category->name }}</h3>
    <div class="category-description">
        {{ $category->description }}
    </div>
</div>
@endsection

@push('styles')
<style>
    .product-list {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 30px;
        margin: 30px 0;
    }
    
    .product-item {
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .product-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    
    .product-image {
        position: relative;
        height: 200px;
        overflow: hidden;
    }
    
    .product-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    
    .product-item:hover .product-image img {
        transform: scale(1.05);
    }
    
    .featured-badge, .sale-badge {
        position: absolute;
        top: 10px;
        padding: 5px 10px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: bold;
        color: white;
    }
    
    .featured-badge {
        left: 10px;
        background-color: #4CAF50;
    }
    
    .sale-badge {
        right: 10px;
        background-color: #FF5722;
    }
    
    .product-details {
        padding: 15px;
    }
    
    .product-details h2 {
        font-size: 18px;
        margin-bottom: 10px;
    }
    
    .price {
        font-weight: bold;
        font-size: 18px;
        color: #333;
        margin-bottom: 10px;
    }
    
    .price.sale .original {
        text-decoration: line-through;
        color: #999;
        font-size: 14px;
        margin-right: 10px;
    }
    
    .price.sale .current {
        color: #FF5722;
    }
    
    .stock-info {
        margin: 10px 0;
        font-size: 14px;
    }
    
    .in-stock {
        color: #4CAF50;
    }
    
    .out-of-stock {
        color: #F44336;
    }
    
    .add-to-cart-form {
        display: flex;
        margin-bottom: 10px;
    }
    
    .quantity-input {
        width: 60px;
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 4px;
        margin-right: 10px;
    }
    
    .btn-primary, .btn-secondary {
        padding: 10px 15px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-weight: bold;
        text-align: center;
        display: inline-block;
        text-decoration: none;
        transition: background-color 0.3s ease;
    }
    
    .btn-primary {
        background-color: #4CAF50;
        color: white;
        flex-grow: 1;
    }
    
    .btn-primary:hover {
        background-color: #388E3C;
    }
    
    .btn-primary:disabled {
        background-color: #A5D6A7;
        cursor: not-allowed;
    }
    
    .btn-secondary {
        background-color: #f5f5f5;
        color: #333;
        border: 1px solid #ddd;
        display: block;
        margin-top: 10px;
    }
    
    .btn-secondary:hover {
        background-color: #e0e0e0;
    }
    
    .category-header {
        margin-bottom: 30px;
        text-align: center;
    }
    
    .category-header h1 {
        font-size: 32px;
        margin-bottom: 10px;
    }
    
    .category-meta {
        display: flex;
        justify-content: space-between;
        margin-top: 10px;
        font-size: 14px;
        color: #666;
    }
    
    .category-banner {
        width: 100%;
        height: 250px;
        overflow: hidden;
        margin-bottom: 30px;
        border-radius: 8px;
    }
    
    .banner-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .product-filters {
        display: flex;
        justify-content: flex-end;
        margin-bottom: 20px;
    }
    
    .filter-select {
        padding: 8px 15px;
        border: 1px solid #ddd;
        border-radius: 4px;
        background-color: white;
    }
    
    .pagination-container {
        margin: 30px 0;
        text-align: center;
    }
    
    .category-footer {
        margin-top: 40px;
        padding-top: 20px;
        border-top: 1px solid #e0e0e0;
    }
    
    .no-products {
        grid-column: 1 / -1;
        text-align: center;
        padding: 40px;
        background-color: #f9f9f9;
        border-radius: 8px;
    }
</style>
@endpush

@push('scripts')
<script>
    // Add any JavaScript for filtering, sorting, etc.
    document.getElementById('sort-products').addEventListener('change', function() {
        // You can implement AJAX sorting here or redirect with query parameters
        const sortValue = this.value;
        const currentUrl = new URL(window.location.href);
        currentUrl.searchParams.set('sort', sortValue);
        window.location.href = currentUrl.toString();
    });
</script>
@endpush