<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GymSetting;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    /**
     * Display gym settings
     */
    public function index()
    {
        $settings = GymSetting::getSettings();
        
        return view('settings.index', compact('settings'));
    }

    /**
     * Update gym settings
     */
    public function update(Request $request)
    {
        $request->validate([
            'gym_name' => 'required|string|max:255',
            'gym_address' => 'nullable|string',
            'gym_phone' => 'nullable|string|max:20',
            'gym_email' => 'nullable|email|max:255',
            'gym_website' => 'nullable|url|max:255',
            'gym_description' => 'nullable|string',
            'receipt_footer' => 'nullable|string',
            'membership_monthly_price' => 'required|numeric|min:0',
            'membership_yearly_price' => 'required|numeric|min:0',
            'membership_daily_price' => 'required|numeric|min:0',
            'daily_price_regular' => 'required|numeric|min:0',
            'daily_price_premium' => 'required|numeric|min:0',
            'currency' => 'required|string|in:IDR,USD,EUR',
            'timezone' => 'required|string',
            'gym_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gym_favicon' => 'nullable|image|mimes:jpeg,png,jpg,gif,ico|max:1024'
        ]);

        $settings = GymSetting::getSettings();
        $data = $request->except(['gym_logo', 'gym_favicon']);

        // Handle logo upload
        if ($request->hasFile('gym_logo')) {
            // Delete old logo if exists
            if ($settings->gym_logo && Storage::disk('public')->exists($settings->gym_logo)) {
                Storage::disk('public')->delete($settings->gym_logo);
            }

            // Store new logo
            $logoPath = $request->file('gym_logo')->store('logos', 'public');
            $data['gym_logo'] = $logoPath;
        }

        // Handle favicon upload
        if ($request->hasFile('gym_favicon')) {
            // Delete old favicon if exists
            if ($settings->gym_favicon && Storage::disk('public')->exists($settings->gym_favicon)) {
                Storage::disk('public')->delete($settings->gym_favicon);
            }

            // Store new favicon
            $faviconPath = $request->file('gym_favicon')->store('favicons', 'public');
            $data['gym_favicon'] = $faviconPath;
        }

        $settings->update($data);

        return redirect()->route('settings.index')
            ->with('success', 'Pengaturan gym berhasil diperbarui.');
    }

    /**
     * Remove logo
     */
    public function removeLogo()
    {
        $settings = GymSetting::getSettings();
        
        if ($settings->gym_logo && Storage::disk('public')->exists($settings->gym_logo)) {
            Storage::disk('public')->delete($settings->gym_logo);
        }
        
        $settings->update(['gym_logo' => null]);
        
        return response()->json(['success' => true]);
    }

    /**
     * Remove favicon
     */
    public function removeFavicon()
    {
        $settings = GymSetting::getSettings();
        
        if ($settings->gym_favicon && Storage::disk('public')->exists($settings->gym_favicon)) {
            Storage::disk('public')->delete($settings->gym_favicon);
        }
        
        $settings->update(['gym_favicon' => null]);
        
        return response()->json(['success' => true]);
    }
}