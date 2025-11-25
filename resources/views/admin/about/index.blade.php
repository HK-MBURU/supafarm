@extends('layouts.admin')

@section('title', 'About Page - SupaFarm Admin')
@section('page-title', 'About Page Management')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-md-6">
            <h3 class="mb-1">About Page Content</h3>
            <p class="text-muted mb-0">Manage your company's about page information</p>
        </div>
        <div class="col-md-6 text-md-end">
            <a href="{{ route('admin.about.edit') }}" class="btn btn-primary px-4">
                <i class="fas fa-edit me-2"></i>Edit Content
            </a>
            <div class="btn-group ms-2">
                @if($about->published_at)
                <form action="{{ route('admin.about.unpublish') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-warning">
                        <i class="fas fa-eye-slash me-1"></i>Unpublish
                    </button>
                </form>
                @else
                <form action="{{ route('admin.about.publish') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-success">
                        <i class="fas fa-eye me-1"></i>Publish
                    </button>
                </form>
                @endif
                <a href="{{ route('admin.about.preview') }}" target="_blank" class="btn btn-outline-info">
                    <i class="fas fa-external-link-alt me-1"></i>Preview
                </a>
            </div>
        </div>
    </div>

    <!-- Status Alert -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4 border-0" role="alert">
        <div class="d-flex align-items-center">
            <i class="fas fa-check-circle me-2"></i>
            <div class="flex-grow-1">{{ session('success') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show mb-4 border-0" role="alert">
        <div class="d-flex align-items-center">
            <i class="fas fa-exclamation-circle me-2"></i>
            <div class="flex-grow-1">{{ session('error') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
    @endif

    <!-- Page Status -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                        <i class="fas fa-info-circle text-white"></i>
                                    </div>
                                </div>
                                <div>
                                    <h5 class="mb-1">{{ $about->title }}</h5>
                                    <p class="text-muted mb-0">
                                        Last updated: {{ $about->updated_at->format('M d, Y \a\t h:i A') }}
                                        @if($about->updated_by)
                                        by {{ $about->updater->name ?? 'Admin' }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <span class="badge {{ $about->published_at ? 'bg-success' : 'bg-secondary' }} px-3 py-2 fs-6">
                                <i class="fas fa-circle me-1" style="font-size: 6px;"></i>
                                {{ $about->published_at ? 'Published' : 'Draft' }}
                            </span>
                            @if($about->published_at)
                            <div class="text-muted small mt-1">
                                Published: {{ $about->published_at->format('M d, Y') }}
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Left Column - Content Overview -->
        <div class="col-lg-8">
            <!-- Introduction & Story -->
            <div class="card border-0 mb-4">
                <div class="card-header bg-light border-0">
                    <h5 class="card-title mb-0 fw-bold">Company Overview</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <h6 class="fw-bold text-primary mb-3">Introduction</h6>
                            <div class="bg-light p-3 border">
                                <div class="text-muted small mb-0">
                                    {!! Str::limit(strip_tags($about->introduction), 200) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <h6 class="fw-bold text-primary mb-3">Our Story</h6>
                            <div class="bg-light p-3 border">
                                <div class="text-muted small mb-0">
                                    {!! Str::limit(strip_tags($about->our_story), 200) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mission, Vision & Values -->
            <div class="card border-0 mb-4">
                <div class="card-header bg-light border-0">
                    <h5 class="card-title mb-0 fw-bold">Mission, Vision & Values</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="text-center p-3 border bg-white">
                                <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                    <i class="fas fa-bullseye text-white fs-5"></i>
                                </div>
                                <h6 class="fw-bold mb-2">Mission</h6>
                                <div class="text-muted small mb-0">
                                    {!! Str::limit(strip_tags($about->mission), 100) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="text-center p-3 border bg-white">
                                <div class="bg-info rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                    <i class="fas fa-eye text-white fs-5"></i>
                                </div>
                                <h6 class="fw-bold mb-2">Vision</h6>
                                <div class="text-muted small mb-0">
                                    {!! Str::limit(strip_tags($about->vision), 100) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="text-center p-3 border bg-white">
                                <div class="bg-success rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                    <i class="fas fa-heart text-white fs-5"></i>
                                </div>
                                <h6 class="fw-bold mb-2">Values</h6>
                                <div class="text-muted small mb-0">
                                    {!! Str::limit(strip_tags($about->values), 100) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Team Section -->
            <div class="card border-0 mb-4">
                <div class="card-header bg-light border-0 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fw-bold">Team Members</h5>
                    <span class="badge bg-secondary">{{ count($about->team_members ?? []) }} members</span>
                </div>
                <div class="card-body">
                    @if(!empty($about->team_members) && count($about->team_members) > 0)
                    <div class="row">
                        @foreach($about->team_members as $index => $member)
                        <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                            <div class="card border h-100">
                                <div class="card-body text-center">
                                    @if(isset($member['image']))
                                    <img src="{{ asset('storage/' . $member['image']) }}"
                                         alt="{{ $member['name'] }}"
                                         class="rounded-circle mb-3"
                                         style="width: 80px; height: 80px; object-fit: cover;">
                                    @else
                                    <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                         style="width: 80px; height: 80px;">
                                        <i class="fas fa-user text-muted fs-4"></i>
                                    </div>
                                    @endif
                                    <h6 class="fw-bold mb-1">{{ $member['name'] }}</h6>
                                    <p class="text-primary small mb-2">{{ $member['position'] ?? 'Team Member' }}</p>
                                    @if(isset($member['bio']))
                                    <div class="text-muted small mb-0">
                                        {!! Str::limit(strip_tags($member['bio']), 60) !!}
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="fas fa-users fa-2x text-muted mb-3"></i>
                        <p class="text-muted mb-0">No team members added yet</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column - Media & Info -->
        <div class="col-lg-4">
            <!-- Contact Information -->
            <div class="card border-0 mb-4">
                <div class="card-header bg-light border-0">
                    <h5 class="card-title mb-0 fw-bold">Contact Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-map-marker-alt text-primary me-3"></i>
                            <div>
                                <strong class="d-block">Address</strong>
                                <span class="text-muted">{{ $about->address }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-phone text-primary me-3"></i>
                            <div>
                                <strong class="d-block">Phone</strong>
                                <span class="text-muted">{{ $about->phone }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="mb-0">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-envelope text-primary me-3"></i>
                            <div>
                                <strong class="d-block">Email</strong>
                                <span class="text-muted">{{ $about->email }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Media Gallery -->
            <div class="card border-0 mb-4">
                <div class="card-header bg-light border-0 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fw-bold">Media Gallery</h5>
                    <span class="badge bg-secondary">{{ count($about->image_urls) }} images</span>
                </div>
                <div class="card-body">
                    @if(count($about->image_urls) > 0)
                    <div class="row g-2">
                        @foreach($about->image_urls as $index => $imageUrl)
                        <div class="col-6">
                            <div class="position-relative">
                                <img src="{{ $imageUrl }}"
                                     alt="About image {{ $index + 1 }}"
                                     class="img-fluid rounded border"
                                     style="height: 100px; width: 100%; object-fit: cover;">
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-3">
                        <i class="fas fa-images fa-2x text-muted mb-2"></i>
                        <p class="text-muted mb-0">No images uploaded</p>
                    </div>
                    @endif

                    @if($about->video_url)
                    <div class="mt-3 pt-3 border-top">
                        <h6 class="fw-bold mb-2">Video</h6>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-video text-primary me-2"></i>
                            <a href="{{ $about->video_url }}" target="_blank" class="text-truncate">
                                {{ Str::limit($about->video_url, 30) }}
                            </a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- SEO Information -->
            <div class="card border-0 mb-4">
                <div class="card-header bg-light border-0">
                    <h5 class="card-title mb-0 fw-bold">SEO Information</h5>
                </div>
                <div class="card-body">
                    @if($about->meta_title)
                    <div class="mb-3">
                        <strong class="d-block text-muted small">Meta Title</strong>
                        <span class="fw-medium">{{ $about->meta_title }}</span>
                    </div>
                    @endif
                    @if($about->meta_description)
                    <div class="mb-3">
                        <strong class="d-block text-muted small">Meta Description</strong>
                        <div class="text-muted small">
                            {!! Str::limit(strip_tags($about->meta_description), 80) !!}
                        </div>
                    </div>
                    @endif
                    @if($about->meta_keywords)
                    <div class="mb-0">
                        <strong class="d-block text-muted small">Meta Keywords</strong>
                        <span class="text-muted">{{ Str::limit($about->meta_keywords, 80) }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card border-0">
                <div class="card-header bg-light border-0">
                    <h5 class="card-title mb-0 fw-bold">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.about.edit') }}" class="btn btn-primary">
                            <i class="fas fa-edit me-2"></i>Edit Content
                        </a>
                        <a href="{{ route('admin.about.preview') }}" target="_blank" class="btn btn-outline-info">
                            <i class="fas fa-external-link-alt me-2"></i>Preview Page
                        </a>
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-download me-2"></i>Export/Import
                            </button>
                            <ul class="dropdown-menu w-100">
                                <li>
                                    <a href="{{ route('admin.about.export') }}" class="dropdown-item">
                                        <i class="fas fa-file-export me-2"></i>Export Data
                                    </a>
                                </li>
                                <li>
                                    <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#importModal">
                                        <i class="fas fa-file-import me-2"></i>Import Data
                                    </button>
                                </li>
                            </ul>
                        </div>
                        <form action="{{ route('admin.about.reset') }}" method="POST" class="d-grid">
                            @csrf
                            <button type="submit"
                                    class="btn btn-outline-danger"
                                    onclick="return confirm('Are you sure you want to reset all content to default? This action cannot be undone.')">
                                <i class="fas fa-undo me-2"></i>Reset to Default
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content border-0">
            <div class="modal-header bg-light border-0">
                <h6 class="modal-title fw-bold">Import About Data</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.about.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Select JSON File</label>
                        <input type="file" name="import_file" class="form-control form-control-sm" accept=".json" required>
                        <div class="form-text">Select a previously exported JSON file</div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-primary">Import</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.card {
    border-radius: 0;
}

.btn {
    border-radius: 0;
    border: 1px solid;
}

.alert {
    border-radius: 0;
}

.badge {
    border-radius: 0;
    font-weight: 500;
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

.btn-outline-info {
    color: #0dcaf0;
    border-color: #0dcaf0;
}

.btn-outline-info:hover {
    background-color: #0dcaf0;
    border-color: #0dcaf0;
    color: #000;
}

.btn-outline-success {
    color: #198754;
    border-color: #198754;
}

.btn-outline-success:hover {
    background-color: #198754;
    border-color: #198754;
    color: white;
}

.btn-outline-warning {
    color: #ffc107;
    border-color: #ffc107;
}

.btn-outline-warning:hover {
    background-color: #ffc107;
    border-color: #ffc107;
    color: #000;
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

.btn-outline-danger {
    color: #dc3545;
    border-color: #dc3545;
}

.btn-outline-danger:hover {
    background-color: #dc3545;
    border-color: #dc3545;
    color: white;
}

.alert-success {
    background-color: #f8fff8;
    border: 1px solid #b8e6b8;
    color: #2d5a2d;
}

.alert-danger {
    background-color: #fff8f8;
    border: 1px solid #e6b8b8;
    color: #5a2d2d;
}

.bg-primary { background-color: #BC450D !important; }
.bg-success { background-color: #198754 !important; }
.bg-info { background-color: #0dcaf0 !important; }

/* Status badge colors */
.badge-warning { background-color: #ffc107; color: #000; }
.badge-info { background-color: #0dcaf0; color: #000; }
.badge-primary { background-color: #BC450D; color: #fff; }
.badge-secondary { background-color: #6c757d; color: #fff; }
.badge-success { background-color: #198754; color: #fff; }

/* Modal */
.modal-content {
    border-radius: 0;
    border: 1px solid #dee2e6;
}

.form-control {
    border-radius: 0;
    border: 1px solid #dee2e6;
}

.form-control:focus {
    border-color: #BC450D;
    box-shadow: none;
}

/* Dropdown */
.dropdown-menu {
    border-radius: 0;
    border: 1px solid #dee2e6;
}

.dropdown-item {
    border-radius: 0;
}

.dropdown-item:hover {
    background-color: #BC450D;
    color: white;
}

/* Team member cards */
.card.border:hover {
    border-color: #BC450D !important;
}
</style>
@endsection
