@extends('layouts.admin')
@section('title', 'Create Product - SupaFarm Admin')
@section('page-title', 'Create Product')

@section('content')
<div class="product-create-container">
    <!-- Header Section -->
    <div class="page-header">
        <div class="header-content">
            <div class="header-left">
                <a href="{{ route('admin.products.index') }}" class="btn-back">
                    <i class="fas fa-arrow-left"></i> Back to Products
                </a>
            </div>
            <div class="header-right">
                <span class="help-text">
                    <i class="fas fa-info-circle"></i> All fields marked with * are required
                </span>
            </div>
        </div>
    </div>

    <!-- Form Section -->
    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" id="productForm">
        @csrf

        <div class="form-layout">
            <!-- Main Content Column -->
            <div class="main-column">
                <!-- Basic Information Card -->
                <div class="form-card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-box"></i> Basic Information
                        </h3>
                    </div>
                    <div class="card-body">
                        <!-- Product Name -->
                        <div class="form-group">
                            <label for="name" class="form-label required">Product Name</label>
                            <input type="text"
                                   class="form-input @error('name') error @enderror"
                                   id="name"
                                   name="name"
                                   value="{{ old('name') }}"
                                   placeholder="Enter product name"
                                   required>
                            @error('name')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Category -->
                        <div class="form-group">
                            <label for="category_id" class="form-label required">Category</label>
                            <select class="form-input @error('category_id') error @enderror"
                                    id="category_id"
                                    name="category_id"
                                    required>
                                <option value="">Select a category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="form-group">
                            <label for="description" class="form-label required">Description</label>
                            <textarea class="form-textarea @error('description') error @enderror"
                                      id="description"
                                      name="description"
                                      rows="5"
                                      placeholder="Describe your product in detail"
                                      required>{{ old('description') }}</textarea>
                            @error('description')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Features (Optional) -->
                        <div class="form-group">
                            <label for="features" class="form-label">Features (Optional)</label>
                            <textarea class="form-textarea @error('features') error @enderror"
                                      id="features"
                                      name="features"
                                      rows="4"
                                      placeholder="List product features (one per line)">{{ old('features') }}</textarea>
                            @error('features')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                            <span class="help-text-small">Separate each feature with a new line</span>
                        </div>
                    </div>
                </div>

                <!-- Pricing & Inventory Card -->
                <div class="form-card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-tag"></i> Pricing & Inventory
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="form-row">
                            <!-- Regular Price -->
                            <div class="form-group">
                                <label for="price" class="form-label required">Regular Price (KSh)</label>
                                <div class="input-group">
                                    <span class="input-prefix">KSh</span>
                                    <input type="number"
                                           class="form-input @error('price') error @enderror"
                                           id="price"
                                           name="price"
                                           value="{{ old('price') }}"
                                           placeholder="0.00"
                                           step="0.01"
                                           min="0"
                                           required>
                                </div>
                                @error('price')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Sale Price -->
                            <div class="form-group">
                                <label for="sale_price" class="form-label">Sale Price (KSh)</label>
                                <div class="input-group">
                                    <span class="input-prefix">KSh</span>
                                    <input type="number"
                                           class="form-input @error('sale_price') error @enderror"
                                           id="sale_price"
                                           name="sale_price"
                                           value="{{ old('sale_price') }}"
                                           placeholder="0.00"
                                           step="0.01"
                                           min="0">
                                </div>
                                @error('sale_price')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                                <span class="help-text-small">Leave empty if not on sale</span>
                            </div>
                        </div>

                        <!-- Stock Quantity -->
                        <div class="form-group">
                            <label for="stock" class="form-label required">Stock Quantity</label>
                            <input type="number"
                                   class="form-input @error('stock') error @enderror"
                                   id="stock"
                                   name="stock"
                                   value="{{ old('stock', 0) }}"
                                   placeholder="0"
                                   min="0"
                                   required>
                            @error('stock')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- SEO Settings Card -->
                <div class="form-card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-search"></i> SEO Settings (Optional)
                        </h3>
                    </div>
                    <div class="card-body">
                        <!-- Meta Title -->
                        <div class="form-group">
                            <label for="meta_title" class="form-label">Meta Title</label>
                            <input type="text"
                                   class="form-input @error('meta_title') error @enderror"
                                   id="meta_title"
                                   name="meta_title"
                                   value="{{ old('meta_title') }}"
                                   placeholder="SEO meta title">
                            @error('meta_title')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                            <span class="help-text-small">Recommended: 50-60 characters</span>
                        </div>

                        <!-- Meta Description -->
                        <div class="form-group">
                            <label for="meta_description" class="form-label">Meta Description</label>
                            <textarea class="form-textarea @error('meta_description') error @enderror"
                                      id="meta_description"
                                      name="meta_description"
                                      rows="3"
                                      placeholder="SEO meta description">{{ old('meta_description') }}</textarea>
                            @error('meta_description')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                            <span class="help-text-small">Recommended: 150-160 characters</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar Column -->
            <div class="sidebar-column">
                <!-- Product Images Card -->
                <div class="form-card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-images"></i> Product Images
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="images" class="form-label">Upload Images</label>
                            <div class="image-upload-area" id="imageUploadArea">
                                <input type="file"
                                       class="image-input"
                                       id="images"
                                       name="images[]"
                                       accept="image/*"
                                       multiple>
                                <div class="upload-placeholder">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <p>Click to upload or drag and drop</p>
                                    <span class="upload-hint">PNG, JPG, GIF, WEBP up to 2MB each</span>
                                </div>
                            </div>
                            @error('images.*')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Image Preview Area -->
                        <div id="imagePreviewContainer" class="image-preview-container"></div>
                    </div>
                </div>

                <!-- Product Status Card -->
                <div class="form-card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-toggle-on"></i> Product Status
                        </h3>
                    </div>
                    <div class="card-body">
                        <!-- Is Active -->
                        <div class="form-group-switch">
                            <div class="switch-wrapper">
                                <input type="checkbox"
                                       class="switch-input"
                                       id="is_active"
                                       name="is_active"
                                       {{ old('is_active', true) ? 'checked' : '' }}>
                                <label for="is_active" class="switch-label">
                                    <span class="switch-slider"></span>
                                </label>
                            </div>
                            <div class="switch-text">
                                <label for="is_active" class="switch-title">Active</label>
                                <span class="switch-description">Product will be visible on the website</span>
                            </div>
                        </div>

                        <!-- Is Featured -->
                        <div class="form-group-switch">
                            <div class="switch-wrapper">
                                <input type="checkbox"
                                       class="switch-input"
                                       id="is_featured"
                                       name="is_featured"
                                       {{ old('is_featured') ? 'checked' : '' }}>
                                <label for="is_featured" class="switch-label">
                                    <span class="switch-slider"></span>
                                </label>
                            </div>
                            <div class="switch-text">
                                <label for="is_featured" class="switch-title">Featured</label>
                                <span class="switch-description">Display in featured products section</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="form-actions-sticky">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Create Product
                    </button>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
    /* Container */
    .product-create-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 20px;
    }

    /* Page Header */
    .page-header {
        margin-bottom: 30px;
    }

    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
    }

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 18px;
        background-color: #f5f5f5;
        color: #333;
        border: 1px solid #ddd;
        border-radius: 4px;
        text-decoration: none;
        font-size: 0.95rem;
        font-weight: 500;
        transition: background-color 0.2s;
    }

    .btn-back:hover {
        background-color: #e0e0e0;
    }

    .help-text {
        color: #666;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    /* Form Layout */
    .form-layout {
        display: grid;
        grid-template-columns: 1fr 380px;
        gap: 30px;
    }

    /* Form Card */
    .form-card {
        background: #ffffff;
        border: 1px solid #e0e0e0;
        border-radius: 4px;
        margin-bottom: 20px;
    }

    .card-header {
        padding: 18px 24px;
        border-bottom: 1px solid #e0e0e0;
        background-color: #fafafa;
    }

    .card-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #333;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .card-title i {
        color: var(--primary-color);
        font-size: 1rem;
    }

    .card-body {
        padding: 24px;
    }

    /* Form Groups */
    .form-group {
        margin-bottom: 20px;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .form-label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: #333;
        font-size: 0.95rem;
    }

    .form-label.required::after {
        content: ' *';
        color: #dc3545;
    }

    /* Form Inputs */
    .form-input,
    .form-textarea {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 0.95rem;
        font-family: inherit;
        background-color: #ffffff;
        transition: border-color 0.2s;
    }

    .form-input:focus,
    .form-textarea:focus {
        outline: none;
        border-color: var(--primary-color);
    }

    .form-input.error,
    .form-textarea.error {
        border-color: #dc3545;
    }

    .form-textarea {
        resize: vertical;
        min-height: 100px;
    }

    /* Input Group */
    .input-group {
        display: flex;
        align-items: stretch;
    }

    .input-prefix {
        display: flex;
        align-items: center;
        padding: 0 14px;
        background-color: #f5f5f5;
        border: 1px solid #ddd;
        border-right: none;
        border-radius: 4px 0 0 4px;
        font-weight: 500;
        color: #666;
        font-size: 0.95rem;
    }

    .input-group .form-input {
        border-radius: 0 4px 4px 0;
    }

    /* Error Messages */
    .error-message {
        display: block;
        color: #dc3545;
        font-size: 0.875rem;
        margin-top: 6px;
    }

    .help-text-small {
        display: block;
        color: #999;
        font-size: 0.85rem;
        margin-top: 6px;
    }

    /* Image Upload Area */
    .image-upload-area {
        position: relative;
        border: 2px dashed #ddd;
        border-radius: 4px;
        padding: 40px 20px;
        text-align: center;
        cursor: pointer;
        transition: border-color 0.2s;
    }

    .image-upload-area:hover {
        border-color: var(--primary-color);
    }

    .image-input {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
    }

    .upload-placeholder i {
        font-size: 3rem;
        color: #ccc;
        margin-bottom: 10px;
    }

    .upload-placeholder p {
        margin: 10px 0 5px 0;
        color: #666;
        font-weight: 500;
    }

    .upload-hint {
        color: #999;
        font-size: 0.85rem;
    }

    /* Image Preview */
    .image-preview-container {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
        margin-top: 20px;
    }

    .image-preview-item {
        position: relative;
        aspect-ratio: 1;
        border-radius: 4px;
        overflow: hidden;
        border: 1px solid #e0e0e0;
    }

    .image-preview-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .image-remove-btn {
        position: absolute;
        top: 8px;
        right: 8px;
        width: 28px;
        height: 28px;
        background-color: rgba(220, 53, 69, 0.9);
        color: white;
        border: none;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
    }

    .image-remove-btn:hover {
        background-color: #dc3545;
    }

    /* Switch Styles */
    .form-group-switch {
        display: flex;
        align-items: flex-start;
        gap: 15px;
        padding: 15px 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .form-group-switch:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .switch-wrapper {
        flex-shrink: 0;
    }

    .switch-input {
        display: none;
    }

    .switch-label {
        display: block;
        width: 52px;
        height: 28px;
        background-color: #ddd;
        border-radius: 14px;
        position: relative;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .switch-slider {
        position: absolute;
        top: 3px;
        left: 3px;
        width: 22px;
        height: 22px;
        background-color: white;
        border-radius: 50%;
        transition: transform 0.2s;
    }

    .switch-input:checked + .switch-label {
        background-color: var(--primary-color);
    }

    .switch-input:checked + .switch-label .switch-slider {
        transform: translateX(24px);
    }

    .switch-text {
        flex-grow: 1;
    }

    .switch-title {
        display: block;
        font-weight: 500;
        color: #333;
        margin-bottom: 4px;
        cursor: pointer;
    }

    .switch-description {
        display: block;
        font-size: 0.85rem;
        color: #666;
    }

    /* Action Buttons */
    .form-actions-sticky {
        position: sticky;
        top: 20px;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 12px 24px;
        border: none;
        border-radius: 4px;
        font-size: 0.95rem;
        font-weight: 500;
        cursor: pointer;
        text-decoration: none;
        transition: background-color 0.2s;
    }

    .btn-primary {
        background-color: var(--primary-color);
        color: white;
    }

    .btn-primary:hover {
        background-color: var(--dark-color);
    }

    .btn-secondary {
        background-color: #6c757d;
        color: white;
    }

    .btn-secondary:hover {
        background-color: #5a6268;
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .form-layout {
            grid-template-columns: 1fr;
        }

        .sidebar-column {
            order: -1;
        }

        .form-actions-sticky {
            position: static;
        }
    }

    @media (max-width: 768px) {
        .product-create-container {
            padding: 15px;
        }

        .header-content {
            flex-direction: column;
            align-items: flex-start;
        }

        .form-row {
            grid-template-columns: 1fr;
        }

        .card-body {
            padding: 20px;
        }

        .image-preview-container {
            grid-template-columns: 1fr;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const imageInput = document.getElementById('images');
        const imagePreviewContainer = document.getElementById('imagePreviewContainer');
        let selectedFiles = [];

        // Handle image selection
        imageInput.addEventListener('change', function(e) {
            const files = Array.from(e.target.files);

            files.forEach(file => {
                if (file.type.startsWith('image/')) {
                    selectedFiles.push(file);
                    displayImagePreview(file);
                }
            });

            updateFileInput();
        });

        // Display image preview
        function displayImagePreview(file) {
            const reader = new FileReader();

            reader.onload = function(e) {
                const previewItem = document.createElement('div');
                previewItem.className = 'image-preview-item';
                previewItem.innerHTML = `
                    <img src="${e.target.result}" alt="Preview">
                    <button type="button" class="image-remove-btn" onclick="removeImage(this, '${file.name}')">
                        <i class="fas fa-times"></i>
                    </button>
                `;

                imagePreviewContainer.appendChild(previewItem);
            };

            reader.readAsDataURL(file);
        }

        // Remove image
        window.removeImage = function(button, fileName) {
            selectedFiles = selectedFiles.filter(file => file.name !== fileName);
            button.closest('.image-preview-item').remove();
            updateFileInput();
        };

        // Update file input with selected files
        function updateFileInput() {
            const dataTransfer = new DataTransfer();
            selectedFiles.forEach(file => dataTransfer.items.add(file));
            imageInput.files = dataTransfer.files;
        }

        // Form validation
        const form = document.getElementById('productForm');
        form.addEventListener('submit', function(e) {
            const price = parseFloat(document.getElementById('price').value);
            const salePrice = parseFloat(document.getElementById('sale_price').value);

            if (salePrice && salePrice >= price) {
                e.preventDefault();
                alert('Sale price must be less than regular price!');
                return false;
            }
        });

        // Auto-fill meta title from product name
        const nameInput = document.getElementById('name');
        const metaTitleInput = document.getElementById('meta_title');

        nameInput.addEventListener('blur', function() {
            if (!metaTitleInput.value) {
                metaTitleInput.value = this.value;
            }
        });
    });
</script>
@endsection
