<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    /**
     * Show edit profile form
     */
    public function edit()
    {
        $user = Auth::user();
        $profile = $user->profile;
        return view('profile.edit', compact('user', 'profile'));
    }

    /**
     * Update profile information
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . $user->id,
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
        ];

        if ($request->hasFile('profile_photo')) {
            $updateData['profile_photo'] = $request->file('profile_photo')->store('profile_photos', 'public');
        }

        $user->update($updateData);

        if ($user->profile) {
            $user->profile->update([
                'address' => $validated['address'],
                'phone' => $validated['phone'],
            ]);
        }

        return redirect()->route('profile.edit')->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * Update profile photo
     */
    public function updatePhoto(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'profile_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $photoPath = $request->file('profile_photo')->store('profile_photos', 'public');

        // Delete old photo if exists
        if ($user->profile_photo) {
            $oldPath = storage_path('app/public/' . $user->profile_photo);
            if (file_exists($oldPath)) {
                unlink($oldPath);
            }
        }

        $user->update(['profile_photo' => $photoPath]);

        return redirect()->route('profile.edit')->with('success', 'Foto profil berhasil diupdate!');
    }

    /**
     * Update password
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if (!Hash::check($validated['current_password'], $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => 'Password saat ini tidak sesuai',
            ]);
        }

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('profile.edit')->with('success', 'Password berhasil diperbarui!');
    }
}
