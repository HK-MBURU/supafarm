@extends('layouts.admin')

@section('title', 'Supa Farm Products - SupaFarm Admin')
@section('page-title', 'Products Overview')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-md-6">
            <h3 class="mb-1">All Products</h3>
            <p class="text-muted mb-0">Manage your product inventory</p>
        </div>
        <div class="col-md-6 text-md-end">
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary px-4">
                <i class="fas fa-plus me-2"></i>Add Product
            </a>
            <a href="{{ route('admin.products.featured') }}" class="btn btn-outline-primary px-4">
                <i class="fas fa-star me-2"></i>Featured
            </a>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $totalProducts ?? App\Models\Product::count() }}</h4>
                            <small>Total Products</small>
                        </div>
                        <i class="fas fa-cube fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $activeProducts ?? App\Models\Product::where('is_active', true)->count() }}</h4>
                            <small>Active Products</small>
                        </div>
                        <i class="fas fa-check-circle fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-dark">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $featuredProducts ?? App\Models\Product::where('is_featured', true)->count() }}</h4>
                            <small>Featured</small>
                        </div>
                        <i class="fas fa-star fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $outOfStock ?? App\Models\Product::where('stock', 0)->count() }}</h4>
                            <small>Out of Stock</small>
                        </div>
                        <i class="fas fa-exclamation-triangle fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alerts -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Products Grid -->
    <div class="row">
        @forelse($products as $product)
        <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
            <div class="card h-100 product-card">
                <!-- Product Image -->
                <div class="position-relative">
                    @if($product->image_url)
                    <img src="{{ $product->image_url }}"
                         class="card-img-top"
                         alt="{{ $product->name }}"
                         style="height: 200px; object-fit: cover;">
                    @else
                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center"
                         style="height: 200px;">
                        <i class="fas fa-cube fa-3x text-muted"></i>
                    </div>
                    @endif

                    <!-- Badges -->
                    <div class="position-absolute top-0 start-0 p-2">
                        @if($product->is_featured)
                        <span class="badge bg-warning text-dark">
                            <i class="fas fa-star me-1"></i>Featured
                        </span>
                        @endif
                    </div>
                    <div class="position-absolute top-0 end-0 p-2">
                        <span class="badge {{ $product->stock > 10 ? 'bg-success' : ($product->stock > 0 ? 'bg-warning' : 'bg-danger') }}">
                            Stock: {{ $product->stock }}
                        </span>
                    </div>
                </div>

                <!-- Card Body -->
                <div class="card-body d-flex flex-column">
                    <!-- Category -->
                    <div class="mb-2">
                        <span class="badge bg-secondary">{{ $product->category->name ?? 'Uncategorized' }}</span>
                    </div>

                    <!-- Product Name -->
                    <h6 class="card-title fw-bold mb-2">{{ Str::limit($product->name, 50) }}</h6>

                    <!-- Description -->
                    <p class="card-text text-muted small flex-grow-1">
                        {{ Str::limit($product->description, 80) }}
                    </p>

                    <!-- Price -->
                    <div class="mb-3">
                        @if($product->sale_price)
                        <div class="d-flex align-items-center gap-2">
                            <span class="h5 mb-0 text-success fw-bold">${{ number_format($product->sale_price, 2) }}</span>
                            <small class="text-muted text-decoration-line-through">${{ number_format($product->price, 2) }}</small>
                            <span class="badge bg-danger ms-2">Save {{ $product->discount_percentage }}%</span>
                        </div>
                        @else
                        <span class="h5 mb-0 fw-bold">${{ number_format($product->price, 2) }}</span>
                        @endif
                    </div>

                    <!-- Status and Actions -->
                    <div class="mt-auto">
                        <!-- Status Toggle -->
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <form action="{{ route('admin.products.toggleStatus', $product->id) }}" method="POST" class="flex-grow-1 me-2">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                        class="btn btn-sm {{ $product->is_active ? 'btn-success' : 'btn-danger' }} w-100 border-0">
                                    <i class="fas fa-{{ $product->is_active ? 'check' : 'times' }} me-1"></i>
                                    {{ $product->is_active ? 'Active' : 'Inactive' }}
                                </button>
                            </form>

                            <!-- Featured Toggle -->
                            <form action="{{ route('admin.products.toggleFeatured', $product->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                        class="btn btn-sm {{ $product->is_featured ? 'btn-warning' : 'btn-outline-warning' }} border-0"
                                        title="{{ $product->is_featured ? 'Remove from featured' : 'Mark as featured' }}">
                                    <i class="fas fa-star {{ $product->is_featured ? 'text-white' : 'text-warning' }}"></i>
                                </button>
                            </form>
                        </div>

                        <!-- Action Buttons -->
                        <div class="btn-group w-100" role="group">
                            <a href="{{ route('admin.products.edit', $product->id) }}"
                               class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="{{ route('admin.products.show', $product->id) }}"
                               class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-eye"></i>
                            </a>
                            <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="d-inline flex-grow-1">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="btn btn-outline-danger btn-sm w-100"
                                        onclick="return confirm('Are you sure you want to delete this product?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="card text-center py-5">
                <div class="card-body">
                    <i class="fas fa-cubes fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No products found</h5>
                    <p class="text-muted mb-3">Get started by creating your first product</p>
                    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Create Product
                    </a>
                </div>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($products->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $products->links() }}
    </div>
    @endif
