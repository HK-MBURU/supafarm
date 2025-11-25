@extends('layouts.admin')

@section('title', 'Manage Contacts - SupaFarm Admin')
@section('page-title', 'Contact Messages')

@section('content')
<div class="container-fluid">
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title text-muted mb-2">Total Contacts</h6>
                            <h3 class="fw-bold mb-0">{{ $totalContacts }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-envelope fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title text-muted mb-2">Unread Messages</h6>
                            <h3 class="fw-bold mb-0 text-warning">{{ $unreadCount }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-envelope-open fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title text-muted mb-2">Read Messages</h6>
                            <h3 class="fw-bold mb-0 text-success">{{ $readCount }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-check-circle fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title text-muted mb-2">Actions</h6>
                            <div>
                                <a href="{{ route('admin.contacts.export') }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-download me-1"></i>Export CSV
                                </a>
                            </div>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-cogs fa-2x text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('admin.contacts.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-6">
                    <label class="form-label small fw-bold text-muted">Search Messages</label>
                    <input type="text"
                           name="search"
                           class="form-control"
                           placeholder="Search by name, email, subject, or message..."
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-muted">Filter by Status</label>
                    <select name="read_status" class="form-select" onchange="this.form.submit()">
                        <option value="">All Status</option>
                        <option value="0" {{ request('read_status') === '0' ? 'selected' : '' }}>Unread</option>
                        <option value="1" {{ request('read_status') === '1' ? 'selected' : '' }}>Read</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search me-1"></i>Search
                    </button>
                    <a href="{{ route('admin.contacts.index') }}" class="btn btn-outline-secondary w-100 mt-2">
                        <i class="fas fa-refresh me-1"></i>Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Bulk Actions -->
    <div class="card border-0 shadow-sm mb-4" id="bulkActionsCard" style="display: none;">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span id="selectedCount" class="fw-bold text-primary">0</span> messages selected
                </div>
                <div class="btn-group">
                    <button type="button" class="btn btn-sm btn-outline-success" onclick="markSelectedAsRead()">
                        <i class="fas fa-envelope-open me-1"></i>Mark as Read
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-warning" onclick="markSelectedAsUnread()">
                        <i class="fas fa-envelope me-1"></i>Mark as Unread
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteSelected()">
                        <i class="fas fa-trash me-1"></i>Delete Selected
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Contacts Cards Grid -->
    <div class="row g-4">
        @if($contacts->count() > 0)
            @foreach($contacts as $contact)
            <div class="col-xxl-4 col-xl-6 col-lg-6 col-md-12">
                <div class="card contact-card h-100 border-0 shadow-sm {{ $contact->is_read ? '' : 'border-warning' }}"
                     data-contact-id="{{ $contact->id }}">
                    <div class="card-header bg-transparent border-0 pb-0">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="d-flex align-items-center">
                                <input type="checkbox" class="contact-checkbox me-2" value="{{ $contact->id }}">
                                <div>
                                    <h6 class="card-title mb-1 fw-bold">{{ $contact->name }}</h6>
                                    <small class="text-muted">{{ $contact->created_at->format('M j, Y g:i A') }}</small>
                                </div>
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary border-0" type="button"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admin.contacts.show', $contact) }}">
                                            <i class="fas fa-eye me-2"></i>View Details
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admin.contacts.edit', $contact) }}">
                                            <i class="fas fa-edit me-2"></i>Edit
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('admin.contacts.destroy', $contact) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger"
                                                    onclick="return confirm('Are you sure you want to delete this contact?')">
                                                <i class="fas fa-trash me-2"></i>Delete
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <a href="mailto:{{ $contact->email }}" class="text-decoration-none">
                                    <small class="text-primary">
                                        <i class="fas fa-envelope me-1"></i>{{ $contact->email }}
                                    </small>
                                </a>
                                <div>
                                    @if($contact->is_read)
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25">
                                            <i class="fas fa-check-circle me-1"></i>Read
                                        </span>
                                    @else
                                        <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25">
                                            <i class="fas fa-envelope me-1"></i>Unread
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <h6 class="fw-semibold text-dark mb-2">{{ $contact->subject }}</h6>

                            <p class="text-muted mb-3 line-clamp-3">
                                {{ Str::limit(strip_tags($contact->message), 120) }}
                            </p>
                        </div>
                    </div>

                    <div class="card-footer bg-transparent border-0 pt-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="fas fa-clock me-1"></i>
                                {{ $contact->created_at->diffForHumans() }}
                            </small>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.contacts.show', $contact) }}"
                                   class="btn btn-outline-primary btn-sm"
                                   title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="mailto:{{ $contact->email }}?subject=Re: {{ $contact->subject }}"
                                   class="btn btn-outline-success btn-sm"
                                   title="Reply">
                                    <i class="fas fa-reply"></i>
                                </a>
                                @if($contact->is_read)
                                    <form action="{{ route('admin.contacts.mark.unread') }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="ids[]" value="{{ $contact->id }}">
                                        <button type="submit" class="btn btn-outline-warning btn-sm" title="Mark as Unread">
                                            <i class="fas fa-envelope"></i>
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.contacts.mark.read') }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="ids[]" value="{{ $contact->id }}">
                                        <button type="submit" class="btn btn-outline-success btn-sm" title="Mark as Read">
                                            <i class="fas fa-envelope-open"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        @else
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-envelope-open fa-4x text-muted mb-4"></i>
                        <h4 class="text-muted mb-3">No contact messages found</h4>
                        <p class="text-muted mb-4">There are no contact messages matching your criteria.</p>
                        <a href="{{ route('admin.contacts.index') }}" class="btn btn-primary">
                            <i class="fas fa-refresh me-2"></i>Reset Filters
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Pagination -->
    @if($contacts->count() > 0)
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    {{ $contacts->links() }}
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Bulk Actions Form -->
<form id="bulkActionForm" method="POST" style="display: none;">
    @csrf
    <input type="hidden" name="ids" id="selectedIds">
</form>
@endsection

@push('styles')
<style>
.contact-card {
    transition: all 0.3s ease;
    border: 1px solid #e9ecef;
}

.contact-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1) !important;
}

.contact-card.border-warning {
    border-left: 4px solid #ffc107 !important;
    background: linear-gradient(135deg, #fff3cd08 0%, #ffffff 100%);
}

.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.contact-checkbox {
    transform: scale(1.1);
    cursor: pointer;
}

.card-header .dropdown-toggle::after {
    display: none;
}

.card-header .dropdown-toggle {
    padding: 4px 8px;
    border-radius: 4px;
}

.card-header .dropdown-toggle:hover {
    background-color: #f8f9fa;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .contact-card .btn-group-sm .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }

    .card-header .d-flex {
        flex-direction: column;
        align-items: flex-start !important;
    }

    .card-header .dropdown {
        align-self: flex-end;
        margin-top: -2rem;
    }
}

@media (max-width: 576px) {
    .col-xxl-4, .col-xl-6, .col-lg-6 {
        flex: 0 0 100%;
        max-width: 100%;
    }

    .contact-card {
        margin-bottom: 1rem;
    }
}

/* Custom scrollbar for better mobile experience */
.table-responsive {
    border-radius: 8px;
}

/* Selection styles */
.contact-card.selected {
    border-color: #0d6efd;
    background-color: #f8f9fe;
}

/* Animation for bulk actions */
#bulkActionsCard {
    animation: slideDown 0.3s ease;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Badge styles */
.badge {
    font-size: 0.7rem;
    font-weight: 500;
    padding: 0.35em 0.65em;
}

/* Card title truncation */
.card-title {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 200px;
}

@media (max-width: 768px) {
    .card-title {
        max-width: 150px;
    }
}
</style>
@endpush

@push('scripts')
<script>
let selectedContacts = new Set();

function getSelectedIds() {
    return Array.from(selectedContacts);
}

function updateBulkActions() {
    const selectedCount = selectedContacts.size;
    const bulkActionsCard = document.getElementById('bulkActionsCard');
    const selectedCountElement = document.getElementById('selectedCount');

    if (selectedCount > 0) {
        bulkActionsCard.style.display = 'block';
        selectedCountElement.textContent = selectedCount;

        // Add selected class to cards
        document.querySelectorAll('.contact-card').forEach(card => {
            const contactId = card.getAttribute('data-contact-id');
            if (selectedContacts.has(contactId)) {
                card.classList.add('selected');
            } else {
                card.classList.remove('selected');
            }
        });
    } else {
        bulkActionsCard.style.display = 'none';
        document.querySelectorAll('.contact-card').forEach(card => {
            card.classList.remove('selected');
        });
    }
}

function toggleContactSelection(checkbox) {
    const contactId = checkbox.value;
    const card = checkbox.closest('.contact-card');

    if (checkbox.checked) {
        selectedContacts.add(contactId);
        card.classList.add('selected');
    } else {
        selectedContacts.delete(contactId);
        card.classList.remove('selected');
    }

    updateBulkActions();
}

function toggleSelectAll(source) {
    const checkboxes = document.querySelectorAll('.contact-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = source.checked;
        toggleContactSelection(checkbox);
    });
}

function selectAll() {
    const checkboxes = document.querySelectorAll('.contact-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = true;
        toggleContactSelection(checkbox);
    });
}

function clearAllSelections() {
    selectedContacts.clear();
    const checkboxes = document.querySelectorAll('.contact-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = false;
    });
    updateBulkActions();
}

