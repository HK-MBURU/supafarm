@extends('layouts.admin')

@section('title', 'Order ' . $order->order_number . ' - SupaFarm Admin')
@section('page-title', 'Order Details')

@section('content')
<div class="container-fluid">
    <!-- Back Button -->
    <div class="row mb-4">
        <div class="col-12">
            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Orders
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Left Column - Order Details -->
        <div class="col-lg-8">
            <!-- Order Summary -->
            <div class="card border-0 mb-4">
                <div class="card-header bg-light border-0 d-flex justify-content-between align-items-center py-3">
                    <h5 class="card-title mb-0 fw-bold">Order Summary</h5>
                    <div class="d-flex gap-2">
                        <span class="badge {{ $order->status_badge_class }} px-3 py-2">
                            {{ ucfirst($order->status) }}
                        </span>
                        <span class="badge {{ $order->payment_status_badge_class }} px-3 py-2">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="text-muted" style="width: 140px;">Order Number:</td>
                                    <td class="fw-bold text-primary">{{ $order->order_number }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Order Date:</td>
                                    <td>{{ $order->created_at->format('M d, Y \a\t h:i A') }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Customer:</td>
                                    <td class="fw-medium">{{ $order->customer->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Email:</td>
                                    <td>{{ $order->customer->email ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="text-muted" style="width: 140px;">Payment Method:</td>
                                    <td>{{ $order->payment_method ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Payment Reference:</td>
                                    <td class="font-monospace">{{ $order->payment_reference ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Currency:</td>
                                    <td>{{ $order->currency }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Assigned User:</td>
                                    <td>{{ $order->user->name ?? 'Not assigned' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="card border-0 mb-4">
                <div class="card-header bg-light border-0 py-3">
                    <h5 class="card-title mb-0 fw-bold">Order Items ({{ $order->items->count() }})</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="px-4 py-3 border-bottom">Product</th>
                                    <th class="px-4 py-3 border-bottom text-center">Price</th>
                                    <th class="px-4 py-3 border-bottom text-center">Quantity</th>
                                    <th class="px-4 py-3 border-bottom text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr class="border-bottom">
                                    <td class="px-4 py-3">
                                        <div class="d-flex align-items-center">
                                            @if($item->product->image_url ?? false)
                                            <img src="{{ $item->product->image_url }}"
                                                 alt="{{ $item->product->name }}"
                                                 class="rounded me-3"
                                                 style="width: 50px; height: 50px; object-fit: cover;">
                                            @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center me-3"
                                                 style="width: 50px; height: 50px;">
                                                <i class="fas fa-cube text-muted"></i>
                                            </div>
                                            @endif
                                            <div>
                                                <div class="fw-medium">{{ $item->product->name ?? 'Product Deleted' }}</div>
                                                @if($item->product->sku ?? false)
                                                <small class="text-muted">SKU: {{ $item->product->sku }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        ${{ number_format($item->unit_price, 2) }}
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="badge bg-secondary">{{ $item->quantity }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-end fw-bold">
                                        ${{ number_format($item->total_price, 2) }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-light">
                                <tr>
                                    <td colspan="3" class="px-4 py-3 text-end fw-medium">Subtotal:</td>
                                    <td class="px-4 py-3 text-end fw-bold">${{ number_format($order->subtotal, 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="px-4 py-3 text-end fw-medium">Shipping Cost:</td>
                                    <td class="px-4 py-3 text-end fw-bold">${{ number_format($order->shipping_cost, 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="px-4 py-3 text-end fw-medium">Tax Amount:</td>
                                    <td class="px-4 py-3 text-end fw-bold">${{ number_format($order->tax_amount, 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="px-4 py-3 text-end fw-bold">Total Amount:</td>
                                    <td class="px-4 py-3 text-end fw-bold fs-5 text-primary">
                                        ${{ number_format($order->total_amount, 2) }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Delivery Information -->
            <div class="card border-0">
                <div class="card-header bg-light border-0 py-3">
                    <h5 class="card-title mb-0 fw-bold">Delivery Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="fw-bold mb-3">Delivery Status</h6>
                            <div class="mb-4">
                                <span class="badge {{ $order->delivery_status_badge_class }} px-3 py-2 fs-6">
                                    {{ $order->delivery_status_text }}
                                </span>
                            </div>

                            @if($order->delivery_person_name)
                            <div class="mb-3">
                                <strong class="text-muted d-block">Delivery Person</strong>
                                <div class="d-flex align-items-center mt-1">
                                    <i class="fas fa-user text-primary me-2"></i>
                                    <span>{{ $order->delivery_person_name }}</span>
                                </div>
                            </div>
                            @endif

                            @if($order->delivery_person_phone)
                            <div class="mb-3">
                                <strong class="text-muted d-block">Contact Number</strong>
                                <div class="d-flex align-items-center mt-1">
                                    <i class="fas fa-phone text-primary me-2"></i>
                                    <span>{{ $order->delivery_person_phone }}</span>
                                </div>
                            </div>
                            @endif

                            @if($order->delivery_zone)
                            <div class="mb-3">
                                <strong class="text-muted d-block">Delivery Zone</strong>
                                <span>{{ $order->delivery_zone }}</span>
                            </div>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <h6 class="fw-bold mb-3">Timeline</h6>
                            <div class="timeline">
                                @if($order->confirmed_at)
                                <div class="timeline-item mb-3">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-check-circle text-success me-3 fs-5"></i>
                                        <div>
                                            <div class="fw-medium">Order Confirmed</div>
                                            <small class="text-muted">{{ $order->confirmed_at->format('M d, Y \a\t h:i A') }}</small>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                @if($order->prepared_at)
                                <div class="timeline-item mb-3">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-box text-info me-3 fs-5"></i>
                                        <div>
                                            <div class="fw-medium">Order Prepared</div>
                                            <small class="text-muted">{{ $order->prepared_at->format('M d, Y \a\t h:i A') }}</small>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                @if($order->dispatched_at)
                                <div class="timeline-item mb-3">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-shipping-fast text-warning me-3 fs-5"></i>
                                        <div>
                                            <div class="fw-medium">Dispatched</div>
                                            <small class="text-muted">{{ $order->dispatched_at->format('M d, Y \a\t h:i A') }}</small>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                @if($order->delivered_at)
                                <div class="timeline-item mb-3">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-check-double text-success me-3 fs-5"></i>
                                        <div>
                                            <div class="fw-medium">Delivered</div>
                                            <small class="text-muted">{{ $order->delivered_at->format('M d, Y \a\t h:i A') }}</small>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                @if($order->estimated_delivery_at)
                                <div class="timeline-item">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-clock text-primary me-3 fs-5"></i>
                                        <div>
                                            <div class="fw-medium">Estimated Delivery</div>
                                            <small class="text-muted">{{ $order->estimated_delivery_at->format('M d, Y \a\t h:i A') }}</small>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($order->delivery_instructions)
                    <div class="row mt-4">
                        <div class="col-12">
                            <h6 class="fw-bold mb-2">Delivery Instructions</h6>
                            <p class="text-muted mb-0">{{ $order->delivery_instructions }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column - Address & Actions -->
        <div class="col-lg-4">
            <!-- Address Information -->
            <div class="card border-0 mb-4">
                <div class="card-header bg-light border-0 py-3">
                    <h5 class="card-title mb-0 fw-bold">Address Information</h5>
                </div>
                <div class="card-body">
                    <!-- Billing Address -->
                    <div class="mb-4">
                        <h6 class="fw-bold mb-3 text-primary">Billing Address</h6>
                        @if(is_array($order->billing_address))
                        <address class="mb-0">
                            <strong>{{ $order->billing_address['full_name'] ?? 'N/A' }}</strong><br>
                            {{ $order->billing_address['address'] ?? '' }}<br>
                            @if($order->billing_address['city'] ?? false)
                            {{ $order->billing_address['city'] }},
                            @endif
                            {{ $order->billing_address['state'] ?? '' }}
                            {{ $order->billing_address['zip_code'] ?? '' }}<br>
                            {{ $order->billing_address['country'] ?? '' }}<br>
                            @if($order->billing_address['phone'] ?? false)
                            <i class="fas fa-phone me-1"></i>{{ $order->billing_address['phone'] }}
                            @endif
                        </address>
                        @else
                        <p class="text-muted mb-0">No billing address provided</p>
                        @endif
                    </div>

                    <!-- Shipping Address -->
                    <div>
                        <h6 class="fw-bold mb-3 text-primary">Shipping Address</h6>
                        @if(is_array($order->shipping_address))
                        <address class="mb-0">
                            <strong>{{ $order->shipping_address['full_name'] ?? 'N/A' }}</strong><br>
                            {{ $order->shipping_address['address'] ?? '' }}<br>
                            @if($order->shipping_address['city'] ?? false)
                            {{ $order->shipping_address['city'] }},
                            @endif
                            {{ $order->shipping_address['state'] ?? '' }}
                            {{ $order->shipping_address['zip_code'] ?? '' }}<br>
                            {{ $order->shipping_address['country'] ?? '' }}<br>
                            @if($order->shipping_address['phone'] ?? false)
                            <i class="fas fa-phone me-1"></i>{{ $order->shipping_address['phone'] }}
                            @endif
                        </address>
                        @else
                        <p class="text-muted mb-0">No shipping address provided</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Order Actions -->
            <div class="card border-0 mb-4">
                <div class="card-header bg-light border-0 py-3">
                    <h5 class="card-title mb-0 fw-bold">Order Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.orders.edit', $order->id) }}"
                           class="btn btn-primary">
                            <i class="fas fa-edit me-2"></i>Edit Order
                        </a>

                        <!-- Status Update -->
                        <div class="dropdown">
                            <button class="btn btn-outline-primary dropdown-toggle w-100" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-sync me-2"></i>Update Status
                            </button>
                            <ul class="dropdown-menu w-100">
                                <li>
                                    <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="confirmed">
                                        <button type="submit" class="dropdown-item">Mark as Confirmed</button>
                                    </form>
                                </li>
                                <li>
                                    <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="processing">
                                        <button type="submit" class="dropdown-item">Mark as Processing</button>
                                    </form>
                                </li>
                                <li>
                                    <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="shipped">
                                        <button type="submit" class="dropdown-item">Mark as Shipped</button>
                                    </form>
                                </li>
                                <li>
                                    <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="delivered">
                                        <button type="submit" class="dropdown-item">Mark as Delivered</button>
                                    </form>
                                </li>
                            </ul>
                        </div>

                        <!-- Delivery Actions -->
                        @if($order->delivery_status !== 'delivered')
                        <div class="dropdown">
                            <button class="btn btn-outline-info dropdown-toggle w-100" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-shipping-fast me-2"></i>Delivery Actions
                            </button>
                            <ul class="dropdown-menu w-100">
                                @if($order->delivery_status === 'pending')
                                <li>
                                    <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#assignDeliveryModal">
                                        Assign Delivery Person
                                    </button>
                                </li>
                                @endif
                                <li>
                                    <form action="{{ route('admin.orders.updateDeliveryStatus', $order->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="delivery_status" value="picked_up">
                                        <button type="submit" class="dropdown-item">Mark as Picked Up</button>
                                    </form>
                                </li>
                                <li>
                                    <form action="{{ route('admin.orders.updateDeliveryStatus', $order->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="delivery_status" value="in_transit">
                                        <button type="submit" class="dropdown-item">Mark as In Transit</button>
                                    </form>
                                </li>
                                <li>
                                    <form action="{{ route('admin.orders.markAsDelivered', $order->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="dropdown-item text-success">Mark as Delivered</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                        @endif

                        <!-- Payment Actions -->
                        <div class="dropdown">
                            <button class="btn btn-outline-success dropdown-toggle w-100" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-credit-card me-2"></i>Payment Actions
                            </button>
                            <ul class="dropdown-menu w-100">
                                <li>
                                    <form action="{{ route('admin.orders.updatePaymentStatus', $order->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="payment_status" value="paid">
                                        <button type="submit" class="dropdown-item">Mark as Paid</button>
                                    </form>
                                </li>
                                <li>
                                    <form action="{{ route('admin.orders.updatePaymentStatus', $order->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="payment_status" value="failed">
                                        <button type="submit" class="dropdown-item">Mark as Failed</button>
                                    </form>
                                </li>
                                <li>
                                    <form action="{{ route('admin.orders.updatePaymentStatus', $order->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="payment_status" value="refunded">
                                        <button type="submit" class="dropdown-item">Mark as Refunded</button>
                                    </form>
                                </li>
                            </ul>
                        </div>

                        <!-- Danger Zone -->
                        @if($order->canBeCancelled())
                        <form action="{{ route('admin.orders.cancel', $order->id) }}" method="POST" class="d-grid">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                    class="btn btn-outline-danger"
                                    onclick="return confirm('Are you sure you want to cancel this order?')">
                                <i class="fas fa-times me-2"></i>Cancel Order
                            </button>
                        </form>
                        @endif

                        @if(in_array($order->status, ['pending', 'cancelled']))
                        <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" class="d-grid">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="btn btn-danger"
                                    onclick="return confirm('Are you sure you want to delete this order? This action cannot be undone.')">
                                <i class="fas fa-trash me-2"></i>Delete Order
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Order Progress -->
            <div class="card border-0">
                <div class="card-header bg-light border-0 py-3">
                    <h5 class="card-title mb-0 fw-bold">Order Progress</h5>
                </div>
                <div class="card-body">
                    <div class="progress mb-3" style="height: 8px;">
                        <div class="progress-bar bg-success"
                             role="progressbar"
                             style="width: {{ $order->progress_percentage }}%"
                             aria-valuenow="{{ $order->progress_percentage }}"
                             aria-valuemin="0"
                             aria-valuemax="100">
                        </div>
                    </div>
                    <div class="text-center">
                        <span class="fw-bold text-primary">{{ $order->progress_percentage }}%</span>
                        <small class="text-muted d-block">Complete</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Assign Delivery Modal -->
<div class="modal fade" id="assignDeliveryModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content border-0">
            <div class="modal-header bg-light border-0">
                <h6 class="modal-title fw-bold">Assign Delivery Person</h6>
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

<!-- Add Bootstrap JS for Dropdowns and Modals -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<style>
.card {
    border-radius: 0;
}

.btn {
    border-radius: 0;
    border: 1px solid;
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

.btn-outline-danger {
    color: #dc3545;
    border-color: #dc3545;
}

.btn-outline-danger:hover {
    background-color: #dc3545;
    border-color: #dc3545;
    color: white;
}

/* Status badge colors */
.badge-warning { background-color: #ffc107; color: #000; }
.badge-info { background-color: #0dcaf0; color: #000; }
.badge-primary { background-color: #BC450D; color: #fff; }
.badge-secondary { background-color: #6c757d; color: #fff; }
.badge-success { background-color: #198754; color: #fff; }
.badge-danger { background-color: #dc3545; color: #fff; }

/* Timeline */
.timeline {
    position: relative;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background-color: #e9ecef;
}

.timeline-item {
    position: relative;
}

/* Progress bar */
.progress {
    border-radius: 0;
}

.progress-bar {
    border-radius: 0;
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

address {
    font-style: normal;
    line-height: 1.6;
}
</style>
@endsection
