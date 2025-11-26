@extends('layouts.admin')

@section('title', 'Create News - SupaFarm Admin')
@section('page-title', 'Create News Article')

@section('content')
    <div class="container-fluid px-0">
        <!-- Header -->
        <div class="admin-header bg-white border-bottom px-4 py-3 mb-4">
            <div
                class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <div class="bg-primary bg-opacity-10 rounded-3 p-3">
                            <i class="fas fa-newspaper fa-lg text-primary"></i>
                        </div>
                    </div>
                    <div>

                        <p class="text-muted mb-0">Add a new news article to your website</p>
                    </div>
                </div>
                <a href="{{ route('admin.news.index') }}" class="btn btn-outline-secondary px-4 py-2 rounded-2">
                    <i class="fas fa-arrow-left me-2"></i>Back to News
                </a>
            </div>
        </div>

        <div class="px-4">
            <!-- Global Error Alert -->
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Please fix the following errors:</strong>
                    </div>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row justify-content-center">
                <div class="col-lg-12 col-xl-12">
                    <div class="card border rounded-3">
                        <div class="card-body p-4">
                            <form action="{{ route('admin.news.store') }}" method="POST" enctype="multipart/form-data"
                                id="newsForm">
                                @csrf

                                <!-- Basic Information -->
                                <div class="mb-4">
                                    <h5 class="fw-semibold mb-3 text-primary">Basic Information</h5>

                                    <!-- Title -->
                                    <div class="mb-4">
                                        <label for="title" class="form-label fw-semibold">Title *</label>
                                        <input type="text"
                                            class="form-control form-control-lg @error('title') is-invalid @enderror"
                                            id="title" name="title" value="{{ old('title') }}"
                                            placeholder="Enter news title" required>
                                        @error('title')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Excerpt -->
                                    <div class="mb-4">
                                        <label for="excerpt" class="form-label fw-semibold">Excerpt</label>
                                        <textarea class="form-control @error('excerpt') is-invalid @enderror" id="excerpt" name="excerpt" rows="3"
                                            placeholder="Brief summary of the news article (optional)">{{ old('excerpt') }}</textarea>
                                        <div class="form-text">A short summary that will appear in news listings. Max 500
                                            characters.</div>
                                        @error('excerpt')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Content -->
                                    <div class="mb-4">
                                        <label for="content" class="form-label fw-semibold">Content *</label>
                                        <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content" rows="8"
                                            placeholder="Write your news content here..." required>{{ old('content') }}</textarea>
                                        @error('content')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Media Section -->
                                <div class="mb-4">
                                    <h5 class="fw-semibold mb-3 text-primary">Media</h5>

                                    <!-- Featured Image -->
                                    <div class="mb-4">
                                        <label class="form-label fw-semibold">Featured Image</label>
                                        <div class="featured-image-upload">
                                            <input type="file" class="d-none" id="featured_image" name="featured_image"
                                                accept="image/jpeg,image/jpg,image/png,image/gif,image/webp">
                                            <div class="upload-placeholder border rounded-3 p-5 text-center"
                                                onclick="document.getElementById('featured_image').click()"
                                                style="cursor: pointer;">
                                                <i class="fas fa-image fa-3x text-muted mb-3"></i>
                                                <h6 class="fw-semibold">Click to upload featured image</h6>
                                                <p class="text-muted small mb-0">Recommended size: 1200x630px • Max: 5MB</p>
                                            </div>
                                            <div class="image-preview mt-3" style="display: none;">
                                                <img src="" alt="Preview" class="rounded-3"
                                                    style="max-height: 200px;">
                                                <button type="button" class="btn btn-sm btn-outline-danger mt-2"
                                                    onclick="removeFeaturedImage()">
                                                    <i class="fas fa-trash me-1"></i>Remove
                                                </button>
                                            </div>
                                        </div>
                                        @error('featured_image')
                                            <div class="text-danger small mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Gallery Images -->
                                    <div class="mb-4">
                                        <label class="form-label fw-semibold">Gallery Images</label>
                                        <div class="gallery-upload">
                                            <input type="file" class="d-none" id="gallery_images" name="gallery_images[]"
                                                multiple accept="image/jpeg,image/jpg,image/png,image/gif,image/webp">
                                            <div class="upload-placeholder border rounded-3 p-5 text-center"
                                                onclick="document.getElementById('gallery_images').click()"
                                                style="cursor: pointer;">
                                                <i class="fas fa-images fa-3x text-muted mb-3"></i>
                                                <h6 class="fw-semibold">Click to upload gallery images</h6>
                                                <p class="text-muted small mb-0">You can select multiple images • Max 10
                                                    images • 5MB each</p>
                                            </div>
                                            <div class="gallery-preview mt-3" style="display: none;">
                                                <div class="row g-2" id="galleryPreview"></div>
                                            </div>
                                        </div>
                                        @error('gallery_images')
                                            <div class="text-danger small mt-2">{{ $message }}</div>
                                        @enderror
                                        @error('gallery_images.*')
                                            <div class="text-danger small mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Settings -->
                                <div class="mb-4">
                                    <h5 class="fw-semibold mb-3 text-primary">Settings</h5>

                                    <div class="row g-3">
                                        <!-- Author -->
                                        <div class="col-md-6">
                                            <label for="author" class="form-label fw-semibold">Author</label>
                                            <input type="text"
                                                class="form-control @error('author') is-invalid @enderror" id="author"
                                                name="author" value="{{ old('author', auth()->user()->name) }}"
                                                placeholder="Article author">
                                            @error('author')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Publication Date -->
                                        <div class="col-md-6">
                                            <label for="published_at" class="form-label fw-semibold">Publication
                                                Date</label>
                                            <input type="datetime-local"
                                                class="form-control @error('published_at') is-invalid @enderror"
                                                id="published_at" name="published_at" value="{{ old('published_at') }}">
                                            <div class="form-text">Schedule for future publication</div>
                                            @error('published_at')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Status Switches -->
                                        <div class="col-12">
                                            <div class="d-flex flex-wrap gap-4">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" name="is_published"
                                                        value="1" id="is_published"
                                                        {{ old('is_published') ? 'checked' : '' }}>
                                                    <label class="form-check-label fw-semibold" for="is_published">
                                                        Publish Immediately
                                                    </label>
                                                </div>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" name="is_featured"
                                                        value="1" id="is_featured"
                                                        {{ old('is_featured') ? 'checked' : '' }}>
                                                    <label class="form-check-label fw-semibold" for="is_featured">
                                                        Feature Article
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="border-top pt-4 mt-4">
                                    <div class="d-flex flex-column flex-sm-row justify-content-end gap-3">
                                        <a href="{{ route('admin.news.index') }}"
                                            class="btn btn-outline-secondary px-4 py-2 rounded-2 order-2 order-sm-1">
                                            <i class="fas fa-times me-2"></i>Cancel
                                        </a>
                                        <button type="submit"
                                            class="btn btn-primary px-4 py-2 rounded-2 order-1 order-sm-2">
                                            <i class="fas fa-plus me-2"></i>Create News Article
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .card {
            border: 1px solid #e9ecef;
            box-shadow: none;
        }

        .form-control-lg {
            padding: 12px 16px;
            font-size: 1.1rem;
            border: 1px solid #e9ecef;
        }

        .form-control:focus {
            border-color: #BC450D;
            box-shadow: 0 0 0 0.2rem rgba(188, 69, 13, 0.1);
        }

        .form-check-input:checked {
            background-color: #BC450D;
            border-color: #BC450D;
        }

        .upload-placeholder {
            transition: all 0.3s ease;
            cursor: pointer;
            background: #f8f9fa;
            border: 2px dashed #dee2e6 !important;
        }

        .upload-placeholder:hover {
            border-color: #BC450D !important;
            background: rgba(188, 69, 13, 0.02);
            transform: translateY(-1px);
        }

        .upload-placeholder:active {
            transform: scale(0.98);
        }

        .image-preview img {
            max-width: 100%;
            display: block;
        }

        .gallery-preview .preview-item {
            position: relative;
            border-radius: 8px;
            overflow: hidden;
            border: 2px solid #e9ecef;
        }

        .gallery-preview .preview-item img {
            width: 100%;
            height: 120px;
            object-fit: cover;
            display: block;
        }

        .gallery-preview .remove-btn {
            position: absolute;
            top: 5px;
            right: 5px;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: rgba(220, 53, 69, 0.95);
            color: white;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
            cursor: pointer;
            opacity: 0;
            transition: opacity 0.2s ease;
            z-index: 10;
        }

        .gallery-preview .preview-item:hover .remove-btn {
            opacity: 1;
        }

        .gallery-preview .remove-btn:hover {
            background: rgba(220, 53, 69, 1);
            transform: scale(1.1);
        }

        .btn {
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: #BC450D;
            border-color: #BC450D;
        }

        .btn-primary:hover {
            background: #9a380a;
            border-color: #9a380a;
            transform: translateY(-1px);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .admin-header {
                padding: 20px 16px;
            }

            .px-4 {
                padding-left: 16px !important;
                padding-right: 16px !important;
            }

            .card-body {
                padding: 20px !important;
            }

            .upload-placeholder {
                padding: 30px 20px !important;
            }
        }

        @media (max-width: 576px) {
            .form-control-lg {
                font-size: 1rem;
            }

            .d-flex.flex-wrap.gap-4 {
                gap: 20px !important;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Define galleryFiles in the global scope
        let galleryFiles = [];

        document.addEventListener('DOMContentLoaded', function() {
            // Featured image handling
            const featuredInput = document.getElementById('featured_image');
            const featuredPreview = document.querySelector('.featured-image-upload .image-preview');
            const featuredPreviewImg = featuredPreview.querySelector('img');
            const featuredPlaceholder = document.querySelector('.featured-image-upload .upload-placeholder');

            featuredInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    // Validate file size (5MB)
                    if (file.size > 5 * 1024 * 1024) {
                        alert('Featured image must be less than 5MB');
                        featuredInput.value = '';
                        return;
                    }

                    // Validate file type
                    const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
                    if (!validTypes.includes(file.type)) {
                        alert('Please upload a valid image file (JPEG, PNG, GIF, or WebP)');
                        featuredInput.value = '';
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = function(e) {
                        featuredPreviewImg.src = e.target.result;
                        featuredPlaceholder.style.display = 'none';
                        featuredPreview.style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                }
            });

            // Gallery images handling
            const galleryInput = document.getElementById('gallery_images');
            const galleryPreview = document.querySelector('.gallery-preview');
            const galleryPlaceholder = document.querySelector('.gallery-upload .upload-placeholder');

            galleryInput.addEventListener('change', function(e) {
                const files = Array.from(e.target.files);

                // Validate file count
                if (galleryFiles.length + files.length > 10) {
                    alert('You can only upload a maximum of 10 gallery images.');
                    return;
                }

                let validFiles = [];

                files.forEach(file => {
                    // Validate file size (5MB)
                    if (file.size > 5 * 1024 * 1024) {
                        alert(`${file.name} is too large. Maximum size is 5MB`);
                        return;
                    }

                    // Validate file type
                    const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif',
                        'image/webp'
                    ];
                    if (!validTypes.includes(file.type)) {
                        alert(`${file.name} is not a valid image file`);
                        return;
                    }

                    validFiles.push(file);
                });

                if (validFiles.length === 0) {
                    galleryInput.value = '';
                    return;
                }

                let processedCount = 0;
                validFiles.forEach(file => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        galleryFiles.push({
                            file: file,
                            preview: e.target.result
                        });

                        processedCount++;
                        if (processedCount === validFiles.length) {
                            updateGalleryPreview();

                            if (galleryFiles.length > 0) {
                                galleryPlaceholder.style.display = 'none';
                                galleryPreview.style.display = 'block';
                            }
                        }
                    };
                    reader.readAsDataURL(file);
                });
            });

            // Set current datetime for publication date
            const now = new Date();
            now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
            document.getElementById('published_at').value = now.toISOString().slice(0, 16);

            // Form validation
            document.getElementById('newsForm').addEventListener('submit', function(e) {
                const title = document.getElementById('title').value.trim();
                const content = document.getElementById('content').value.trim();

                if (!title) {
                    e.preventDefault();
                    alert('Please enter a news title.');
                    document.getElementById('title').focus();
                    return;
                }

                if (!content) {
                    e.preventDefault();
                    alert('Please enter news content.');
                    document.getElementById('content').focus();
                    return;
                }

                // Update gallery input with current files
                if (galleryFiles.length > 0) {
                    const dt = new DataTransfer();
                    galleryFiles.forEach(item => dt.items.add(item.file));
                    document.getElementById('gallery_images').files = dt.files;
                }
            });
        });

        // Update gallery preview
        function updateGalleryPreview() {
            const previewGrid = document.getElementById('galleryPreview');
            previewGrid.innerHTML = '';

            galleryFiles.forEach((item, index) => {
                const col = document.createElement('div');
                col.className = 'col-6 col-sm-4 col-md-3 col-lg-2';
                col.innerHTML = `
                <div class="preview-item">
                    <img src="${item.preview}" alt="Gallery preview ${index + 1}">
                    <button type="button" class="remove-btn" onclick="removeGalleryImage(${index})">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
                previewGrid.appendChild(col);
            });
        }

        // Remove featured image
        function removeFeaturedImage() {
            const featuredInput = document.getElementById('featured_image');
            const featuredPreview = document.querySelector('.featured-image-upload .image-preview');
            const featuredPlaceholder = document.querySelector('.featured-image-upload .upload-placeholder');

            featuredInput.value = '';
            featuredPreview.style.display = 'none';
            featuredPlaceholder.style.display = 'block';
        }

        // Remove gallery image
        function removeGalleryImage(index) {
            galleryFiles.splice(index, 1);
            updateGalleryPreview();

            if (galleryFiles.length === 0) {
                document.querySelector('.gallery-upload .upload-placeholder').style.display = 'block';
                document.querySelector('.gallery-preview').style.display = 'none';
                document.getElementById('gallery_images').value = '';
            } else {
                // Update the file input
                const dt = new DataTransfer();
                galleryFiles.forEach(item => dt.items.add(item.file));
                document.getElementById('gallery_images').files = dt.files;
            }
        }
    </script>
@endpush
