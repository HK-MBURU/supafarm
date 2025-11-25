<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'description',
        'features',
        'price',
        'sale_price',
        'stock',
        'is_featured',
        'is_active',
        'meta_title',
        'meta_description',
        'image',
        'original_filename',
    ];

    protected $casts = [
        'image' => 'array',
        'original_filename' => 'array',
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    protected $appends = ['image_url', 'image_urls'];

    public function getImageUrlAttribute()
    {
        if (empty($this->image)) {
            return null;
        }

        if (is_array($this->image)) {
            return asset('storage/' . $this->image[0]);
        }

        return asset('storage/' . $this->image);
    }

    public function getImageUrlsAttribute()
    {
        if (empty($this->image)) {
            return [];
        }

        if (is_array($this->image)) {
            return array_map(function ($img) {
                return asset('storage/' . $img);
            }, $this->image);
        }

        return [asset('storage/' . $this->image)];
    }

    public function getHasDiscountAttribute()
    {
        return !is_null($this->sale_price) && $this->sale_price < $this->price;
    }

    public function getDiscountPercentageAttribute()
    {
        if (!$this->has_discount) {
            return 0;
        }

        return round((($this->price - $this->sale_price) / $this->price) * 100);
    }

    public function getDisplayPriceAttribute()
    {
        return $this->has_discount ? $this->sale_price : $this->price;
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    // Scope for active products
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope for featured products
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    // Scope for products in stock
    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    // Scope for products on sale
    public function scopeOnSale($query)
    {
        return $query->whereNotNull('sale_price')
                    ->where('sale_price', '<', DB::raw('price'));
    }
}
