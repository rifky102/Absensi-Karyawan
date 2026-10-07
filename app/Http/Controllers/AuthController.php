<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Unit;
use App\Models\EmployeeProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Handle login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login' => 'required|string', // username or email
            'password' => 'required|string',
        ]);

        $user = User::where('username', $credentials['login'])
            ->orWhere('email', $credentials['login'])
            ->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'login' => 'Username/Email atau Password salah',
            ]);
        }

        if (!$user->is_active) {
            throw ValidationException::withMessages([
                'login' => 'Akun Anda tidak aktif',
            ]);
        }

        Auth::login($user, $request->boolean('remember'));

        return redirect()->intended('/dashboard')->with('success', 'Login berhasil!');
    }

    /**
     * Show register form
     */
    public function showRegister()
    {
        $units = Unit::where('is_active', true)->get();
        return view('auth.register', compact('units'));
    }

    /**
     * Handle register
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|unique:users|max:255',
            'email' => 'nullable|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'unit_id' => 'required|exists:units,id',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'unit_id' => $validated['unit_id'],
            'role' => 'guru', // Default role
        ]);

        // Create empty employee profile
        EmployeeProfile::create([
            'user_id' => $user->id,
            'nik' => '',
        ]);

        Auth::login($user);

        return redirect('/dashboard')->with('success', 'Registrasi berhasil!');
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Logout berhasil!');
    }
}
