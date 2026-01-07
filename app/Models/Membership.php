<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Membership extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'packet_id',
        'type',
        'category',
        'start_date',
        'end_date',
        'price',
        'status'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'price' => 'decimal:2'
    ];

    // Relasi dengan Member
    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    // Relasi dengan Packet
    public function packet()
    {
        return $this->belongsTo(Packet::class);
    }

    // Relasi dengan Payment
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    // Check if membership is expired
    public function isExpired()
    {
        return $this->end_date < Carbon::today();
    }

    // Update status based on end date
    public function updateStatus()
    {
        if ($this->isExpired()) {
            $this->update(['status' => 'expired']);
            $this->member->updateStatus();
        }
    }

    // Get membership type label
    public function getTypeLabel()
    {
        return match($this->type) {
            'monthly' => 'Bulanan',
            'yearly' => 'Tahunan',
            'custom' => 'Custom',
            default => $this->type
        };
    }

    // Get category label
    public function getCategoryLabel()
    {
        return match($this->category) {
            'regular' => 'Regular',
            'pt' => 'With PT',
            default => $this->category
        };
    }
}