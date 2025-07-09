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

        // Get the cart from session or create a new one
        $cart = session()->get('cart', []);

        // If item exists in cart, update quantity
        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $quantity;
        } else {
            // Add new item to cart
            $cart[$productId] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'price' => $product->sale_price > 0 ? $product->sale_price : $product->price,
                'quantity' => $quantity,
                'image' => $product->image_url,
            ];
        }

        // Save cart back to session
        session()->put('cart', $cart);

        // Return success response
        return response()->json([
            'success' => true,
            'message' => 'Product added to cart successfully.',
            'product_name' => $product->name,
            'cartCount' => array_sum(array_column($cart, 'quantity')),
        ]);
    }

    /**
     * Update cart item quantity
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
     * Remove item from cart
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
     * Clear the entire cart
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

    // Add this method to your CartController.php
    public function getCount()
    {
        $cart = session()->get('cart', []);
        $cartCount = array_sum(array_column($cart, 'quantity'));

        return response()->json([
            'cartCount' => $cartCount
        ]);
    }
}
