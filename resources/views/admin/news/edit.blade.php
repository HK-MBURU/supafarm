@extends('layouts.admin')

@section('title', 'Edit ' . $news->title . ' - SupaFarm Admin')
@section('page-title', 'Edit News Article')

@section('content')
<div class="container-fluid px-0">
    <!-- Header -->
    <div class="admin-header bg-white border-bottom px-4 py-3 mb-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
            <div class="d-flex align-items-center">
                <div class="me-3">
                    <div class="bg-primary bg-opacity-10 rounded-3 p-3">
                        <i class="fas fa-edit fa-lg text-primary"></i>
                    </div>
                </div>
                <div>
                   
                    <p class="text-muted mb-0">Update your news article content and settings</p>
                </div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.news.show', $news) }}" class="btn btn-outline-primary px-4 py-2 rounded-2">
                    <i class="fas fa-eye me-2"></i>View Article
                </a>
                <a href="{{ route('admin.news.index') }}" class="btn btn-outline-secondary px-4 py-2 rounded-2">
                    <i class="fas fa-arrow-left me-2"></i>Back to News
                </a>
            </div>
        </div>
    </div>

    <div class="px-4">
        <!-- Global Error Alert -->
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Please fix the following errors:</strong>
                </div>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
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
                        <form action="{{ route('admin.news.update', $news) }}" method="POST" enctype="multipart/form-data" id="newsForm">
                            @csrf
                            @method('PUT')

                            <!-- Basic Information -->
                            <div class="mb-4">
                                <h5 class="fw-semibold mb-3 text-primary">Basic Information</h5>
                                
                                <!-- Title -->
                                <div class="mb-4">
                                    <label for="title" class="form-label fw-semibold">Title *</label>
                                    <input type="text" 
                                           class="form-control form-control-lg @error('title') is-invalid @enderror" 
                                           id="title" 
                                           name="title" 
                                           value="{{ old('title', $news->title) }}" 
                                           placeholder="Enter news title" 
                                           required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Excerpt -->
                                <div class="mb-4">
                                    <label for="excerpt" class="form-label fw-semibold">Excerpt</label>
                                    <textarea class="form-control @error('excerpt') is-invalid @enderror" 
                                              id="excerpt" 
                                              name="excerpt" 
                                              rows="3" 
                                              placeholder="Brief summary of the news article (optional)">{{ old('excerpt', $news->excerpt) }}</textarea>
                                    <div class="form-text">A short summary that will appear in news listings. Max 500 characters.</div>
                                    @error('excerpt')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Content -->
                                <div class="mb-4">
                                    <label for="content" class="form-label fw-semibold">Content *</label>
                                    <textarea class="form-control @error('content') is-invalid @enderror" 
                                              id="content" 
                                              name="content" 
                                              rows="8" 
                                              placeholder="Write your news content here..." 
                                              required>{{ old('content', $news->content) }}</textarea>
                                    @error('content')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Media Section -->
                            <div class="mb-4">
                                <h5 class="fw-semibold mb-3 text-primary">Media</h5>
                                
                                <!-- Current Featured Image -->
                                @if($news->featured_image)
                                <div class="mb-4">
                                    <label class="form-label fw-semibold">Current Featured Image</label>
                                    <div class="current-featured-image">
                                        <img src="{{ $news->featured_image_url }}" 
                                             alt="Current featured image" 
                                             class="rounded-3"
                                             style="max-height: 200px;">
                                        <div class="mt-2">
                                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeCurrentFeaturedImage()">
                                                <i class="fas fa-trash me-1"></i>Remove Current Image
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <!-- New Featured Image -->
                                <div class="mb-4">
                                    <label class="form-label fw-semibold">{{ $news->featured_image ? 'Replace Featured Image' : 'Featured Image' }}</label>
                                    <div class="featured-image-upload position-relative">
                                        <input type="file" 
                                               class="featured-image-input" 
                                               id="featured_image" 
                                               name="featured_image" 
                                               accept=".jpeg,.jpg,.png,.gif,.webp">
                                        <div class="upload-placeholder border rounded-3 p-5 text-center position-relative">
                                            <i class="fas fa-image fa-3x text-muted mb-3"></i>
                                            <h6 class="fw-semibold">Click to upload featured image</h6>
                                            <p class="text-muted small mb-0">Recommended size: 1200x630px • Max: 5MB</p>
                                        </div>
                                        <div class="image-preview mt-3" style="display: none;">
                                            <img src="" alt="Preview" class="rounded-3" style="max-height: 200px;">
                                            <button type="button" class="btn btn-sm btn-outline-danger mt-2" onclick="removeFeaturedImage()">
                                                <i class="fas fa-trash me-1"></i>Remove
                                            </button>
                                        </div>
                                    </div>
                                    @error('featured_image')
                                        <div class="text-danger small mt-2">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Current Gallery Images -->
                                @if($news->gallery_images && count($news->gallery_images) > 0)
                                <div class="mb-4">
                                    <label class="form-label fw-semibold">Current Gallery Images</label>
                                    <div class="current-gallery">
                                        <div class="row g-2">
                                            @foreach($news->gallery_image_urls as $index => $imageUrl)
                                            <div class="col-6 col-sm-4 col-md-3">
                                                <div class="gallery-item position-relative">
                                                    <img src="{{ $imageUrl }}" 
                                                         alt="Gallery image {{ $loop->iteration }}"
                                                         class="rounded-3"
                                                         style="width: 100%; height: 100px; object-fit: cover;">
                                                    <button type="button" 
                                                            class="btn btn-sm btn-outline-danger position-absolute top-0 end-0 m-1"
                                                            onclick="removeGalleryImage({{ $index }})">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <!-- New Gallery Images -->
                                <div class="mb-4">
                                    <label class="form-label fw-semibold">Add More Gallery Images</label>
                                    <div class="gallery-upload position-relative">
                                        <input type="file" 
                                               class="gallery-input" 
                                               id="gallery_images" 
                                               name="gallery_images[]" 
                                               multiple 
                                               accept=".jpeg,.jpg,.png,.gif,.webp">
                                        <div class="upload-placeholder border rounded-3 p-5 text-center position-relative">
                                            <i class="fas fa-images fa-3x text-muted mb-3"></i>
                                            <h6 class="fw-semibold">Click to upload gallery images</h6>
                                            <p class="text-muted small mb-0">You can select multiple images • Max 10 images • 5MB each</p>
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
                                               class="form-control @error('author') is-invalid @enderror" 
                                               id="author" 
                                               name="author" 
                                               value="{{ old('author', $news->author) }}" 
                                               placeholder="Article author">
                                        @error('author')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Publication Date -->
                                    <div class="col-md-6">
                                        <label for="published_at" class="form-label fw-semibold">Publication Date</label>
                                        <input type="datetime-local" 
                                               class="form-control @error('published_at') is-invalid @enderror" 
                                               id="published_at" 
                                               name="published_at" 
                                               value="{{ old('published_at', $news->published_at ? $news->published_at->format('Y-m-d\TH:i') : '') }}">
                                        <div class="form-text">Schedule for future publication</div>
                                        @error('published_at')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Status Switches -->
                                    <div class="col-12">
                                        <div class="d-flex flex-wrap gap-4">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" name="is_published" value="1" id="is_published" {{ old('is_published', $news->is_published) ? 'checked' : '' }}>
                                                <label class="form-check-label fw-semibold" for="is_published">
                                                    Publish Article
                                                </label>
                                            </div>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="is_featured" {{ old('is_featured', $news->is_featured) ? 'checked' : '' }}>
                                                <label class="form-check-label fw-semibold" for="is_featured">
                                                    Feature Article
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Article Statistics -->
                            <div class="mb-4">
                                <h5 class="fw-semibold mb-3 text-primary">Article Statistics</h5>
                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <div class="stat-item border rounded-3 p-3 text-center">
                                            <div class="stat-value text-primary fw-bold">{{ $news->views }}</div>
                                            <div class="stat-label text-muted small">Total Views</div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="stat-item border rounded-3 p-3 text-center">
                                            <div class="stat-value text-info fw-bold">{{ $news->read_time }}</div>
                                            <div class="stat-label text-muted small">Read Time (min)</div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="stat-item border rounded-3 p-3 text-center">
                                            <div class="stat-value text-success fw-bold">{{ $news->created_at->format('M j, Y') }}</div>
                                            <div class="stat-label text-muted small">Created Date</div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="stat-item border rounded-3 p-3 text-center">
                                            <div class="stat-value text-warning fw-bold">{{ $news->updated_at->format('M j, Y') }}</div>
                                            <div class="stat-label text-muted small">Last Updated</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="border-top pt-4 mt-4">
                                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3">
                                    <div>
                                        <a href="{{ route('admin.news.show', $news) }}" class="btn btn-outline-secondary px-4 py-2 rounded-2">
                                            <i class="fas fa-times me-2"></i>Cancel
                                        </a>
                                    </div>
                                    <div class="d-flex gap-3">
                                        <button type="submit" class="btn btn-primary px-4 py-2 rounded-2">
                                            <i class="fas fa-save me-2"></i>Update Article
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Hidden form for removing current featured image -->
<form id="removeFeaturedImageForm" action="{{ route('admin.news.update', $news) }}" method="POST" style="display: none;">
    @csrf
    @method('PUT')
    <input type="hidden" name="remove_featured_image" value="1">
