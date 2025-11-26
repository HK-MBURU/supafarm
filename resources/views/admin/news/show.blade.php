@extends('layouts.admin')

@section('title', $news->title . ' - SupaFarm Admin')
@section('page-title', 'News Details')

@section('content')
<div class="container-fluid px-0">
    <!-- Header -->
    <div class="admin-header bg-white border-bottom px-4 py-3 mb-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
            <div class="d-flex align-items-center">
                <div class="me-3">
                    <div class="bg-primary bg-opacity-10 rounded-3 p-3">
                        <i class="fas fa-newspaper fa-lg text-primary"></i>
                    </div>
                </div>
                <div>
                 
                    <p class="text-muted mb-0">View and manage news article</p>
                </div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.news.edit', $news) }}" class="btn btn-primary px-4 py-2 rounded-2">
                    <i class="fas fa-edit me-2"></i>Edit Article
                </a>
                <a href="{{ route('admin.news.index') }}" class="btn btn-outline-secondary px-4 py-2 rounded-2">
                    <i class="fas fa-arrow-left me-2"></i>Back to News
                </a>
            </div>
        </div>
    </div>

    <div class="px-4">
        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Article Card -->
                <div class="card border rounded-3 mb-4">
                    <!-- Featured Image -->
                    @if($news->featured_image)
                    <div class="featured-image-container">
                        <img src="{{ $news->featured_image_url }}" 
                             alt="{{ $news->title }}" 
                             class="featured-image">
                    </div>
                    @endif

                    <div class="card-body p-4">
                        <!-- Article Header -->
                        <div class="article-header mb-4">
                            <div class="d-flex flex-wrap gap-2 mb-3">
                                @if($news->is_featured)
                                <span class="badge featured-badge">
                                    <i class="fas fa-star me-1"></i>Featured
                                </span>
                                @endif
                                @if($news->is_published)
                                <span class="badge published-badge">
                                    <i class="fas fa-check-circle me-1"></i>Published
                                </span>
                                @else
                                <span class="badge draft-badge">
                                    <i class="fas fa-edit me-1"></i>Draft
                                </span>
                                @endif
                                <span class="badge views-badge">
                                    <i class="fas fa-eye me-1"></i>{{ $news->views }} views
                                </span>
                            </div>

                            <h1 class="article-title">{{ $news->title }}</h1>

                            @if($news->excerpt)
                            <div class="article-excerpt">
                                <p class="lead text-muted">{{ $news->excerpt }}</p>
                            </div>
                            @endif

                            <!-- Article Meta -->
                            <div class="article-meta">
                                <div class="meta-grid">
                                    <div class="meta-item">
                                        <i class="fas fa-user"></i>
                                        <div>
                                            <span class="meta-label">Author</span>
                                            <span class="meta-value">{{ $news->author ?: 'Unknown' }}</span>
                                        </div>
                                    </div>
                                    <div class="meta-item">
                                        <i class="fas fa-calendar"></i>
                                        <div>
                                            <span class="meta-label">Published</span>
                                            <span class="meta-value">{{ $news->published_date }}</span>
                                        </div>
                                    </div>
                                    <div class="meta-item">
                                        <i class="fas fa-clock"></i>
                                        <div>
                                            <span class="meta-label">Read Time</span>
                                            <span class="meta-value">{{ $news->read_time }} min read</span>
                                        </div>
                                    </div>
                                    <div class="meta-item">
                                        <i class="fas fa-calendar-plus"></i>
                                        <div>
                                            <span class="meta-label">Created</span>
                                            <span class="meta-value">{{ $news->created_at->format('M j, Y \a\t g:i A') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Article Content -->
                        <div class="article-content">
                            <div class="content-body">
                                {!! nl2br(e($news->content)) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Gallery Images -->
                @if($news->gallery_images && count($news->gallery_images) > 0)
                <div class="card border rounded-3 mb-4">
                    <div class="card-header bg-transparent border-bottom p-4">
                        <h5 class="fw-semibold mb-0">Gallery Images</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="gallery-grid">
                            @foreach($news->gallery_image_urls as $imageUrl)
                            <div class="gallery-item">
                                <img src="{{ $imageUrl }}" 
                                     alt="Gallery image {{ $loop->iteration }}"
                                     class="gallery-image">
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Actions Card -->
                <div class="card border rounded-3 mb-4">
                    <div class="card-header bg-transparent border-bottom p-4">
                        <h5 class="fw-semibold mb-0">Article Actions</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="d-grid gap-3">
                            <!-- Status Toggle -->
                            <form action="{{ route('admin.news.toggle-status', $news) }}" method="POST" class="d-grid">
                                @csrf
                                @method('PATCH')
                                <button type="submit" 
                                        class="btn {{ $news->is_published ? 'btn-warning' : 'btn-success' }} py-2 rounded-2">
                                    <i class="fas {{ $news->is_published ? 'fa-eye-slash' : 'fa-eye' }} me-2"></i>
                                    {{ $news->is_published ? 'Unpublish Article' : 'Publish Article' }}
                                </button>
                            </form>

                            <!-- Featured Toggle -->
                            <form action="{{ route('admin.news.toggle-featured', $news) }}" method="POST" class="d-grid">
                                @csrf
                                @method('PATCH')
                                <button type="submit" 
                                        class="btn {{ $news->is_featured ? 'btn-secondary' : 'btn-info' }} py-2 rounded-2">
                                    <i class="fas fa-star me-2"></i>
                                    {{ $news->is_featured ? 'Remove from Featured' : 'Mark as Featured' }}
                                </button>
                            </form>

                            <!-- Edit Button -->
                            <a href="{{ route('admin.news.edit', $news) }}" 
                               class="btn btn-outline-primary py-2 rounded-2">
                                <i class="fas fa-edit me-2"></i>Edit Article
                            </a>

                            <!-- Delete Button -->
                            <button type="button" 
                                    class="btn btn-outline-danger py-2 rounded-2" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#deleteModal">
                                <i class="fas fa-trash me-2"></i>Delete Article
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Article Information -->
                <div class="card border rounded-3 mb-4">
                    <div class="card-header bg-transparent border-bottom p-4">
                        <h5 class="fw-semibold mb-0">Article Information</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="info-grid">
                            <div class="info-item">
                                <span class="info-label">Status</span>
                                <span class="info-value {{ $news->is_published ? 'text-success' : 'text-warning' }}">
                                    {{ $news->is_published ? 'Published' : 'Draft' }}
                                </span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Featured</span>
                                <span class="info-value {{ $news->is_featured ? 'text-info' : 'text-muted' }}">
                                    {{ $news->is_featured ? 'Yes' : 'No' }}
                                </span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Views</span>
                                <span class="info-value">{{ $news->views }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Read Time</span>
                                <span class="info-value">{{ $news->read_time }} minutes</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Created</span>
                                <span class="info-value">{{ $news->created_at->format('M j, Y') }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Last Updated</span>
                                <span class="info-value">{{ $news->updated_at->format('M j, Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="card border rounded-3">
                    <div class="card-header bg-transparent border-bottom p-4">
                        <h5 class="fw-semibold mb-0">Quick Actions</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="d-grid gap-2">
                            <a href="{{ route('admin.news.create') }}" class="btn btn-outline-primary rounded-2 py-2">
                                <i class="fas fa-plus me-2"></i>Create New Article
                            </a>
                            <a href="{{ route('admin.news.index') }}" class="btn btn-outline-secondary rounded-2 py-2">
                                <i class="fas fa-list me-2"></i>View All Articles
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the news article "<strong>{{ $news->title }}</strong>"?</p>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Warning:</strong> This action cannot be undone. All associated images will also be deleted.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('admin.news.destroy', $news) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-2"></i>Delete Article
                    </button>
                </form>
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

.btn {
    border-radius: 8px;
    font-weight: 500;
    border: 1px solid transparent;
}

.btn-primary {
    background: #BC450D;
    border-color: #BC450D;
}

.btn-primary:hover {
    background: #9a380a;
    border-color: #9a380a;
}

/* Featured Image */
.featured-image-container {
    width: 100%;
    overflow: hidden;
    border-bottom: 1px solid #e9ecef;
}

.featured-image {
    width: 100%;
    height: 400px;
    object-fit: cover;
    display: block;
}

/* Badges */
.badge {
    padding: 8px 12px;
    font-size: 0.75rem;
    font-weight: 600;
    border-radius: 6px;
    border: 1px solid transparent;
}

.featured-badge {
    background: rgba(23, 162, 184, 0.1);
    color: #0dcaf0;
    border-color: rgba(23, 162, 184, 0.2);
}

.published-badge {
    background: rgba(40, 167, 69, 0.1);
    color: #198754;
    border-color: rgba(40, 167, 69, 0.2);
}

.draft-badge {
    background: rgba(255, 193, 7, 0.1);
    color: #ffc107;
    border-color: rgba(255, 193, 7, 0.2);
}

.views-badge {
    background: rgba(108, 117, 125, 0.1);
    color: #6c757d;
    border-color: rgba(108, 117, 125, 0.2);
}

/* Article Header */
.article-title {
    font-size: 2rem;
    font-weight: 700;
    line-height: 1.2;
    color: #2d3748;
    margin-bottom: 1rem;
}

.article-excerpt .lead {
    font-size: 1.25rem;
    line-height: 1.6;
    color: #6c757d;
}

/* Article Meta */
.meta-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    padding: 1.5rem 0;
    border-top: 1px solid #e9ecef;
    border-bottom: 1px solid #e9ecef;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.meta-item i {
    width: 16px;
    color: #BC450D;
    font-size: 0.9rem;
}

.meta-label {
    display: block;
    font-size: 0.75rem;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 600;
}

.meta-value {
    display: block;
    font-size: 0.9rem;
    color: #2d3748;
    font-weight: 500;
}

/* Article Content */
.article-content {
    margin-top: 2rem;
}

.content-body {
    font-size: 1.1rem;
    line-height: 1.7;
    color: #4a5568;
}

.content-body p {
    margin-bottom: 1.5rem;
}

.content-body p:last-child {
    margin-bottom: 0;
}

/* Gallery */
.gallery-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 1rem;
}

.gallery-item {
    border-radius: 8px;
    overflow: hidden;
    border: 1px solid #e9ecef;
}

.gallery-image {
    width: 100%;
    height: 120px;
    object-fit: cover;
    display: block;
}

/* Info Grid */
.info-grid {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-bottom: 0.75rem;
    border-bottom: 1px solid #f8f9fa;
}

.info-item:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.info-label {
    font-size: 0.875rem;
    color: #6c757d;
    font-weight: 500;
}

.info-value {
    font-size: 0.875rem;
    font-weight: 600;
    color: #2d3748;
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
    
    .article-title {
        font-size: 1.75rem;
    }
    
    .featured-image {
        height: 250px;
    }
    
    .meta-grid {
        grid-template-columns: 1fr;
        gap: 0.75rem;
    }
    
    .gallery-grid {
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        gap: 0.75rem;
    }
}

@media (max-width: 576px) {
    .article-title {
        font-size: 1.5rem;
    }
    
    .article-excerpt .lead {
        font-size: 1.1rem;
    }
    
    .content-body {
        font-size: 1rem;
    }
    
    .admin-header .d-flex.gap-2 {
        flex-direction: column;
        width: 100%;
    }
    
    .admin-header .btn {
        width: 100%;
    }
}
</style>
@endpush