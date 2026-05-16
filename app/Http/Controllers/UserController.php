<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Show active users only
    public function index()
    {
        // Show all users (including soft deleted) with trashed ones at the bottom
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

    // Soft delete user
    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return redirect()->route('users.index')->with('error', 'You cannot delete your own account.');
        }

        // Check if user has important active records
        if ($user->assignedAppointments()->whereNull('deleted_at')->exists() || $user->createdAppointments()->whereNull('deleted_at')->exists()) {
            return redirect()->route('users.index')->with('error', 'Cannot delete user with active appointments. Reassign or complete appointments first.');
        }

        // Soft delete
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

    // Permanently delete user (only if no related records)
    public function forceDelete($id)
    {
        $user = User::withTrashed()->findOrFail($id);

        // Check if user has any related records (including soft-deleted ones)
        if ($user->assignedAppointments()->withTrashed()->exists() || $user->createdAppointments()->withTrashed()->exists() || $user->serviceRecords()->withTrashed()->exists() || $user->histories()->exists()) {
            return redirect()->route('users.trashed')->with('error', 'Cannot permanently delete user with existing records. Archive the records first.');
        }

        $user->forceDelete();

        return redirect()->route('users.trashed')->with('success', 'User permanently deleted!');
    }
}
