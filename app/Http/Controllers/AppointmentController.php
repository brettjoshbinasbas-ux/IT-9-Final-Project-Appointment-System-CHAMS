<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAppointmentRequest;
use App\Models\Client;
use App\Models\User;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // ← Add at top

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $status = $request->query('status');
        $date = $request->query('date');

        $query = Appointment::with(['client', 'staff']);

        /** @var AuthManager $auth */
        $auth = auth();
        if ($auth->user()->isStaff()) {
            $query->where('staff_id', $auth->id());
        }

        $appointments = $query->when($status, fn($q) => $q->where('status', $status))->when($date, fn($q) => $q->whereDate('appointment_date', $date))->latest()->paginate(10);

        $statuses = Appointment::STATUSES;

        return view('appointments.index', compact('status', 'date', 'appointments', 'statuses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clients = Client::orderBy('first_name')->get();
        $staff = User::where('role', 'staff')->orderBy('name')->get();
        $statuses = Appointment::STATUSES;

        return view('appointments.create', compact('clients', 'staff', 'statuses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAppointmentRequest $request)
    {
        $data = $request->validated();
        $data['created_by'] = Auth::id(); // ← Fixed

        Appointment::create($data);

        return redirect()->route('appointments.index')->with('success', 'Appointment scheduled successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Appointment $appointment)
    {
        $appointment->load(['client', 'staff', 'creator', 'serviceRecord']);

        return view('appointments.show', compact('appointment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Appointment $appointment)
    {
        if ($appointment->status === 'completed') {
            return redirect()->route('appointments.show', $appointment)->with('error', 'Completed appointments cannot be edited.');
        }

        $clients = Client::orderBy('first_name')->get();
        $staff = User::where('role', 'staff')->orderBy('name')->get();
        $statuses = Appointment::STATUSES;

        return view('appointments.edit', compact('appointment', 'clients', 'staff', 'statuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreAppointmentRequest $request, Appointment $appointment)
    {
        if ($appointment->status === 'completed') {
            return redirect()->route('appointments.show', $appointment)->with('error', 'Completed appointments cannot be edited.');
        }

        $appointment->update($request->validated());

        return redirect()->route('appointments.show', $appointment)->with('success', 'Appointment updated successfully!');
    }

    // Update ONLY the status (PATCH route)
    public function updateStatus(Request $request, Appointment $appointment)
    {
        if ($appointment->status === 'completed') {
            return redirect()->route('appointments.show', $appointment)->with('error', 'Completed appointments cannot change status.');
        }

        $request->validate([
            'status' => ['required', \Illuminate\Validation\Rule::in(array_keys(Appointment::STATUSES))],
        ]);

        $appointment->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Appointment status updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Appointment $appointment)
    {
        $appointment->delete();

        return redirect()->route('appointments.index')->with('success', 'Appointment deleted.');
    }
}
