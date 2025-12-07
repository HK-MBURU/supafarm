@extends('layouts.admin')
@section('title', 'Create Category - SupaFarm Admin')
@section('page-title', 'Create Category')

@section('content')
    <div class="category-create-container">
        <!-- Header Section -->
        <div class="page-header">
            <div class="header-content">
                <div class="header-left">
                    <a href="{{ route('admin.categories.index') }}" class="btn-back">
                        <i class="fas fa-arrow-left"></i> Back to Categories
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
        <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data" id="categoryForm">
            @csrf

            <div class="form-layout">
                <!-- Main Content Column -->
                <div class="main-column">
                    <!-- Basic Information Card -->
                    <div class="form-card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-folder"></i> Category Information
                            </h3>
                        </div>
                        <div class="card-body">
                            <!-- Category Name -->
                            <div class="form-group">
                                <label for="name" class="form-label required">Category Name</label>
                                <input type="text" class="form-input @error('name') error @enderror" id="name"
                                    name="name" value="{{ old('name') }}" placeholder="Enter category name" required>
                                @error('name')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="form-group">
                                <label for="description" class="form-label">Description (Optional)</label>
                                <textarea class="form-textarea @error('description') error @enderror" id="description" name="description" rows="4"
                                    placeholder="Describe this category">{{ old('description') }}</textarea>
                                @error('description')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                                <span class="help-text-small">Provide a brief description for this category</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar Column -->
                <div class="sidebar-column">
                    <!-- Category Image Card -->
                    <div class="form-card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-image"></i> Category Image
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="image" class="form-label">Upload Image</label>
                                <div class="image-upload-area" id="imageUploadArea">
                                    <input type="file" class="image-input" id="image" name="image"
                                        accept="image/*">
                                    <div class="upload-placeholder">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                        <p>Click to upload or drag and drop</p>
                                        <span class="upload-hint">PNG, JPG, GIF, WEBP up to 2MB</span>
                                    </div>
                                </div>
                                @error('image')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror

                                <!-- Image Preview -->
                                <div id="imagePreviewContainer" class="image-preview-container">
                                    <div class="image-preview-item" id="singleImagePreview" style="display: none;">
                                        <img id="previewImage" src="" alt="Preview">
                                        <button type="button" class="image-remove-btn" id="removeImageBtn">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                                <span class="help-text-small">Recommended: 600x400px or similar aspect ratio</span>
                            </div>
                        </div>
                    </div>

                    <!-- Category Status Card -->
                    <div class="form-card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-toggle-on"></i> Category Status
                            </h3>
                        </div>
                        <div class="card-body">
                            <!-- Is Active -->
                            <div class="form-group-switch">
                                <div class="switch-wrapper">
                                    <input type="hidden" name="is_active" value="0"> <!-- Always send 0 first -->
                                    <input type="checkbox" class="switch-input" id="is_active" name="is_active"
                                        value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label for="is_active" class="switch-label">
                                        <span class="switch-slider"></span>
                                    </label>
                                </div>
                                <div class="switch-text">
                                    <label for="is_active" class="switch-title">Active</label>
                                    <span class="switch-description">Category will be visible on the website</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Action Buttons -->
                    <div class="form-actions-sticky">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Create Category
                        </button>
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <style>
        /* Container */
        .category-create-container {
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
            background-color: #fafafa;
            margin-bottom: 15px;
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

        /* Single Image Preview */
        .image-preview-container {
            margin-bottom: 15px;
        }

        #singleImagePreview {
            position: relative;
            aspect-ratio: 3/2;
            border-radius: 4px;
            overflow: hidden;
            border: 1px solid #e0e0e0;
        }

        #singleImagePreview img {
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

        .switch-input:checked+.switch-label {
            background-color: var(--primary-color);
        }

        .switch-input:checked+.switch-label .switch-slider {
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
            .category-create-container {
                padding: 15px;
            }

            .header-content {
                flex-direction: column;
                align-items: flex-start;
            }

            .card-body {
                padding: 20px;
            }
        }

        /* Ensure CSS variables are defined */
        :root {
            --primary-color: #BC450D;
            --dark-color: #541907;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const imageInput = document.getElementById('image');
            const imagePreviewContainer = document.getElementById('singleImagePreview');
            const previewImage = document.getElementById('previewImage');
            const removeImageBtn = document.getElementById('removeImageBtn');
            const uploadArea = document.getElementById('imageUploadArea');

            // Handle image selection
            imageInput.addEventListener('change', function(e) {
                const file = e.target.files[0];

                if (file && file.type.startsWith('image/')) {
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        previewImage.src = e.target.result;
                        imagePreviewContainer.style.display = 'block';
                    };

                    reader.readAsDataURL(file);
                }
            });

            // Handle image removal
            removeImageBtn.addEventListener('click', function() {
                imageInput.value = '';
                previewImage.src = '';
                imagePreviewContainer.style.display = 'none';
            });

            // Form validation
            const form = document.getElementById('categoryForm');
            form.addEventListener('submit', function(e) {
                const name = document.getElementById('name').value.trim();

                if (!name) {
                    e.preventDefault();
                    alert('Please enter a category name');
                    document.getElementById('name').focus();
                    return false;
                }

                // Show loading state
                const submitBtn = form.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creating...';
                submitBtn.disabled = true;

                // Debug log
                const formData = new FormData(form);
                console.log('Category Form Data:');
                for (let [key, value] of formData.entries()) {
                    console.log(`${key}:`, value);
                }

                // Re-enable button after 5 seconds if still on page (as fallback)
                setTimeout(() => {
                    if (submitBtn.disabled) {
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                    }
                }, 5000);

                return true;
            });

            // Drag and drop functionality
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                uploadArea.addEventListener(eventName, preventDefaults, false);
            });

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            ['dragenter', 'dragover'].forEach(eventName => {
                uploadArea.addEventListener(eventName, highlight, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                uploadArea.addEventListener(eventName, unhighlight, false);
            });

            function highlight(e) {
                uploadArea.style.borderColor = '#BC450D';
                uploadArea.style.backgroundColor = '#f9f9f9';
            }

            function unhighlight(e) {
                uploadArea.style.borderColor = '#ddd';
                uploadArea.style.backgroundColor = '#fafafa';
            }

            uploadArea.addEventListener('drop', handleDrop, false);

            function handleDrop(e) {
                const dt = e.dataTransfer;
                const files = dt.files;

                if (files.length > 0) {
                    imageInput.files = files;
                    const event = new Event('change', {
                        bubbles: true
                    });
                    imageInput.dispatchEvent(event);
                }
            }
        });
    </script>
@endsection
