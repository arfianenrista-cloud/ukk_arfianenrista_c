<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) return redirect()->route('dashboard');
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'Username' => 'required|string',
            'Password' => 'required|string',
        ]);

        $user = User::where('Username', $request->Username)->first();

        if (!$user || !Hash::check($request->Password, $user->Password)) {
            ActivityLog::catat('LOGIN GAGAL', "Username: {$request->Username}");
            return back()->withErrors(['Username' => 'Username atau password salah.'])->withInput();
        }

        if ($user->Status === 'nonaktif') {
            return back()->withErrors(['Username' => 'Akun Anda telah dinonaktifkan.']);
        }

        Auth::login($user, $request->boolean('remember'));
        ActivityLog::catat('LOGIN', 'Berhasil masuk ke sistem', $user);

        return redirect()->route('dashboard');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'NamaLengkap' => 'required|string|max:255',
            'Username'    => 'required|string|max:50|unique:users,Username',
            'Email'       => 'required|email|unique:users,Email',
            'NIS'         => 'required|string|max:20',
            'Rayon'       => 'required|string|max:100',
            'Rombel'      => 'required|string|max:100',
            'Password'    => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'NamaLengkap' => $request->NamaLengkap,
            'Username'    => $request->Username,
            'Email'       => $request->Email,
            'Password'    => Hash::make($request->Password),
            'NIS'         => $request->NIS,
            'Rayon'       => $request->Rayon,
            'Rombel'      => $request->Rombel,
            'Barcode'     => 'STD-' . $request->NIS,
            'Role'        => 'siswa',
            'Status'      => 'aktif',
        ]);

        ActivityLog::catat('REGISTRASI', "Siswa baru: {$user->NamaLengkap} ({$user->NIS})", $user);

        Auth::login($user);
        return redirect()->route('dashboard')->with('success', 'Registrasi berhasil! Selamat datang, ' . $user->NamaLengkap);
    }

    public function logout(Request $request)
    {
        ActivityLog::catat('LOGOUT', 'Keluar dari sistem');
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
