@extends('layouts.app')

@section('content')
<div class="category-header">
    <h1>{{ ucfirst($category) }}</h1>
    <p>Explore our range of {{ $category }} products</p>
</div>

<div class="product-list">
    @foreach($products as $product)
    <div class="product-item">
        <img src="{{ asset('images/' . $product['image']) }}" alt="{{ $product['name'] }}">
        <div class="product-details">
            <h2>{{ $product['name'] }}</h2>
            <p class="price">${{ number_format($product['price'], 2) }}</p>
            <form action="/cart/add" method="POST">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product['id'] }}">
                <button type="submit" class="btn-primary">Add to Cart</button>
            </form>
        </div>
    </div>
    @endforeach
</div>
@endsection