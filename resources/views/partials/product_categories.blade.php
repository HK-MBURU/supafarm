    <div class="featured-products">
        <h2>Our Products</h2>

        <div class="product-cards">
            @foreach ($categories as $category)
                <div class="product-card">
                    @if ($category->image_url)
                        <img src="{{ $category->image_url }}" alt="{{ $category->name }}">
                    @else
                        <img src="{{ asset('images/no-image-available.jpg') }}" alt="No image available" class="img-fluid">
                    @endif
                    <h3>{{ $category->name }}</h3>
                    <p>{{ $category->description }}</p>
                  <a href="{{ route('products.category', $category->id) }}" class="btn-secondary text-center">View Details</a>
                </div>
            @endforeach


        </div>
    </div>
