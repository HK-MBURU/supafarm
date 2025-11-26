@extends('layouts.admin')

@section('title', 'Media Library - SupaFarm Admin')
@section('page-title', 'Media Library')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-6">
          
            <p class="text-muted mb-0">Manage images, videos, and YouTube content</p>
        </div>
        <div class="col-md-6 text-md-end">
            <a href="{{ route('admin.media.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Add Media
            </a>
        </div>
    </div>

    <!-- Stats -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-muted mb-2">Total Media</h6>
                            <h3 class="fw-bold mb-0 text-primary">{{ $media->total() }}</h3>
                        </div>
                        <div class="align-self-center">
                            <div class="bg-primary bg-opacity-10 p-3 rounded">
                                <i class="fas fa-images fa-2x text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-muted mb-2">Images</h6>
                            <h3 class="fw-bold mb-0 text-success">{{ \App\Models\Media::images()->count() }}</h3>
                        </div>
                        <div class="align-self-center">
                            <div class="bg-success bg-opacity-10 p-3 rounded">
                                <i class="fas fa-image fa-2x text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-muted mb-2">Videos</h6>
                            <h3 class="fw-bold mb-0 text-info">{{ \App\Models\Media::videos()->count() }}</h3>
                        </div>
                        <div class="align-self-center">
                            <div class="bg-info bg-opacity-10 p-3 rounded">
                                <i class="fas fa-video fa-2x text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-muted mb-2">YouTube</h6>
                            <h3 class="fw-bold mb-0 text-danger">{{ \App\Models\Media::youtube()->count() }}</h3>
                        </div>
                        <div class="align-self-center">
                            <div class="bg-danger bg-opacity-10 p-3 rounded">
                                <i class="fab fa-youtube fa-2x text-danger"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Media Grid -->
    <div class="row g-4">
        @forelse($media as $item)
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card media-card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent border-0 p-0 position-relative">
                    <!-- Thumbnail -->
                    @if($item->type === 'image')
                    <img src="{{ $item->thumbnail_url }}"
                         alt="{{ $item->title }}"
                         class="card-img-top"
                         style="height: 200px; object-fit: cover;">
                    @elseif($item->type === 'video')
                    <div class="position-relative">
                        <img src="{{ $item->thumbnail_url }}"
                             alt="{{ $item->title }}"
                             class="card-img-top"
                             style="height: 200px; object-fit: cover;">
                        <div class="position-absolute top-50 start-50 translate-middle">
                            <i class="fas fa-play-circle fa-3x text-white opacity-75"></i>
                        </div>
                    </div>
                    @elseif($item->type === 'youtube')
                    <div class="position-relative">
                        <img src="{{ $item->thumbnail_url }}"
                             alt="{{ $item->title }}"
                             class="card-img-top"
                             style="height: 200px; object-fit: cover;">
                        <div class="position-absolute top-50 start-50 translate-middle">
                            <i class="fab fa-youtube fa-3x text-danger"></i>
                        </div>
                    </div>
                    @endif

                    <!-- Status Badge -->
                    <div class="position-absolute top-0 end-0 m-2">
                        @if($item->is_active)
                        <span class="badge bg-success">Active</span>
                        @else
                        <span class="badge bg-secondary">Inactive</span>
                        @endif
                    </div>

                    <!-- Type Badge -->
                    <div class="position-absolute top-0 start-0 m-2">
                        <span class="badge bg-primary">{{ $item->display_type }}</span>
                    </div>
                </div>

                <div class="card-body">
                    <h6 class="card-title fw-bold mb-2">{{ Str::limit($item->title, 50) }}</h6>
                    @if($item->description)
                    <p class="card-text text-muted small mb-3">{{ Str::limit($item->description, 80) }}</p>
                    @endif

                    <div class="d-flex justify-content-between align-items-center">
                      
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('admin.media.show', $item->id) }}"
                               class="btn btn-outline-primary" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.media.edit', $item->id) }}"
                               class="btn btn-outline-secondary" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.media.destroy', $item->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger"
                                        onclick="return confirm('Are you sure you want to delete this media item?')"
                                        title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="fas fa-images fa-4x text-muted mb-4"></i>
                    <h4 class="text-muted mb-3">No media items found</h4>
                    <p class="text-muted mb-4">Get started by uploading your first media item</p>
                    <a href="{{ route('admin.media.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Add Media
                    </a>
                </div>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($media->hasPages())
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    {{ $media->links() }}
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@push('styles')
<style>
.media-card {
    transition: all 0.3s ease;
}

.media-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
}

.card-img-top {
    border-radius: 12px 12px 0 0;
}
</style>
@endpush
