<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Packet extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name',
        'type',
        'sessions',
        'duration_days',
        'price',
        'duration_minutes',
        'membership_months',
        'start_date',
        'end_date',
        'description',
        'is_active'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date'
    ];

    public function ptMembers()
    {
        return $this->hasMany(PTMember::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    public function getFormattedDurationAttribute()
    {
        $months = $this->duration_days / 30;
        return $months == 1 ? '1 bulan' : $months . ' bulan';
    }
}
