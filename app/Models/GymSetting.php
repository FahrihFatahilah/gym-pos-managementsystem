<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class GymSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'gym_name',
        'gym_logo',
        'gym_favicon',
        'gym_address',
        'gym_phone',
        'gym_email',
        'gym_website',
        'gym_description',
        'receipt_footer',
        'membership_monthly_price',
        'membership_yearly_price',
        'membership_daily_price',
        'daily_price_regular',
        'daily_price_premium',
        'currency',
        'timezone'
    ];

    protected $casts = [
        'membership_monthly_price' => 'decimal:2',
        'membership_yearly_price' => 'decimal:2',
        'membership_daily_price' => 'decimal:2',
        'daily_price_regular' => 'decimal:2',
        'daily_price_premium' => 'decimal:2'
    ];

    /**
     * Get gym settings (singleton pattern)
     */
    public static function getSettings()
    {
        $settings = self::first();
        
        if (!$settings) {
            $settings = self::create([
                'gym_name' => 'Gym & POS System',
                'gym_address' => 'Jl. Contoh No. 123, Jakarta',
                'gym_phone' => '(021) 1234-5678',
                'gym_email' => 'info@gym.com',
                'receipt_footer' => 'Terima kasih atas kunjungan Anda!\nSemoga sehat selalu',
                'membership_monthly_price' => 150000,
                'membership_yearly_price' => 1500000,
                'daily_price_regular' => 25000,
                'daily_price_premium' => 35000
            ]);
        }
        
        return $settings;
    }

    /**
     * Get gym favicon URL
     */
    public function getFaviconUrlAttribute()
    {
        if ($this->gym_favicon && Storage::disk('public')->exists($this->gym_favicon)) {
            return Storage::disk('public')->url($this->gym_favicon);
        }
        
        return asset('favicon.ico');
    }

    /**
     * Get gym logo URL
     */
    public function getLogoUrlAttribute()
    {
        if ($this->gym_logo && Storage::disk('public')->exists($this->gym_logo)) {
            return Storage::disk('public')->url($this->gym_logo);
        }
        
        return asset('images/default-logo.png');
    }

    /**
     * Get formatted phone number
     */
    public function getFormattedPhoneAttribute()
    {
        return $this->gym_phone ?: '-';
    }

    /**
     * Get formatted address
     */
    public function getFormattedAddressAttribute()
    {
        return $this->gym_address ?: '-';
    }

    /**
     * Get currency symbol
     */
    public function getCurrencySymbolAttribute()
    {
        return match($this->currency) {
            'IDR' => 'Rp',
            'USD' => '$',
            'EUR' => '€',
            default => 'Rp'
        };
    }

    /**
     * Format price with currency
     */
    public function formatPrice($amount)
    {
        return $this->currency_symbol . ' ' . number_format($amount, 0, ',', '.');
    }
}