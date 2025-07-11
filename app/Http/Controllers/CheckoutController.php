<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    /**
     * Show the checkout page
     */
    public function index()
    {
        $cart = $this->getCart();

        // Check if cart is empty
        if ($cart->items()->count() === 0) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty. Add some products before checking out.');
        }

        // Calculate totals
        $subtotal = $cart->total;
        $shipping = $this->calculateShipping($cart);
        $tax = $this->calculateTax($subtotal, $shipping);
        $total = $subtotal + $shipping + $tax;

        return view('checkout.index', [
            'cart' => $cart,
            'cartItems' => $cart->items()->with('product')->get(),
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'tax' => $tax,
            'total' => $total,
            'customer' => $this->getCustomerData(),
        ]);
    }

    /**
     * Process the checkout
     */
    public function process(Request $request)
    {
        $cart = $this->getCart();

        // Check if cart is empty
        if ($cart->items()->count() === 0) {
            return response()->json([
                'success' => false,
                'message' => 'Your cart is empty.',
            ], 400);
        }

        // Validate the request
        $validator = Validator::make($request->all(), [
            // Customer information
            'customer.first_name' => 'required|string|max:255',
            'customer.last_name' => 'required|string|max:255',
            'customer.email' => 'required|email|max:255',
            'customer.phone' => 'required|string|max:20',

            // Shipping/delivery location
            'shipping.latitude' => 'required|numeric|between:-90,90',
            'shipping.longitude' => 'required|numeric|between:-180,180',
            'shipping.full_address' => 'required|string|max:500',

            // Payment method
            'payment.method' => 'required|in:mpesa,cash_on_delivery',
            'payment.mpesa_phone' => 'required_if:payment.method,mpesa|string|max:20',

            // Optional notes
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Create or update customer
            $customer = $this->createOrUpdateCustomer($request->input('customer'));

            // Calculate totals
            $subtotal = $cart->total;
            $shipping = $this->calculateShipping($cart, $request->input('shipping'));
            $tax = $this->calculateTax($subtotal, $shipping);
            $total = $subtotal + $shipping + $tax;

            // Prepare shipping address from location data
            $shippingAddress = [
                'full_address' => $request->input('shipping.full_address'),
                'latitude' => $request->input('shipping.latitude'),
                'longitude' => $request->input('shipping.longitude'),
            ];

            // Create order
            $order = Order::create([
                'order_number' => $this->generateOrderNumber(),
                'customer_id' => $customer->id,
                'user_id' => auth()->id(),
                'status' => 'pending',
                'subtotal' => $subtotal,
                'shipping_cost' => $shipping,
                'tax_amount' => $tax,
                'total_amount' => $total,
                'currency' => 'KES',
                'payment_method' => $request->input('payment.method'),
                'payment_status' => 'pending',
                'billing_address' => $shippingAddress, // Use shipping address as billing
                'shipping_address' => $shippingAddress,
                'notes' => $request->input('notes'),
            ]);

            // Create order items
            foreach ($cart->items as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->price,
                    'total' => $cartItem->quantity * $cartItem->price,
                ]);

                // Update product stock
                $cartItem->product->decrement('stock', $cartItem->quantity);
            }

            // Process payment
            $paymentResult = $this->processPayment($order, $request->input('payment'));

            if ($paymentResult['success']) {
                $order->update([
                    'payment_status' => $paymentResult['payment_status'] ?? 'paid',
                    'status' => 'confirmed',
                    'payment_reference' => $paymentResult['reference'] ?? null,
                ]);

                // Clear cart
                $cart->items()->delete();
                $cart->update(['total' => 0]);
                session(['cart_count' => 0]);

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Order placed successfully!',
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'redirect_url' => route('checkout.success', $order->order_number),
                ]);
            } else {
                DB::rollback();
                return response()->json([
                    'success' => false,
                    'message' => $paymentResult['message'] ?? 'Payment processing failed.',
                ], 400);
            }
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Checkout error: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'cart_id' => $cart->id ?? null,
                'request_data' => $request->except(['payment'])
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while processing your order. Please try again.',
            ], 500);
        }
    }

    /**
     * Show order success page
     */
    public function success($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)->firstOrFail();

        return view('checkout.success', [
            'order' => $order,
            'orderItems' => $order->items()->with('product')->get(),
        ]);
    }

    /**
     * Get cart for current session
     */
    private function getCart()
    {
        if (!session()->has('cart_session_id')) {
            session(['cart_session_id' => Str::uuid()]);
        }

        $sessionId = session('cart_session_id');
        $userId = auth()->id();

        $cart = Cart::firstOrCreate(
            ['session_id' => $sessionId],
            [
                'user_id' => $userId,
                'is_guest' => !$userId,
                'total' => 0,
            ]
        );

        if ($userId && $cart->user_id !== $userId) {
            $cart->update([
                'user_id' => $userId,
                'is_guest' => false,
            ]);
        }

        return $cart;
    }

    /**
     * Get customer data for prefilling forms
     */
    private function getCustomerData()
    {
        if (auth()->check()) {
            $user = auth()->user();
            return [
                'first_name' => $user->first_name ?? '',
                'last_name' => $user->last_name ?? '',
                'email' => $user->email ?? '',
                'phone' => $user->phone ?? '',
            ];
        }

        return [
            'first_name' => '',
            'last_name' => '',
            'email' => '',
            'phone' => '',
        ];
    }

    /**
     * Create or update customer
     */
    private function createOrUpdateCustomer($customerData)
    {
        return Customer::updateOrCreate(
            ['email' => $customerData['email']],
            $customerData
        );
    }

    /**
     * Calculate shipping cost based on location
     */
    private function calculateShipping($cart, $shippingData = null)
    {
        $subtotal = $cart->total;

        // Free shipping over KSh 5000
        if ($subtotal >= 5000) {
            return 0;
        }

        // If location data is provided, you can implement location-based pricing
        if ($shippingData && isset($shippingData['latitude'], $shippingData['longitude'])) {
            // Example: Calculate distance from your store/warehouse
            // $distance = $this->calculateDistance($shippingData['latitude'], $shippingData['longitude']);
            // return $this->getShippingCostByDistance($distance);

            // For now, use standard rates
            return $this->getLocationBasedShipping($shippingData['full_address']);
        }

        // Standard shipping rate
        return 300;
    }

    /**
     * Get shipping cost based on location/address
     */
    private function getLocationBasedShipping($address)
    {
        $address = strtolower($address);

        // Define shipping zones (you can expand this)
        $freeZones = ['nairobi cbd', 'westlands', 'karen', 'kilimani'];
        $standardZones = ['kasarani', 'embakasi', 'langata', 'dagoretti'];
        $expressZones = ['kiambu', 'machakos', 'kajiado'];

        foreach ($freeZones as $zone) {
            if (strpos($address, $zone) !== false) {
                return 200; // Reduced rate for nearby areas
            }
        }

        foreach ($standardZones as $zone) {
            if (strpos($address, $zone) !== false) {
                return 300; // Standard rate
            }
        }

        foreach ($expressZones as $zone) {
            if (strpos($address, $zone) !== false) {
                return 500; // Higher rate for distant areas
            }
        }

        // Default rate for unspecified areas
        return 400;
    }

    /**
     * Calculate tax
     */
    private function calculateTax($subtotal, $shipping)
    {
        // 16% VAT on subtotal only (not shipping)
        return round(($subtotal * 0.16), 2);
    }

    /**
     * Generate unique order number
     */
    private function generateOrderNumber()
    {
        $prefix = 'ORD';
        $timestamp = now()->format('Ymd');
        $random = strtoupper(Str::random(4));

        return $prefix . $timestamp . $random;
    }

    /**
     * Process payment based on method
     */
    private function processPayment($order, $paymentData)
    {
        switch ($paymentData['method']) {
            case 'mpesa':
                return $this->processMpesaPayment($order, $paymentData);

            case 'cash_on_delivery':
                return $this->processCashOnDeliveryPayment($order, $paymentData);

            default:
                return ['success' => false, 'message' => 'Invalid payment method'];
        }
    }

    /**
     * Process M-Pesa payment
     */
    private function processMpesaPayment($order, $paymentData)
    {
        // Validate phone number
        if (empty($paymentData['mpesa_phone']) || strlen($paymentData['mpesa_phone']) < 10) {
            return ['success' => false, 'message' => 'Invalid M-Pesa phone number'];
        }

        // Clean phone number
        $phone = preg_replace('/[^0-9]/', '', $paymentData['mpesa_phone']);
        if (substr($phone, 0, 1) === '0') {
            $phone = '254' . substr($phone, 1);
        } elseif (substr($phone, 0, 3) !== '254') {
            $phone = '254' . $phone;
        }

        // Here you would integrate with actual M-Pesa API
        // For now, this is a mock implementation
        try {
            // Simulate M-Pesa STK Push
            $success = rand(1, 10) > 2; // 80% success rate for demo

            if ($success) {
                return [
                    'success' => true,
                    'reference' => 'MP' . time() . rand(1000, 9999),
                    'payment_status' => 'paid',
                    'message' => 'M-Pesa payment completed successfully'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'M-Pesa payment failed. Please try again or use a different payment method.'
                ];
            }
        } catch (\Exception $e) {
            Log::error('M-Pesa payment error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'M-Pesa service is currently unavailable. Please try again later.'
            ];
        }
    }

    /**
     * Process cash on delivery
     */
    private function processCashOnDeliveryPayment($order, $paymentData)
    {
        return [
            'success' => true,
            'reference' => 'COD' . time() . rand(1000, 9999),
            'payment_status' => 'pending', // COD is pending until delivery
            'message' => 'Order created for cash on delivery'
        ];
    }

    /**
     * Calculate distance between two coordinates (optional - for advanced shipping)
     */
    private function calculateDistance($lat1, $lon1, $lat2 = -1.2921, $lon2 = 36.8219) // Default: Nairobi
    {
        $earthRadius = 6371; // Earth's radius in kilometers

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distance = $earthRadius * $c;

        return $distance; // Distance in kilometers
    }
}
