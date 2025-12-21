<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    protected $fillable = [
        'seller_id',
        'name',
        'description',
        'price',
        'stock',
        'category',
        'variant',
        'is_approved',
        'is_active',
        'status'
    ];

    protected $casts = [
        'is_approved' => 'boolean',
        'is_active' => 'boolean',
        'price' => 'decimal:2',
        'stock' => 'integer',
    ];

    // Relationships
    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('order');
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Status attribute
    public function getStatusAttribute()
    {
        if ($this->is_approved === true) {
            return self::STATUS_APPROVED;
        }

        if ($this->is_approved === false && $this->is_active === false) {
            return self::STATUS_REJECTED;
        }

        return self::STATUS_PENDING;
    }

    // Stock management methods

    /**
     * Check if product is out of stock
     */
    public function isOutOfStock(): bool
    {
        return $this->stock <= 0;
    }

    /**
     * Check if product has low stock (5 or fewer items)
     */
    public function isLowStock(): bool
    {
        return $this->stock > 0 && $this->stock <= 5;
    }

    /**
     * Check if product is in stock
     */
    public function isInStock(): bool
    {
        return $this->stock > 0;
    }

    /**
     * Get stock status as string
     */
    public function getStockStatus(): string
    {
        if ($this->isOutOfStock()) {
            return 'out_of_stock';
        } elseif ($this->isLowStock()) {
            return 'low_stock';
        } else {
            return 'in_stock';
        }
    }

    /**
     * Get stock status message
     */
    public function getStockMessage(): string
    {
        if ($this->isOutOfStock()) {
            return 'Out of stock';
        } elseif ($this->isLowStock()) {
            return "Only {$this->stock} left!";
        } elseif ($this->stock <= 10) {
            return "Low stock";
        } else {
            return "In stock";
        }
    }

    /**
     * Decrease stock when item is purchased
     */
    public function decreaseStock(int $quantity = 1): bool
    {
        if ($this->stock >= $quantity) {
            $this->stock -= $quantity;
            return $this->save();
        }
        return false;
    }

    /**
     * Increase stock when item is restocked
     */
    public function increaseStock(int $quantity = 1): bool
    {
        $this->stock += $quantity;
        return $this->save();
    }

    /**
     * Check if enough stock is available
     */
    public function hasStock(int $quantity = 1): bool
    {
        return $this->stock >= $quantity;
    }

    // Query scopes

    /**
     * Scope: Get only in-stock products
     */
    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    /**
     * Scope: Get low stock products
     */
    public function scopeLowStock($query)
    {
        return $query->where('stock', '>', 0)->where('stock', '<=', 5);
    }

    /**
     * Scope: Get out of stock products
     */
    public function scopeOutOfStock($query)
    {
        return $query->where('stock', '<=', 0);
    }

    /**
     * Scope: Get approved products
     */
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    /**
     * Scope: Get active products
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
