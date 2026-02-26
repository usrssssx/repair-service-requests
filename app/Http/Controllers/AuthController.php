<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function showLogin()
    {
        $users = User::orderBy('name')->get();

        return view('auth.login', compact('users'));
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
        ]);

        $request->session()->put('user_id', $data['user_id']);
        $request->session()->regenerate();

        return redirect('/');
    }

    public function logout(Request $request)
    {
        $request->session()->forget('user_id');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
