<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index() 
    {
        $users = User::latest()->get();

        return view('users.index',compact('users'));
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

        return redirect()->route('users.index')->with('success','User account created successfully!');
    }

    public function destroy(User $user)
    {
        if($user->id === Auth::id()){
            return redirect()->route('users.index')->with('error','You cannot delete your own account.');
        }
    
        $user->delete();

        return redirect()->route('users.index')->with('success','User account deleted successfully!');
    }
}
