@extends('layouts.admin')

@section('title', 'Edit Order ' . $order->order_number . ' - SupaFarm Admin')
@section('page-title', 'Edit Order')

@section('content')
<div class="container-fluid">
    <!-- Back Button -->
    <div class="row mb-4">
        <div class="col-12">
            <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Order
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Left Column - Edit Form -->
        <div class="col-lg-8">
            <div class="card border-0">
                <div class="card-header bg-light border-0">
                    <h5 class="card-title mb-0 fw-bold">Edit Order Details</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Basic Information -->
                        <div class="mb-4">
                            <h6 class="fw-bold border-bottom pb-2 mb-3">Basic Information</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="customer_id" class="form-label">Customer <span class="text-danger">*</span></label>
                                        <select class="form-control @error('customer_id') is-invalid @enderror"
                                                id="customer_id"
                                                name="customer_id"
                                                required>
                                            <option value="">Select Customer</option>
                                            @foreach($customers as $customer)
                                            <option value="{{ $customer->id }}"
                                                    {{ old('customer_id', $order->customer_id) == $customer->id ? 'selected' : '' }}>
                                                {{ $customer->name }} - {{ $customer->email }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('customer_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="user_id" class="form-label">Assigned User</label>
                                        <select class="form-control @error('user_id') is-invalid @enderror"
                                                id="user_id"
                                                name="user_id">
                                            <option value="">Not Assigned</option>
                                            @foreach($users as $user)
                                            <option value="{{ $user->id }}"
                                                    {{ old('user_id', $order->user_id) == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('user_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="status" class="form-label">Order Status <span class="text-danger">*</span></label>
                                        <select class="form-control @error('status') is-invalid @enderror"
                                                id="status"
                                                name="status"
                                                required>
                                            <option value="pending" {{ old('status', $order->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="confirmed" {{ old('status', $order->status) == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                            <option value="processing" {{ old('status', $order->status) == 'processing' ? 'selected' : '' }}>Processing</option>
                                            <option value="shipped" {{ old('status', $order->status) == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                            <option value="delivered" {{ old('status', $order->status) == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                            <option value="cancelled" {{ old('status', $order->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="payment_status" class="form-label">Payment Status <span class="text-danger">*</span></label>
                                        <select class="form-control @error('payment_status') is-invalid @enderror"
                                                id="payment_status"
                                                name="payment_status"
                                                required>
                                            <option value="pending" {{ old('payment_status', $order->payment_status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="paid" {{ old('payment_status', $order->payment_status) == 'paid' ? 'selected' : '' }}>Paid</option>
                                            <option value="failed" {{ old('payment_status', $order->payment_status) == 'failed' ? 'selected' : '' }}>Failed</option>
                                            <option value="refunded" {{ old('payment_status', $order->payment_status) == 'refunded' ? 'selected' : '' }}>Refunded</option>
                                        </select>
                                        @error('payment_status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="delivery_status" class="form-label">Delivery Status <span class="text-danger">*</span></label>
                                        <select class="form-control @error('delivery_status') is-invalid @enderror"
                                                id="delivery_status"
                                                name="delivery_status"
                                                required>
                                            <option value="pending" {{ old('delivery_status', $order->delivery_status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="assigned" {{ old('delivery_status', $order->delivery_status) == 'assigned' ? 'selected' : '' }}>Assigned</option>
                                            <option value="picked_up" {{ old('delivery_status', $order->delivery_status) == 'picked_up' ? 'selected' : '' }}>Picked Up</option>
                                            <option value="in_transit" {{ old('delivery_status', $order->delivery_status) == 'in_transit' ? 'selected' : '' }}>In Transit</option>
                                            <option value="delivered" {{ old('delivery_status', $order->delivery_status) == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                            <option value="failed" {{ old('delivery_status', $order->delivery_status) == 'failed' ? 'selected' : '' }}>Failed</option>
                                        </select>
                                        @error('delivery_status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Information -->
                        <div class="mb-4">
                            <h6 class="fw-bold border-bottom pb-2 mb-3">Payment Information</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="payment_method" class="form-label">Payment Method <span class="text-danger">*</span></label>
                                        <input type="text"
                                               class="form-control @error('payment_method') is-invalid @enderror"
                                               id="payment_method"
                                               name="payment_method"
                                               value="{{ old('payment_method', $order->payment_method) }}"
                                               required>
                                        @error('payment_method')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="payment_reference" class="form-label">Payment Reference</label>
                                        <input type="text"
                                               class="form-control @error('payment_reference') is-invalid @enderror"
                                               id="payment_reference"
                                               name="payment_reference"
                                               value="{{ old('payment_reference', $order->payment_reference) }}">
                                        @error('payment_reference')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Financial Information -->
                        <div class="mb-4">
                            <h6 class="fw-bold border-bottom pb-2 mb-3">Financial Information</h6>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="subtotal" class="form-label">Subtotal ($) <span class="text-danger">*</span></label>
                                        <input type="number"
                                               step="0.01"
                                               min="0"
                                               class="form-control @error('subtotal') is-invalid @enderror"
                                               id="subtotal"
                                               name="subtotal"
                                               value="{{ old('subtotal', $order->subtotal) }}"
                                               required>
                                        @error('subtotal')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="shipping_cost" class="form-label">Shipping Cost ($) <span class="text-danger">*</span></label>
                                        <input type="number"
                                               step="0.01"
                                               min="0"
                                               class="form-control @error('shipping_cost') is-invalid @enderror"
                                               id="shipping_cost"
                                               name="shipping_cost"
                                               value="{{ old('shipping_cost', $order->shipping_cost) }}"
                                               required>
                                        @error('shipping_cost')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="tax_amount" class="form-label">Tax Amount ($) <span class="text-danger">*</span></label>
                                        <input type="number"
                                               step="0.01"
                                               min="0"
                                               class="form-control @error('tax_amount') is-invalid @enderror"
                                               id="tax_amount"
                                               name="tax_amount"
                                               value="{{ old('tax_amount', $order->tax_amount) }}"
                                               required>
                                        @error('tax_amount')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="total_amount" class="form-label">Total Amount ($) <span class="text-danger">*</span></label>
                                        <input type="number"
                                               step="0.01"
                                               min="0"
                                               class="form-control @error('total_amount') is-invalid @enderror"
                                               id="total_amount"
                                               name="total_amount"
                                               value="{{ old('total_amount', $order->total_amount) }}"
                                               required>
                                        @error('total_amount')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="currency" class="form-label">Currency <span class="text-danger">*</span></label>
                                        <input type="text"
                                               class="form-control @error('currency') is-invalid @enderror"
                                               id="currency"
                                               name="currency"
                                               value="{{ old('currency', $order->currency) }}"
                                               required
                                               maxlength="3">
                                        @error('currency')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Delivery Information -->
                        <div class="mb-4">
                            <h6 class="fw-bold border-bottom pb-2 mb-3">Delivery Information</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="delivery_person_name" class="form-label">Delivery Person Name</label>
                                        <input type="text"
                                               class="form-control @error('delivery_person_name') is-invalid @enderror"
                                               id="delivery_person_name"
                                               name="delivery_person_name"
                                               value="{{ old('delivery_person_name', $order->delivery_person_name) }}">
                                        @error('delivery_person_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="delivery_person_phone" class="form-label">Delivery Person Phone</label>
                                        <input type="text"
                                               class="form-control @error('delivery_person_phone') is-invalid @enderror"
                                               id="delivery_person_phone"
                                               name="delivery_person_phone"
                                               value="{{ old('delivery_person_phone', $order->delivery_person_phone) }}">
                                        @error('delivery_person_phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="delivery_zone" class="form-label">Delivery Zone</label>
                                        <input type="text"
                                               class="form-control @error('delivery_zone') is-invalid @enderror"
                                               id="delivery_zone"
                                               name="delivery_zone"
                                               value="{{ old('delivery_zone', $order->delivery_zone) }}">
                                        @error('delivery_zone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="estimated_delivery_at" class="form-label">Estimated Delivery</label>
                                        <input type="datetime-local"
                                               class="form-control @error('estimated_delivery_at') is-invalid @enderror"
                                               id="estimated_delivery_at"
                                               name="estimated_delivery_at"
                                               value="{{ old('estimated_delivery_at', $order->estimated_delivery_at ? $order->estimated_delivery_at->format('Y-m-d\TH:i') : '') }}">
                                        @error('estimated_delivery_at')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="delivery_instructions" class="form-label">Delivery Instructions</label>
                                <textarea class="form-control @error('delivery_instructions') is-invalid @enderror"
                                          id="delivery_instructions"
                                          name="delivery_instructions"
                                          rows="3">{{ old('delivery_instructions', $order->delivery_instructions) }}</textarea>
                                @error('delivery_instructions')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Address Information -->
                        <div class="mb-4">
                            <h6 class="fw-bold border-bottom pb-2 mb-3">Address Information</h6>

                            <!-- Billing Address -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h6 class="fw-bold text-primary mb-3">Billing Address</h6>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="billing_full_name" class="form-label">Full Name</label>
                                        <input type="text"
                                               class="form-control"
                                               id="billing_full_name"
                                               name="billing_full_name"
                                               value="{{ old('billing_full_name', is_array($order->billing_address) ? ($order->billing_address['full_name'] ?? '') : '') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="billing_phone" class="form-label">Phone</label>
                                        <input type="text"
                                               class="form-control"
                                               id="billing_phone"
                                               name="billing_phone"
                                               value="{{ old('billing_phone', is_array($order->billing_address) ? ($order->billing_address['phone'] ?? '') : '') }}">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="billing_address" class="form-label">Address</label>
                                        <input type="text"
                                               class="form-control"
                                               id="billing_address"
                                               name="billing_address"
                                               value="{{ old('billing_address', is_array($order->billing_address) ? ($order->billing_address['address'] ?? '') : '') }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="billing_city" class="form-label">City</label>
                                        <input type="text"
                                               class="form-control"
                                               id="billing_city"
                                               name="billing_city"
                                               value="{{ old('billing_city', is_array($order->billing_address) ? ($order->billing_address['city'] ?? '') : '') }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="billing_state" class="form-label">State</label>
                                        <input type="text"
                                               class="form-control"
                                               id="billing_state"
                                               name="billing_state"
                                               value="{{ old('billing_state', is_array($order->billing_address) ? ($order->billing_address['state'] ?? '') : '') }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="billing_zip_code" class="form-label">ZIP Code</label>
                                        <input type="text"
                                               class="form-control"
                                               id="billing_zip_code"
                                               name="billing_zip_code"
                                               value="{{ old('billing_zip_code', is_array($order->billing_address) ? ($order->billing_address['zip_code'] ?? '') : '') }}">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="billing_country" class="form-label">Country</label>
                                        <input type="text"
                                               class="form-control"
                                               id="billing_country"
                                               name="billing_country"
                                               value="{{ old('billing_country', is_array($order->billing_address) ? ($order->billing_address['country'] ?? '') : '') }}">
                                    </div>
                                </div>
                            </div>

                            <!-- Shipping Address -->
                            <div class="row">
                                <div class="col-12">
                                    <h6 class="fw-bold text-primary mb-3">Shipping Address</h6>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="shipping_full_name" class="form-label">Full Name</label>
                                        <input type="text"
                                               class="form-control"
                                               id="shipping_full_name"
                                               name="shipping_full_name"
                                               value="{{ old('shipping_full_name', is_array($order->shipping_address) ? ($order->shipping_address['full_name'] ?? '') : '') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="shipping_phone" class="form-label">Phone</label>
                                        <input type="text"
                                               class="form-control"
                                               id="shipping_phone"
                                               name="shipping_phone"
                                               value="{{ old('shipping_phone', is_array($order->shipping_address) ? ($order->shipping_address['phone'] ?? '') : '') }}">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="shipping_address" class="form-label">Address</label>
                                        <input type="text"
                                               class="form-control"
                                               id="shipping_address"
                                               name="shipping_address"
                                               value="{{ old('shipping_address', is_array($order->shipping_address) ? ($order->shipping_address['address'] ?? '') : '') }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="shipping_city" class="form-label">City</label>
                                        <input type="text"
                                               class="form-control"
                                               id="shipping_city"
                                               name="shipping_city"
                                               value="{{ old('shipping_city', is_array($order->shipping_address) ? ($order->shipping_address['city'] ?? '') : '') }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="shipping_state" class="form-label">State</label>
                                        <input type="text"
                                               class="form-control"
                                               id="shipping_state"
                                               name="shipping_state"
                                               value="{{ old('shipping_state', is_array($order->shipping_address) ? ($order->shipping_address['state'] ?? '') : '') }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="shipping_zip_code" class="form-label">ZIP Code</label>
                                        <input type="text"
                                               class="form-control"
                                               id="shipping_zip_code"
                                               name="shipping_zip_code"
                                               value="{{ old('shipping_zip_code', is_array($order->shipping_address) ? ($order->shipping_address['zip_code'] ?? '') : '') }}">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="shipping_country" class="form-label">Country</label>
                                        <input type="text"
                                               class="form-control"
                                               id="shipping_country"
                                               name="shipping_country"
                                               value="{{ old('shipping_country', is_array($order->shipping_address) ? ($order->shipping_address['country'] ?? '') : '') }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Information -->
                        <div class="mb-4">
                            <h6 class="fw-bold border-bottom pb-2 mb-3">Additional Information</h6>
                            <div class="mb-3">
                                <label for="notes" class="form-label">Order Notes</label>
                                <textarea class="form-control @error('notes') is-invalid @enderror"
                                          id="notes"
                                          name="notes"
                                          rows="4">{{ old('notes', $order->notes) }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex gap-2 pt-3 border-top">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Update Order
                            </button>
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-outline-secondary">
                                Cancel
                            </a>
                            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary ms-auto">
                                Back to Orders
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Right Column - Order Summary & Actions -->
        <div class="col-lg-4">
            <!-- Order Summary -->
            <div class="card border-0 mb-4">
                <div class="card-header bg-light border-0">
                    <h5 class="card-title mb-0 fw-bold">Order Summary</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong class="text-muted d-block">Order Number</strong>
                        <span class="fw-bold text-primary">{{ $order->order_number }}</span>
                    </div>
                    <div class="mb-3">
                        <strong class="text-muted d-block">Current Status</strong>
                        <span class="badge {{ $order->status_badge_class }}">{{ ucfirst($order->status) }}</span>
                    </div>
                    <div class="mb-3">
                        <strong class="text-muted d-block">Payment Status</strong>
                        <span class="badge {{ $order->payment_status_badge_class }}">{{ ucfirst($order->payment_status) }}</span>
                    </div>
                    <div class="mb-3">
                        <strong class="text-muted d-block">Delivery Status</strong>
                        <span class="badge {{ $order->delivery_status_badge_class }}">{{ $order->delivery_status_text }}</span>
                    </div>
                    <div class="mb-3">
                        <strong class="text-muted d-block">Total Amount</strong>
                        <span class="fw-bold">${{ number_format($order->total_amount, 2) }}</span>
                    </div>
                    <div class="mb-3">
                        <strong class="text-muted d-block">Items Count</strong>
                        <span>{{ $order->items->count() }} items</span>
                    </div>
                    <div class="mb-3">
                        <strong class="text-muted d-block">Created</strong>
                        <span>{{ $order->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="mb-0">
                        <strong class="text-muted d-block">Last Updated</strong>
                        <span>{{ $order->updated_at->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card border-0">
                <div class="card-header bg-light border-0">
                    <h5 class="card-title mb-0 fw-bold">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.orders.show', $order->id) }}"
                           class="btn btn-outline-primary">
                            <i class="fas fa-eye me-2"></i>View Order
                        </a>

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

.form-control {
    border-radius: 0;
    border: 1px solid #dee2e6;
}

.form-control:focus {
    border-color: #BC450D;
    box-shadow: none;
}

.invalid-feedback {
    display: block;
}

.badge {
    border-radius: 0;
    font-weight: 500;
}

.bg-light {
    background-color: #f8f9fa !important;
}

.border-top {
    border-top: 1px solid #dee2e6 !important;
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

.btn-outline-danger {
    color: #dc3545;
    border-color: #dc3545;
}

.btn-outline-danger:hover {
    background-color: #dc3545;
    border-color: #dc3545;
    color: white;
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

/* Status badge colors */
.badge-warning { background-color: #ffc107; color: #000; }
.badge-info { background-color: #0dcaf0; color: #000; }
.badge-primary { background-color: #BC450D; color: #fff; }
.badge-secondary { background-color: #6c757d; color: #fff; }
.badge-success { background-color: #198754; color: #fff; }
.badge-danger { background-color: #dc3545; color: #fff; }

/* Section headers */
h6.fw-bold.border-bottom {
    border-color: #BC450D !important;
}
</style>

<script>
// Auto-calculate total amount
document.addEventListener('DOMContentLoaded', function() {
    const subtotalInput = document.getElementById('subtotal');
    const shippingInput = document.getElementById('shipping_cost');
    const taxInput = document.getElementById('tax_amount');
    const totalInput = document.getElementById('total_amount');

    function calculateTotal() {
        const subtotal = parseFloat(subtotalInput.value) || 0;
        const shipping = parseFloat(shippingInput.value) || 0;
        const tax = parseFloat(taxInput.value) || 0;
        const total = subtotal + shipping + tax;

        totalInput.value = total.toFixed(2);
    }

    subtotalInput.addEventListener('input', calculateTotal);
    shippingInput.addEventListener('input', calculateTotal);
    taxInput.addEventListener('input', calculateTotal);
});
</script>
@endsection
