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

    @include('partials.product_categories')
    @include('partials.popular-products')
    @include('partials.latest-news')

    @include('partials.gallery-scroll')

    @include('partials.about')
    @include('partials.seo')
@endsection
