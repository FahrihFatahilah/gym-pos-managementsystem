<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'membership_id',
        'amount',
        'payment_method',
        'status',
        'payment_date',
        'notes'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date'
    ];

    // Relasi dengan Member
    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    // Relasi dengan Membership
    public function membership()
    {
        return $this->belongsTo(Membership::class);
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

    // Get status label
    public function getStatusLabel()
    {
        return match($this->status) {
            'pending' => 'Pending',
            'completed' => 'Selesai',
            'failed' => 'Gagal',
            default => $this->status
        };
    }
}