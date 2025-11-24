@extends('layouts.admin')

@section('title', 'Dashboard - SupaFarm Admin')
@section('page-title', 'Dashboard Overview')

@section('content')
    <!-- Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-value">1,247</div>
            <div class="stat-label">Total Orders</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">$24,580</div>
            <div class="stat-label">Total Revenue</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">356</div>
            <div class="stat-label">Products</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">89%</div>
            <div class="stat-label">Success Rate</div>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="card">
        <div class="card-header">
            <h3>Recent Orders</h3>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Customer</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>#SF-001</td>
                        <td>John Doe</td>
                        <td>$120.00</td>
                        <td><span class="badge badge-success">Delivered</span></td>
                        <td>2024-01-15</td>
                        <td>
                            <a href="#" class="btn btn-sm btn-accent">View</a>
                        </td>
                    </tr>
                    <tr>
                        <td>#SF-002</td>
                        <td>Jane Smith</td>
                        <td>$85.50</td>
                        <td><span class="badge badge-warning">Processing</span></td>
                        <td>2024-01-15</td>
                        <td>
                            <a href="#" class="btn btn-sm btn-accent">View</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="card">
        <div class="card-header">
            <h3>Quick Actions</h3>
        </div>
        <div class="card-body">
            <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                <a href="#" class="btn btn-primary">Add New Product</a>
                <a href="#" class="btn btn-secondary">Manage Categories</a>
                <a href="#" class="btn btn-accent">View Orders</a>
                <a href="#" class="btn">Update About Page</a>
            </div>
        </div>
    </div>
@endsection
