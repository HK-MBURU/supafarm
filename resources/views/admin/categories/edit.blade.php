@extends('layouts.admin')

@section('title', 'Edit ' . $category->name . ' - SupaFarm Admin')
@section('page-title', 'Edit Category')

@section('content')
<div class="container-fluid">
    <!-- Back Button -->
    <div class="row mb-4">
        <div class="col-12">
            <a href="{{ route('admin.categories.show', $category->id) }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Category
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">Edit Category Details</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Category Name -->
                        <div class="mb-4">
                            <label for="name" class="form-label">Category Name <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control @error('name') is-invalid @enderror"
                                   id="name"
                                   name="name"
                                   value="{{ old('name', $category->name) }}"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description"
                                      name="description"
                                      rows="4">{{ old('description', $category->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Image Upload -->
                        <div class="mb-4">
                            <label for="image" class="form-label">Category Image</label>

                            <!-- Current Image Preview -->
                            @if($category->image_url)
                            <div class="mb-3">
                                <p class="text-muted mb-2">Current Image:</p>
                                <div class="d-flex align-items-center gap-3">
                                    <img src="{{ $category->image_url }}"
                                         alt="{{ $category->name }}"
                                         class="img-fluid"
                                         style="max-height: 150px; max-width: 150px; object-fit: cover;">
                                    <div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="remove_image" value="1" id="removeImage">
                                            <label class="form-check-label text-danger" for="removeImage">
                                                Remove current image
                                            </label>
                                        </div>
                                        @if($category->original_filename)
                                        <small class="text-muted d-block mt-1">
                                            File: {{ $category->original_filename }}
                                        </small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- New Image Upload -->
                            <input type="file"
                                   class="form-control @error('image') is-invalid @enderror"
                                   id="image"
                                   name="image"
                                   accept="image/*">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                Supported formats: JPEG, PNG, JPG, GIF, WEBP. Max size: 2MB
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input"
                                       type="checkbox"
                                       name="is_active"
                                       value="1"
                                       id="isActive"
                                       {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label fw-medium" for="isActive">
                                    Active Category
                                </label>
                            </div>
                            <div class="form-text">
                                Inactive categories won't be visible to customers
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex gap-2 pt-3 border-top">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Update Category
                            </button>
                            <a href="{{ route('admin.categories.show', $category->id) }}" class="btn btn-outline-secondary">
                                Cancel
                            </a>
                            <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary ms-auto">
                                Back to List
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Category Info Card -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">Category Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong class="text-muted d-block">ID</strong>
                        <span>#{{ $category->id }}</span>
                    </div>
                    <div class="mb-3">
                        <strong class="text-muted d-block">Current Status</strong>
                        <span class="badge {{ $category->is_active ? 'bg-success' : 'bg-danger' }}">
                            {{ $category->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    <div class="mb-3">
                        <strong class="text-muted d-block">Products Count</strong>
                        <span>{{ $category->products->count() }} products</span>
                    </div>
                    <div class="mb-3">
                        <strong class="text-muted d-block">Created</strong>
                        <span>{{ $category->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="mb-0">
                        <strong class="text-muted d-block">Last Updated</strong>
                        <span>{{ $category->updated_at->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions Card -->
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.categories.show', $category->id) }}"
                           class="btn btn-outline-primary">
                            <i class="fas fa-eye me-2"></i>View Category
                        </a>

                        @if($category->products->count() > 0)
                        <button class="btn btn-outline-danger" disabled title="Cannot delete category with products">
                            <i class="fas fa-trash me-2"></i>Delete Category
                        </button>
                        <small class="text-muted text-center">
                            Remove all products first to delete
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
                    </div>
                </div>
            </div>

            <!-- Image Preview Card -->
            <div class="card mt-4">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">Image Preview</h5>
                </div>
                <div class="card-body text-center">
                    <div id="imagePreview" class="mb-3">
                        @if($category->image_url)
                            <img src="{{ $category->image_url }}"
                                 alt="Current image"
                                 class="img-fluid"
                                 style="max-height: 200px; object-fit: cover;">
                        @else
                            <div class="py-4 text-muted">
                                <i class="fas fa-image fa-2x mb-2"></i>
                                <p class="mb-0">No image</p>
                            </div>
                        @endif
                    </div>
                    <small class="text-muted">New image will replace the current one</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add JavaScript for image preview -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const imageInput = document.getElementById('image');
    const imagePreview = document.getElementById('imagePreview');
    const removeImageCheckbox = document.getElementById('removeImage');

    // Handle new image preview
    imageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.innerHTML = `<img src="${e.target.result}" class="img-fluid" style="max-height: 200px; object-fit: cover;" alt="Preview">`;
            };
            reader.readAsDataURL(file);
        }
    });

    // Handle remove image checkbox
    if (removeImageCheckbox) {
        removeImageCheckbox.addEventListener('change', function() {
            if (this.checked) {
                imagePreview.innerHTML = `
                    <div class="py-4 text-muted">
                        <i class="fas fa-image fa-2x mb-2"></i>
                        <p class="mb-0">Image will be removed</p>
                    </div>
                `;
            } else {
                imagePreview.innerHTML = `<img src="{{ $category->image_url }}" class="img-fluid" style="max-height: 200px; object-fit: cover;" alt="Current image">`;
            }
        });
    }
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

.form-text {
    color: #6c757d;
    font-size: 0.875rem;
}
</style>
@endsection
