<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'product_name', // Add this since it exists in your table
        'quantity',
        'price',
        'total', // This will match your renamed column
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    /**
     * Get the order that owns the order item
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the product associated with the order item
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the total price for this order item (accessor)
     */
    public function getTotalPriceAttribute()
    {
        return $this->total ?? ($this->quantity * $this->price);
    }

    /**
     * Boot the model to auto-calculate totals
     */
    protected static function boot()
    {
        parent::boot();

        // Automatically calculate total when creating/updating
        static::creating(function ($orderItem) {
            if (empty($orderItem->total)) {
                $orderItem->total = $orderItem->quantity * $orderItem->price;
            }
            
            // Store product name for reference
            if (empty($orderItem->product_name) && $orderItem->product) {
                $orderItem->product_name = $orderItem->product->name;
            }
        });

        static::updating(function ($orderItem) {
            if ($orderItem->isDirty(['quantity', 'price'])) {
                $orderItem->total = $orderItem->quantity * $orderItem->price;
            }
        });
    }
}