<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    // GET /api/appointments
    public function index()
    {
        $appointments = Appointment::with(['client','staff'])->latest()->get();

        return response()->json([
            'success' => true,
            'data' => $appointments ,
            'total' => $appointments->count(),
        ]);
    }

    public function show(Appointment $appointment)
    {
        $appointment->load(['client','staff','serviceRecord']);

        return response()->json([
            'success' => true,
            'data' => $appointment,
        ]);
    }

    public function today()
    {
        $appointments = Appointment::with(['client','staff'])->today()->get();

        return response()->json([
            'success' => true,
            'data' => today()->toDateString(),
            'data' => $appointments,
            'total' => $appointments->count(),
        ]);
    }
}
