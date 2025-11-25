@extends('layouts.admin')

@section('title', 'View Contact - SupaFarm Admin')
@section('page-title', 'View Contact Message')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <a href="{{ route('admin.contacts.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Contacts
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0">
                <div class="card-header bg-light border-0 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fw-bold">Message Details</h5>
                    <div class="btn-group">
                        <a href="{{ route('admin.contacts.edit', $contact) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-edit me-1"></i>Edit
                        </a>
                        <form action="{{ route('admin.contacts.destroy', $contact) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                    onclick="return confirm('Are you sure you want to delete this contact?')">
                                <i class="fas fa-trash me-1"></i>Delete
                            </button>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <strong>Name:</strong>
                            <p class="mb-0">{{ $contact->name }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Email:</strong>
                            <p class="mb-0">
                                <a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a>
                            </p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-12">
                            <strong>Subject:</strong>
                            <p class="mb-0">{{ $contact->subject }}</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <strong>Message:</strong>
                            <div class="border rounded p-3 bg-light">
                                {!! nl2br(e($contact->message)) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0">
                <div class="card-header bg-light border-0">
                    <h5 class="card-title mb-0 fw-bold">Contact Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Status:</strong>
                        <div>
                            @if($contact->is_read)
                                <span class="badge bg-success">Read</span>
                            @else
                                <span class="badge bg-warning">Unread</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3">
                        <strong>Submitted:</strong>
                        <div>{{ $contact->created_at->format('F j, Y g:i A') }}</div>
                    </div>

                    <div class="mb-3">
                        <strong>Last Updated:</strong>
                        <div>{{ $contact->updated_at->format('F j, Y g:i A') }}</div>
                    </div>

                    <div class="mb-0">
                        <strong>Contact ID:</strong>
                        <div>#{{ $contact->id }}</div>
                    </div>
                </div>
            </div>

            <div class="card border-0 mt-4">
                <div class="card-header bg-light border-0">
                    <h5 class="card-title mb-0 fw-bold">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="mailto:{{ $contact->email }}?subject=Re: {{ $contact->subject }}"
                           class="btn btn-primary">
                            <i class="fas fa-reply me-2"></i>Reply via Email
                        </a>

                        @if($contact->is_read)
                            <form action="{{ route('admin.contacts.mark.unread') }}" method="POST" class="d-grid">
                                @csrf
                                <input type="hidden" name="ids[]" value="{{ $contact->id }}">
                                <button type="submit" class="btn btn-outline-warning">
                                    <i class="fas fa-envelope me-2"></i>Mark as Unread
                                </button>
                            </form>
                        @else
                            <form action="{{ route('admin.contacts.mark.read') }}" method="POST" class="d-grid">
                                @csrf
                                <input type="hidden" name="ids[]" value="{{ $contact->id }}">
                                <button type="submit" class="btn btn-outline-success">
                                    <i class="fas fa-envelope-open me-2"></i>Mark as Read
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
