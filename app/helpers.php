<?php

if (!function_exists('gymSettings')) {
    /**
     * Get gym settings
     */
    function gymSettings()
    {
        return App\Models\GymSetting::getSettings();
    }
}

if (!function_exists('formatPrice')) {
    /**
     * Format price with currency
     */
    function formatPrice($amount)
    {
        $settings = gymSettings();
        return $settings->formatPrice($amount);
    }
}

if (!function_exists('gymName')) {
    /**
     * Get gym name
     */
    function gymName()
    {
        return gymSettings()->gym_name;
    }
}

if (!function_exists('gymLogo')) {
    /**
     * Get gym logo URL
     */
    function gymLogo()
    {
        return gymSettings()->logo_url;
    }
}