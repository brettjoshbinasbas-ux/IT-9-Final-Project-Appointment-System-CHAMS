<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use Illuminate\Database\Eloquent\Builder;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $search = request('search');

        $clients = Client::when($search, function (Builder $query) use ($search) {
            $query
                ->where('first_name', 'like', "%{$search}%")
                ->orWhere('last_name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%");
        })
            ->latest()
            ->paginate(10);

        return view('clients.index', compact('clients', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('clients.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreClientRequest $request)
    {
        Client::create($request->validated());
        return redirect()->route('clients.index')->with('success', 'Client record registered successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client)
    {
        $client->load(['appointments.staff', 'appointments.serviceRecord', 'serviceRecord.appointment', 'appointments.creator', 'serviceRecord.staff']);

        return view('clients.show', compact('client'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client)
    {
        return view('clients.edit', compact('client'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateClientRequest $request, Client $client)
    {
        $client->update($request->validated());
        return redirect()->route('clients.show', $client)->with('success', 'Client record updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        $client->delete();
        return redirect()->route('clients.index')->with('success', 'Client record deleted successfully!');
    }

    /**
     * Display trashed (soft-deleted) clients.
     */
    public function trashed()
    {
        $clients = Client::onlyTrashed()->latest('deleted_at')->paginate(10);
        return view('clients.trashed', compact('clients'));
    }

    /**
     * Restore a soft-deleted client.
     */
    public function restore($id)
    {
        $client = Client::withTrashed()->findOrFail($id);
        $client->restore();

        return redirect()->route('clients.trashed')->with('success', 'Client restored successfully!');
    }

    /**
     * Permanently delete a client (force delete).
     */
    public function forceDelete($id)
    {
        $client = Client::withTrashed()->findOrFail($id);
        $client->forceDelete();

        return redirect()->route('clients.trashed')->with('success', 'Client permanently deleted!');
    }
}
