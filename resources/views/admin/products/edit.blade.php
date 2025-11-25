@extends('layouts.admin')

@section('title', 'Edit ' . $product->name . ' - SupaFarm Admin')
@section('page-title', 'Edit Product')

@section('content')
<div class="container-fluid">
    <!-- Back Button -->
    <div class="row mb-4">
        <div class="col-12">
            <a href="{{ route('admin.products.show', $product->id) }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Product
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Left Column - Edit Form -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">Edit Product Details</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Basic Information -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Product Name <span class="text-danger">*</span></label>
                                    <input type="text"
                                           class="form-control @error('name') is-invalid @enderror"
                                           id="name"
                                           name="name"
                                           value="{{ old('name', $product->name) }}"
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                                    <select class="form-control @error('category_id') is-invalid @enderror"
                                            id="category_id"
                                            name="category_id"
                                            required>
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                        <option value="{{ $category->id }}"
                                                {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Pricing -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="price" class="form-label">Regular Price ($) <span class="text-danger">*</span></label>
                                    <input type="number"
                                           step="0.01"
                                           min="0"
                                           class="form-control @error('price') is-invalid @enderror"
                                           id="price"
                                           name="price"
                                           value="{{ old('price', $product->price) }}"
                                           required>
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="sale_price" class="form-label">Sale Price ($)</label>
                                    <input type="number"
                                           step="0.01"
                                           min="0"
                                           class="form-control @error('sale_price') is-invalid @enderror"
                                           id="sale_price"
                                           name="sale_price"
                                           value="{{ old('sale_price', $product->sale_price) }}">
                                    @error('sale_price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Stock & Status -->
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="stock" class="form-label">Stock Quantity <span class="text-danger">*</span></label>
                                    <input type="number"
                                           min="0"
                                           class="form-control @error('stock') is-invalid @enderror"
                                           id="stock"
                                           name="stock"
                                           value="{{ old('stock', $product->stock) }}"
                                           required>
                                    @error('stock')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label d-block">Status</label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input"
                                               type="checkbox"
                                               name="is_active"
                                               value="1"
                                               id="isActive"
                                               {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-medium" for="isActive">
                                            Active
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label d-block">Featured</label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input"
                                               type="checkbox"
                                               name="is_featured"
                                               value="1"
                                               id="isFeatured"
                                               {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-medium" for="isFeatured">
                                            Featured
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="mb-3">
                            <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description"
                                      name="description"
                                      rows="4"
                                      required>{{ old('description', $product->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Features -->
                        <div class="mb-3">
                            <label for="features" class="form-label">Features</label>
                            <textarea class="form-control @error('features') is-invalid @enderror"
                                      id="features"
                                      name="features"
                                      rows="3">{{ old('features', $product->features) }}</textarea>
                            @error('features')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">List product features separated by new lines or bullets</div>
                        </div>

                        <!-- Image Upload -->
                        <div class="mb-4">
                            <label for="images" class="form-label">Product Images</label>

                            <!-- Current Images -->
                            @if(count($product->image_urls) > 0)
                            <div class="mb-3">
                                <p class="text-muted mb-2">Current Images:</p>
                                <div class="row g-2">
                                    @foreach($product->image_urls as $index => $imageUrl)
                                    <div class="col-md-3 col-6">
                                        <div class="position-relative">
                                            <img src="{{ $imageUrl }}"
                                                 alt="Product Image {{ $index + 1 }}"
                                                 class="img-fluid rounded border"
                                                 style="height: 100px; width: 100%; object-fit: cover;">
                                            <div class="form-check position-absolute top-0 start-0 m-1">
                                                <input class="form-check-input"
                                                       type="checkbox"
                                                       name="remove_images[]"
                                                       value="{{ $index }}"
                                                       id="removeImage{{ $index }}">
                                                <label class="form-check-label text-white bg-danger rounded px-1"
                                                       for="removeImage{{ $index }}"
                                                       style="font-size: 0.7rem;">
                                                    Remove
                                                </label>
                                            </div>
                                        </div>
                                        @if($product->original_filename && isset($product->original_filename[$index]))
                                        <small class="text-muted d-block mt-1">
                                            {{ $product->original_filename[$index] }}
                                        </small>
                                        @endif
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            <!-- New Images Upload -->
                            <input type="file"
                                   class="form-control @error('images') is-invalid @enderror"
                                   id="images"
                                   name="images[]"
                                   multiple
                                   accept="image/*">
                            @error('images')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @error('images.*')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                Supported formats: JPEG, PNG, JPG, GIF, WEBP. Max size: 2MB per image. You can select multiple images.
                            </div>

                            <!-- Image Preview -->
                            <div id="imagePreview" class="row g-2 mt-2"></div>
                        </div>

                        <!-- SEO Information -->
                        <div class="mb-4">
                            <h6 class="fw-bold border-bottom pb-2 mb-3">SEO Information</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="meta_title" class="form-label">Meta Title</label>
                                        <input type="text"
                                               class="form-control @error('meta_title') is-invalid @enderror"
                                               id="meta_title"
                                               name="meta_title"
                                               value="{{ old('meta_title', $product->meta_title) }}"
                                               maxlength="255">
                                        @error('meta_title')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Recommended: 50-60 characters</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="meta_description" class="form-label">Meta Description</label>
                                        <textarea class="form-control @error('meta_description') is-invalid @enderror"
                                                  id="meta_description"
                                                  name="meta_description"
                                                  rows="2"
                                                  maxlength="320">{{ old('meta_description', $product->meta_description) }}</textarea>
                                        @error('meta_description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Recommended: 150-160 characters</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex gap-2 pt-3 border-top">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Update Product
                            </button>
                            <a href="{{ route('admin.products.show', $product->id) }}" class="btn btn-outline-secondary">
                                Cancel
                            </a>
                            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary ms-auto">
                                Back to List
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Right Column - Product Info & Actions -->
        <div class="col-lg-4">
            <!-- Product Summary -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">Product Summary</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong class="text-muted d-block">ID</strong>
                        <span>#{{ $product->id }}</span>
                    </div>
                    <div class="mb-3">
                        <strong class="text-muted d-block">Current Status</strong>
                        <span class="badge {{ $product->is_active ? 'bg-success' : 'bg-danger' }}">
                            {{ $product->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    <div class="mb-3">
                        <strong class="text-muted d-block">Featured</strong>
                        <span class="badge {{ $product->is_featured ? 'bg-warning text-dark' : 'bg-secondary' }}">
                            {{ $product->is_featured ? 'Yes' : 'No' }}
                        </span>
                    </div>
                    <div class="mb-3">
                        <strong class="text-muted d-block">Images</strong>
                        <span>{{ count($product->image_urls) }} image(s)</span>
                    </div>
                    <div class="mb-3">
                        <strong class="text-muted d-block">Created</strong>
                        <span>{{ $product->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="mb-0">
                        <strong class="text-muted d-block">Last Updated</strong>
                        <span>{{ $product->updated_at->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.products.show', $product->id) }}"
                           class="btn btn-outline-primary">
                            <i class="fas fa-eye me-2"></i>View Product
                        </a>

                        <!-- Stock Update -->
                        <form action="{{ route('admin.products.updateStock', $product->id) }}" method="POST" class="d-grid">
                            @csrf
                            @method('PATCH')
                            <div class="input-group">
                                <input type="number"
                                       name="stock"
                                       class="form-control"
                                       placeholder="Stock"
                                       value="{{ $product->stock }}"
                                       min="0">
                                <button type="submit" class="btn btn-outline-success">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                            </div>
                        </form>

                        <!-- Danger Zone -->
                        @if($product->orderItems->count() > 0 || $product->reviews->count() > 0 || $product->cartItems->count() > 0)
                        <button class="btn btn-outline-danger" disabled title="Cannot delete product with orders, reviews, or cart items">
                            <i class="fas fa-trash me-2"></i>Delete Product
                        </button>
                        <small class="text-muted text-center">
                            Remove all associated data first to delete
                        </small>
                        @else
                        <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="d-grid">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="btn btn-outline-danger"
                                    onclick="return confirm('Are you sure you want to delete this product? This action cannot be undone.')">
                                <i class="fas fa-trash me-2"></i>Delete Product
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Current Main Image -->
            <div class="card mt-4">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">Current Main Image</h5>
                </div>
                <div class="card-body text-center">
                    @if($product->image_url)
                        <img src="{{ $product->image_url }}"
                             alt="Current main image"
                             class="img-fluid rounded border"
                             style="max-height: 200px; object-fit: cover;">
                    @else
                        <div class="py-4 text-muted">
                            <i class="fas fa-image fa-2x mb-2"></i>
                            <p class="mb-0">No main image</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for Image Preview -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const imagesInput = document.getElementById('images');
    const imagePreview = document.getElementById('imagePreview');

    // Handle new image preview
    imagesInput.addEventListener('change', function(e) {
        imagePreview.innerHTML = '';
        const files = e.target.files;

        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const col = document.createElement('div');
                    col.className = 'col-md-3 col-6';
                    col.innerHTML = `
                        <div class="position-relative">
                            <img src="${e.target.result}"
                                 class="img-fluid rounded border"
                                 style="height: 100px; width: 100%; object-fit: cover;"
                                 alt="Preview">
                            <small class="text-muted d-block mt-1 text-truncate">${file.name}</small>
                        </div>
                    `;
                    imagePreview.appendChild(col);
                };
                reader.readAsDataURL(file);
            }
        }
    });

    // Price validation
    const priceInput = document.getElementById('price');
    const salePriceInput = document.getElementById('sale_price');

    function validatePrices() {
        if (salePriceInput.value && parseFloat(salePriceInput.value) >= parseFloat(priceInput.value)) {
            salePriceInput.setCustomValidity('Sale price must be less than regular price');
        } else {
            salePriceInput.setCustomValidity('');
        }
    }

    priceInput.addEventListener('input', validatePrices);
    salePriceInput.addEventListener('input', validatePrices);
});
</script>

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

.form-control {
    border-radius: 0;
    border: 1px solid #dee2e6;
}

.form-control:focus {
    border-color: #BC450D;
    box-shadow: none;
}

.form-check-input:checked {
    background-color: #BC450D;
    border-color: #BC450D;
}

.form-check-input:focus {
    border-color: #BC450D;
    box-shadow: none;
}

.invalid-feedback {
    display: block;
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

.btn-outline-danger {
    color: #dc3545;
    border-color: #dc3545;
}

.btn-outline-danger:hover {
    background-color: #dc3545;
    border-color: #dc3545;
    color: white;
}

.btn-outline-secondary {
    color: #6c757d;
    border-color: #6c757d;
}

.btn-outline-secondary:hover {
    background-color: #6c757d;
    border-color: #6c757d;
    color: white;
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

.form-text {
    color: #6c757d;
    font-size: 0.875rem;
}

.position-relative .form-check-input {
    background-color: #dc3545;
    border-color: #dc3545;
}

.position-relative .form-check-input:checked {
    background-color: #dc3545;
    border-color: #dc3545;
}
</style>
@endsection
