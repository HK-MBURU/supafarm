<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_id',
        'product_id',
        'quantity',
        'price',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'decimal:2',
    ];

    /**
     * Get the cart that owns the cart item
     */
    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    /**
     * Get the product associated with the cart item
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the total price for this cart item
     */
    public function getTotalPriceAttribute()
    {
        return $this->quantity * $this->price;
    }

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        // Automatically update cart total when cart item is saved or deleted
        static::saved(function ($cartItem) {
            $cartItem->cart->recalculateTotal();
        });

        static::deleted(function ($cartItem) {
            $cartItem->cart->recalculateTotal();
        });
    }
}