<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $user = User::with('role')->where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return back()->withErrors([
                'email' => 'Mikir kids! salah itu kids.',
            ])->withInput();
        }

        Auth::login($user);

        $roleId = $user->role->first()->idrole ?? null;

        switch ($roleId) {
            case 1:
                return redirect()->route('admin.dashboard');
            case 4:
                return redirect()->route('resepsionis.dashboard');
            case 2:
                return redirect()->route('dokter.dashboard');
            case 3:
                return redirect()->route('perawat.dashboard');
            case 5:
                return redirect()->route('pemilik.dashboard');
            default:
                Auth::logout();
                return back()->withErrors(['email' => 'Siapa kamu kids?'])->withInput();
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
