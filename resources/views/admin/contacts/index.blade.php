@extends('layouts.admin')

@section('title', 'Manage Contacts - SupaFarm Admin')
@section('page-title', 'Contact Messages')

@section('content')
<div class="container-fluid">
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card border-0">
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
            <div class="card border-0">
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
            <div class="card border-0">
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
            <div class="card border-0">
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
    <div class="card border-0 mb-4">
        <div class="card-body">
            <form action="{{ route('admin.contacts.index') }}" method="GET" class="row g-3">
                <div class="col-md-6">
                    <input type="text"
                           name="search"
                           class="form-control"
                           placeholder="Search by name, email, subject, or message..."
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="read_status" class="form-control" onchange="this.form.submit()">
                        <option value="">All Status</option>
                        <option value="0" {{ request('read_status') === '0' ? 'selected' : '' }}>Unread</option>
                        <option value="1" {{ request('read_status') === '1' ? 'selected' : '' }}>Read</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search me-1"></i>Search
                    </button>
                    <a href="{{ route('admin.contacts.index') }}" class="btn btn-outline-secondary">
                        Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Contacts Table -->
    <div class="card border-0">
        <div class="card-header bg-light border-0 d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0 fw-bold">Contact Messages</h5>
            <div class="btn-group">
                <button type="button" class="btn btn-sm btn-outline-primary" onclick="selectAll()">
                    <i class="fas fa-check-square me-1"></i>Select All
                </button>
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
        <div class="card-body p-0">
            @if($contacts->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th width="50">
                                    <input type="checkbox" id="selectAll" onchange="toggleSelectAll(this)">
                                </th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Subject</th>
                                <th>Message Preview</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th width="120">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($contacts as $contact)
                            <tr class="{{ $contact->is_read ? '' : 'table-warning' }}">
                                <td>
                                    <input type="checkbox" class="contact-checkbox" value="{{ $contact->id }}">
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if(!$contact->is_read)
                                            <span class="badge bg-warning me-2" title="Unread">New</span>
                                        @endif
                                        <strong>{{ $contact->name }}</strong>
                                    </div>
                                </td>
                                <td>
                                    <a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a>
                                </td>
                                <td>{{ Str::limit($contact->subject, 50) }}</td>
                                <td>{{ Str::limit(strip_tags($contact->message), 70) }}</td>
                                <td>
                                    @if($contact->is_read)
                                        <span class="badge bg-success">Read</span>
                                    @else
                                        <span class="badge bg-warning">Unread</span>
                                    @endif
                                </td>
                                <td>{{ $contact->created_at->format('M j, Y g:i A') }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.contacts.show', $contact) }}"
                                           class="btn btn-outline-primary"
                                           title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.contacts.edit', $contact) }}"
                                           class="btn btn-outline-secondary"
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.contacts.destroy', $contact) }}"
                                              method="POST"
                                              class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to delete this contact?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="card-footer bg-light border-0">
                    {{ $contacts->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-envelope-open fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No contact messages found</h5>
                    <p class="text-muted">There are no contact messages matching your criteria.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Bulk Actions Form -->
<form id="bulkActionForm" method="POST" style="display: none;">
    @csrf
    <input type="hidden" name="ids" id="selectedIds">
</form>
@endsection

@push('scripts')
<script>
function getSelectedIds() {
    const checkboxes = document.querySelectorAll('.contact-checkbox:checked');
    return Array.from(checkboxes).map(cb => cb.value);
}

function toggleSelectAll(source) {
    const checkboxes = document.querySelectorAll('.contact-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = source.checked;
    });
}

function selectAll() {
    const checkboxes = document.querySelectorAll('.contact-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = true;
    });
    document.getElementById('selectAll').checked = true;
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

    if (confirm(`Are you sure you want to delete ${ids.length} contact(s)?`)) {
        const form = document.getElementById('bulkActionForm');
        form.action = "{{ route('admin.contacts.bulk.destroy') }}";
        document.getElementById('selectedIds').value = JSON.stringify(ids);
        form.submit();
    }
}
</script>
@endpush
