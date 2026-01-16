<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PTMember extends Model
{
    protected $fillable = [
        'member_code',
        'name',
        'phone',
        'email',
        'address',
        'personal_trainer_id',
        'packet_id',
        'start_date',
        'end_date',
        'sessions_remaining',
        'total_sessions',
        'amount_paid',
        'payment_method',
        'status',
        'notes',
        'user_id',
        'transaction_date'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'amount_paid' => 'decimal:2',
        'transaction_date' => 'date'
    ];

    public function personalTrainer()
    {
        return $this->belongsTo(PersonalTrainer::class);
    }

    public function packet()
    {
        return $this->belongsTo(Packet::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function updateStatus()
    {
        if ($this->sessions_remaining <= 0 && $this->end_date < Carbon::today()) {
            $this->update(['status' => 'expired']);
        } elseif ($this->sessions_remaining <= 0) {
            $this->update(['status' => 'completed']);
        } elseif ($this->end_date < Carbon::today()) {
            $this->update(['status' => 'expired']);
        } else {
            $this->update(['status' => 'active']);
        }
    }

    public function getFormattedAmountAttribute()
    {
        return 'Rp ' . number_format($this->amount_paid, 0, ',', '.');
    }
}
