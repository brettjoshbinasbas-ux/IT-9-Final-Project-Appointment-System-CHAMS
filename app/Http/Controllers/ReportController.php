<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Client;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $staffActivity = User::where('role', 'staff')
            ->when(Auth::user()->isStaff(), function ($query) {
                $query->where('id', Auth::id());
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

    /**
     * Export reports to CSV
     */
    public function exportCsv()
    {
        $fileName = 'reports_' . date('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');

            // Add CSV headers
            fputcsv($file, ['Report Type', 'Date Generated', now()->format('Y-m-d H:i:s')]);
            fputcsv($file, []);

            // Staff Activity Report
            fputcsv($file, ['STAFF ACTIVITY REPORT']);
            fputcsv($file, ['Staff Name', 'Appointments Handled']);

            $staffActivity = User::where('role', 'staff')
                ->when(Auth::user()->isStaff(), function ($query) {
                    $query->where('id', Auth::id());
                })
                ->withCount('assignedAppointments')
                ->orderBy('assigned_appointments_count', 'desc')
                ->get();

            foreach ($staffActivity as $staff) {
                fputcsv($file, [$staff->name, $staff->assigned_appointments_count]);
            }

            fputcsv($file, []);

            // Client Visits Report
            fputcsv($file, ['CLIENT VISITS REPORT']);
            fputcsv($file, ['Client Name', 'Total Visits']);

            $clientVisits = Client::withCount('appointments')->orderBy('appointments_count', 'desc')->limit(10)->get();

            foreach ($clientVisits as $client) {
                fputcsv($file, [$client->full_name, $client->appointments_count]);
            }

            fputcsv($file, []);

            // Weekly Summary
            fputcsv($file, ['WEEKLY SUMMARY (Last 7 Days)']);
            fputcsv($file, ['Date', 'Appointments']);

            for ($i = 6; $i >= 0; $i--) {
                $date = now()->subDays($i);
                $count = Appointment::whereDate('appointment_date', $date)->count();
                fputcsv($file, [$date->format('D, M d'), $count]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export reports to PDF
     */
    public function exportPdf()
    {
        // Get data for PDF
        $staffActivity = User::where('role', 'staff')
            ->when(Auth::user()->isStaff(), function ($query) {
                $query->where('id', Auth::id());
            })
            ->withCount('assignedAppointments')
            ->orderBy('assigned_appointments_count', 'desc')
            ->get();

        $clientVisits = Client::withCount('appointments')->orderBy('appointments_count', 'desc')->limit(10)->get();

        $weeklyAppointments = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $weeklyAppointments[] = [
                'date' => $date->format('D, M d'),
                'count' => Appointment::whereDate('appointment_date', $date)->count(),
            ];
        }

        $completedCount = Appointment::where('status', 'completed')->count();
        $cancelledCount = Appointment::where('status', 'cancelled')->count();
        $completedThisMonth = Appointment::where('status', 'completed')->whereMonth('appointment_date', now()->month)->count();

        $pdf = Pdf::loadView('reports.export-pdf', compact('staffActivity', 'clientVisits', 'weeklyAppointments', 'completedCount', 'cancelledCount', 'completedThisMonth'));

        return $pdf->download('reports_' . date('Y-m-d_His') . '.pdf');
    }

    
}
