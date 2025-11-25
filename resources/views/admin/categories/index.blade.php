@extends('layouts.admin')

@section('title', 'Supa Farm Categories - SupaFarm Admin')
@section('page-title', 'Categories Overview')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-md-6">
            <h3 class="mb-1">All Categories</h3>
            <p class="text-muted mb-0">Manage your product categories</p>
        </div>
        <div class="col-md-6 text-md-end">
            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary px-4">
                <i class="fas fa-plus me-2"></i>Add Category
            </a>
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

    <!-- Categories Table -->
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="px-4">Category</th>
                            <th class="px-4">Products</th>
                            <th class="px-4">Status</th>
                            <th class="px-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                        <tr>
                            <td class="px-4">
                                <div class="d-flex align-items-center">
                                    @if($category->image_url)
                                    <img src="{{ $category->image_url }}" alt="{{ $category->name }}"
                                         class="rounded me-3" style="width: 40px; height: 40px; object-fit: cover;">
                                    @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center me-3"
                                         style="width: 40px; height: 40px;">
                                        <i class="fas fa-folder text-muted"></i>
                                    </div>
                                    @endif
                                    <div>
                                        <div class="fw-medium">{{ $category->name }}</div>
                                        @if($category->description)
                                        <small class="text-muted">{{ Str::limit($category->description, 50) }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-4">
                                <span class="badge bg-secondary">{{ $category->products_count ?? $category->products->count() }}</span>
                            </td>
                            <td class="px-4">
                                <span class="badge {{ $category->is_active ? 'bg-success' : 'bg-danger' }}">
                                    {{ $category->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-4">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.categories.edit', $category->id) }}"
                                       class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-edit me-1"></i>Edit
                                    </a>
                                    <a href="{{ route('admin.categories.show', $category->id) }}"
                                       class="btn btn-outline-secondary btn-sm">
                                        <i class="fas fa-eye me-1"></i>View
                                    </a>
                                    <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="btn btn-outline-danger btn-sm"
                                                onclick="return confirm('Are you sure you want to delete this category?')">
                                            <i class="fas fa-trash me-1"></i>Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <div class="py-4">
                                    <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">No categories found</h5>
                                    <p class="text-muted mb-3">Get started by creating your first category</p>
                                    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus me-2"></i>Create Category
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    @if($categories->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $categories->links() }}
    </div>
    @endif
</div>



<style>
/* Custom flat styling */
.card {
    border: 1px solid #dee2e6;
    border-radius: 0;
}

.btn {
    border-radius: 0;
    border: 1px solid;
}

.alert {
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
    padding: 16px;
    vertical-align: middle;
    border-bottom: 1px solid #dee2e6;
}

.table-hover tbody tr:hover {
    background-color: #f8f9fa;
}

.badge {
    border-radius: 0;
    font-weight: 500;
}

.btn-group .btn {
    margin-right: 4px;
}

.btn-group .btn:last-child {
    margin-right: 0;
}

/* Custom colors using SupaFarm palette */
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

.table-light {
    background-color: #f8f9fa;
}
</style>
@endsection
