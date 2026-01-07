<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PersonalTrainer extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'email',
        'specialization',
        'hourly_rate',
        'is_active'
    ];

    protected $casts = [
        'hourly_rate' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    public function members()
    {
        return $this->hasMany(Member::class);
    }

    public function ptMembers()
    {
        return $this->hasMany(PTMember::class);
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }
}
