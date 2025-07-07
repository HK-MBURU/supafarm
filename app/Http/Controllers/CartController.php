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
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);
        $cart = $this->getCart();

        // Check product availability
        if (($product->stock ?? 0) < $request->quantity) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sorry, the requested quantity is not available in stock.'
                ]);
            }
            return redirect()->back()->with('error', 'Sorry, the requested quantity is not available in stock.');
        }

        // Get current price (use sale price if available)
        $price = $product->sale_price && $product->sale_price < $product->price
            ? $product->sale_price
            : $product->price;

        // Check if this product is already in cart
        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {
            // Update existing cart item
            $newQuantity = $cartItem->quantity + $request->quantity;

            // Check if new quantity exceeds stock
            if (($product->stock ?? 0) < $newQuantity) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Sorry, we don\'t have enough stock to add more of this item.'
                    ]);
                }
                return redirect()->back()->with('error', 'Sorry, we don\'t have enough stock to add more of this item.');
            }

            $cartItem->update([
                'quantity' => $newQuantity,
                'price' => $price,
            ]);
        } else {
            // Create new cart item
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'price' => $price,
            ]);
        }

        // Recalculate cart total
        $cart->recalculateTotal();

        // Update cart count in session
        $cartCount = $cart->items()->sum('quantity');
        session(['cart_count' => $cartCount]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $product->name . ' has been added to your cart!',
                'cartCount' => $cartCount
            ]);
        }

        return redirect()->back()->with('success', $product->name . ' has been added to your cart!');
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
}
