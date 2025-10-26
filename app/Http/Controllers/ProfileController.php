<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Models\Employee;

class ProfileController extends Controller
{
    /**
     * Display the user's profile
     */
    public function show()
    {
        $user = auth()->user();
        $employee = Employee::where('user_id', $user->id)->first();

        return view('profile.show', compact('user', 'employee'));
    }

    /**
     * Show the form for editing the profile
     */
    public function edit()
    {
        $user = auth()->user();
        $employee = Employee::where('user_id', $user->id)->first();

        return view('profile.edit', compact('user', 'employee'));
    }

    /**
     * Update the user's profile
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photoName = time() . '_' . $photo->getClientOriginalName();
            $photo->move(public_path('uploads/profiles'), $photoName);
            $user->photo = $photoName;
        }

        $user->save();

        // Update employee record if exists
        $employee = Employee::where('user_id', $user->id)->first();
        if ($employee) {
            $employee->email = $validated['email'];
            if (isset($validated['phone'])) {
                $employee->phone = $validated['phone'];
            }
            $employee->save();
        }

        return redirect()->route('profile.show')->with('success', 'Profile updated successfully!');
    }

    /**
     * Show the form for changing password
     */
    public function editPassword()
    {
        return view('profile.change-password');
    }

    /**
     * Update the user's password
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = auth()->user();

        // Check if current password is correct
        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }

        // Update password
        $user->password = Hash::make($validated['password']);
        $user->save();

        return redirect()->route('profile.show')->with('success', 'Password changed successfully!');
    }
}
