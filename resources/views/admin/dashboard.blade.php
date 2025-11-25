@extends('layouts.admin')

@section('title', 'Dashboard - SupaFarm Admin')
@section('page-title', 'Dashboard Overview')

@section('content')
<div class="container-fluid">
    <!-- Stats Grid -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-muted mb-2">Total Orders</h6>
                            <h3 class="fw-bold mb-0 text-primary">{{ $stats['totalOrders'] }}</h3>
                            <small class="text-muted">
                                <i class="fas fa-clock me-1"></i>
                                {{ $stats['todayOrders'] }} today
                            </small>
                        </div>
                        <div class="align-self-center">
                            <div class="bg-primary bg-opacity-10 p-3 rounded">
                                <i class="fas fa-shopping-cart fa-2x text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-muted mb-2">Total Revenue</h6>
                            <h3 class="fw-bold mb-0 text-success">
                                {{ \App\Models\Order::getDefaultCurrencySymbol() }}{{ number_format($stats['totalRevenue'], 2) }}
                            </h3>
                            <small class="text-muted">
                                <i class="fas fa-calendar me-1"></i>
                                {{ \App\Models\Order::getDefaultCurrencySymbol() }}{{ number_format($stats['thisWeekRevenue'], 2) }} this week
                            </small>
                        </div>
                        <div class="align-self-center">
                            <div class="bg-success bg-opacity-10 p-3 rounded">
                                <i class="fas fa-dollar-sign fa-2x text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-muted mb-2">Products & Categories</h6>
                            <h3 class="fw-bold mb-0 text-info">{{ $stats['totalProducts'] }}</h3>
                            <small class="text-muted">
                                <i class="fas fa-tags me-1"></i>
                                {{ $stats['totalCategories'] }} categories
                            </small>
                        </div>
                        <div class="align-self-center">
                            <div class="bg-info bg-opacity-10 p-3 rounded">
                                <i class="fas fa-cube fa-2x text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-muted mb-2">Success Rate</h6>
                            <h3 class="fw-bold mb-0 text-warning">{{ $stats['successRate'] }}%</h3>
                            <small class="text-muted">
                                <i class="fas fa-users me-1"></i>
                                {{ $stats['totalCustomers'] }} customers
                            </small>
                        </div>
                        <div class="align-self-center">
                            <div class="bg-warning bg-opacity-10 p-3 rounded">
                                <i class="fas fa-chart-line fa-2x text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Orders & Quick Actions -->
        <div class="col-lg-8">
            <!-- Recent Orders -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fw-bold">Recent Orders</h5>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-primary">
                        View All
                    </a>
                </div>
                <div class="card-body p-0">
                    @if($recentOrders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="px-4 py-3 border-bottom">Order #</th>
                                        <th class="px-4 py-3 border-bottom">Customer</th>
                                        <th class="px-4 py-3 border-bottom text-end">Amount</th>
                                        <th class="px-4 py-3 border-bottom text-center">Status</th>
                                        <th class="px-4 py-3 border-bottom text-center">Date</th>
                                        <th class="px-4 py-3 border-bottom text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentOrders as $order)
                                    <tr class="border-bottom">
                                        <td class="px-4 py-3">
                                            <span class="fw-bold text-primary">{{ $order->order_number }}</span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="d-flex flex-column">
                                                <span class="fw-medium text-dark">{{ $order->customer->full_name ?? 'N/A' }}</span>
                                                <small class="text-muted">{{ $order->customer->email ?? '' }}</small>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-end">
                                            <span class="fw-bold text-dark">{{ $order->formatted_total_amount }}</span>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <span class="badge {{ $order->status_badge_class }} px-3 py-2">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <small class="text-muted">{{ $order->created_at->format('M d, Y') }}</small>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <a href="{{ route('admin.orders.show', $order->id) }}"
                                               class="btn btn-sm btn-outline-primary"
                                               title="View Order">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No orders yet</h5>
                            <p class="text-muted">Start by creating your first order</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0">
                    <h5 class="card-title mb-0 fw-bold">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3 col-6">
                            <a href="{{ route('admin.products.create') }}" class="btn btn-primary w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3">
                                <i class="fas fa-plus-circle fa-2x mb-2"></i>
                                <span>Add Product</span>
                            </a>
                        </div>
                        <div class="col-md-3 col-6">
                            <a href="{{ route('admin.categories.index') }}" class="btn btn-success w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3">
                                <i class="fas fa-tags fa-2x mb-2"></i>
                                <span>Categories</span>
                            </a>
                        </div>
                        <div class="col-md-3 col-6">
                            <a href="{{ route('admin.orders.index') }}" class="btn btn-info w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3">
                                <i class="fas fa-shopping-cart fa-2x mb-2"></i>
                                <span>View Orders</span>
                            </a>
                        </div>
                        <div class="col-md-3 col-6">
                            <a href="{{ route('admin.about.edit') }}" class="btn btn-warning w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3">
                                <i class="fas fa-edit fa-2x mb-2"></i>
                                <span>About Page</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar - Recent Activity -->
        <div class="col-lg-4">
            <!-- Low Stock Alert -->
            @if($lowStockProducts->count() > 0)
            <div class="card border-0 shadow-sm mb-4 border-warning">
                <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fw-bold text-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>Low Stock
                    </h5>
                    <span class="badge bg-warning">{{ $lowStockProducts->count() }}</span>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @foreach($lowStockProducts as $product)
                        <div class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                            <div>
                                <h6 class="mb-1 fw-semibold">{{ Str::limit($product->name, 25) }}</h6>
                                <small class="text-muted">Stock: {{ $product->stock }}</small>
                            </div>
                            <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-outline-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Recent Contacts -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fw-bold">Recent Contacts</h5>
                    <a href="{{ route('admin.contacts.index') }}" class="btn btn-sm btn-outline-primary">
                        View All
                    </a>
                </div>
                <div class="card-body">
                    @if($recentContacts->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($recentContacts as $contact)
                            <div class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 {{ $contact->is_read ? '' : 'bg-light' }}">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 fw-semibold">{{ $contact->name }}</h6>
                                    <p class="mb-1 text-muted small">{{ Str::limit($contact->subject, 40) }}</p>
                                    <small class="text-muted">{{ $contact->created_at->diffForHumans() }}</small>
                                </div>
                                @if(!$contact->is_read)
                                    <span class="badge bg-warning ms-2">New</span>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-envelope fa-2x text-muted mb-2"></i>
                            <p class="text-muted mb-0">No contact messages</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- System Status -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0">
                    <h5 class="card-title mb-0 fw-bold">System Status</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="fw-medium">Orders Pending</span>
                        <span class="badge bg-warning">{{ $stats['pendingOrders'] }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="fw-medium">Total Customers</span>
                        <span class="badge bg-info">{{ $stats['totalCustomers'] }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="fw-medium">Contact Messages</span>
                        <span class="badge bg-primary">{{ $stats['totalContacts'] }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-medium">Active Products</span>
                        <span class="badge bg-success">{{ $stats['totalProducts'] }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.card {
    border-radius: 12px;
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1) !important;
}

.btn {
    border-radius: 8px;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-1px);
}

.badge {
    border-radius: 6px;
    font-weight: 500;
}

.table th {
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.5px;
    background-color: #f8f9fa !important;
}

.list-group-item {
    border-radius: 8px;
    margin-bottom: 8px;
    border: 1px solid #f0f0f0;
}

.list-group-item:hover {
    background-color: #f8f9fa;
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

.btn-success {
    background-color: #198754;
    border-color: #198754;
}

.btn-info {
    background-color: #0dcaf0;
    border-color: #0dcaf0;
    color: #000;
}

.btn-warning {
    background-color: #ffc107;
    border-color: #ffc107;
    color: #000;
}

/* Status badges */
.badge-warning { background-color: #ffc107; color: #000; }
.badge-success { background-color: #198754; color: #fff; }
.badge-info { background-color: #0dcaf0; color: #000; }
.badge-primary { background-color: #BC450D; color: #fff; }
.badge-secondary { background-color: #6c757d; color: #fff; }

/* Responsive adjustments */
@media (max-width: 768px) {
    .card-body .row .col-md-3 {
        margin-bottom: 15px;
    }

    .table-responsive {
        font-size: 0.875rem;
    }
}
</style>
@endpush
