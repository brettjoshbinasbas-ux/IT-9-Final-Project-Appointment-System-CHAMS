<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Show all users (including soft deleted)
    public function index()
    {
        $users = User::withTrashed()->latest()->get();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);

        User::create($data);

        return redirect()->route('users.index')->with('success', 'User account created successfully!');
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $request->validated();

        // Only update password if provided
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'User updated successfully!');
    }

    // Soft delete user
    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return redirect()->route('users.index')->with('error', 'You cannot delete your own account.');
        }

        if ($user->assignedAppointments()->whereNull('deleted_at')->exists() || $user->createdAppointments()->whereNull('deleted_at')->exists()) {
            return redirect()->route('users.index')->with('error', 'Cannot delete user with active appointments. Reassign or complete appointments first.');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'User account deactivated successfully!');
    }

    // Show soft-deleted users
    public function trashed()
    {
        $users = User::onlyTrashed()->latest('deleted_at')->get();
        return view('users.trashed', compact('users'));
    }

    // Restore soft-deleted user
    public function restore($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->restore();

        return redirect()->route('users.trashed')->with('success', 'User restored successfully!');
    }

    // Permanently delete user
    public function forceDelete($id)
    {
        $user = User::withTrashed()->findOrFail($id);

        if ($user->assignedAppointments()->withTrashed()->exists() || $user->createdAppointments()->withTrashed()->exists() || $user->serviceRecords()->withTrashed()->exists() || $user->histories()->exists()) {
            return redirect()->route('users.trashed')->with('error', 'Cannot permanently delete user with existing records.');
        }

        $user->forceDelete();

        return redirect()->route('users.trashed')->with('success', 'User permanently deleted!');
    }
}
