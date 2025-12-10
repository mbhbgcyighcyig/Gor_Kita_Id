<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    // ==================== USER AUTH ====================
    
    public function showUserLogin()
    {
        // Jika sudah login, redirect sesuai role
        if (Auth::check()) {
            return $this->redirectByRole();
        }
        return view('auth.user-login');
    }

    public function userLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            return $this->redirectByRole();
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return redirect('/home');
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'user',
        ]);

        Auth::login($user);
        return redirect('/home')->with('success', 'Registrasi berhasil!');
    }

    public function userLogout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    // ==================== ADMIN AUTH ====================
    
    public function showAdminLogin()
    {
        // Jika sudah login sebagai admin, langsung ke dashboard
        if (Auth::check() && Auth::user()->role === 'admin') {
            return redirect('/admin/dashboard');
        }
        if (Auth::check()) {
            return view('auth.admin-login')->with('warning', 'Anda sudah login sebagai user. Silakan logout terlebih dahulu untuk login sebagai admin.');
        }
        return view('auth.admin-login');
    }

    public function adminLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        Log::info('ADMIN LOGIN ATTEMPT:', $credentials);

        // Cek dulu apakah user ada dan role admin
        $user = User::where('email', $credentials['email'])->first();
        
        if (!$user) {
            Log::info('USER NOT FOUND');
            return back()->withErrors(['email' => 'Email tidak ditemukan.']);
        }

        Log::info('USER FOUND:', [
            'id' => $user->id,
            'email' => $user->email, 
            'role' => $user->role,
            'password_match' => Hash::check($credentials['password'], $user->password)
        ]);

        // Cek role sebelum attempt login
        if ($user->role !== 'admin') {
            Log::info('ROLE CHECK FAILED - User is not admin');
            return back()->withErrors(['email' => 'Akses ditolak! Hanya admin.']);
        }

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            Log::info('ADMIN LOGIN SUCCESS');
            return redirect('/admin/dashboard');
        }

        Log::info('LOGIN FAILED - Password mismatch');
        return back()->withErrors(['email' => 'Login gagal! Periksa email dan password.']);
    }

    public function adminLogout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/admin/login');
    }

    // ==================== HELPER METHOD ====================
    
    private function redirectByRole()
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect('/login');
        }

        return $user->role === 'admin' 
            ? redirect('/admin/dashboard')
            : redirect('/home');
    }
}