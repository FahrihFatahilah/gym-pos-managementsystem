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
        'amount_paid',
        'payment_method'
    ];

    protected $casts = [
        'visit_date' => 'date',
        'amount_paid' => 'decimal:2'
    ];

    public function personalTrainer()
    {
        return $this->belongsTo(PersonalTrainer::class);
    }
}