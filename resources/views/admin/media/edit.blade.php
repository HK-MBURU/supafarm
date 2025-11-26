@extends('layouts.admin')

@section('title', isset($media) ? 'Edit Media - SupaFarm Admin' : 'Add Media - SupaFarm Admin')
@section('page-title', isset($media) ? 'Edit Media' : 'Add Media')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <a href="{{ route('admin.media.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Media
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0">
                    <h5 class="card-title mb-0 fw-bold">{{ isset($media) ? 'Edit Media Item' : 'Add New Media Item' }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ isset($media) ? route('admin.media.update', $media->id) : route('admin.media.store') }}"
                          method="POST"
                          enctype="multipart/form-data">
                        @csrf
                        @if(isset($media))
                            @method('PUT')
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="title" class="form-label">Title *</label>
                                    <input type="text"
                                           class="form-control @error('title') is-invalid @enderror"
                                           id="title"
                                           name="title"
                                           value="{{ old('title', $media->title ?? '') }}"
                                           required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="type" class="form-label">Media Type *</label>
                                    <select class="form-select @error('type') is-invalid @enderror"
                                            id="type"
                                            name="type"
                                            required>
                                        <option value="">Select Type</option>
                                        <option value="image" {{ old('type', $media->type ?? '') == 'image' ? 'selected' : '' }}>Image</option>
                                        <option value="video" {{ old('type', $media->type ?? '') == 'video' ? 'selected' : '' }}>Video File</option>
                                        <option value="youtube" {{ old('type', $media->type ?? '') == 'youtube' ? 'selected' : '' }}>YouTube Video</option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description"
                                      name="description"
                                      rows="3">{{ old('description', $media->description ?? '') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- File Upload Section -->
                        <div id="fileUploadSection" class="mb-3" style="display: none;">
                            <label for="file" class="form-label">Upload File *</label>
                            <input type="file"
                                   class="form-control @error('file') is-invalid @enderror"
                                   id="file"
                                   name="file"
                                   accept=".jpeg,.jpg,.png,.gif,.webp,.mp4,.mov,.avi">
                            @error('file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                For images: JPEG, PNG, JPG, GIF, WEBP (Max: 10MB)<br>
                                For videos: MP4, MOV, AVI (Max: 10MB)
                            </div>
                        </div>

                        <!-- YouTube URL Section -->
                        <div id="youtubeSection" class="mb-3" style="display: none;">
                            <label for="youtube_url" class="form-label">YouTube URL *</label>
                            <input type="url"
                                   class="form-control @error('youtube_url') is-invalid @enderror"
                                   id="youtube_url"
                                   name="youtube_url"
                                   value="{{ old('youtube_url', $media->youtube_url ?? '') }}"
                                   placeholder="https://www.youtube.com/watch?v=...">
                            @error('youtube_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input"
                                       type="checkbox"
                                       name="is_active"
                                       value="1"
                                       id="is_active"
                                       {{ old('is_active', isset($media) ? $media->is_active : true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Active (Visible to users)
                                </label>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>
                                {{ isset($media) ? 'Update Media' : 'Create Media' }}
                            </button>
                            <a href="{{ route('admin.media.index') }}" class="btn btn-outline-secondary">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('type');
    const fileUploadSection = document.getElementById('fileUploadSection');
    const youtubeSection = document.getElementById('youtubeSection');

    function toggleSections() {
        const selectedType = typeSelect.value;

        // Hide all sections first
        fileUploadSection.style.display = 'none';
        youtubeSection.style.display = 'none';

        // Show relevant section based on type
        if (selectedType === 'image' || selectedType === 'video') {
            fileUploadSection.style.display = 'block';
        } else if (selectedType === 'youtube') {
            youtubeSection.style.display = 'block';
        }
    }

    // Initial toggle
    toggleSections();

    // Toggle on change
    typeSelect.addEventListener('change', toggleSections);
});
</script>
@endpush
