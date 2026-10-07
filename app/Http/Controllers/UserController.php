<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Unit;
use App\Models\EmployeeProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display all users
     */
    public function index()
    {
        $users = User::with('unit', 'profile')->paginate(15);
        return view('users.index', compact('users'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        $units = Unit::where('is_active', true)->get();
        $roles = ['guru', 'ob', 'keamanan', 'staff_it', 'management', 'tu', 'bidang_usaha'];
        return view('users.create', compact('units', 'roles'));
    }

    /**
     * Store new user
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|unique:users|max:255',
            'email' => 'nullable|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:guru,ob,keamanan,staff_it,management,tu,bidang_usaha',
            'unit_id' => 'required|exists:units,id',
            'is_administrator' => 'boolean',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'nik' => 'nullable|string',
            'address' => 'nullable|string',
            'place_of_birth' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'education' => 'nullable|in:SMA,Sarjana',
            'tmt' => 'nullable|date',
            'phone' => 'nullable|string',
        ]);

        $profilePhoto = null;
        if ($request->hasFile('profile_photo')) {
            $profilePhoto = $request->file('profile_photo')->store('profile_photos', 'public');
        }

        $user = User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'unit_id' => $validated['unit_id'],
            'is_administrator' => $validated['is_administrator'] ?? false,
            'profile_photo' => $profilePhoto,
        ]);

        // Create employee profile
        EmployeeProfile::create([
            'user_id' => $user->id,
            'nik' => $validated['nik'] ?? '',
            'address' => $validated['address'],
            'place_of_birth' => $validated['place_of_birth'],
            'date_of_birth' => $validated['date_of_birth'],
            'education' => $validated['education'],
            'tmt' => $validated['tmt'],
            'phone' => $validated['phone'],
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan!');
    }

    /**
     * Show edit form
     */
    public function edit(User $user)
    {
        $units = Unit::where('is_active', true)->get();
        $roles = ['guru', 'ob', 'keamanan', 'staff_it', 'management', 'tu', 'bidang_usaha'];
        return view('users.edit', compact('user', 'units', 'roles'));
    }

    /**
     * Update user
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'nullable|email|unique:users,email,' . $user->id,
            'role' => 'required|in:guru,ob,keamanan,staff_it,management,tu,bidang_usaha',
            'unit_id' => 'required|exists:units,id',
            'is_active' => 'boolean',
            'is_administrator' => 'boolean',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'nik' => 'nullable|string',
            'address' => 'nullable|string',
            'place_of_birth' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'education' => 'nullable|in:SMA,Sarjana',
            'tmt' => 'nullable|date',
            'phone' => 'nullable|string',
        ]);

        $updateData = [
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'unit_id' => $validated['unit_id'],
            'is_active' => $validated['is_active'] ?? true,
            'is_administrator' => $validated['is_administrator'] ?? false,
        ];

        if ($request->hasFile('profile_photo')) {
            $updateData['profile_photo'] = $request->file('profile_photo')->store('profile_photos', 'public');
        }

        $user->update($updateData);

        // Update or create employee profile
        if ($user->profile) {
            $user->profile->update([
                'nik' => $validated['nik'] ?? '',
                'address' => $validated['address'],
                'place_of_birth' => $validated['place_of_birth'],
                'date_of_birth' => $validated['date_of_birth'],
                'education' => $validated['education'],
                'tmt' => $validated['tmt'],
                'phone' => $validated['phone'],
            ]);
        } else {
            // Create profile if it doesn't exist
            EmployeeProfile::create([
                'user_id' => $user->id,
                'nik' => $validated['nik'] ?? '',
                'address' => $validated['address'],
                'place_of_birth' => $validated['place_of_birth'],
                'date_of_birth' => $validated['date_of_birth'],
                'education' => $validated['education'],
                'tmt' => $validated['tmt'],
                'phone' => $validated['phone'],
            ]);
        }

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui!');
    }

    /**
     * Delete user
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User berhasil dihapus!');
    }
}
