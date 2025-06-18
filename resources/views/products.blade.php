@extends('layouts.app')

@section('content')
    <div class="page-header">
        <h1>Our Products</h1>
    </div>

    <div class="products-list">
        <div class="product-item">
            <div class="product-image">
                <img src="{{ asset('images/honey.jpg') }}" alt="Honey">
            </div>
            <div class="product-details">
                <h2>Organic Honey</h2>
                <p class="price">$12.99</p>
                <p class="description">Pure, natural honey sourced from our farm. No additives or preservatives.</p>
                <button class="add-to-cart">Add to Cart</button>
            </div>
        </div>

        <div class="product-item">
            <div class="product-image">
                <img src="{{ asset('images/eggs.jpg') }}" alt="Eggs">
            </div>
            <div class="product-details">
                <h2>Farm Fresh Eggs</h2>
                <p class="price">$5.99</p>
                <p class="description">Free-range eggs from healthy hens raised in ethical conditions.</p>
                <button class="add-to-cart">Add to Cart</button>
            </div>
        </div>

        <div class="product-item">
            <div class="product-image">
                <img src="{{ asset('images/coffee.jpg') }}" alt="Coffee">
            </div>
            <div class="product-details">
                <h2>Premium Coffee</h2>
                <p class="price">$15.99</p>
                <p class="description">Freshly roasted coffee beans with rich aroma and flavor.</p>
                <button class="add-to-cart">Add to Cart</button>
            </div>
        </div>
    </div>
@endsection