function markSelectedAsRead() {
    const ids = getSelectedIds();
    if (ids.length === 0) {
        alert('Please select at least one contact.');
        return;
    }

    const form = document.getElementById('bulkActionForm');
    form.action = "{{ route('admin.contacts.mark.read') }}";
    document.getElementById('selectedIds').value = JSON.stringify(ids);
    form.submit();
}

function markSelectedAsUnread() {
    const ids = getSelectedIds();
    if (ids.length === 0) {
        alert('Please select at least one contact.');
        return;
    }

    const form = document.getElementById('bulkActionForm');
    form.action = "{{ route('admin.contacts.mark.unread') }}";
    document.getElementById('selectedIds').value = JSON.stringify(ids);
    form.submit();
}

function deleteSelected() {
    const ids = getSelectedIds();
    if (ids.length === 0) {
        alert('Please select at least one contact.');
        return;
    }

    if (confirm(`Are you sure you want to delete ${ids.length} contact message(s)? This action cannot be undone.`)) {
        const form = document.getElementById('bulkActionForm');
        form.action = "{{ route('admin.contacts.bulk.destroy') }}";
        document.getElementById('selectedIds').value = JSON.stringify(ids);
        form.submit();
    }
}

// Initialize event listeners
document.addEventListener('DOMContentLoaded', function() {
    // Add change event to all checkboxes
    document.querySelectorAll('.contact-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            toggleContactSelection(this);
        });
    });

    // Add click event to cards for selection (optional)
    document.querySelectorAll('.contact-card').forEach(card => {
        card.addEventListener('click', function(e) {
            // Don't trigger if clicking on links, buttons, or the checkbox itself
            if (e.target.tagName === 'A' || e.target.tagName === 'BUTTON' || e.target.type === 'checkbox') {
                return;
            }

            const checkbox = this.querySelector('.contact-checkbox');
            if (checkbox) {
                checkbox.checked = !checkbox.checked;
                toggleContactSelection(checkbox);
            }
        });
    });
});

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Ctrl+A to select all
    if (e.ctrlKey && e.key === 'a') {
        e.preventDefault();
        selectAll();
    }

    // Escape to clear selection
    if (e.key === 'Escape') {
        clearAllSelections();
    }
});
</script>
@endpush
