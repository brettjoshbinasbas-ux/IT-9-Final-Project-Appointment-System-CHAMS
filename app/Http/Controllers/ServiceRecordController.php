<?php

namespace App\Http\Controllers;

use App\Models\ServiceRecord;
use App\Http\Requests\StoreServiceRecordRequest;
use App\Http\Requests\UpdateServiceRecordRequest;
use App\Models\Appointment;

class ServiceRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $records = ServiceRecord::with(['client', 'staff', 'appointment'])
            ->latest()
            ->paginate(10);

        return view('service-records.index', compact('records'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreServiceRecordRequest $request)
    {
        $appointment = Appointment::findOrFail($request->appointment_id);

        $data = [
            'appointment_id' => $appointment->id,
            'client_id' => $appointment->client_id,
            'staff_id' => $appointment->staff_id,
            'description' => $request->description,
            'service_date' => $request->service_date,
            'remarks' => $request->remarks,
        ];

        ServiceRecord::forceCreate($data);

        $appointment->update(['status' => 'completed']);

        return redirect()->route('appointments.show', $appointment)->with('success', 'Service record saved and appointment marked as completed!');
    }

    /**
     * Display the specified resource.
     */
    public function show(ServiceRecord $serviceRecord)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ServiceRecord $serviceRecord)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateServiceRecordRequest $request, ServiceRecord $serviceRecord)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ServiceRecord $serviceRecord)
    {
        //
    }
}
