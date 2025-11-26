@extends('layouts.admin')

@section('title', $media->title . ' - SupaFarm Admin')
@section('page-title', 'Media Details')

@section('content')
<div class="container-fluid">
    <!-- Back Button -->
    <div class="row mb-4">
        <div class="col-12">
            <a href="{{ route('admin.media.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Media Library
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Media Preview Section -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent border-0">
                    <h5 class="card-title mb-0">Media Preview</h5>
                </div>
                <div class="card-body">
                    @if($media->type === 'image')
                        <!-- Image Preview -->
                        <div class="text-center">
                            <img src="{{ $media->file_url }}" 
                                 alt="{{ $media->title }}" 
                                 class="img-fluid rounded-3 shadow-sm"
                                 style="max-height: 600px; object-fit: contain;">
                        </div>
                    @elseif($media->type === 'video')
                        <!-- Video Preview -->
                        <div class="text-center">
                            <video controls 
                                   class="rounded-3 shadow-sm" 
                                   style="max-width: 100%; max-height: 600px;">
                                <source src="{{ $media->file_url }}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        </div>
                    @elseif($media->type === 'youtube')
                        <!-- YouTube Embed -->
                        <div class="ratio ratio-16x9">
                            <iframe src="{{ $media->youtube_embed_url }}" 
                                    title="{{ $media->title }}"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                    allowfullscreen
                                    class="rounded-3 shadow-sm">
                            </iframe>
                        </div>
                    @elseif($media->type === 'tiktok')
                        <!-- TikTok Preview -->
                        <div class="text-center">
                            <div class="tiktok-preview bg-light rounded-3 p-4 shadow-sm">
                                <i class="fab fa-tiktok fa-4x text-dark mb-3"></i>
                                <h6 class="fw-semibold">TikTok Video</h6>
                                <p class="text-muted mb-3">{{ $media->title }}</p>
                                <a href="{{ $media->video_url }}" 
                                   target="_blank" 
                                   class="btn btn-dark btn-sm">
                                    <i class="fab fa-tiktok me-1"></i>View on TikTok
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Media Details Section -->
        <div class="col-lg-4">
            <!-- Basic Information Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent border-0">
                    <h5 class="card-title mb-0">Media Information</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <!-- Title -->
                        <div class="col-12">
                            <label class="form-label fw-semibold text-muted small mb-1">Title</label>
                            <p class="mb-0 fs-6">{{ $media->title }}</p>
                        </div>

                        <!-- Description -->
                        <div class="col-12">
                            <label class="form-label fw-semibold text-muted small mb-1">Description</label>
                            <p class="mb-0 fs-6">{{ $media->description ?: 'No description provided' }}</p>
                        </div>

                        <!-- Type -->
                        <div class="col-12">
                            <label class="form-label fw-semibold text-muted small mb-1">Media Type</label>
                            <div class="d-flex align-items-center">
                                @if($media->type === 'image')
                                    <span class="badge bg-primary me-2">
                                        <i class="fas fa-image me-1"></i>Image
                                    </span>
                                @elseif($media->type === 'video')
                                    <span class="badge bg-success me-2">
                                        <i class="fas fa-video me-1"></i>Video
                                    </span>
                                @elseif($media->type === 'youtube')
                                    <span class="badge bg-danger me-2">
                                        <i class="fab fa-youtube me-1"></i>YouTube
                                    </span>
                                @elseif($media->type === 'tiktok')
                                    <span class="badge bg-dark me-2">
                                        <i class="fab fa-tiktok me-1"></i>TikTok
                                    </span>
                                @endif
                                <span class="fs-6">{{ $media->display_type }}</span>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="col-12">
                            <label class="form-label fw-semibold text-muted small mb-1">Status</label>
                            <div>
                                @if($media->is_active)
                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle me-1"></i>Active
                                    </span>
                                @else
                                    <span class="badge bg-secondary">
                                        <i class="fas fa-times-circle me-1"></i>Inactive
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Order -->
                        <div class="col-12">
                            <label class="form-label fw-semibold text-muted small mb-1">Display Order</label>
                            <p class="mb-0 fs-6">{{ $media->order }}</p>
                        </div>

                        <!-- File Information for Images/Videos -->
                        @if(in_array($media->type, ['image', 'video']))
                            <div class="col-12">
                                <label class="form-label fw-semibold text-muted small mb-1">File Information</label>
                                <div class="bg-light rounded p-3">
                                    @if($media->file_path)
                                        <div class="mb-2">
                                            <strong>Path:</strong> 
                                            <span class="text-muted">{{ $media->file_path }}</span>
                                        </div>
                                    @endif
                                    @if($media->file_size)
                                        <div class="mb-2">
                                            <strong>Size:</strong> 
                                            <span class="text-muted">{{ $media->file_size }}</span>
                                        </div>
                                    @endif
                                    @if($media->file_extension)
                                        <div class="mb-2">
                                            <strong>Format:</strong> 
                                            <span class="text-muted text-uppercase">{{ $media->file_extension }}</span>
                                        </div>
                                    @endif
                                    @if($media->file_url)
                                        <div>
                                            <a href="{{ $media->file_url }}" 
                                               target="_blank" 
                                               class="btn btn-outline-primary btn-sm w-100">
                                                <i class="fas fa-external-link-alt me-1"></i>View Original
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Link Information for YouTube/TikTok -->
                        @if(in_array($media->type, ['youtube', 'tiktok']))
                            <div class="col-12">
                                <label class="form-label fw-semibold text-muted small mb-1">Video Information</label>
                                <div class="bg-light rounded p-3">
                                    @if($media->youtube_url)
                                        <div class="mb-2">
                                            <strong>URL:</strong> 
                                            <a href="{{ $media->youtube_url }}" 
                                               target="_blank" 
                                               class="text-decoration-none">
                                                {{ Str::limit($media->youtube_url, 40) }}
                                            </a>
                                        </div>
                                    @endif
                                    @if($media->youtube_id)
                                        <div class="mb-3">
                                            <strong>Video ID:</strong> 
                                            <span class="text-muted">{{ $media->youtube_id }}</span>
                                        </div>
                                    @endif
                                    @if($media->youtube_url)
                                        <div>
                                            <a href="{{ $media->youtube_url }}" 
                                               target="_blank" 
                                               class="btn btn-outline-primary btn-sm w-100">
                                                <i class="fas fa-external-link-alt me-1"></i>View on Platform
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Thumbnail Information -->
                        @if($media->thumbnail_path)
                            <div class="col-12">
                                <label class="form-label fw-semibold text-muted small mb-1">Thumbnail</label>
                                <div class="text-center">
                                    <img src="{{ $media->thumbnail_url }}" 
                                         alt="Thumbnail" 
                                         class="img-thumbnail rounded"
                                         style="max-height: 120px;">
                                    @if($media->thumbnail_path && !filter_var($media->thumbnail_path, FILTER_VALIDATE_URL))
                                        <div class="mt-2">
                                            <small class="text-muted">{{ $media->thumbnail_path }}</small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Timeline Card -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0">
                    <h5 class="card-title mb-0">Timeline</h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <h6 class="fw-semibold mb-1">Created</h6>
                                @if($media->created_at)
                                    <p class="text-muted small mb-0">
                                        {{ $media->created_at->format('M j, Y \a\t g:i A') }}
                                    </p>
                                    <small class="text-muted">{{ $media->created_at->diffForHumans() }}</small>
                                @else
                                    <p class="text-muted small mb-0">Not available</p>
                                @endif
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-marker bg-info"></div>
                            <div class="timeline-content">
                                <h6 class="fw-semibold mb-1">Last Updated</h6>
                                @if($media->updated_at)
                                    <p class="text-muted small mb-0">
                                        {{ $media->updated_at->format('M j, Y \a\t g:i A') }}
                                    </p>
                                    <small class="text-muted">{{ $media->updated_at->diffForHumans() }}</small>
                                @else
                                    <p class="text-muted small mb-0">Not available</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.media.edit', $media) }}" 
                           class="btn btn-primary">
                            <i class="fas fa-edit me-2"></i>Edit Media
                        </a>
                        
                        <form action="{{ route('admin.media.toggle-status', $media) }}" 
                              method="POST" 
                              class="d-grid">
                            @csrf
                            @method('PATCH')
                            <button type="submit" 
                                    class="btn {{ $media->is_active ? 'btn-warning' : 'btn-success' }}">
                                <i class="fas {{ $media->is_active ? 'fa-eye-slash' : 'fa-eye' }} me-2"></i>
                                {{ $media->is_active ? 'Deactivate' : 'Activate' }}
                            </button>
                        </form>

                        <div class="btn-group" role="group">
                            <a href="{{ route('admin.media.index') }}" 
                               class="btn btn-outline-secondary">
                                <i class="fas fa-list me-2"></i>All Media
                            </a>
                            <button type="button" 
                                    class="btn btn-outline-danger" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#deleteModal">
                                <i class="fas fa-trash me-2"></i>Delete
                            </button>
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
                <p>Are you sure you want to delete this media item?</p>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Warning:</strong> This action cannot be undone. 
                    @if(in_array($media->type, ['image', 'video']))
                        The associated file will also be permanently deleted from storage.
                    @endif
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('admin.media.destroy', $media) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-2"></i>Delete Media
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -30px;
    top: 5px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
}

