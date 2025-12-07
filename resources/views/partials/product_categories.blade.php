<div class="featured-products">
    <h2>Our Products</h2>
    <div class="product-cards">
        @foreach ($categories as $category)
            <div class="product-card">
                @if ($category->image_url)
                    <img src="{{ $category->image_url }}"
                            alt="{{ $category->name }}"
                            loading="lazy"
                            width="300"
                            height="200">
                @else
                    <img src="{{ asset('images/no-image-available.jpg') }}"
                            alt="No image available"
                            class="img-fluid"
                            loading="lazy"
                            width="300"
                            height="200">
                @endif
                <h3>{{ $category->name }}</h3>
                <p>{{ Str::limit($category->description, 120) }}</p> {{-- MODIFIED LINE --}}
                <a href="{{ route('products.category', $category->id) }}" class="btn-secondary text-center">View Details</a>
            </div>
        @endforeach
    </div>
</div>
