<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_code',
        'user_id',
        'total_amount',
        'payment_method',
        'status'
    ];

    protected $casts = [
        'total_amount' => 'decimal:2'
    ];

    // Relasi dengan User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi dengan TransactionDetail
    public function details()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    // Generate transaction code
    public static function generateCode()
    {
        $date = date('Ymd');
        $lastTransaction = self::whereDate('created_at', today())->latest()->first();
        $sequence = $lastTransaction ? (int)substr($lastTransaction->transaction_code, -4) + 1 : 1;
        
        return 'TRX' . $date . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    // Get payment method label
    public function getPaymentMethodLabel()
    {
        return match($this->payment_method) {
            'cash' => 'Tunai',
            'qris' => 'QRIS',
            'transfer' => 'Transfer',
            default => $this->payment_method
        };
    }

    // Calculate total from details
    public function calculateTotal()
    {
        return $this->details->sum('subtotal');
    }
}