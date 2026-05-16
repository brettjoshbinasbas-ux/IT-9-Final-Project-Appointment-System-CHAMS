<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        // General Settings
        Setting::set('app_name', 'CHAMS', 'text', 'general', 'Application name');
        Setting::set('app_timezone', 'Asia/Manila', 'text', 'general', 'System timezone');
        Setting::set('date_format', 'Y-m-d', 'text', 'general', 'Date format');
        Setting::set('time_format', 'H:i', 'text', 'general', 'Time format');
        
        // Business Hours
        Setting::set('business_start_time', '09:00', 'text', 'business', 'Business start time');
        Setting::set('business_end_time', '17:00', 'text', 'business', 'Business end time');
        Setting::set('business_days', json_encode(['monday', 'tuesday', 'wednesday', 'thursday', 'friday']), 'json', 'business', 'Working days');
        Setting::set('appointment_duration', '30', 'number', 'business', 'Default appointment duration (minutes)');
        Setting::set('advance_booking_days', '30', 'number', 'business', 'Maximum days in advance for booking');
        
        // Notification Settings
        Setting::set('enable_email_notifications', true, 'boolean', 'notifications', 'Send email notifications');
        Setting::set('reminder_hours_before', '24', 'number', 'notifications', 'Hours before appointment to send reminder');
        Setting::set('admin_notification_email', 'admin@chams.com', 'text', 'notifications', 'Admin email for notifications');
        
        // Appearance Settings
        Setting::set('primary_color', '#8b5a8f', 'text', 'appearance', 'Primary theme color');
        Setting::set('sidebar_color', '#2a1a2e', 'text', 'appearance', 'Sidebar background color');
        Setting::set('company_logo', null, 'text', 'appearance', 'Company logo URL');
        Setting::set('company_name', 'Clinical Health Appointment Management System', 'text', 'appearance', 'Company full name');
    }
}