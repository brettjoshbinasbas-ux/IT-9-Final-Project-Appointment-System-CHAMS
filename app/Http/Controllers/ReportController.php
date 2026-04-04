<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Client;
use App\Models\User;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        // Daily appointments (today)
        $dailyAppointments = Appointment::with(['client', 'staff'])
            ->whereDate('appointment_date', today())
            ->get();

        // Completed appointments (all time)
        $completedCount = Appointment::where('status', 'completed')->count();

        // Completed appointments (this month)
        $completedThisMonth = Appointment::where('status', 'completed')->whereMonth('appointment_date', now()->month)->count();

        // Cancelled appointments
        $cancelledCount = Appointment::where('status', 'cancelled')->count();

        // Staff activity (appointments per staff member)
        /** @var AuthManager $auth */
        $auth = auth();

        $staffActivity = User::where('role', 'staff')
            ->when($auth->user()->isStaff(), function ($query) {
                /** @var AuthManager $auth */
                $auth = auth();
                $query->where('id', $auth->id());
            })
            ->withCount('assignedAppointments')
            ->orderBy('assigned_appointments_count', 'desc')
            ->get();
            
        // Client visit summary (appointments per client)
        $clientVisits = Client::withCount('appointments')->orderBy('appointments_count', 'desc')->limit(10)->get();

        // Weekly summary (last 7 days)
        $weeklyAppointments = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $weeklyAppointments[] = [
                'date' => $date->format('D, M d'),
                'count' => Appointment::whereDate('appointment_date', $date)->count(),
            ];
        }

        return view('reports.index', compact('dailyAppointments', 'completedCount', 'completedThisMonth', 'cancelledCount', 'staffActivity', 'clientVisits', 'weeklyAppointments'));
    }
}
