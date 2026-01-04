<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPermission extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'permission',
        'granted'
    ];

    protected $casts = [
        'granted' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function getAvailablePermissions()
    {
        return [
            'dashboard' => 'Dashboard',
            'members' => 'Kelola Member',
            'memberships' => 'Kelola Membership',
            'products' => 'Kelola Produk',
            'pos' => 'Point of Sale',
            'stocks' => 'Kelola Stok',
            'reports' => 'Laporan',
            'branches' => 'Kelola Cabang',
            'users' => 'Kelola User',
            'settings' => 'Pengaturan',
            'my_members' => 'Member Saya (PT)'
        ];
    }
}