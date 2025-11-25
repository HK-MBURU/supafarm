<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'customer_id',
        'user_id',
        'status',
        'subtotal',
        'shipping_cost',
        'tax_amount',
        'total_amount',
        'currency',
        'payment_method',
        'payment_status',
        'payment_reference',
        'billing_address',
        'shipping_address',
        'delivery_latitude',
        'delivery_longitude',
        'delivery_instructions',
        'estimated_delivery_at',
        'delivery_status',
        'delivery_person_name',
        'delivery_person_phone',
        'delivery_distance_km',
        'delivery_zone',
        'notes',
        'confirmed_at',
        'prepared_at',
        'dispatched_at',
        'shipped_at',
        'delivered_at',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'delivery_latitude' => 'decimal:8',
        'delivery_longitude' => 'decimal:8',
        'delivery_distance_km' => 'decimal:2',
        'billing_address' => 'array',
        'shipping_address' => 'array',
        'estimated_delivery_at' => 'datetime',
        'confirmed_at' => 'datetime',
        'prepared_at' => 'datetime',
        'dispatched_at' => 'datetime',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    /**
     * Get the customer that owns the order
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the user that owns the order
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the order items
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get the order status badge class
     */
    public function getStatusBadgeClassAttribute()
    {
        return match ($this->status) {
            'pending' => 'badge-warning',
            'confirmed' => 'badge-info',
            'processing' => 'badge-primary',
            'shipped' => 'badge-secondary',
            'delivered' => 'badge-success',
            'cancelled' => 'badge-danger',
            default => 'badge-light',
        };
    }

    /**
     * Get the payment status badge class
     */
    public function getPaymentStatusBadgeClassAttribute()
    {
        return match ($this->payment_status) {
            'pending' => 'badge-warning',
            'paid' => 'badge-success',
            'failed' => 'badge-danger',
            'refunded' => 'badge-info',
            default => 'badge-light',
        };
    }

    /**
     * Get the delivery status badge class
     */
    public function getDeliveryStatusBadgeClassAttribute()
    {
        return match ($this->delivery_status) {
            'pending' => 'badge-secondary',
            'assigned' => 'badge-info',
            'picked_up' => 'badge-primary',
            'in_transit' => 'badge-warning',
            'delivered' => 'badge-success',
            'failed' => 'badge-danger',
            default => 'badge-light',
        };
    }

    /**
     * Get delivery status in human readable format
     */
    public function getDeliveryStatusTextAttribute()
    {
        return match ($this->delivery_status) {
            'pending' => 'Awaiting Assignment',
            'assigned' => 'Assigned to Rider',
            'picked_up' => 'Picked Up',
            'in_transit' => 'Out for Delivery',
            'delivered' => 'Delivered',
            'failed' => 'Delivery Failed',
            default => 'Unknown',
        };
    }

    /**
     * Get the delivery coordinates as an array
     */
    public function getDeliveryCoordinatesAttribute()
    {
        if ($this->delivery_latitude && $this->delivery_longitude) {
            return [
                'lat' => (float) $this->delivery_latitude,
                'lng' => (float) $this->delivery_longitude,
            ];
        }
        return null;
    }

    /**
     * Get the delivery address from shipping_address
     */
    public function getDeliveryAddressAttribute()
    {
        if (is_array($this->shipping_address)) {
            return $this->shipping_address['full_address'] ??
                $this->shipping_address['address'] ??
                'Address not available';
        }
        return $this->shipping_address ?? 'Address not available';
    }

    /**
     * Check if order is delivered
     */
    public function isDelivered()
    {
        return $this->delivery_status === 'delivered' && !is_null($this->delivered_at);
    }

    /**
     * Check if order is in transit
     */
    public function isInTransit()
    {
        return in_array($this->delivery_status, ['assigned', 'picked_up', 'in_transit']);
    }

    /**
     * Check if order can be cancelled
     */
    public function canBeCancelled()
    {
        return in_array($this->status, ['pending', 'confirmed']) &&
            in_array($this->delivery_status, ['pending', 'assigned']);
    }

    /**
     * Get estimated delivery time in human readable format
     */
    public function getEstimatedDeliveryTimeAttribute()
    {
        if (!$this->estimated_delivery_at) {
            return 'Not set';
        }

        $now = Carbon::now();
        $estimated = Carbon::parse($this->estimated_delivery_at);

        if ($estimated->isPast()) {
            return 'Overdue by ' . $estimated->diffForHumans($now, true);
        }

        return $estimated->diffForHumans($now);
    }

    /**
     * Get order progress percentage
     */
    public function getProgressPercentageAttribute()
    {
        $statusProgress = match ($this->status) {
            'pending' => 10,
            'confirmed' => 25,
            'processing' => 50,
            'shipped' => 75,
            'delivered' => 100,
            'cancelled' => 0,
            default => 0,
        };

        $deliveryProgress = match ($this->delivery_status) {
            'pending' => 0,
            'assigned' => 20,
            'picked_up' => 40,
            'in_transit' => 80,
            'delivered' => 100,
            'failed' => 0,
            default => 0,
        };

        return max($statusProgress, $deliveryProgress);
    }

    /**
     * Scope for orders with specific delivery status
     */
    public function scopeDeliveryStatus($query, $status)
    {
        return $query->where('delivery_status', $status);
    }

    /**
     * Scope for orders within delivery radius
     */
    public function scopeWithinRadius($query, $latitude, $longitude, $radiusKm)
    {
        return $query->whereRaw(
            "(6371 * acos(cos(radians(?)) * cos(radians(delivery_latitude)) * cos(radians(delivery_longitude) - radians(?)) + sin(radians(?)) * sin(radians(delivery_latitude)))) <= ?",
            [$latitude, $longitude, $latitude, $radiusKm]
        );
    }

    /**
     * Scope for pending deliveries
     */
    public function scopePendingDelivery($query)
    {
        return $query->whereIn('delivery_status', ['pending', 'assigned', 'picked_up', 'in_transit']);
    }

    /**
     * Scope for today's orders
     */
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', Carbon::today());
    }

    /**
     * Update delivery status with timestamp
     */
    public function updateDeliveryStatus($status, $additionalData = [])
    {
        $updates = array_merge(['delivery_status' => $status], $additionalData);

        switch ($status) {
            case 'confirmed':
                $updates['confirmed_at'] = now();
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

        return $this->update($updates);
    }

    /**
     * Get the next expected status
     */
    public function getNextExpectedStatusAttribute()
    {
        return match ($this->delivery_status) {
            'pending' => 'assigned',
            'assigned' => 'picked_up',
            'picked_up' => 'in_transit',
            'in_transit' => 'delivered',
            'delivered' => null,
            'failed' => 'assigned',
            default => 'pending',
        };
    }

    public function getCurrencySymbolAttribute()
    {
        return match (strtoupper($this->currency)) {
            'USD' => '$',
            'EUR' => '€',
            'GBP' => '£',
            'KES' => 'KSh',
            'KSH' => 'KSh',
            'CAD' => 'C$',
            'AUD' => 'A$',
            'JPY' => '¥',
            'CNY' => '¥',
            'INR' => '₹',
            default => $this->currency . ' ', // Fallback: use currency code with space
        };
    }

    /**
     * Format amount with currency symbol
     */
    public function formatAmount($amount)
    {
        $symbol = $this->currency_symbol;
        $formattedAmount = number_format($amount, 2);

        // For currencies that typically go before the amount
        $prefixCurrencies = ['USD', 'EUR', 'GBP', 'CAD', 'AUD', 'KES', 'KSH'];

        if (in_array(strtoupper($this->currency), $prefixCurrencies)) {
            return $symbol . $formattedAmount;
        }

        // For currencies that typically go after the amount
        return $formattedAmount . ' ' . $symbol;
    }

    /**
     * Get formatted subtotal
     */
    public function getFormattedSubtotalAttribute()
    {
        return $this->formatAmount($this->subtotal);
    }

    /**
     * Get formatted shipping cost
     */
    public function getFormattedShippingCostAttribute()
    {
        return $this->formatAmount($this->shipping_cost);
    }

    /**
     * Get formatted tax amount
     */
    public function getFormattedTaxAmountAttribute()
    {
        return $this->formatAmount($this->tax_amount);
    }

    /**
     * Get formatted total amount
     */
    public function getFormattedTotalAmountAttribute()
    {
        return $this->formatAmount($this->total_amount);
    }

    /**
     * Get default currency (for cases where order currency might be null)
     */
    public static function getDefaultCurrency()
    {
        return 'KES'; // Kenyan Shilling as default
    }

    /**
     * Get default currency symbol
     */
    public static function getDefaultCurrencySymbol()
    {
        return 'KSh';
    }
}
