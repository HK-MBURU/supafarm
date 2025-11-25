<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::with(['customer', 'user', 'items'])
            ->latest()
            ->paginate(15);

        $stats = [
            'totalOrders' => Order::count(),
            'pendingOrders' => Order::where('status', 'pending')->count(),
            'todayOrders' => Order::today()->count(),
            'revenue' => Order::where('status', 'delivered')->sum('total_amount'),
        ];

        return view('admin.orders.index', array_merge(compact('orders'), $stats));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = Customer::where('is_active', true)->get();
        $users = User::where('is_active', true)->get();

        return view('admin.orders.create', compact('customers', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'user_id' => 'nullable|exists:users,id',
            'status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled',
            'payment_method' => 'required|string|max:255',
            'payment_status' => 'required|in:pending,paid,failed,refunded',
            'payment_reference' => 'nullable|string|max:255',
            'subtotal' => 'required|numeric|min:0',
            'shipping_cost' => 'required|numeric|min:0',
            'tax_amount' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'currency' => 'required|string|max:3',
            'delivery_status' => 'required|in:pending,assigned,picked_up,in_transit,delivered,failed',
            'delivery_person_name' => 'nullable|string|max:255',
            'delivery_person_phone' => 'nullable|string|max:20',
            'delivery_zone' => 'nullable|string|max:255',
            'delivery_instructions' => 'nullable|string',
            'notes' => 'nullable|string',
            'estimated_delivery_at' => 'nullable|date',
        ]);

        // Generate order number
        $validated['order_number'] = $this->generateOrderNumber();

        // Handle address data
        if ($request->has('billing_address')) {
            $validated['billing_address'] = [
                'full_name' => $request->billing_full_name,
                'address' => $request->billing_address,
                'city' => $request->billing_city,
                'state' => $request->billing_state,
                'zip_code' => $request->billing_zip_code,
                'country' => $request->billing_country,
                'phone' => $request->billing_phone,
            ];
        }

        if ($request->has('shipping_address')) {
            $validated['shipping_address'] = [
                'full_name' => $request->shipping_full_name,
                'address' => $request->shipping_address,
                'city' => $request->shipping_city,
                'state' => $request->shipping_state,
                'zip_code' => $request->shipping_zip_code,
                'country' => $request->shipping_country,
                'phone' => $request->shipping_phone,
            ];
        }

        // Set timestamps based on status
        if ($validated['status'] === 'confirmed') {
            $validated['confirmed_at'] = now();
        }

        if ($validated['delivery_status'] === 'delivered') {
            $validated['delivered_at'] = now();
        }

        $order = Order::create($validated);

        return redirect()->route('admin.orders.show', $order->id)
            ->with('success', 'Order created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $order = Order::with(['customer', 'user', 'items.product'])
            ->findOrFail($id);

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $order = Order::with(['customer', 'user', 'items'])->findOrFail($id);
        $customers = Customer::where('is_active', true)->get();
        $users = User::where('is_active', true)->get();

        return view('admin.orders.edit', compact('order', 'customers', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $order = Order::findOrFail($id);

        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'user_id' => 'nullable|exists:users,id',
            'status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled',
            'payment_method' => 'required|string|max:255',
            'payment_status' => 'required|in:pending,paid,failed,refunded',
            'payment_reference' => 'nullable|string|max:255',
            'subtotal' => 'required|numeric|min:0',
            'shipping_cost' => 'required|numeric|min:0',
            'tax_amount' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'currency' => 'required|string|max:3',
            'delivery_status' => 'required|in:pending,assigned,picked_up,in_transit,delivered,failed',
            'delivery_person_name' => 'nullable|string|max:255',
            'delivery_person_phone' => 'nullable|string|max:20',
            'delivery_zone' => 'nullable|string|max:255',
            'delivery_instructions' => 'nullable|string',
            'notes' => 'nullable|string',
            'estimated_delivery_at' => 'nullable|date',
        ]);

        // Handle address data
        if ($request->has('billing_address')) {
            $validated['billing_address'] = [
                'full_name' => $request->billing_full_name,
                'address' => $request->billing_address,
                'city' => $request->billing_city,
                'state' => $request->billing_state,
                'zip_code' => $request->billing_zip_code,
                'country' => $request->billing_country,
                'phone' => $request->billing_phone,
            ];
        }

        if ($request->has('shipping_address')) {
            $validated['shipping_address'] = [
                'full_name' => $request->shipping_full_name,
                'address' => $request->shipping_address,
                'city' => $request->shipping_city,
                'state' => $request->shipping_state,
                'zip_code' => $request->shipping_zip_code,
                'country' => $request->shipping_country,
                'phone' => $request->shipping_phone,
            ];
        }

        // Update timestamps based on status changes
        if ($order->status !== 'confirmed' && $validated['status'] === 'confirmed') {
            $validated['confirmed_at'] = now();
        }

        if ($order->delivery_status !== 'delivered' && $validated['delivery_status'] === 'delivered') {
            $validated['delivered_at'] = now();
            $validated['status'] = 'delivered';
        }

        // If order is cancelled, update delivery status
        if ($validated['status'] === 'cancelled') {
            $validated['delivery_status'] = 'failed';
        }

        $order->update($validated);

        return redirect()->route('admin.orders.show', $order->id)
            ->with('success', 'Order updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $order = Order::findOrFail($id);

        // Check if order can be deleted (only pending orders can be deleted)
        if (!in_array($order->status, ['pending', 'cancelled'])) {
            return redirect()->route('admin.orders.index')
                ->with('error', 'Cannot delete order. Only pending or cancelled orders can be deleted.');
        }

        // Delete order items first
        $order->items()->delete();
        $order->delete();

        return redirect()->route('admin.orders.index')
            ->with('success', 'Order deleted successfully.');
    }

    /**
     * Display pending orders.
     */
    public function pending()
    {
        $orders = Order::with(['customer', 'user', 'items'])
            ->where('status', 'pending')
            ->latest()
            ->paginate(15);

        return view('admin.orders.pending', compact('orders'));
    }

    /**
     * Display delivery orders.
     */
    public function delivery()
    {
        $orders = Order::with(['customer', 'user', 'items'])
            ->whereIn('delivery_status', ['assigned', 'picked_up', 'in_transit'])
            ->latest()
            ->paginate(15);

        return view('admin.orders.delivery', compact('orders'));
    }

    /**
     * Update order status.
     */
    public function updateStatus(Request $request, string $id)
    {
        $order = Order::findOrFail($id);

        $request->validate([
            'status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled',
        ]);

        $updates = ['status' => $request->status];

        // Set timestamps based on status
        switch ($request->status) {
            case 'confirmed':
                $updates['confirmed_at'] = now();
                break;
            case 'processing':
                $updates['prepared_at'] = now();
                break;
            case 'shipped':
                $updates['shipped_at'] = now();
                break;
            case 'delivered':
                $updates['delivered_at'] = now();
                $updates['delivery_status'] = 'delivered';
                break;
            case 'cancelled':
                $updates['delivery_status'] = 'failed';
                break;
        }

        $order->update($updates);

        return redirect()->back()
            ->with('success', 'Order status updated successfully.');
    }

    /**
     * Update delivery status.
     */
    public function updateDeliveryStatus(Request $request, string $id)
    {
        $order = Order::findOrFail($id);

        $request->validate([
            'delivery_status' => 'required|in:pending,assigned,picked_up,in_transit,delivered,failed',
            'delivery_person_name' => 'nullable|string|max:255',
            'delivery_person_phone' => 'nullable|string|max:20',
        ]);

        $updates = [
            'delivery_status' => $request->delivery_status,
            'delivery_person_name' => $request->delivery_person_name,
            'delivery_person_phone' => $request->delivery_person_phone,
        ];

        // Set timestamps based on delivery status
        switch ($request->delivery_status) {
            case 'assigned':
                $updates['confirmed_at'] = $order->confirmed_at ?? now();
                break;
            case 'picked_up':
                $updates['prepared_at'] = now();
                break;
            case 'in_transit':
                $updates['dispatched_at'] = now();
                break;
            case 'delivered':
                $updates['delivered_at'] = now();
                $updates['status'] = 'delivered';
                break;
        }

        $order->update($updates);

        return redirect()->back()
            ->with('success', 'Delivery status updated successfully.');
    }

    /**
     * Update payment status.
     */
    public function updatePaymentStatus(Request $request, string $id)
    {
        $order = Order::findOrFail($id);

        $request->validate([
            'payment_status' => 'required|in:pending,paid,failed,refunded',
            'payment_reference' => 'nullable|string|max:255',
        ]);

        $order->update([
            'payment_status' => $request->payment_status,
            'payment_reference' => $request->payment_reference,
        ]);

        return redirect()->back()
            ->with('success', 'Payment status updated successfully.');
    }

    /**
     * Assign delivery person to order.
     */
    public function assignDelivery(Request $request, string $id)
    {
        $order = Order::findOrFail($id);

        $request->validate([
            'delivery_person_name' => 'required|string|max:255',
            'delivery_person_phone' => 'required|string|max:20',
        ]);

        $order->update([
            'delivery_person_name' => $request->delivery_person_name,
            'delivery_person_phone' => $request->delivery_person_phone,
            'delivery_status' => 'assigned',
            'confirmed_at' => $order->confirmed_at ?? now(),
        ]);

        return redirect()->back()
            ->with('success', 'Delivery person assigned successfully.');
    }

    /**
     * Mark order as delivered.
     */
    public function markAsDelivered(string $id)
    {
        $order = Order::findOrFail($id);

        $order->update([
            'status' => 'delivered',
            'delivery_status' => 'delivered',
            'delivered_at' => now(),
        ]);

        return redirect()->back()
            ->with('success', 'Order marked as delivered successfully.');
    }

    /**
     * Cancel order.
     */
    public function cancel(string $id)
    {
        $order = Order::findOrFail($id);

        if (!$order->canBeCancelled()) {
            return redirect()->back()
                ->with('error', 'This order cannot be cancelled at its current stage.');
        }

        $order->update([
            'status' => 'cancelled',
            'delivery_status' => 'failed',
        ]);

        return redirect()->back()
            ->with('success', 'Order cancelled successfully.');
    }

    /**
     * Generate unique order number.
     */
    private function generateOrderNumber()
    {
        $prefix = 'ORD';
        $date = now()->format('Ymd');

        do {
            $number = $prefix . $date . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
        } while (Order::where('order_number', $number)->exists());

        return $number;
    }

    /**
     * Get order statistics for dashboard.
     */
    public function statistics()
    {
        $stats = [
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'today_orders' => Order::today()->count(),
            'total_revenue' => Order::where('status', 'delivered')->sum('total_amount'),
            'avg_order_value' => Order::where('status', 'delivered')->avg('total_amount') ?? 0,
        ];

        // Monthly revenue data for charts
        $monthlyRevenue = Order::where('status', 'delivered')
            ->whereYear('created_at', now()->year)
            ->selectRaw('MONTH(created_at) as month, SUM(total_amount) as revenue')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return response()->json([
            'stats' => $stats,
            'monthly_revenue' => $monthlyRevenue,
        ]);
    }

    public function bulkConfirm(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:orders,id'
        ]);

        $count = Order::whereIn('id', $request->ids)
            ->where('status', 'pending')
            ->update([
                'status' => 'confirmed',
                'confirmed_at' => now()
            ]);

        return redirect()->route('admin.orders.index')
            ->with('success', "{$count} orders confirmed successfully.");
    }

    /**
     * Bulk mark orders as processing
     */
    public function bulkProcessing(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:orders,id'
        ]);

        $count = Order::whereIn('id', $request->ids)
            ->where('status', 'confirmed')
            ->update([
                'status' => 'processing',
                'prepared_at' => now()
            ]);

        return redirect()->route('admin.orders.index')
            ->with('success', "{$count} orders marked as processing.");
    }

    /**
     * Bulk cancel orders
     */
    public function bulkCancel(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:orders,id'
        ]);

        $count = Order::whereIn('id', $request->ids)
            ->whereIn('status', ['pending', 'confirmed'])
            ->update([
                'status' => 'cancelled',
                'delivery_status' => 'failed'
            ]);

        return redirect()->route('admin.orders.index')
            ->with('success', "{$count} orders cancelled successfully.");
    }
}
