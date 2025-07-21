<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:6',
            'role' => 'required|in:admin,supplier,wholesaler,vendor',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        // If vendor, log in and redirect to vendor dashboard
        if ($user->role === 'vendor') {
            Auth::login($user);
            return redirect()->route('vendor.dashboard')->with('success', 'Registered successfully as a vendor!');
        }

        return redirect()->route('login')->with('success', 'Registered successfully! You can now log in.');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            '_token' => 'required|string', // Ensure CSRF token is present
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            // Regenerate the session ID for security
            $request->session()->regenerate();

            // Regenerate CSRF token
            $request->session()->regenerateToken();

            $user = Auth::user();

            // Add user info to session
            session([
                'user_id' => $user->id,
                'user_role' => $user->role,
                'user_name' => $user->name,
            ]);

            // Redirect to intended URL or role-based dashboard
            $route = match ($user->role) {
                'admin' => route('admin.dashboard'),
                'supplier' => route('supplier.dashboard'),
                'wholesaler' => route('wholesaler.dashboard'),
                'vendor' => route('vendor.dashboard'),
                default => null
            };

            if (!$route) {
                Auth::logout();
                return back()->with('error', 'Invalid user role');
            }

            return redirect()->intended($route)
                ->with('status', 'Welcome back, ' . ucfirst($user->role) . '!');
        }

        return back()->with('error', 'The provided credentials do not match our records.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
