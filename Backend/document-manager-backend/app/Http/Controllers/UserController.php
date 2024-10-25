<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return User::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|string'
        ]);

        $user = User::create([
            'firstName' => $validated['firstName'],
            'lastName' => $validated['lastName'],
            'email' =>  $validated['email'],
            'password' => bcrypt($validated['password']),
            'role' => $validated['role'],
        ]);

        return response()->json($user, 201);
    }

    public function show(User $user)
    {
        return $user;
    }

    public function update(Request $request, User $user)
{
    $validated = $request->validate([
        'firstName' => 'sometimes|string|max:255',
        'lastName' => 'sometimes|string|max:255',
        'email' => 'sometimes|email|unique:users,email,' . $user->id,
        'role' => 'sometimes|in:user,admin,manager', // Validate role to allow only 'User' or 'Admin' or 'manager'
    ]);

    // Update the user with the validated data
    $user->update($validated);

    return response()->json($user);
}


    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(null, 204);
    }
}
