<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'branch_id',
        'personal_trainer_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relasi dengan Transaction
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    // Relasi dengan StockHistory
    public function stockHistories()
    {
        return $this->hasMany(StockHistory::class);
    }

    // Relasi dengan Branch
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    // Relasi dengan PersonalTrainer
    public function personalTrainer()
    {
        return $this->belongsTo(PersonalTrainer::class);
    }

    // Relasi dengan UserPermission
    public function permissions()
    {
        return $this->hasMany(UserPermission::class);
    }

    // Check if user has permission
    public function hasPermission($permission)
    {
        // Admin has all permissions
        if ($this->role === 'admin') {
            return true;
        }

        // Check custom permissions
        $userPermission = $this->permissions()->where('permission', $permission)->first();
        
        if ($userPermission) {
            return $userPermission->granted;
        }

        // Default permissions by role
        return $this->getDefaultPermissions()[$permission] ?? false;
    }

    // Get default permissions by role
    public function getDefaultPermissions()
    {
        $defaults = [
            'admin' => [
                'dashboard' => true,
                'members' => true,
                'memberships' => true,
                'products' => true,
                'pos' => true,
                'stocks' => true,
                'reports' => true,
                'branches' => true,
                'users' => true,
                'settings' => true,
                'my_members' => false
            ],
            'staff' => [
                'dashboard' => true,
                'members' => true,
                'memberships' => true,
                'products' => true,
                'pos' => true,
                'stocks' => true,
                'reports' => false,
                'branches' => false,
                'users' => false,
                'settings' => false,
                'my_members' => false
            ],
            'owner' => [
                'dashboard' => true,
                'members' => false,
                'memberships' => false,
                'products' => false,
                'pos' => false,
                'stocks' => false,
                'reports' => true,
                'branches' => false,
                'users' => false,
                'settings' => false,
                'my_members' => false
            ],
            'pt' => [
                'dashboard' => false,
                'members' => false,
                'memberships' => false,
                'products' => false,
                'pos' => false,
                'stocks' => false,
                'reports' => false,
                'branches' => false,
                'users' => false,
                'settings' => false,
                'my_members' => true
            ]
        ];

        return $defaults[$this->role] ?? [];
    }

    // Get role label
    public function getRoleLabel()
    {
        return match($this->role) {
            'admin' => 'Administrator',
            'staff' => 'Staff/Kasir',
            'owner' => 'Owner',
            'pt' => 'Personal Trainer',
            default => $this->role
        };
    }
}
