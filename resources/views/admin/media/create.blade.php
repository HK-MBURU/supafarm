@extends('layouts.admin')

@section('title', 'Add Media - SupaFarm Admin')
@section('page-title', 'Add Media')

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

        <!-- Global Error Alert -->
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
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

        <!-- Success Message -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Error Message -->
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-transparent border-0 pb-0">
                        <p class="text-muted mb-0">Upload multiple images, videos, or add YouTube/TikTok links</p>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.media.store') }}" method="POST" enctype="multipart/form-data"
                            id="mediaForm">
                            @csrf

                            <!-- Media Type Selection -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold mb-3">Media Type *</label>
                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <div class="media-type-card {{ old('type') == 'image' ? 'active' : '' }}"
                                            data-type="image">
                                            <div class="card border-2 h-100 text-center">
                                                <div class="card-body p-3">
                                                    <i class="fas fa-images fa-2x text-primary mb-2"></i>
                                                    <h6 class="fw-semibold mb-1">Images</h6>
                                                    <p class="text-muted small mb-0">Multiple images</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="media-type-card {{ old('type') == 'video' ? 'active' : '' }}"
                                            data-type="video">
                                            <div class="card border-2 h-100 text-center">
                                                <div class="card-body p-3">
                                                    <i class="fas fa-video fa-2x text-success mb-2"></i>
                                                    <h6 class="fw-semibold mb-1">Videos</h6>
                                                    <p class="text-muted small mb-0">Multiple videos</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="media-type-card {{ old('type') == 'youtube' ? 'active' : '' }}"
                                            data-type="youtube">
                                            <div class="card border-2 h-100 text-center">
                                                <div class="card-body p-3">
                                                    <i class="fab fa-youtube fa-2x text-danger mb-2"></i>
                                                    <h6 class="fw-semibold mb-1">YouTube</h6>
                                                    <p class="text-muted small mb-0">Multiple links</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="media-type-card {{ old('type') == 'tiktok' ? 'active' : '' }}"
                                            data-type="tiktok">
                                            <div class="card border-2 h-100 text-center">
                                                <div class="card-body p-3">
                                                    <i class="fab fa-tiktok fa-2x text-dark mb-2"></i>
                                                    <h6 class="fw-semibold mb-1">TikTok</h6>
                                                    <p class="text-muted small mb-0">Multiple links</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="type" id="mediaType" value="{{ old('type') }}">
                                @error('type')
                                    <div class="text-danger small mt-2">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Basic Information -->
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label for="title" class="form-label fw-semibold">Title *</label>
                                        <input type="text"
                                            class="form-control form-control-lg @error('title') is-invalid @enderror"
                                            id="title" name="title" value="{{ old('title') }}"
                                            placeholder="Enter media title" required>
                                        @error('title')
                                            <div class="invalid-feedback d-flex align-items-center">
                                                <i class="fas fa-exclamation-circle me-2"></i>{{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Status</label>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="is_active" value="1"
                                                id="is_active" {{ old('is_active', true) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_active">
                                                Active
                                            </label>
                                        </div>
                                        <small class="text-muted">Make this media visible to users</small>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="description" class="form-label fw-semibold">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                    rows="3" placeholder="Brief description of the media">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback d-flex align-items-center">
                                        <i class="fas fa-exclamation-circle me-2"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- File Upload Section -->
                            <div id="fileUploadSection" class="media-section"
                                style="display: {{ in_array(old('type'), ['image', 'video']) ? 'block' : 'none' }};">
                                <div class="mb-4">
                                    <label class="form-label fw-semibold">Upload Files *</label>
                                    <div
                                        class="file-upload-area @error('files') border-danger @enderror @error('files.*') border-danger @enderror">
                                        <input type="file" class="file-upload-input" id="file" name="files[]"
                                            multiple accept="">
                                        <div class="file-upload-placeholder">
                                            <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                                            <h6 class="fw-semibold">Drop your files here or click to browse</h6>
                                            <p class="text-muted small mb-0" id="fileRequirements"></p>
                                            <small class="text-info">
                                                <i class="fas fa-info-circle me-1"></i>
                                                You can select multiple files
                                            </small>
                                        </div>
                                        <div class="file-upload-preview" style="display: none;">
                                            <div class="preview-grid" id="previewGrid"></div>
                                            <div class="mt-3 text-end">
                                                <button type="button" class="btn btn-sm btn-outline-danger"
                                                    onclick="removeAllFiles()">
                                                    <i class="fas fa-trash me-1"></i>Remove All
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    @error('files')
                                        <div class="text-danger small mt-2 d-flex align-items-center">
                                            <i class="fas fa-exclamation-circle me-2"></i>{{ $message }}
                                        </div>
                                    @enderror
                                    @error('files.*')
                                        <div class="text-danger small mt-2 d-flex align-items-center">
                                            <i class="fas fa-exclamation-circle me-2"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Links Section -->
                            <div id="linksSection" class="media-section"
                                style="display: {{ in_array(old('type'), ['youtube', 'tiktok']) ? 'block' : 'none' }};">
                                <div class="mb-4">
                                    <label class="form-label fw-semibold" id="linksLabel">Add Links *</label>
                                    <div class="links-container @error('links') border rounded p-3 border-danger @enderror"
                                        id="linksContainer">
                                        @if (old('links') && is_array(old('links')))
                                            @foreach (old('links') as $index => $link)
                                                <div class="link-input-group mb-2">
                                                    <div class="input-group">
                                                        <span class="input-group-text bg-light" id="linkIcon">
                                                            <i
                                                                class="fab fa-{{ old('type') }} {{ old('type') == 'youtube' ? 'text-danger' : 'text-dark' }}"></i>
                                                        </span>
                                                        <input type="url"
                                                            class="form-control link-input @error('links.' . $index) is-invalid @enderror"
                                                            name="links[]" value="{{ $link }}"
                                                            placeholder="{{ old('type') == 'youtube' ? 'https://www.youtube.com/watch?v=...' : 'https://www.tiktok.com/@username/video/...' }}">
                                                        @if ($index === 0)
                                                            <button type="button" class="btn btn-outline-secondary"
                                                                onclick="addMoreLinks(this)">
                                                                <i class="fas fa-plus"></i>
                                                            </button>
                                                        @else
                                                            <button type="button" class="btn btn-outline-danger"
                                                                onclick="removeLink(this)">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        @endif
                                                        @error('links.' . $index)
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="link-input-group mb-2">
                                                <div class="input-group">
                                                    <span class="input-group-text bg-light" id="linkIcon">
                                                        <i class="fab fa-youtube text-danger"></i>
                                                    </span>
                                                    <input type="url" class="form-control link-input" name="links[]"
                                                        placeholder="Enter video URL">
                                                    <button type="button" class="btn btn-outline-secondary"
                                                        onclick="addMoreLinks(this)">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    @error('links')
                                        <div class="text-danger small mt-2 d-flex align-items-center">
                                            <i class="fas fa-exclamation-circle me-2"></i>{{ $message }}
                                        </div>
                                    @enderror
                                    <div class="mt-2">
                                        <small class="text-muted" id="linksHelp">
                                            <i class="fas fa-info-circle me-1"></i>
                                            Add multiple video links
                                        </small>
                                    </div>
                                </div>

                                <!-- Links Preview -->
                                <div id="linksPreview" class="mt-4" style="display: none;">
                                    <h6 class="fw-semibold mb-3">Preview</h6>
                                    <div class="row g-3" id="linksPreviewGrid"></div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="row mt-5">
                                <div class="col-12">
                                    <div class="d-flex gap-3 justify-content-end">
                                        <a href="{{ route('admin.media.index') }}"
                                            class="btn btn-outline-secondary px-4">
                                            <i class="fas fa-times me-2"></i>Cancel
                                        </a>
                                        <button type="submit" class="btn btn-primary px-4" id="submitButton" disabled>
                                            <i class="fas fa-plus me-2"></i>Add Media
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
@endsection

@push('styles')
    <style>
        .alert {
            border-radius: 12px;
            border: none;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .alert-danger {
            background: rgba(220, 53, 69, 0.05);
            border-left: 4px solid #dc3545;
            color: #721c24;
        }

        .alert-success {
            background: rgba(40, 167, 69, 0.05);
            border-left: 4px solid #28a745;
            color: #155724;
        }

        .invalid-feedback {
            display: flex;
            align-items: center;
            font-weight: 500;
        }

        .border-danger {
            border-color: #dc3545 !important;
            background: rgba(220, 53, 69, 0.02);
        }

        .media-type-card {
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .media-type-card .card {
            border-color: #e9ecef !important;
            transition: all 0.3s ease;
            height: 120px;
        }

        .media-type-card:hover .card {
            border-color: #BC450D !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .media-type-card.active .card {
            border-color: #BC450D !important;
            background-color: rgba(188, 69, 13, 0.05);
        }

        .file-upload-area {
            border: 2px dashed #dee2e6;
            border-radius: 12px;
            padding: 40px 20px;
            text-align: center;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            min-height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .file-upload-area:hover {
            border-color: #BC450D;
            background-color: rgba(188, 69, 13, 0.02);
        }

        .file-upload-area.dragover {
            border-color: #BC450D;
            background-color: rgba(188, 69, 13, 0.05);
        }

        .file-upload-input {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }

        .file-upload-placeholder {
            pointer-events: none;
        }

        .preview-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }

        .preview-item {
            position: relative;
            border-radius: 12px;
            overflow: hidden;
            background: #f8f9fa;
            border: 1px solid #e9ecef;
        }

        .preview-item img,
        .preview-item video {
            width: 100%;
            height: 120px;
            object-fit: cover;
            display: block;
        }

        .preview-item .preview-info {
            padding: 10px;
            background: white;
        }

        .preview-item .preview-info h6 {
            font-size: 0.8rem;
            margin-bottom: 5px;
            word-break: break-all;
        }

        .preview-item .preview-info .file-size {
            font-size: 0.7rem;
            color: #6c757d;
        }

        .preview-item .remove-btn {
            position: absolute;
            top: 5px;
            right: 5px;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: rgba(220, 53, 69, 0.9);
            color: white;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
            cursor: pointer;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .preview-item:hover .remove-btn {
            opacity: 1;
        }

        .link-preview-card {
            border: 1px solid #e9ecef;
            border-radius: 12px;
            overflow: hidden;
            background: white;
            transition: all 0.3s ease;
        }

        .link-preview-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .link-preview-card img {
            width: 100%;
            height: 120px;
            object-fit: cover;
        }

        .link-preview-card .card-body {
            padding: 15px;
        }

        .link-preview-card h6 {
            font-size: 0.9rem;
            margin-bottom: 5px;
        }

        .link-preview-card .platform-badge {
            position: absolute;
            top: 10px;
            right: 10px;
        }

        .media-section {
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-control-lg {
            padding: 12px 16px;
            font-size: 1.1rem;
        }

        .form-check-input:checked {
            background-color: #BC450D;
            border-color: #BC450D;
        }

        .link-input-group:not(:first-child) {
            margin-top: 10px;
        }

        .link-input-group .input-group .btn {
            border-radius: 0 0.375rem 0.375rem 0;
        }
    </style>
@endpush

@push('scripts')
    <script>
        const mediaTypeCards = document.querySelectorAll('.media-type-card');
        const mediaTypeInput = document.getElementById('mediaType');
        const fileUploadSection = document.getElementById('fileUploadSection');
        const linksSection = document.getElementById('linksSection');
        const fileInput = document.getElementById('file');
        const fileRequirements = document.getElementById('fileRequirements');
        const fileUploadArea = document.querySelector('.file-upload-area');
        const fileUploadPlaceholder = document.querySelector('.file-upload-placeholder');
        const fileUploadPreview = document.querySelector('.file-upload-preview');
        const previewGrid = document.getElementById('previewGrid');
        const linksContainer = document.getElementById('linksContainer');
        const linksLabel = document.getElementById('linksLabel');
        const linksHelp = document.getElementById('linksHelp');
        const linksPreview = document.getElementById('linksPreview');
        const linksPreviewGrid = document.getElementById('linksPreviewGrid');
        const submitButton = document.getElementById('submitButton');

        let selectedFiles = [];
        let linkInputs = [];

        // Media type selection
        mediaTypeCards.forEach(card => {
            card.addEventListener('click', function() {
                // Remove active class from all cards
                mediaTypeCards.forEach(c => c.classList.remove('active'));

                // Add active class to clicked card
                this.classList.add('active');

                // Set media type value
                const type = this.dataset.type;
                mediaTypeInput.value = type;

                // Show relevant section
                toggleMediaSections(type);

                // Update requirements and labels
                updateRequirements(type);

                // Reset form sections
                resetFormSections();

                // Enable submit button
                updateSubmitButton();
            });
        });

        function toggleMediaSections(type) {
            // Hide all sections
            fileUploadSection.style.display = 'none';
            linksSection.style.display = 'none';

            // Show relevant section
            if (type === 'image' || type === 'video') {
                fileUploadSection.style.display = 'block';
                updateFileInputAccept(type); // Add this line
            } else if (type === 'youtube' || type === 'tiktok') {
                linksSection.style.display = 'block';
                updateLinksSection(type);
            }
        }

        // Add this new function
        function updateFileInputAccept(type) {
            const fileInput = document.getElementById('file');

            if (type === 'image') {
                fileInput.accept = '.jpeg,.jpg,.png,.gif,.webp,.svg';
                fileInput.setAttribute('accept', '.jpeg,.jpg,.png,.gif,.webp,.svg');
            } else if (type === 'video') {
                fileInput.accept = '.mp4,.mov,.avi,.wmv';
                fileInput.setAttribute('accept', '.mp4,.mov,.avi,.wmv');
            } else {
                fileInput.accept = '';
                fileInput.removeAttribute('accept');
            }

            // Also clear any selected files when type changes
            selectedFiles = [];
            fileInput.value = '';
            updateFilePreview();
            updateSubmitButton();
        }

        function updateRequirements(type) {
            if (type === 'image') {
                fileRequirements.textContent = 'Supported formats: JPG, PNG, GIF, WEBP • Max size: 10MB per file';
            } else if (type === 'video') {
                fileRequirements.textContent = 'Supported formats: MP4, MOV, AVI • Max size: 10MB per file';
            }
        }

        function updateLinksSection(type) {
            const linkIcon = document.querySelector('#linkIcon i');
            const firstInput = linksContainer.querySelector('.link-input');

            if (type === 'youtube') {
                linksLabel.textContent = 'YouTube Links *';
                linksHelp.innerHTML = '<i class="fas fa-info-circle me-1"></i>Add multiple YouTube video links';
                linkIcon.className = 'fab fa-youtube text-danger';
                firstInput.placeholder = 'https://www.youtube.com/watch?v=... or https://youtu.be/...';
            } else if (type === 'tiktok') {
                linksLabel.textContent = 'TikTok Links *';
                linksHelp.innerHTML = '<i class="fas fa-info-circle me-1"></i>Add multiple TikTok video links';
                linkIcon.className = 'fab fa-tiktok text-dark';
                firstInput.placeholder = 'https://www.tiktok.com/@username/video/...';
            }
        }

        function resetFormSections() {
            // Reset file upload
            selectedFiles = [];
            fileInput.value = '';
            fileUploadPlaceholder.style.display = 'block';
            fileUploadPreview.style.display = 'none';
            previewGrid.innerHTML = '';

            // Reset links
            linksPreview.style.display = 'none';
            linksPreviewGrid.innerHTML = '';

            // Reset link inputs (keep first one)
            const firstInputGroup = linksContainer.querySelector('.link-input-group');
            linksContainer.innerHTML = '';
            linksContainer.appendChild(firstInputGroup);
            linkInputs = [firstInputGroup.querySelector('.link-input')];

            // Clear first input
            linkInputs[0].value = '';
        }

        // File upload handling
        fileInput.addEventListener('change', handleFileSelect);

        // Drag and drop functionality
        fileUploadArea.addEventListener('dragover', function(e) {
            e.preventDefault();
            this.classList.add('dragover');
        });

        fileUploadArea.addEventListener('dragleave', function(e) {
            e.preventDefault();
            this.classList.remove('dragover');
        });

        fileUploadArea.addEventListener('drop', function(e) {
            e.preventDefault();
            this.classList.remove('dragover');

            if (e.dataTransfer.files.length) {
                fileInput.files = e.dataTransfer.files;
                handleFileSelect();
            }
        });

        function handleFileSelect() {
            const files = Array.from(fileInput.files);
            if (!files.length) return;

            const mediaType = mediaTypeInput.value;

            // Validate files based on selected media type
            const validFiles = files.filter(file => {
                if (mediaType === 'image' && !file.type.startsWith('image/')) {
                    return false;
                }
                if (mediaType === 'video' && !file.type.startsWith('video/')) {
                    return false;
                }
                return true;
            });

            if (validFiles.length === 0) {
                alert(`Please select ${mediaType} files only.`);
                return;
            }

            // Add to selected files
            selectedFiles = [...selectedFiles, ...validFiles];

            // Update preview
            updateFilePreview();
            updateSubmitButton();
        }

        function updateFilePreview() {
            if (selectedFiles.length === 0) {
                fileUploadPlaceholder.style.display = 'block';
                fileUploadPreview.style.display = 'none';
                return;
            }

            fileUploadPlaceholder.style.display = 'none';
            fileUploadPreview.style.display = 'block';

            previewGrid.innerHTML = '';

            selectedFiles.forEach((file, index) => {
                const previewItem = document.createElement('div');
                previewItem.className = 'preview-item';

                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewItem.innerHTML = `
                        <img src="${e.target.result}" alt="${file.name}">
                        <button type="button" class="remove-btn" onclick="removeFile(${index})">
                            <i class="fas fa-times"></i>
                        </button>
                        <div class="preview-info">
                            <h6>${file.name}</h6>
                            <div class="file-size">${formatFileSize(file.size)}</div>
                        </div>
                    `;
                    };
                    reader.readAsDataURL(file);
                } else if (file.type.startsWith('video/')) {
                    previewItem.innerHTML = `
                    <video src="${URL.createObjectURL(file)}" muted></video>
                    <button type="button" class="remove-btn" onclick="removeFile(${index})">
                        <i class="fas fa-times"></i>
                    </button>
                    <div class="preview-info">
                        <h6>${file.name}</h6>
                        <div class="file-size">${formatFileSize(file.size)}</div>
                    </div>
                `;
                }

                previewGrid.appendChild(previewItem);
            });
        }

        function removeFile(index) {
            selectedFiles.splice(index, 1);
            updateFilePreview();
            updateSubmitButton();
        }

        function removeAllFiles() {
            selectedFiles = [];
            fileInput.value = '';
            updateFilePreview();
            updateSubmitButton();
        }

        // Links management
        function addMoreLinks(button) {
            const inputGroup = document.createElement('div');
            inputGroup.className = 'link-input-group';
            inputGroup.innerHTML = `
            <div class="input-group">
                <span class="input-group-text bg-light">
                    <i class="${mediaTypeInput.value === 'youtube' ? 'fab fa-youtube text-danger' : 'fab fa-tiktok text-dark'}"></i>
                </span>
                <input type="url"
                       class="form-control link-input"
                       name="links[]"
                       placeholder="${mediaTypeInput.value === 'youtube' ? 'https://www.youtube.com/watch?v=...' : 'https://www.tiktok.com/@username/video/...'}">
                <button type="button" class="btn btn-outline-danger" onclick="removeLink(this)">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;

            linksContainer.appendChild(inputGroup);

            const newInput = inputGroup.querySelector('.link-input');
            linkInputs.push(newInput);

            newInput.addEventListener('input', handleLinkInput);
        }

        function removeLink(button) {
            const inputGroup = button.closest('.link-input-group');
            const input = inputGroup.querySelector('.link-input');

            const inputIndex = linkInputs.indexOf(input);
            if (inputIndex > -1) {
                linkInputs.splice(inputIndex, 1);
            }

            inputGroup.remove();
            updateLinksPreview();
            updateSubmitButton();
        }

        function handleLinkInput(e) {
            updateLinksPreview();
            updateSubmitButton();
        }

        function updateLinksPreview() {
            const validLinks = linkInputs
                .map(input => input.value.trim())
                .filter(url => url && isValidLink(url));

            if (validLinks.length === 0) {
                linksPreview.style.display = 'none';
                return;
            }

            linksPreview.style.display = 'block';
            linksPreviewGrid.innerHTML = '';

            validLinks.forEach((url, index) => {
                const platform = mediaTypeInput.value;
                const previewCard = document.createElement('div');
                previewCard.className = 'col-md-6 col-lg-4';

                if (platform === 'youtube') {
                    const videoId = extractYouTubeId(url);
                    if (videoId) {
                        previewCard.innerHTML = `
                        <div class="link-preview-card">
                            <img src="https://img.youtube.com/vi/${videoId}/hqdefault.jpg" alt="YouTube Thumbnail">
                            <span class="badge bg-danger platform-badge">YouTube</span>
                            <div class="card-body">
                                <h6>YouTube Video</h6>
                                <small class="text-muted">${url}</small>
                            </div>
                        </div>
                    `;
                    }
                } else if (platform === 'tiktok') {
                    previewCard.innerHTML = `
                    <div class="link-preview-card">
                        <div style="height: 120px; background: linear-gradient(45deg, #000, #333); display: flex; align-items: center; justify-content: center;">
                            <i class="fab fa-tiktok fa-2x text-white"></i>
                        </div>
                        <span class="badge bg-dark platform-badge">TikTok</span>
                        <div class="card-body">
                            <h6>TikTok Video</h6>
                            <small class="text-muted">${url}</small>
                        </div>
                    </div>
                `;
                }

                linksPreviewGrid.appendChild(previewCard);
            });
        }

        function isValidLink(url) {
            const mediaType = mediaTypeInput.value;

            if (mediaType === 'youtube') {
                return extractYouTubeId(url) !== null;
            } else if (mediaType === 'tiktok') {
                return url.includes('tiktok.com') && url.includes('/video/');
            }

            return false;
        }

        function extractYouTubeId(url) {
            const regex = /(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/;
            const match = url.match(regex);
            return match ? match[1] : null;
        }

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        function updateSubmitButton() {
            const mediaType = mediaTypeInput.value;
            let isValid = false;

            if (mediaType === 'image' || mediaType === 'video') {
                isValid = selectedFiles.length > 0;
            } else if (mediaType === 'youtube' || mediaType === 'tiktok') {
                const validLinks = linkInputs
                    .map(input => input.value.trim())
                    .filter(url => url && isValidLink(url));
                isValid = validLinks.length > 0;
            }

            submitButton.disabled = !isValid;
        }

        // Initialize link input events
        const firstLinkInput = linksContainer.querySelector('.link-input');
        firstLinkInput.addEventListener('input', handleLinkInput);
        linkInputs.push(firstLinkInput);

        // Form validation
        document.getElementById('mediaForm').addEventListener('submit', function(e) {
            const mediaType = mediaTypeInput.value;
            if (!mediaType) {
                e.preventDefault();
                alert('Please select a media type.');
                return;
            }

            if ((mediaType === 'image' || mediaType === 'video') && selectedFiles.length === 0) {
                e.preventDefault();
                alert('Please select at least one file to upload.');
                return;
            }

            if ((mediaType === 'youtube' || mediaType === 'tiktok')) {
                const validLinks = linkInputs
                    .map(input => input.value.trim())
                    .filter(url => url && isValidLink(url));

                if (validLinks.length === 0) {
                    e.preventDefault();
                    alert('Please enter at least one valid video link.');
                    return;
                }
            }
        });

        // Initialize form with old input values
        document.addEventListener('DOMContentLoaded', function() {
            const oldType = '{{ old('type') }}';
            if (oldType) {
                const mediaTypeInput = document.getElementById('mediaType');
                mediaTypeInput.value = oldType;

                // Trigger media type selection
                const card = document.querySelector(`[data-type="${oldType}"]`);
                if (card) {
                    card.click();
                }

                // Update submit button based on existing content
                updateSubmitButton();
            }
        });
    </script>
@endpush
