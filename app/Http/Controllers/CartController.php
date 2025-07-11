<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CartController extends Controller
{
    public function index()
    {
        $cart = $this->getCart();
        $cartItems = $cart->items()->with('product')->get();

        return view('cart.index', [
            'cart' => $cart,
            'cartItems' => $cartItems,
            'total' => $cart->total,
            'loginUser' => 'HK-MBURU', // Replace with Auth::user()->name if using auth
            'timestamp' => now()->format('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Add a product to the cart
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $productId = $request->input('product_id');
        $quantity = $request->input('quantity');

        $product = Product::findOrFail($productId);

        // Check if product is in stock
        if ($product->stock < $quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Not enough stock available.',
            ], 422);
        }

        // Get or create cart
        $cart = $this->getCart();

        // Check if item already exists in cart
        $existingItem = $cart->items()->where('product_id', $productId)->first();

        if ($existingItem) {
            // Update existing item quantity
            $newQuantity = $existingItem->quantity + $quantity;
            
            // Check if new quantity exceeds stock
            if ($product->stock < $newQuantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Not enough stock available. You already have ' . $existingItem->quantity . ' in your cart.',
                ], 422);
            }
            
            $existingItem->update(['quantity' => $newQuantity]);
        } else {
            // Create new cart item
            $cart->items()->create([
                'product_id' => $productId,
                'quantity' => $quantity,
                'price' => $product->sale_price > 0 ? $product->sale_price : $product->price,
            ]);
        }

        // Recalculate cart total
        $cart->recalculateTotal();

        // Update cart count in session
        $cartCount = $cart->items()->sum('quantity');
        session(['cart_count' => $cartCount]);

        // Return success response
        return response()->json([
            'success' => true,
            'message' => 'Product added to cart successfully.',
            'product_name' => $product->name,
            'cartCount' => $cartCount,
        ]);
    }

    /**
     * Update cart item quantity via AJAX
     */
    public function updateQuantity(Request $request, CartItem $cartItem)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        // Ensure the cart item belongs to the current cart
        $cart = $this->getCart();
        if ($cartItem->cart_id !== $cart->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access.',
            ], 403);
        }

        // Check product availability
        $product = $cartItem->product;
        if (($product->stock ?? 0) < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, only ' . ($product->stock ?? 0) . ' items available in stock.',
            ], 422);
        }

        // Update quantity
        $cartItem->update([
            'quantity' => $request->quantity,
        ]);

        // Recalculate cart total
        $cart->refresh();
        $cartTotal = $cart->recalculateTotal();

        // Update cart count in session
        $cartCount = $cart->items()->sum('quantity');
        session(['cart_count' => $cartCount]);

        return response()->json([
            'success' => true,
            'message' => 'Cart updated successfully!',
            'item_total' => number_format($cartItem->quantity * $cartItem->price, 2),
            'cart_total' => number_format($cartTotal, 2),
            'cart_count' => $cartCount,
            'quantity' => $cartItem->quantity,
        ]);
    }

    /**
     * Update cart item quantity (for form submission fallback)
     */
    public function update(Request $request, CartItem $cartItem)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        // Ensure the cart item belongs to the current cart
        $cart = $this->getCart();
        if ($cartItem->cart_id !== $cart->id) {
            abort(403);
        }

        // Check product availability
        $product = $cartItem->product;
        if (($product->stock ?? 0) < $request->quantity) {
            return redirect()->back()->with('error', 'Sorry, the requested quantity is not available in stock.');
        }

        // Update quantity
        $cartItem->update([
            'quantity' => $request->quantity,
        ]);

        // Recalculate cart total
        $cart->recalculateTotal();

        // Update cart count in session
        session(['cart_count' => $cart->items()->sum('quantity')]);

        return redirect()->back()->with('success', 'Cart updated successfully!');
    }

    /**
     * Remove item from cart via AJAX
     */
    public function removeItem(CartItem $cartItem)
    {
        // Ensure the cart item belongs to the current cart
        $cart = $this->getCart();
        if ($cartItem->cart_id !== $cart->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access.',
            ], 403);
        }

        $productName = $cartItem->product->name;
        $cartItem->delete();

        // Recalculate cart total
        $cart->refresh();
        $cartTotal = $cart->recalculateTotal();

        // Update cart count in session
        $cartCount = $cart->items()->sum('quantity');
        session(['cart_count' => $cartCount]);

        return response()->json([
            'success' => true,
            'message' => $productName . ' has been removed from your cart.',
            'cart_total' => number_format($cartTotal, 2),
            'cart_count' => $cartCount,
            'cart_empty' => $cartCount === 0,
        ]);
    }

    /**
     * Remove item from cart (for form submission fallback)
     */
    public function remove(CartItem $cartItem)
    {
        // Ensure the cart item belongs to the current cart
        $cart = $this->getCart();
        if ($cartItem->cart_id !== $cart->id) {
            abort(403);
        }

        $productName = $cartItem->product->name;
        $cartItem->delete();

        // Recalculate cart total
        $cart->recalculateTotal();

        // Update cart count in session
        session(['cart_count' => $cart->items()->sum('quantity')]);

        return redirect()->back()->with('success', $productName . ' has been removed from your cart.');
    }

    /**
     * Clear the entire cart via AJAX
     */
    public function clearCart()
    {
        $cart = $this->getCart();
        $cart->items()->delete();
        $cart->total = 0;
        $cart->save();

        // Update cart count in session
        session(['cart_count' => 0]);

        return response()->json([
            'success' => true,
            'message' => 'Your cart has been cleared.',
            'cart_total' => '0.00',
            'cart_count' => 0,
            'cart_empty' => true,
        ]);
    }

    /**
     * Clear the entire cart (for form submission fallback)
     */
    public function clear()
    {
        $cart = $this->getCart();
        $cart->items()->delete();
        $cart->total = 0;
        $cart->save();

        // Update cart count in session
        session(['cart_count' => 0]);

        return redirect()->back()->with('success', 'Your cart has been cleared.');
    }

    /**
     * Get or create a cart for the current session
     */
    private function getCart()
    {
        // Generate a session ID if not exists
        if (!session()->has('cart_session_id')) {
            session(['cart_session_id' => Str::uuid()]);
        }

        $sessionId = session('cart_session_id');
        $userId = auth()->id(); // Will be null for guests

        // Find or create the cart
        $cart = Cart::firstOrCreate(
            ['session_id' => $sessionId],
            [
                'user_id' => $userId,
                'is_guest' => !$userId,
                'total' => 0,
            ]
        );

        // Update user_id if user logs in
        if ($userId && $cart->user_id !== $userId) {
            $cart->update([
                'user_id' => $userId,
                'is_guest' => false,
            ]);
        }

        return $cart;
    }

    /**
     * Cart checkout page
     */
    public function checkout()
    {
        $cart = $this->getCart();

        // Check if cart is empty
        if ($cart->items()->count() === 0) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty. Add some products before checking out.');
        }

        return view('cart.checkout', [
            'cart' => $cart,
            'cartItems' => $cart->items()->with('product')->get(),
            'total' => $cart->total,
            'loginUser' => 'HK-MBURU', // Replace with Auth::user()->name if using auth
        ]);
    }

    /**
     * Get cart count for AJAX requests
     */
    public function getCount()
    {
        $cart = $this->getCart();
        $cartCount = $cart->items()->sum('quantity');

        return response()->json([
            'cartCount' => $cartCount
        ]);
    }
}