</div>

<!-- Add Bootstrap Icons -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

<style>
.card {
    border: 1px solid #dee2e6;
    border-radius: 0;
    transition: all 0.2s ease;
}

.card:hover {
    border-color: #BC450D;
}

.btn {
    border-radius: 0;
    border: 1px solid;
}

.alert {
    border-radius: 0;
    border: 1px solid;
}

.badge {
    border-radius: 0;
    font-weight: 500;
}

.btn-group .btn {
    margin-right: -1px;
    border-radius: 0;
}

.btn-group .btn:first-child {
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
}

.btn-group .btn:last-child {
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;
    margin-right: 0;
}

.product-card {
    transition: transform 0.2s ease;
}

.product-card:hover {
    transform: translateY(-2px);
}

.card-img-top {
    border-bottom: 1px solid #dee2e6;
}

/* Custom colors */
.btn-primary {
    background-color: #BC450D;
    border-color: #BC450D;
}

.btn-primary:hover {
    background-color: #a33a0b;
    border-color: #a33a0b;
}

.btn-outline-primary {
    color: #BC450D;
    border-color: #BC450D;
}

.btn-outline-primary:hover {
    background-color: #BC450D;
    border-color: #BC450D;
    color: white;
}

.btn-success {
    background-color: #198754;
    border-color: #198754;
}

.btn-danger {
    background-color: #dc3545;
    border-color: #dc3545;
}

.btn-warning {
    background-color: #ffc107;
    border-color: #ffc107;
    color: #000;
}

.btn-outline-warning {
    color: #ffc107;
    border-color: #ffc107;
}

.btn-outline-warning:hover {
    background-color: #ffc107;
    border-color: #ffc107;
    color: #000;
}

.alert-success {
    background-color: #f8fff8;
    border-color: #b8e6b8;
    color: #2d5a2d;
}

.alert-danger {
    background-color: #fff8f8;
    border-color: #e6b8b8;
    color: #5a2d2d;
}

.bg-primary { background-color: #BC450D !important; }
.bg-success { background-color: #198754 !important; }
.bg-warning { background-color: #ffc107 !important; }
.bg-danger { background-color: #dc3545 !important; }

.position-absolute .badge {
    font-size: 0.7rem;
}

.card-title {
    line-height: 1.3;
}

.card-text {
    line-height: 1.4;
}
</style>
@endsection
