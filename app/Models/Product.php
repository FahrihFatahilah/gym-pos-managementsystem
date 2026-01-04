<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'stock',
        'unit',
        'is_active'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    // Relasi dengan StockHistory
    public function stockHistories()
    {
        return $this->hasMany(StockHistory::class);
    }

    // Relasi dengan TransactionDetail
    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    // Update stock
    public function updateStock($quantity, $type = 'in', $reason = null, $userId = null)
    {
        if ($type === 'in') {
            $this->increment('stock', $quantity);
        } else {
            $this->decrement('stock', $quantity);
        }

        // Create stock history
        $this->stockHistories()->create([
            'user_id' => $userId ?? auth()->id(),
            'type' => $type,
            'quantity' => $quantity,
            'reason' => $reason
        ]);
    }

    // Check if stock is low (less than 10)
    public function isLowStock()
    {
        return $this->stock < 10;
    }

    // Scope for active products
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}