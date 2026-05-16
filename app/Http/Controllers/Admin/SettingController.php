<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SettingController extends Controller
{
    public function index()
    {
        // Get all settings grouped
        $settings = Setting::all()->pluck('value', 'key')->toArray();
        
        return view('settings.index', compact('settings'));
    }
    
    public function update(Request $request)
    {
        // Get all settings from form
        $settings = $request->except('_token', '_method');
        
        // Process business days array if present
        if (isset($settings['business_days'])) {
            $settings['business_days'] = json_encode($settings['business_days']);
        }
        
        // Update each setting
        foreach ($settings as $key => $value) {
            $setting = Setting::where('key', $key)->first();
            if ($setting) {
                // Handle boolean values
                if ($setting->type === 'boolean') {
                    $value = filter_var($value, FILTER_VALIDATE_BOOLEAN);
                }
                $setting->update(['value' => $value]);
            }
        }
        
        // Clear cache so settings reload
        Cache::forget('app_settings');
        
        return redirect()->route('admin.settings.index')
            ->with('success', 'System settings updated successfully!');
    }
    
    public function reset()
    {
        // Reset to defaults
        \Artisan::call('db:seed', ['--class' => 'SettingsSeeder', '--force' => true]);
        Cache::forget('app_settings');
        
        return redirect()->route('admin.settings.index')
            ->with('success', 'Settings reset to default values!');
    }
}