</form>
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
}

.upload-placeholder:hover {
    border-color: #BC450D;
    background: rgba(188, 69, 13, 0.02);
}

.featured-image-input,
.gallery-input {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    cursor: pointer;
}

.current-featured-image img {
    border: 2px solid #e9ecef;
}

.gallery-item .btn {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.7rem;
}

.gallery-preview .preview-item {
    position: relative;
    border-radius: 8px;
    overflow: hidden;
    border: 1px solid #e9ecef;
}

.gallery-preview .preview-item img {
    width: 100%;
    height: 80px;
    object-fit: cover;
}

.gallery-preview .remove-btn {
    position: absolute;
    top: 4px;
    right: 4px;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: rgba(220, 53, 69, 0.9);
    color: white;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.6rem;
    cursor: pointer;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.gallery-preview .preview-item:hover .remove-btn {
    opacity: 1;
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

.stat-item {
    transition: all 0.3s ease;
}

.stat-item:hover {
    border-color: #BC450D;
}

.stat-value {
    font-size: 1.5rem;
    line-height: 1;
    margin-bottom: 0.25rem;
}

.stat-label {
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
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
    
    .admin-header .d-flex.gap-2 {
        flex-direction: column;
        width: 100%;
    }
    
    .admin-header .btn {
        width: 100%;
    }
}

@media (max-width: 576px) {
    .form-control-lg {
        font-size: 1rem;
    }
    
    .d-flex.flex-wrap.gap-4 {
        gap: 20px !important;
    }
    
    .stat-value {
        font-size: 1.25rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
let galleryFiles = [];

document.addEventListener('DOMContentLoaded', function() {
    // Featured image handling
    const featuredInput = document.getElementById('featured_image');
    const featuredPreview = document.querySelector('.image-preview');
    const featuredPreviewImg = featuredPreview.querySelector('img');
    const featuredPlaceholder = document.querySelector('.featured-image-upload .upload-placeholder');

    // Make the placeholder clickable for featured image
    featuredPlaceholder.addEventListener('click', function() {
        featuredInput.click();
    });

    featuredInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
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

    // Make the placeholder clickable for gallery images
    galleryPlaceholder.addEventListener('click', function() {
        galleryInput.click();
    });

    galleryInput.addEventListener('change', function(e) {
        const files = Array.from(e.target.files);
        
        // Calculate total images (current + new)
        const currentGalleryCount = {{ $news->gallery_images ? count($news->gallery_images) : 0 }};
        if (currentGalleryCount + galleryFiles.length + files.length > 10) {
            alert('You can only have maximum 10 gallery images in total.');
            return;
        }

        files.forEach(file => {
            const reader = new FileReader();
            reader.onload = function(e) {
                galleryFiles.push({
                    file: file,
                    preview: e.target.result
                });
                updateGalleryPreview();
            };
            reader.readAsDataURL(file);
        });

        if (galleryFiles.length > 0) {
            galleryPlaceholder.style.display = 'none';
            galleryPreview.style.display = 'block';
        }
    });

    // Make the entire upload area clickable
    document.querySelectorAll('.upload-placeholder').forEach(placeholder => {
        placeholder.style.cursor = 'pointer';
    });

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
    });
});

function updateGalleryPreview() {
    const previewGrid = document.getElementById('galleryPreview');
    previewGrid.innerHTML = '';

    galleryFiles.forEach((item, index) => {
        const col = document.createElement('div');
        col.className = 'col-6 col-sm-4 col-md-3';
        col.innerHTML = `
            <div class="preview-item">
                <img src="${item.preview}" alt="Gallery preview">
                <button type="button" class="remove-btn" onclick="removeNewGalleryImage(${index})">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        previewGrid.appendChild(col);
    });
}

// Remove current featured image
function removeCurrentFeaturedImage() {
    if (confirm('Are you sure you want to remove the current featured image? This action cannot be undone.')) {
        document.getElementById('removeFeaturedImageForm').submit();
    }
}

// Remove new featured image preview
function removeFeaturedImage() {
    const featuredInput = document.getElementById('featured_image');
    const featuredPreview = document.querySelector('.image-preview');
    const featuredPlaceholder = document.querySelector('.featured-image-upload .upload-placeholder');

    featuredInput.value = '';
    featuredPreview.style.display = 'none';
    featuredPlaceholder.style.display = 'block';
}

// Remove existing gallery image
function removeGalleryImage(index) {
    if (confirm('Are you sure you want to remove this gallery image? This action cannot be undone.')) {
        // This would typically be handled via an AJAX request to remove the specific image
        // For now, we'll just reload the page to show the updated state
        window.location.href = '{{ route("admin.news.edit", $news) }}?remove_gallery=' + index;
    }
}

// Remove new gallery image from preview
function removeNewGalleryImage(index) {
    galleryFiles.splice(index, 1);
    updateGalleryPreview();

    if (galleryFiles.length === 0) {
        document.querySelector('.gallery-upload .upload-placeholder').style.display = 'block';
        document.querySelector('.gallery-preview').style.display = 'none';
    }

    // Update the file input
    const dt = new DataTransfer();
    galleryFiles.forEach(item => dt.items.add(item.file));
    document.getElementById('gallery_images').files = dt.files;
}
</script>
@endpush