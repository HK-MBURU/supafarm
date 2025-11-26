@extends('layouts.admin')

@section('title', $product->name . ' - SupaFarm Admin')
@section('page-title', 'Product Details')

@section('content')
<div class="container-fluid">
    <!-- Back Button -->
    <div class="row mb-4">
        <div class="col-12">
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Products
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Left Column - Product Images & Basic Info -->
        <div class="col-lg-5">
            <!-- Product Images -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">Product Images</h5>
                </div>
                <div class="card-body">
                    @if(count($product->image_urls) > 0)
                    <div class="row">
                        <!-- Main Image -->
                        <div class="col-12 mb-4">
                            <img src="{{ $product->image_urls[0] }}"
                                 alt="{{ $product->name }}"
                                 class="img-fluid w-100 rounded"
                                 style="max-height: 400px; object-fit: cover;"
                                 id="mainImage">
                        </div>

                        <!-- Thumbnails -->
                        @if(count($product->image_urls) > 1)
                        <div class="col-12">
                            <div class="row g-2">
                                @foreach($product->image_urls as $index => $imageUrl)
                                <div class="col-3">
                                    <img src="{{ $imageUrl }}"
                                         alt="{{ $product->name }} - Image {{ $index + 1 }}"
                                         class="img-fluid rounded cursor-pointer"
                                         style="height: 80px; width: 100%; object-fit: cover; cursor: pointer;"
                                         onclick="document.getElementById('mainImage').src = this.src">
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="fas fa-image fa-3x text-muted mb-3"></i>
                        <p class="text-muted mb-0">No images available</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">Quick Stats</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="border-end">
                                <div class="h4 mb-1 text-primary">{{ $product->reviews->count() }}</div>
                                <small class="text-muted">Reviews</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="h4 mb-1 text-success">{{ $product->orderItems->count() }}</div>
                            <small class="text-muted">Orders</small>
                        </div>
                        <div class="col-6">
                            <div class="border-end">
                                <div class="h4 mb-1 text-info">{{ $product->cartItems->count() }}</div>
                                <small class="text-muted">In Carts</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="h4 mb-1 text-warning">{{ $product->views ?? 0 }}</div>
                            <small class="text-muted">Views</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Product Details -->
        <div class="col-lg-7">
            <!-- Basic Information -->
            <div class="card mb-4">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Basic Information</h5>
                    <div class="d-flex gap-2">
                        @if($product->is_featured)
                        <span class="badge bg-warning text-dark">
                            <i class="fas fa-star me-1"></i>Featured
                        </span>
                        @endif
                        <span class="badge {{ $product->is_active ? 'bg-success' : 'bg-danger' }}">
                            {{ $product->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="text-muted" style="width: 120px;">ID:</td>
                                    <td class="fw-medium">#{{ $product->id }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Name:</td>
                                    <td class="fw-medium">{{ $product->name }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Category:</td>
                                    <td>
                                        <span class="badge bg-secondary">
                                            {{ $product->category->name ?? 'Uncategorized' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Stock:</td>
                                    <td>
                                        <span class="badge {{ $product->stock > 10 ? 'bg-success' : ($product->stock > 0 ? 'bg-warning' : 'bg-danger') }}">
                                            {{ $product->stock }} units
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="text-muted" style="width: 120px;">Created:</td>
                                    <td>{{ $product->created_at->format('M d, Y') }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Updated:</td>
                                    <td>{{ $product->updated_at->format('M d, Y') }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">SKU:</td>
                                    <td class="fw-medium">{{ $product->sku ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Weight:</td>
                                    <td>{{ $product->weight ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pricing Information -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">Pricing Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <strong class="text-muted d-block">Regular Price</strong>
                                <span class="h5">KSh{{ number_format($product->price, 2) }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            @if($product->sale_price)
                            <div class="mb-3">
                                <strong class="text-muted d-block">Sale Price</strong>
                                <span class="h5 text-success">${{ number_format($product->sale_price, 2) }}</span>
                                <div class="text-danger small">
                                    <i class="fas fa-tag me-1"></i>
                                    Save ${{ number_format($product->price - $product->sale_price, 2) }}
                                    ({{ round((($product->price - $product->sale_price) / $product->price) * 100) }}%)
                                </div>
                            </div>
                            @else
                            <div class="mb-3">
                                <strong class="text-muted d-block">Sale Price</strong>
                                <span class="text-muted">No sale price set</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description & Features -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">Description & Features</h5>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h6 class="fw-bold mb-2">Description</h6>
                        <p class="text-muted">{{ $product->description ?? 'No description provided.' }}</p>
                    </div>

                    @if($product->features)
                    <div>
                        <h6 class="fw-bold mb-2">Features</h6>
                        <div class="text-muted">
                            {!! nl2br(e($product->features)) !!}
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- SEO Information -->
            @if($product->meta_title || $product->meta_description)
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">SEO Information</h5>
                </div>
                <div class="card-body">
                    @if($product->meta_title)
                    <div class="mb-3">
                        <strong class="text-muted d-block">Meta Title</strong>
                        <span>{{ $product->meta_title }}</span>
                    </div>
                    @endif
                    @if($product->meta_description)
                    <div>
                        <strong class="text-muted d-block">Meta Description</strong>
                        <span class="text-muted">{{ $product->meta_description }}</span>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Actions Section -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">Product Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <a href="{{ route('admin.products.edit', $product->id) }}"
                               class="btn btn-primary w-100">
                                <i class="fas fa-edit me-2"></i>Edit Product
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <form action="{{ route('admin.products.toggleStatus', $product->id) }}" method="POST" class="w-100">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                        class="btn {{ $product->is_active ? 'btn-warning' : 'btn-success' }} w-100">
                                    <i class="fas fa-{{ $product->is_active ? 'pause' : 'play' }} me-2"></i>
                                    {{ $product->is_active ? 'Deactivate' : 'Activate' }}
                                </button>
                            </form>
                        </div>
                        <div class="col-md-4 mb-3">
                            <form action="{{ route('admin.products.toggleFeatured', $product->id) }}" method="POST" class="w-100">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                        class="btn {{ $product->is_featured ? 'btn-outline-warning' : 'btn-warning' }} w-100">
                                    <i class="fas fa-star me-2"></i>
                                    {{ $product->is_featured ? 'Remove Featured' : 'Mark Featured' }}
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Danger Zone -->
                    <div class="row mt-4 pt-4 border-top">
                        <div class="col-12">
                            <h6 class="text-danger mb-3">Danger Zone</h6>
                            @if($product->orderItems->count() > 0 || $product->reviews->count() > 0 || $product->cartItems->count() > 0)
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                This product cannot be deleted because it has associated orders, reviews, or cart items.
                            </div>
                            <button class="btn btn-danger" disabled>
                                <i class="fas fa-trash me-2"></i>Delete Product
                            </button>
                            @else
                            <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="btn btn-danger"
                                        onclick="return confirm('Are you sure you want to delete this product? This action cannot be undone.')">
                                    <i class="fas fa-trash me-2"></i>Delete Product
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border: 1px solid #dee2e6;
    border-radius: 0;
}

.card-header {
    border-bottom: 1px solid #dee2e6;
    padding: 1rem 1.25rem;
}

.card-title {
    font-weight: 600;
}

.btn {
    border-radius: 0;
    border: 1px solid;
}

.table {
    margin-bottom: 0;
}

.table-borderless td {
    border: none;
    padding: 4px 0;
}

.badge {
    border-radius: 0;
    font-weight: 500;
}

.bg-light {
    background-color: #f8f9fa !important;
}

.border-top {
    border-top: 1px solid #dee2e6 !important;
}

.cursor-pointer {
    cursor: pointer;
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

.btn-danger {
    background-color: #dc3545;
    border-color: #dc3545;
}

.alert-warning {
    background-color: #fff3cd;
    border-color: #ffecb5;
    color: #664d03;
}

.bg-primary { background-color: #BC450D !important; }
.bg-success { background-color: #198754 !important; }
.bg-warning { background-color: #ffc107 !important; }
.bg-danger { background-color: #dc3545 !important; }

.text-primary { color: #BC450D !important; }
</style>
@endsection
