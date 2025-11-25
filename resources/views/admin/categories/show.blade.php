@extends('layouts.admin')

@section('title', $category->name . ' - SupaFarm Admin')
@section('page-title', 'Category Details')

@section('content')
<div class="container-fluid">
    <!-- Back Button -->
    <div class="row mb-4">
        <div class="col-12">
            <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Categories
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Left Column - Category Details -->
        <div class="col-lg-8">
            <!-- Basic Information Card -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">Basic Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="text-muted" style="width: 120px;">ID:</td>
                                    <td class="fw-medium">#{{ $category->id }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Name:</td>
                                    <td class="fw-medium">{{ $category->name }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Status:</td>
                                    <td>
                                        <span class="badge {{ $category->is_active ? 'bg-success' : 'bg-danger' }}">
                                            {{ $category->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="text-muted" style="width: 120px;">Created:</td>
                                    <td>{{ $category->created_at->format('M d, Y') }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Updated:</td>
                                    <td>{{ $category->updated_at->format('M d, Y') }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Products:</td>
                                    <td class="fw-medium">{{ $category->products->count() }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description Card -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">Description</h5>
                </div>
                <div class="card-body">
                    @if($category->description)
                        <p class="mb-0">{{ $category->description }}</p>
                    @else
                        <p class="text-muted mb-0">No description provided.</p>
                    @endif
                </div>
            </div>

            <!-- Products Card -->
            @if($category->products->count() > 0)
            <div class="card">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Products in this Category ({{ $category->products->count() }})</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="px-4">Product Name</th>
                                    <th class="px-4">Price</th>
                                    <th class="px-4">Stock</th>
                                    <th class="px-4">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($category->products as $product)
                                <tr>
                                    <td class="px-4">
                                        <div class="d-flex align-items-center">
                                            @if($product->image)
                                            <img src="{{ $product->image_url ?? '/placeholder-image.jpg' }}"
                                                 alt="{{ $product->name }}"
                                                 class="rounded me-3" style="width: 40px; height: 40px; object-fit: cover;">
                                            @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center me-3"
                                                 style="width: 40px; height: 40px;">
                                                <i class="fas fa-cube text-muted"></i>
                                            </div>
                                            @endif
                                            <div>
                                                <div class="fw-medium">{{ $product->name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4">${{ number_format($product->price, 2) }}</td>
                                    <td class="px-4">{{ $product->stock_quantity ?? 'N/A' }}</td>
                                    <td class="px-4">
                                        <span class="badge {{ $product->is_active ? 'bg-success' : 'bg-danger' }}">
                                            {{ $product->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @else
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fas fa-cubes fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No Products Found</h5>
                    <p class="text-muted mb-0">There are no products in this category yet.</p>
                </div>
            </div>
            @endif
        </div>

        <!-- Right Column - Image & Actions -->
        <div class="col-lg-4">
            <!-- Category Image Card -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">Category Image</h5>
                </div>
                <div class="card-body text-center">
                    @if($category->image_url)
                        <img src="{{ $category->image_url }}" alt="{{ $category->name }}"
                             class="img-fluid mb-3" style="max-height: 300px; object-fit: cover;">
                        <p class="text-muted small">
                            Original: {{ $category->original_filename ?? 'N/A' }}
                        </p>
                    @else
                        <div class="py-5">
                            <i class="fas fa-image fa-3x text-muted mb-3"></i>
                            <p class="text-muted mb-0">No image available</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Actions Card -->
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.categories.edit', $category->id) }}"
                           class="btn btn-outline-primary">
                            <i class="fas fa-edit me-2"></i>Edit Category
                        </a>

                        @if($category->products->count() > 0)
                        <button class="btn btn-outline-danger" disabled title="Cannot delete category with products">
                            <i class="fas fa-trash me-2"></i>Delete Category
                        </button>
                        <small class="text-muted text-center mt-1">
                            Remove all products first to delete this category
                        </small>
                        @else
                        <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="d-grid">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="btn btn-outline-danger"
                                    onclick="return confirm('Are you sure you want to delete this category? This action cannot be undone.')">
                                <i class="fas fa-trash me-2"></i>Delete Category
                            </button>
                        </form>
                        @endif

                        <!-- Toggle Status -->
                        <form action="{{ route('admin.categories.toggleStatus', $category->id) }}" method="POST" class="d-grid">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-outline-{{ $category->is_active ? 'warning' : 'success' }}">
                                <i class="fas fa-{{ $category->is_active ? 'pause' : 'play' }} me-2"></i>
                                {{ $category->is_active ? 'Deactivate' : 'Activate' }} Category
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Quick Stats Card -->
            <div class="card mt-4">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">Quick Stats</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <div class="h4 mb-1">{{ $category->products->count() }}</div>
                                <small class="text-muted">Total Products</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="h4 mb-1">{{ $category->products->where('is_active', true)->count() }}</div>
                            <small class="text-muted">Active Products</small>
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

.table th {
    border-bottom: 2px solid #dee2e6;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.875rem;
    padding: 12px 16px;
}

.table td {
    padding: 12px 16px;
    vertical-align: middle;
    border-bottom: 1px solid #dee2e6;
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

/* Custom colors */
.btn-outline-primary {
    color: #BC450D;
    border-color: #BC450D;
}

.btn-outline-primary:hover {
    background-color: #BC450D;
    border-color: #BC450D;
    color: white;
}

.btn-outline-danger {
    color: #dc3545;
    border-color: #dc3545;
}

.btn-outline-danger:hover {
    background-color: #dc3545;
    border-color: #dc3545;
    color: white;
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

.btn-outline-success {
    color: #198754;
    border-color: #198754;
}

.btn-outline-success:hover {
    background-color: #198754;
    border-color: #198754;
    color: white;
}
</style>
@endsection
