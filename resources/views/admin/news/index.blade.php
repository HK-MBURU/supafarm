@extends('layouts.admin')

@section('title', 'News Management - SupaFarm Admin')
@section('page-title', 'News Management')

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

                        <p class="text-muted mb-0">Manage your news articles and publications</p>
                    </div>
                </div>
                <a href="{{ route('admin.news.create') }}" class="btn btn-primary px-4 py-2 rounded-2 fw-medium">
                    <i class="fas fa-plus me-2"></i>Create News
                </a>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row g-3 mb-5 px-4">
            <div class="col-xl-3 col-md-6">
                <div class="stat-card bg-white border rounded-3 p-4 h-100">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted fw-medium mb-2">Total Articles</h6>
                            <h2 class="fw-bold text-primary mb-0">{{ $news->total() }}</h2>
                        </div>
                        <div class="stat-icon">
                            <div class="bg-primary bg-opacity-10 rounded-3 p-3">
                                <i class="fas fa-newspaper fa-lg text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="stat-card bg-white border rounded-3 p-4 h-100">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted fw-medium mb-2">Published</h6>
                            <h2 class="fw-bold text-success mb-0">{{ \App\Models\News::published()->count() }}</h2>
                        </div>
                        <div class="stat-icon">
                            <div class="bg-success bg-opacity-10 rounded-3 p-3">
                                <i class="fas fa-check-circle fa-lg text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="stat-card bg-white border rounded-3 p-4 h-100">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted fw-medium mb-2">Drafts</h6>
                            <h2 class="fw-bold text-warning mb-0">
                                {{ \App\Models\News::where('is_published', false)->count() }}</h2>
                        </div>
                        <div class="stat-icon">
                            <div class="bg-warning bg-opacity-10 rounded-3 p-3">
                                <i class="fas fa-edit fa-lg text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="stat-card bg-white border rounded-3 p-4 h-100">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted fw-medium mb-2">Featured</h6>
                            <h2 class="fw-bold text-info mb-0">{{ \App\Models\News::featured()->count() }}</h2>
                        </div>
                        <div class="stat-icon">
                            <div class="bg-info bg-opacity-10 rounded-3 p-3">
                                <i class="fas fa-star fa-lg text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters and Search -->
        <div class="px-4 mb-4">
            <div class="card border rounded-3">
                <div class="card-body p-4">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-6 col-lg-4">
                            <label class="form-label fw-semibold">Search Articles</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-search text-muted"></i>
                                </span>
                                <input type="text" class="form-control border-start-0" id="searchInput"
                                    placeholder="Search by title, content, or author..." value="{{ request('search') }}">
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-3">
                            <label class="form-label fw-semibold">Status</label>
                            <select class="form-select" id="statusFilter">
                                <option value="">All Status</option>
                                <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published
                                </option>
                                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            </select>
                        </div>
                        <div class="col-md-4 col-lg-3">
                            <label class="form-label fw-semibold">Featured</label>
                            <select class="form-select" id="featuredFilter">
                                <option value="">All Articles</option>
                                <option value="true" {{ request('featured') === 'true' ? 'selected' : '' }}>Featured Only
                                </option>
                            </select>
                        </div>
                        <div class="col-md-4 col-lg-2">
                            <button type="button" class="btn btn-outline-secondary w-100" onclick="resetFilters()">
                                <i class="fas fa-refresh me-2"></i>Reset
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- News Grid -->
        <div class="px-4">
            @if ($news->count() > 0)
                <div class="news-grid" id="newsGrid">
                    @foreach ($news as $article)
                        <div class="news-card" data-status="{{ $article->is_published ? 'published' : 'draft' }}"
                            data-featured="{{ $article->is_featured ? 'true' : 'false' }}">
                            <div class="news-image">
                                <img src="{{ $article->featured_image_url }}" alt="{{ $article->title }}" class="news-img"
                                    loading="lazy">

                                <!-- Status Badges -->
                                <div class="news-badges">
                                    @if ($article->is_featured)
                                        <span class="badge featured-badge">
                                            <i class="fas fa-star me-1"></i>Featured
                                        </span>
                                    @endif
                                    @if ($article->is_published)
                                        <span class="badge published-badge">
                                            <i class="fas fa-check-circle me-1"></i>Published
                                        </span>
                                    @else
                                        <span class="badge draft-badge">
                                            <i class="fas fa-edit me-1"></i>Draft
                                        </span>
                                    @endif
                                </div>

                                <!-- Quick Actions -->
                                <div class="news-actions">
                                    <a href="{{ route('admin.news.show', $article) }}" class="action-btn view-btn"
                                        title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.news.edit', $article) }}" class="action-btn edit-btn"
                                        title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="action-btn delete-btn" title="Delete"
                                        data-bs-toggle="modal" data-bs-target="#deleteModal"
                                        data-article-id="{{ $article->id }}"
                                        data-article-title="{{ $article->title }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="news-content">
                                <h3 class="news-title">{{ Str::limit($article->title, 60) }}</h3>

                                @if ($article->excerpt)
                                    <p class="news-excerpt">{{ Str::limit($article->excerpt, 120) }}</p>
                                @else
                                    <p class="news-excerpt text-muted">
                                        {{ Str::limit(strip_tags($article->content), 120) }}</p>
                                @endif

                                <div class="news-meta">
                                    <div class="meta-item">
                                        <i class="fas fa-user me-1"></i>
                                        <span>{{ $article->author ?: 'Unknown' }}</span>
                                    </div>
                                    <div class="meta-item">
                                        <i class="fas fa-calendar me-1"></i>
                                        <span>{{ $article->published_date }}</span>
                                    </div>
                                    <div class="meta-item">
                                        <i class="fas fa-eye me-1"></i>
                                        <span>{{ $article->views }} views</span>
                                    </div>
                                    <div class="meta-item">
                                        <i class="fas fa-clock me-1"></i>
                                        <span>{{ $article->read_time }} min read</span>
                                    </div>
                                </div>

                                <div class="news-footer">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="status-actions">
                                            <form action="{{ route('admin.news.toggle-status', $article) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                    class="btn btn-sm {{ $article->is_published ? 'btn-warning' : 'btn-success' }}">
                                                    <i
                                                        class="fas {{ $article->is_published ? 'fa-eye-slash' : 'fa-eye' }} me-1"></i>
                                                    {{ $article->is_published ? 'Unpublish' : 'Publish' }}
                                                </button>
                                            </form>

                                            <form action="{{ route('admin.news.toggle-featured', $article) }}"
                                                method="POST" class="d-inline ms-2">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                    class="btn btn-sm {{ $article->is_featured ? 'btn-secondary' : 'btn-info' }}">
                                                    <i
                                                        class="fas {{ $article->is_featured ? 'fa-star' : 'fa-star' }} me-1"></i>
                                                    {{ $article->is_featured ? 'Unfeature' : 'Feature' }}
                                                </button>
                                            </form>
                                        </div>

                                        <a href="{{ route('admin.news.show', $article) }}"
                                            class="btn btn-outline-primary btn-sm">
                                            Read More
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if ($news->hasPages())
                    <div class="pagination-container mt-5">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted">
                                Showing {{ $news->firstItem() }} to {{ $news->lastItem() }} of {{ $news->total() }}
                                articles
                            </div>
                            {{ $news->links() }}
                        </div>
                    </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="empty-state text-center py-5">
                    <div class="empty-icon mb-4">
                        <i class="fas fa-newspaper fa-4x text-muted"></i>
                    </div>
                    <h3 class="text-muted mb-3">No news articles found</h3>
                    <p class="text-muted mb-4">Get started by creating your first news article</p>
                    <a href="{{ route('admin.news.create') }}" class="btn btn-primary px-4">
                        <i class="fas fa-plus me-2"></i>Create News Article
                    </a>
                </div>
            @endif
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
                    <p>Are you sure you want to delete the news article "<strong id="deleteArticleTitle"></strong>"?</p>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Warning:</strong> This action cannot be undone. All associated images will also be deleted.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form id="deleteForm" method="POST">
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
        .stat-card {
            transition: all 0.3s ease;
            border: 1px solid #e9ecef;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            border-color: #BC450D;
        }

        .card {
            border: 1px solid #e9ecef;
            box-shadow: none;
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

        /* News Grid Layout */
        .news-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 24px;
            animation: fadeIn 0.6s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* News Card Styles */
        .news-card {
            background: white;
            border: 1px solid #e9ecef;
            border-radius: 16px;
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .news-card:hover {
            border-color: #BC450D;
            transform: translateY(-4px);
        }

        .news-image {
            position: relative;
            height: 200px;
            overflow: hidden;
            background: #f8f9fa;
        }

        .news-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .news-card:hover .news-img {
            transform: scale(1.05);
        }

        /* Badges */
        .news-badges {
            position: absolute;
            top: 12px;
            left: 12px;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .badge {
            padding: 6px 10px;
            font-size: 0.7rem;
            font-weight: 600;
            border-radius: 6px;
        }

        .featured-badge {
            background: rgba(23, 162, 184, 0.9);
            color: white;
        }

        .published-badge {
            background: rgba(40, 167, 69, 0.9);
            color: white;
        }

        .draft-badge {
            background: rgba(255, 193, 7, 0.9);
            color: #212529;
        }

        /* Actions */
        .news-actions {
            position: absolute;
            top: 12px;
            right: 12px;
            display: flex;
            gap: 6px;
            opacity: 0;
            transform: translateY(10px);
            transition: all 0.3s ease;
        }

        .news-card:hover .news-actions {
            opacity: 1;
            transform: translateY(0);
        }

        .action-btn {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all 0.2s ease;
            backdrop-filter: blur(10px);
        }

        .view-btn {
            background: rgba(33, 150, 243, 0.9);
            color: white;
        }

        .edit-btn {
            background: rgba(76, 175, 80, 0.9);
            color: white;
        }

        .delete-btn {
            background: rgba(244, 67, 54, 0.9);
            color: white;
            border: none;
        }

        .action-btn:hover {
            transform: scale(1.1);
        }

        /* News Content */
        .news-content {
            padding: 20px;
        }

        .news-title {
            font-weight: 600;
            margin-bottom: 12px;
            line-height: 1.4;
            color: #2d3748;
            font-size: 1.1rem;
        }

        .news-excerpt {
            color: #718096;
            font-size: 0.9rem;
            line-height: 1.5;
            margin-bottom: 16px;
        }

        .news-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 16px;
            padding-bottom: 16px;
            border-bottom: 1px solid #e9ecef;
        }

        .meta-item {
            display: flex;
            align-items: center;
            font-size: 0.8rem;
            color: #a0aec0;
        }

        .meta-item i {
            font-size: 0.7rem;
        }

        .news-footer {
            margin-top: auto;
        }

        .status-actions .btn {
            font-size: 0.75rem;
            padding: 6px 12px;
        }

        /* Empty State */
        .empty-state {
            grid-column: 1 / -1;
            text-align: center;
            padding: 60px 20px;
        }

        .empty-icon {
            font-size: 4rem;
            color: #e9ecef;
            margin-bottom: 24px;
        }

        .empty-state h3 {
            color: #6c757d;
            margin-bottom: 12px;
        }

        /* Pagination */
        .pagination-container .pagination {
            margin-bottom: 0;
        }

        .page-link {
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 8px 16px;
            color: #6c757d;
            text-decoration: none;
            transition: all 0.2s ease;
            margin: 0 4px;
        }

        .page-link:hover {
            background: #f8f9fa;
            border-color: #BC450D;
            color: #BC450D;
        }

        .page-item.active .page-link {
            background: #BC450D;
            border-color: #BC450D;
            color: white;
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .news-grid {
                grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            }
        }

        @media (max-width: 768px) {
            .admin-header {
                padding: 20px 16px;
            }

            .px-4 {
                padding-left: 16px !important;
                padding-right: 16px !important;
            }

            .news-grid {
                grid-template-columns: 1fr;
                gap: 16px;
            }

            .stat-card {
                padding: 20px;
            }

            .empty-state {
                padding: 40px 16px;
            }

            .news-meta {
                gap: 8px;
            }

            .status-actions {
                margin-bottom: 12px;
            }

            .news-footer .d-flex {
                flex-direction: column;
                gap: 12px;
            }

            .news-footer .d-flex>* {
                width: 100%;
            }
        }

        @media (max-width: 576px) {
            .news-grid {
                grid-template-columns: 1fr;
            }

            .news-image {
                height: 180px;
            }

            .news-content {
                padding: 16px;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Delete modal handling
            const deleteModal = document.getElementById('deleteModal');
            deleteModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const articleId = button.getAttribute('data-article-id');
                const articleTitle = button.getAttribute('data-article-title');

                document.getElementById('deleteArticleTitle').textContent = articleTitle;
                document.getElementById('deleteForm').action = `/admin/news/${articleId}`;
            });

            // Filter functionality
            const searchInput = document.getElementById('searchInput');
            const statusFilter = document.getElementById('statusFilter');
            const featuredFilter = document.getElementById('featuredFilter');

            function applyFilters() {
                const params = new URLSearchParams();

                if (searchInput.value) params.set('search', searchInput.value);
                if (statusFilter.value) params.set('status', statusFilter.value);
                if (featuredFilter.value) params.set('featured', featuredFilter.value);

                window.location.href = '{{ route('admin.news.index') }}?' + params.toString();
            }

            // Debounce search input
            let searchTimeout;
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(applyFilters, 500);
            });

            statusFilter.addEventListener('change', applyFilters);
            featuredFilter.addEventListener('change', applyFilters);

            // Reset filters
            window.resetFilters = function() {
                searchInput.value = '';
                statusFilter.value = '';
                featuredFilter.value = '';
                window.location.href = '{{ route('admin.news.index') }}';
            };

            // Add loading animation to news cards
            const newsCards = document.querySelectorAll('.news-card');
            newsCards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
            });
        });
    </script>
@endpush
