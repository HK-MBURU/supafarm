@extends('layouts.app')

@section('content')
<div class="products-header">
    <h1>Our Products</h1>
    <p>Explore our range of farm-fresh products</p>
</div>

<div class="category-grid">
    <div class="category-card">
        <img src="{{ asset('images/honey.jpg') }}" alt="Honey">
        <div class="category-content">
            <h2>Honey</h2>
            <p>Our pure, natural honey is harvested with care.</p>
            <a href="/products/honey" class="btn-primary">View Products</a>
        </div>
    </div>
    
    <div class="category-card">
        <img src="{{ asset('images/eggs.jpg') }}" alt="Eggs">
        <div class="category-content">
            <h2>Eggs</h2>
            <p>Farm-fresh eggs from free-range hens.</p>
            <a href="/products/eggs" class="btn-primary">View Products</a>
        </div>
    </div>
    
    <div class="category-card">
        <img src="{{ asset('images/coffee.jpg') }}" alt="Coffee">
        <div class="category-content">
            <h2>Coffee</h2>
            <p>Freshly roasted coffee beans with rich flavor.</p>
            <a href="/products/coffee" class="btn-primary">View Products</a>
        </div>
    </div>
</div>
@endsection