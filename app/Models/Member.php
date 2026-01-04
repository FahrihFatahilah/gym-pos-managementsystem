<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'address',
        'status',
        'personal_trainer_id',
        'fitness_goals'
    ];

    protected $casts = [
        'status' => 'string'
    ];

    // Relasi dengan Membership
    public function memberships()
    {
        return $this->hasMany(Membership::class);
    }

    // Relasi dengan Payment
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    // Relasi dengan Personal Trainer
    public function personalTrainer()
    {
        return $this->belongsTo(PersonalTrainer::class);
    }

    // Get active membership
    public function activeMembership()
    {
        return $this->hasOne(Membership::class)->where('status', 'active')->latest();
    }

    // Check if member has active membership
    public function hasActiveMembership()
    {
        return $this->activeMembership()->exists();
    }

    // Update member status based on membership
    public function updateStatus()
    {
        $activeMembership = $this->activeMembership;
        
        if ($activeMembership && $activeMembership->end_date >= Carbon::today()) {
            $this->update(['status' => 'active']);
        } else {
            $this->update(['status' => 'expired']);
        }
    }
}