@extends('layouts.admin')

@section('title', 'Supa Farm Orders - SupaFarm Admin')
@section('page-title', 'Orders Overview')

@section('content')
    <div class="container-fluid">
        <!-- Header Section -->
        <div class="row mb-4">
            <div class="col-md-8">
                <h3 class="mb-1">All Orders</h3>
                <p class="text-muted mb-0">Manage customer orders and deliveries</p>
            </div>
            <div class="col-md-4 text-md-end">
                <a href="{{ route('admin.orders.create') }}" class="btn btn-primary px-4">
                    <i class="fas fa-plus me-2"></i>Create Order
                </a>
                <div class="btn-group ms-2">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-filter me-1"></i>Filters
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('admin.orders.index') }}">All Orders</a></li>
                        <li><a class="dropdown-item" href="{{ route('admin.orders.pending') }}">Pending Orders</a></li>
                        <li><a class="dropdown-item" href="{{ route('admin.orders.delivery') }}">Delivery Orders</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-muted mb-2">Total Orders</h6>
                                <h3 class="fw-bold mb-0">{{ $totalOrders }}</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-shopping-cart fa-2x text-primary"></i>
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
                                <h6 class="card-title text-muted mb-2">Pending Orders</h6>
                                <h3 class="fw-bold mb-0 text-warning">{{ $pendingOrders }}</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-clock fa-2x text-warning"></i>
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
                                <h6 class="card-title text-muted mb-2">Today's Orders</h6>
                                <h3 class="fw-bold mb-0 text-success">{{ $todayOrders }}</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-calendar-day fa-2x text-success"></i>
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
                                <h6 class="card-title text-muted mb-2">Total Revenue</h6>
                                <h3 class="fw-bold mb-0 text-info">
                                    {{ \App\Models\Order::getDefaultCurrencySymbol() }}{{ number_format($revenue, 2) }}</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-dollar-sign fa-2x text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alerts -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fas fa-check-circle me-2"></i>
                    <div class="flex-grow-1">{{ session('success') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <div class="flex-grow-1">{{ session('error') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif

        <!-- Filters and Search -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <form action="{{ route('admin.orders.index') }}" method="GET" class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-muted">Search Orders</label>
                        <input type="text" name="search" class="form-control"
                            placeholder="Search by order number, customer name..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-bold text-muted">Status</label>
                        <select name="status" class="form-select" onchange="this.form.submit()">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending
                            </option>
                            <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmed
                            </option>
                            <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>
                                Processing</option>
                            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed
                            </option>
                            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled
                            </option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-bold text-muted">Payment</label>
                        <select name="payment_status" class="form-select" onchange="this.form.submit()">
                            <option value="">All Payments</option>
                            <option value="pending" {{ request('payment_status') === 'pending' ? 'selected' : '' }}>
                                Pending</option>
                            <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Paid
                            </option>
                            <option value="failed" {{ request('payment_status') === 'failed' ? 'selected' : '' }}>Failed
                            </option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-bold text-muted">Delivery</label>
                        <select name="delivery_status" class="form-select" onchange="this.form.submit()">
                            <option value="">All Delivery</option>
                            <option value="pending" {{ request('delivery_status') === 'pending' ? 'selected' : '' }}>
                                Pending</option>
                            <option value="assigned" {{ request('delivery_status') === 'assigned' ? 'selected' : '' }}>
                                Assigned</option>
                            <option value="in_transit"
                                {{ request('delivery_status') === 'in_transit' ? 'selected' : '' }}>In Transit</option>
                            <option value="delivered" {{ request('delivery_status') === 'delivered' ? 'selected' : '' }}>
                                Delivered</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search me-1"></i>Search
                        </button>
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary w-100 mt-2">
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
                        <span id="selectedCount" class="fw-bold text-primary">0</span> orders selected
                    </div>
                    <div class="btn-group">
                        <button type="button" class="btn btn-sm btn-outline-success"
                            onclick="markSelectedAsConfirmed()">
                            <i class="fas fa-check me-1"></i>Confirm Selected
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-warning"
                            onclick="markSelectedAsProcessing()">
                            <i class="fas fa-cog me-1"></i>Mark as Processing
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="cancelSelected()">
                            <i class="fas fa-times me-1"></i>Cancel Selected
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Orders Cards Grid -->
        <div class="row g-4">
            @if ($orders->count() > 0)
                @foreach ($orders as $order)
                    <div class="col-xxl-4 col-xl-6 col-lg-6 col-md-12">
                        <div class="card order-card h-100 border-0 shadow-sm {{ $order->status === 'pending' ? 'border-warning' : '' }}"
                            data-order-id="{{ $order->id }}">
                            <div class="card-header bg-transparent border-0 pb-0">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="d-flex align-items-center">
                                        <input type="checkbox" class="order-checkbox me-2" value="{{ $order->id }}">
                                        <div>
                                            <h6 class="card-title mb-1 fw-bold text-primary">{{ $order->order_number }}
                                            </h6>
                                            <small class="text-muted">#{{ $order->id }} •
                                                {{ $order->created_at->format('M d, Y') }}</small>
                                        </div>
                                    </div>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary border-0" type="button"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a class="dropdown-item"
                                                    href="{{ route('admin.orders.show', $order->id) }}">
                                                    <i class="fas fa-eye me-2"></i>View Details
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item"
                                                    href="{{ route('admin.orders.edit', $order->id) }}">
                                                    <i class="fas fa-edit me-2"></i>Edit Order
                                                </a>
                                            </li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li>
                                                <form action="{{ route('admin.orders.destroy', $order->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger"
                                                        onclick="return confirm('Are you sure you want to delete this order?')">
                                                        <i class="fas fa-trash me-2"></i>Delete Order
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body">
                                <!-- Customer Info -->
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div>
                                            <h6 class="fw-semibold text-dark mb-1">{{ $order->customer->name ?? 'N/A' }}
                                            </h6>
                                            <small class="text-muted">
                                                <i
                                                    class="fas fa-envelope me-1"></i>{{ $order->customer->email ?? 'No email' }}
                                            </small>
                                        </div>
                                        <div class="text-end">
                                            <div class="fw-bold text-dark fs-5">{{ $order->formatted_total_amount }}</div>
                                            <small class="text-muted">{{ $order->items->count() }} items</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Status Badges -->
                                <div class="row g-2 mb-3">
                                    <div class="col-4">
                                        <span class="badge {{ $order->status_badge_class }} w-100 py-2">
                                            <i class="fas fa-circle me-1" style="font-size: 6px;"></i>
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </div>
                                    <div class="col-4">
                                        <span class="badge {{ $order->payment_status_badge_class }} w-100 py-2">
                                            <i class="fas fa-credit-card me-1" style="font-size: 8px;"></i>
                                            {{ ucfirst($order->payment_status) }}
                                        </span>
                                    </div>
                                    <div class="col-4">
                                        <span class="badge {{ $order->delivery_status_badge_class }} w-100 py-2">
                                            {{ $order->delivery_status_text }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Delivery Info -->
                                @if ($order->delivery_person_name || $order->estimated_delivery_at)
                                    <div class="mb-3 p-3 bg-light rounded">
                                        @if ($order->delivery_person_name)
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <small class="text-muted">Delivery Person</small>
                                                <small class="fw-medium">{{ $order->delivery_person_name }}</small>
                                            </div>
                                        @endif
                                        @if ($order->estimated_delivery_at)
                                            <div class="d-flex justify-content-between align-items-center">
                                                <small class="text-muted">Estimated Delivery</small>
                                                <small class="text-info">
                                                    <i class="fas fa-clock me-1"></i>
                                                    {{ $order->estimated_delivery_time }}
                                                </small>
                                            </div>
                                        @endif
                                    </div>
                                @endif

                                <!-- Order Items Preview -->
                                <div class="mb-3">
                                    <small class="text-muted fw-bold d-block mb-2">Order Items:</small>
                                    <div class="order-items-preview">
                                        @foreach ($order->items->take(3) as $item)
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <small class="text-truncate" style="max-width: 70%;">
                                                    {{ $item->product_name ?? 'Product' }}
                                                </small>
                                                <small class="text-muted">
                                                    {{ $item->quantity }} × {{ $order->formatAmount($item->price) }}
                                                </small>
                                            </div>
                                        @endforeach
                                        @if ($order->items->count() > 3)
                                            <small class="text-muted">+{{ $order->items->count() - 3 }} more items</small>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer bg-transparent border-0 pt-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        <i class="fas fa-clock me-1"></i>
                                        {{ $order->created_at->diffForHumans() }}
                                    </small>
                                    <div class="btn-group btn-group-sm">
                                        <!-- Quick Actions -->
                                        <a href="{{ route('admin.orders.show', $order->id) }}"
                                            class="btn btn-outline-primary btn-sm" title="View Order">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        @if ($order->status === 'pending')
                                            <form action="{{ route('admin.orders.updateStatus', $order->id) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="confirmed">
                                                <button type="submit" class="btn btn-outline-success btn-sm"
                                                    title="Confirm Order">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                        @endif

                                        @if ($order->canBeCancelled())
                                            <form action="{{ route('admin.orders.cancel', $order->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-outline-danger btn-sm"
                                                    onclick="return confirm('Are you sure you want to cancel this order?')"
                                                    title="Cancel Order">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        @endif

                                        @if ($order->delivery_status === 'pending' && $order->status === 'confirmed')
                                            <button type="button" class="btn btn-outline-info btn-sm"
                                                data-bs-toggle="modal"
                                                data-bs-target="#assignDeliveryModal{{ $order->id }}"
                                                title="Assign Delivery">
                                                <i class="fas fa-user"></i>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Assign Delivery Modal -->
                    @if ($order->delivery_status === 'pending' && $order->status === 'confirmed')
                        <div class="modal fade" id="assignDeliveryModal{{ $order->id }}" tabindex="-1">
                            <div class="modal-dialog modal-sm">
                                <div class="modal-content border-0 shadow-lg">
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
                                                <input type="text" name="delivery_person_name"
                                                    class="form-control form-control-sm"
                                                    value="{{ $order->delivery_person_name }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label small fw-bold">Phone Number</label>
                                                <input type="text" name="delivery_person_phone"
                                                    class="form-control form-control-sm"
                                                    value="{{ $order->delivery_person_phone }}" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-0">
                                            <button type="button" class="btn btn-sm btn-outline-secondary"
                                                data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-sm btn-primary">Assign</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            @else
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center py-5">
                            <i class="fas fa-shopping-cart fa-4x text-muted mb-4"></i>
                            <h4 class="text-muted mb-3">No orders found</h4>
                            <p class="text-muted mb-4">Get started by creating your first order</p>
                            <a href="{{ route('admin.orders.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Create Order
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Pagination -->
        @if ($orders->count() > 0)
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            {{ $orders->links() }}
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
        .order-card {
            transition: all 0.3s ease;
            border: 1px solid #e9ecef;
        }

        .order-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1) !important;
        }

        .order-card.border-warning {
            border-left: 4px solid #ffc107 !important;
            background: linear-gradient(135deg, #fff3cd08 0%, #ffffff 100%);
        }

        .order-items-preview {
            max-height: 120px;
            overflow-y: auto;
        }

        .order-items-preview::-webkit-scrollbar {
            width: 4px;
        }

        .order-items-preview::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 2px;
        }

        .order-items-preview::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 2px;
        }

        .order-checkbox {
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

        /* Status badge improvements */
        .badge {
            font-size: 0.7rem;
            font-weight: 500;
            padding: 0.5em 0.75em;
        }

        .badge-warning {
            background-color: #fff3cd !important;
            color: #856404 !important;
            border: 1px solid #ffeaa7;
        }

        .badge-success {
            background-color: #d1edff !important;
            color: #0c5460 !important;
            border: 1px solid #bee5eb;
        }

        .badge-primary {
            background-color: #d1ecf1 !important;
            color: #0c5460 !important;
            border: 1px solid #bee5eb;
        }

        .badge-info {
            background-color: #d1ecf1 !important;
            color: #0c5460 !important;
            border: 1px solid #bee5eb;
        }

        .badge-danger {
            background-color: #f8d7da !important;
            color: #721c24 !important;
            border: 1px solid #f5c6cb;
        }

        .badge-secondary {
            background-color: #e2e3e5 !important;
            color: #383d41 !important;
            border: 1px solid #d6d8db;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .order-card .btn-group-sm .btn {
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

            .order-items-preview {
                max-height: 100px;
            }
        }

        @media (max-width: 576px) {

            .col-xxl-4,
            .col-xl-6,
            .col-lg-6 {
                flex: 0 0 100%;
                max-width: 100%;
            }

            .order-card {
                margin-bottom: 1rem;
            }
        }

        /* Selection styles */
        .order-card.selected {
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

        /* Custom colors for buttons */
        .btn-primary {
            background-color: #BC450D;
            border-color: #BC450D;
        }

        .btn-primary:hover {
            background-color: #a33a0b;
            border-color: #a33a0b;
        }

        /* Alert improvements */
        .alert {
            border-radius: 8px;
        }

        /* Modal improvements */
        .modal-content {
            border-radius: 8px;
        }
    </style>
@endpush

@push('scripts')
    <script>
        let selectedOrders = new Set();

        function getSelectedIds() {
            return Array.from(selectedOrders);
        }

        function updateBulkActions() {
            const selectedCount = selectedOrders.size;
            const bulkActionsCard = document.getElementById('bulkActionsCard');
            const selectedCountElement = document.getElementById('selectedCount');

            if (selectedCount > 0) {
                bulkActionsCard.style.display = 'block';
                selectedCountElement.textContent = selectedCount;

                // Add selected class to cards
                document.querySelectorAll('.order-card').forEach(card => {
                    const orderId = card.getAttribute('data-order-id');
                    if (selectedOrders.has(orderId)) {
                        card.classList.add('selected');
                    } else {
                        card.classList.remove('selected');
                    }
                });
            } else {
                bulkActionsCard.style.display = 'none';
                document.querySelectorAll('.order-card').forEach(card => {
                    card.classList.remove('selected');
                });
            }
        }

        function toggleOrderSelection(checkbox) {
            const orderId = checkbox.value;
            const card = checkbox.closest('.order-card');

            if (checkbox.checked) {
                selectedOrders.add(orderId);
                card.classList.add('selected');
            } else {
                selectedOrders.delete(orderId);
                card.classList.remove('selected');
            }

            updateBulkActions();
        }

        function toggleSelectAll(source) {
            const checkboxes = document.querySelectorAll('.order-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = source.checked;
                toggleOrderSelection(checkbox);
            });
        }

        function selectAll() {
            const checkboxes = document.querySelectorAll('.order-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = true;
                toggleOrderSelection(checkbox);
            });
        }

        function clearAllSelections() {
            selectedOrders.clear();
            const checkboxes = document.querySelectorAll('.order-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = false;
            });
            updateBulkActions();
        }

        function markSelectedAsConfirmed() {
            const ids = getSelectedIds();
            if (ids.length === 0) {
                alert('Please select at least one order.');
                return;
            }

            if (confirm(`Are you sure you want to confirm ${ids.length} order(s)?`)) {
                const form = document.getElementById('bulkActionForm');
                form.action = "{{ route('admin.orders.bulk.confirm') }}";
                document.getElementById('selectedIds').value = JSON.stringify(ids);
                form.submit();
            }
        }

        function markSelectedAsProcessing() {
            const ids = getSelectedIds();
            if (ids.length === 0) {
                alert('Please select at least one order.');
                return;
            }

            if (confirm(`Are you sure you want to mark ${ids.length} order(s) as processing?`)) {
                const form = document.getElementById('bulkActionForm');
                form.action = "{{ route('admin.orders.bulk.processing') }}";
                document.getElementById('selectedIds').value = JSON.stringify(ids);
                form.submit();
            }
        }

        function cancelSelected() {
            const ids = getSelectedIds();
            if (ids.length === 0) {
                alert('Please select at least one order.');
                return;
            }

            if (confirm(`Are you sure you want to cancel ${ids.length} order(s)? This action cannot be undone.`)) {
                const form = document.getElementById('bulkActionForm');
                form.action = "{{ route('admin.orders.bulk.cancel') }}";
                document.getElementById('selectedIds').value = JSON.stringify(ids);
                form.submit();
            }
        }

        // Initialize event listeners
        document.addEventListener('DOMContentLoaded', function() {
            // Add change event to all checkboxes
            document.querySelectorAll('.order-checkbox').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    toggleOrderSelection(this);
                });
            });

            // Add click event to cards for selection (optional)
            document.querySelectorAll('.order-card').forEach(card => {
                card.addEventListener('click', function(e) {
                    // Don't trigger if clicking on links, buttons, or the checkbox itself
                    if (e.target.tagName === 'A' || e.target.tagName === 'BUTTON' || e.target
                        .type === 'checkbox' || e.target.closest('button') || e.target.closest('a')
                    ) {
                        return;
                    }

                    const checkbox = this.querySelector('.order-checkbox');
                    if (checkbox) {
                        checkbox.checked = !checkbox.checked;
                        toggleOrderSelection(checkbox);
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
