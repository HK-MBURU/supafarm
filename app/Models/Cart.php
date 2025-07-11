<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
        'is_guest',
        'total',
    ];

    protected $casts = [
        'total' => 'decimal:2',
        'is_guest' => 'boolean',
    ];

    /**
     * Get the user that owns the cart
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the cart items
     */
    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Recalculate the total price of the cart
     */
    public function recalculateTotal()
    {
        $total = $this->items()->sum(\DB::raw('quantity * price'));
        $this->update(['total' => $total]);
        return $total;
    }

    /**
     * Get the total quantity of items in the cart
     */
    public function getTotalQuantityAttribute()
    {
        return $this->items()->sum('quantity');
    }

    /**
     * Check if the cart is empty
     */
    public function isEmpty()
    {
        return $this->items()->count() === 0;
    }
}