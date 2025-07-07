<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
        'total',
        'is_guest',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Get the total price of all items in the cart
     */
    public function recalculateTotal(): float
    {
        $total = 0;
        
        foreach ($this->items as $item) {
            $price = $item->product->sale_price ?? $item->product->price;
            $total += $price * $item->quantity;
        }
        
        $this->total = $total;
        $this->save();
        
        return $total;
    }
}