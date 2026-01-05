<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyUser extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'email',
        'personal_trainer_id',
        'fitness_goals',
        'visit_date',
        'valid_until',
        'amount_paid',
        'is_custom_price',
        'custom_price',
        'payment_method'
    ];

    protected $casts = [
        'visit_date' => 'date',
        'valid_until' => 'date',
        'amount_paid' => 'decimal:2',
        'custom_price' => 'decimal:2',
        'is_custom_price' => 'boolean'
    ];

    public function personalTrainer()
    {
        return $this->belongsTo(PersonalTrainer::class);
    }
}