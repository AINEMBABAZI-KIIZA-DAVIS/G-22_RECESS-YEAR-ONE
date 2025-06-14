<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ConfirmPasswordController extends Controller
{
    public function showConfirmForm()
    {
        return view('auth.passwords.confirm');
    }

    public function confirm(Request $request)
    {
        $request->validate([
            'password' => 'required|current_password',
        ]);

        $request->session()->put('auth.password_confirmed_at', time());

        return redirect()->intended();
    }
}
