@extends('layouts.admin')

@section('title', 'Supa Farm Orders - SupaFarm Admin')
@section('page-title', 'Orders Overview')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-md-6">
            <h3 class="mb-1">All Orders</h3>
            <p class="text-muted mb-0">Manage customer orders and deliveries</p>
        </div>
        <div class="col-md-6 text-md-end">
            <a href="{{ route('admin.orders.create') }}" class="btn btn-primary px-4">
                <i class="fas fa-plus me-2"></i>Create Order
            </a>
            <div class="btn-group ms-2">
                <a href="{{ route('admin.orders.pending') }}" class="btn btn-outline-warning">
                    <i class="fas fa-clock me-1"></i>Pending
                </a>
                <a href="{{ route('admin.orders.delivery') }}" class="btn btn-outline-info">
                    <i class="fas fa-shipping-fast me-1"></i>Delivery
                </a>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 bg-primary text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0">{{ $totalOrders }}</h4>
                            <small class="opacity-75">Total Orders</small>
                        </div>
                        <i class="fas fa-shopping-cart fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 bg-warning text-dark h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0">{{ $pendingOrders }}</h4>
                            <small class="opacity-75">Pending Orders</small>
                        </div>
                        <i class="fas fa-clock fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 bg-success text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0">{{ $todayOrders }}</h4>
                            <small class="opacity-75">Today's Orders</small>
                        </div>
                        <i class="fas fa-calendar-day fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 bg-info text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0">${{ number_format($revenue, 2) }}</h4>
                            <small class="opacity-75">Total Revenue</small>
                        </div>
                        <i class="fas fa-dollar-sign fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alerts -->
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

    <!-- Orders Table -->
    <div class="card border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="min-width: 1200px;">
                    <thead class="table-light">
                        <tr>
                            <th class="px-4 py-3 border-bottom" style="width: 140px;">Order Info</th>
                            <th class="px-4 py-3 border-bottom" style="width: 180px;">Customer</th>
                            <th class="px-4 py-3 border-bottom" style="width: 120px;">Date & Time</th>
                            <th class="px-4 py-3 border-bottom text-end" style="width: 120px;">Amount</th>
                            <th class="px-4 py-3 border-bottom text-center" style="width: 100px;">Status</th>
                            <th class="px-4 py-3 border-bottom text-center" style="width: 100px;">Payment</th>
                            <th class="px-4 py-3 border-bottom text-center" style="width: 140px;">Delivery</th>
                            <th class="px-4 py-3 border-bottom text-center" style="width: 200px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr class="border-bottom">
                            <!-- Order Info -->
                            <td class="px-4 py-3">
                                <div class="d-flex flex-column">
                                    <span class="fw-bold text-primary mb-1">{{ $order->order_number }}</span>
                                    <small class="text-muted">#{{ $order->id }}</small>
                                    <div class="mt-1">
                                        <small class="badge bg-light text-dark">
                                            <i class="fas fa-cube me-1"></i>{{ $order->items->count() }} items
                                        </small>
                                    </div>
                                </div>
                            </td>

                            <!-- Customer -->
                            <td class="px-4 py-3">
                                <div class="d-flex flex-column">
                                    <span class="fw-medium text-dark mb-1">{{ $order->customer->name ?? 'N/A' }}</span>
                                    <small class="text-muted text-truncate" style="max-width: 150px;">
                                        {{ $order->customer->email ?? 'No email' }}
                                    </small>
                                    @if($order->customer->phone ?? false)
                                    <small class="text-muted">
                                        <i class="fas fa-phone me-1"></i>{{ $order->customer->phone }}
                                    </small>
                                    @endif
                                </div>
                            </td>

                            <!-- Date & Time -->
                            <td class="px-4 py-3">
                                <div class="d-flex flex-column">
                                    <span class="fw-medium">{{ $order->created_at->format('M d, Y') }}</span>
                                    <small class="text-muted">{{ $order->created_at->format('h:i A') }}</small>
                                    @if($order->estimated_delivery_at)
                                    <small class="text-info mt-1">
                                        <i class="fas fa-clock me-1"></i>
                                        {{ $order->estimated_delivery_time }}
                                    </small>
                                    @endif
                                </div>
                            </td>

                            <!-- Amount -->
                            <td class="px-4 py-3 text-end">
                                <div class="d-flex flex-column align-items-end">
                                    <span class="fw-bold text-dark fs-6">${{ number_format($order->total_amount, 2) }}</span>
                                    <small class="text-muted">
                                        <span class="d-inline-block me-2">Sub: ${{ number_format($order->subtotal, 2) }}</span>
                                        <span class="d-inline-block">Ship: ${{ number_format($order->shipping_cost, 2) }}</span>
                                    </small>
                                </div>
                            </td>

                            <!-- Status -->
                            <td class="px-4 py-3 text-center">
                                <span class="badge {{ $order->status_badge_class }} px-3 py-2">
                                    <i class="fas fa-circle me-1" style="font-size: 6px;"></i>
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>

                            <!-- Payment -->
                            <td class="px-4 py-3 text-center">
                                <span class="badge {{ $order->payment_status_badge_class }} px-3 py-2">
                                    <i class="fas fa-credit-card me-1" style="font-size: 8px;"></i>
                                    {{ ucfirst($order->payment_status) }}
                                </span>
                                @if($order->payment_method)
                                <small class="d-block text-muted mt-1 text-truncate" style="max-width: 90px;">
                                    {{ $order->payment_method }}
                                </small>
                                @endif
                            </td>

                            <!-- Delivery -->
                            <td class="px-4 py-3 text-center">
                                <div class="d-flex flex-column align-items-center">
                                    <span class="badge {{ $order->delivery_status_badge_class }} px-3 py-2 mb-1">
                                        {{ $order->delivery_status_text }}
                                    </span>
                                    @if($order->delivery_person_name)
                                    <small class="text-muted text-truncate" style="max-width: 120px;">
                                        <i class="fas fa-user me-1"></i>{{ $order->delivery_person_name }}
                                    </small>
                                    @endif
                                </div>
                            </td>

                            <!-- Actions -->
                            <td class="px-4 py-3 text-center">
                                <div class="d-flex flex-column gap-2">
                                    <!-- Primary Actions -->
                                    <div class="d-flex justify-content-center gap-1">
                                        <a href="{{ route('admin.orders.show', $order->id) }}"
                                           class="btn btn-sm btn-outline-primary px-3"
                                           title="View Order">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.orders.edit', $order->id) }}"
                                           class="btn btn-sm btn-outline-secondary px-3"
                                           title="Edit Order">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="btn btn-sm btn-outline-danger px-3"
                                                    onclick="return confirm('Are you sure you want to delete this order?')"
                                                    title="Delete Order">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>

                                    <!-- Quick Actions -->
                                    <div class="d-flex justify-content-center gap-1 flex-wrap">
                                        @if($order->status === 'pending')
                                        <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="confirmed">
                                            <button type="submit" class="btn btn-sm btn-success px-2" title="Confirm Order">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                        @endif

                                        @if($order->canBeCancelled())
                                        <form action="{{ route('admin.orders.cancel', $order->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                    class="btn btn-sm btn-danger px-2"
                                                    onclick="return confirm('Are you sure you want to cancel this order?')"
                                                    title="Cancel Order">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                        @endif

                                        @if($order->delivery_status === 'pending' && $order->status === 'confirmed')
                                        <button type="button"
                                                class="btn btn-sm btn-info px-2"
                                                data-bs-toggle="modal"
                                                data-bs-target="#assignDeliveryModal{{ $order->id }}"
                                                title="Assign Delivery">
                                            <i class="fas fa-user"></i>
                                        </button>
                                        @endif

                                        @if($order->delivery_status === 'in_transit')
                                        <form action="{{ route('admin.orders.markAsDelivered', $order->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                    class="btn btn-sm btn-success px-2"
                                                    onclick="return confirm('Mark this order as delivered?')"
                                                    title="Mark Delivered">
                                                <i class="fas fa-check-circle"></i>
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>

                        <!-- Assign Delivery Modal -->
                        @if($order->delivery_status === 'pending' && $order->status === 'confirmed')
                        <div class="modal fade" id="assignDeliveryModal{{ $order->id }}" tabindex="-1">
                            <div class="modal-dialog modal-sm">
                                <div class="modal-content border-0">
                                    <div class="modal-header bg-light border-0">
                                        <h6 class="modal-title fw-bold">Assign Delivery</h6>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('admin.orders.assignDelivery', $order->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label small fw-bold">Delivery Person</label>
                                                <input type="text"
                                                       name="delivery_person_name"
                                                       class="form-control form-control-sm"
                                                       value="{{ $order->delivery_person_name }}"
                                                       required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label small fw-bold">Phone Number</label>
                                                <input type="text"
                                                       name="delivery_person_phone"
                                                       class="form-control form-control-sm"
                                                       value="{{ $order->delivery_person_phone }}"
                                                       required>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-0">
                                            <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-sm btn-primary">Assign</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endif
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="py-4">
                                    <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">No orders found</h5>
                                    <p class="text-muted mb-3">Get started by creating your first order</p>
                                    <a href="{{ route('admin.orders.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus me-2"></i>Create Order
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    @if($orders->hasPages())
    <div class="d-flex justify-content-center mt-4">
        <nav>
            {{ $orders->links() }}
        </nav>
    </div>
    @endif
</div>

<!-- Add Bootstrap Icons -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<!-- Add Bootstrap JS for Modals -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

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

.table {
    margin-bottom: 0;
}

.table th {
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.5px;
    background-color: #f8f9fa !important;
}

.table td {
    vertical-align: middle;
    border-bottom: 1px solid #f0f0f0;
}

.table-hover tbody tr:hover {
    background-color: #fafafa;
}

.badge {
    border-radius: 0;
    font-weight: 500;
    font-size: 0.7rem;
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

.btn-success {
    background-color: #198754;
    border-color: #198754;
}

.btn-danger {
    background-color: #dc3545;
    border-color: #dc3545;
}

.btn-warning {
    background-color: #ffc107;
    border-color: #ffc107;
    color: #000;
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

.btn-info {
    background-color: #0dcaf0;
    border-color: #0dcaf0;
    color: #000;
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
.bg-warning { background-color: #ffc107 !important; }
.bg-info { background-color: #0dcaf0 !important; }

/* Status badge colors */
.badge-warning { background-color: #ffc107; color: #000; }
.badge-info { background-color: #0dcaf0; color: #000; }
.badge-primary { background-color: #BC450D; color: #fff; }
.badge-secondary { background-color: #6c757d; color: #fff; }
.badge-success { background-color: #198754; color: #fff; }
.badge-danger { background-color: #dc3545; color: #fff; }
.badge-light { background-color: #f8f9fa; color: #000; border: 1px solid #dee2e6; }

.modal-content {
    border-radius: 0;
    border: 1px solid #dee2e6;
}

.modal-header {
    border-bottom: 1px solid #dee2e6;
    background-color: #f8f9fa;
}

.form-control {
    border-radius: 0;
    border: 1px solid #dee2e6;
}

.form-control:focus {
    border-color: #BC450D;
    box-shadow: none;
}

/* Responsive improvements */
@media (max-width: 768px) {
    .container-fluid {
        padding-left: 10px;
        padding-right: 10px;
    }

    .btn-group .btn {
        padding-left: 12px;
        padding-right: 12px;
    }
}

/* Table scroll improvements */
.table-responsive {
    border: 1px solid #f0f0f0;
}

/* Pagination styling */
.pagination {
    margin-bottom: 0;
}

.page-link {
    border-radius: 0;
    border: 1px solid #dee2e6;
    color: #BC450D;
}

.page-link:hover {
    background-color: #BC450D;
    border-color: #BC450D;
    color: white;
}

.page-item.active .page-link {
    background-color: #BC450D;
    border-color: #BC450D;
}
</style>
@endsection
