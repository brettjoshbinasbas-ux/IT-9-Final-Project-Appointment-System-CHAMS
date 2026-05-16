<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Client;
use App\Models\ServiceRecord;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalClients = Client::count();
        $todayCount = Appointment::today()->count();
        $completedCount = Appointment::byStatus(Appointment::STATUS_COMPLETED)->count();
        $scheduledCount = Appointment::byStatus(Appointment::STATUS_SCHEDULED)->count();
        $upcomingAppointments = Appointment::with(['client', 'staff'])
            ->upcoming()
            ->limit(5)
            ->get();
        $recentRecords = ServiceRecord::with(['client', 'appointment'])
            ->latest()
            ->limit(5)
            ->get();

        // Chart data
        $statusCounts = [];
        $statusLabels = [];
        foreach (Appointment::STATUSES as $key => $label) {
            $statusLabels[] = $label;
            $statusCounts[] = Appointment::where('status', $key)->count();
        }

        // Last 7 days trend
        $weekLabels = [];
        $weekCounts = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $weekLabels[] = $date->format('D');
            $weekCounts[] = Appointment::whereDate('appointment_date', $date)->count();
        }

        return view('dashboard', compact('totalClients', 'todayCount', 'completedCount', 'scheduledCount', 'upcomingAppointments', 'recentRecords', 'statusLabels', 'statusCounts', 'weekLabels', 'weekCounts'));
    }
}