.timeline-content {
    padding-bottom: 10px;
}

.tiktok-preview {
    border: 2px dashed #dee2e6;
}

.img-thumbnail {
    border: 1px solid #e9ecef;
    padding: 4px;
}

.ratio-16x9 {
    border-radius: 12px;
    overflow: hidden;
}

.btn-group .btn {
    border-radius: 8px;
}
</style>
@endpush

@push('scripts')
<script>
// Auto-play YouTube video when modal is shown (optional)
document.addEventListener('DOMContentLoaded', function() {
    const deleteModal = document.getElementById('deleteModal');
    
    deleteModal.addEventListener('show.bs.modal', function () {
        // Pause any playing videos when delete modal opens
        const videos = document.querySelectorAll('video');
        videos.forEach(video => {
            video.pause();
        });
    });
});

// Add confirmation for status toggle
document.addEventListener('DOMContentLoaded', function() {
    const statusForm = document.querySelector('form[action*="toggle-status"]');
    if (statusForm) {
        statusForm.addEventListener('submit', function(e) {
            const isActive = {{ $media->is_active ? 'true' : 'false' }};
            const action = isActive ? 'deactivate' : 'activate';
            
            if (!confirm(`Are you sure you want to ${action} this media item?`)) {
                e.preventDefault();
            }
        });
    }
});
</script>
@endpush