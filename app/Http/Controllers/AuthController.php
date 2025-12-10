<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // ==================== USER LOGIN ====================
    
    // TAMPILKAN HALAMAN LOGIN PENGGUNA
    public function tampilkanLoginPengguna()
    {
        // Jika sudah login, redirect ke home
        if (session()->has('user_id')) {
            return redirect('/home');
        }
        
        if (view()->exists('auth.user-login')) {
            return view('auth.user-login');
        } else {
            return view('auth.admin-login', ['is_user_login' => true]);
        }
    }
    
    // PROSES LOGIN PENGGUNA
    public function loginPengguna(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6'
        ], [
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password minimal 6 karakter'
        ]);
        
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        
        // Cari user berdasarkan email
        $user = User::where('email', $request->email)->first();
        
        // Cek password
        if ($user && Hash::check($request->password, $user->password)) {
            // Set session MANUAL (karena gak pake Laravel Auth)
            session([
                'user_id' => $user->id,
                'user_name' => $user->name,
                'user_email' => $user->email,
                'user_role' => $user->role,
                'is_admin' => $user->role === 'admin'
            ]);
            
            // Redirect berdasarkan role
            if ($user->role === 'admin') {
                return redirect('/admin/dashboard')->with('success', 'Selamat datang Admin!');
            }
            
            return redirect('/home')->with('success', 'Login berhasil!');
        }
        
        return back()->with('error', 'Email atau password salah!')->withInput();
    }
    
    // ==================== ADMIN LOGIN ====================
    
    // TAMPILKAN HALAMAN LOGIN ADMIN
    public function tampilkanLoginAdmin()
    {
        // Jika sudah login sebagai admin, redirect ke dashboard
        if (session()->has('user_id') && session('user_role') === 'admin') {
            return redirect('/admin/dashboard');
        }
        
        if (view()->exists('auth.admin-login')) {
            return view('auth.admin-login', ['is_user_login' => false]);
        } else {
            return view('auth.simple-admin-login');
        }
    }
    
    // PROSES LOGIN ADMIN
    public function loginAdmin(Request $request)
    {
        // Validasi input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ], [
            'email.required' => 'Email admin harus diisi',
            'email.email' => 'Format email tidak valid',
            'password.required' => 'Password admin harus diisi'
        ]);
        
        // Cari user
        $user = User::where('email', $request->email)->first();
        
        // Cek jika user ada, password benar, dan role admin
        if ($user && Hash::check($request->password, $user->password)) {
            if ($user->role !== 'admin') {
                return back()->with('error', 'Akses ditolak! Hanya admin yang boleh login.')->withInput();
            }
            
            // Set session
            session([
                'user_id' => $user->id,
                'user_name' => $user->name,
                'user_email' => $user->email,
                'user_role' => $user->role,
                'is_admin' => true
            ]);
            
            return redirect('/admin/dashboard')->with('success', 'Selamat datang Admin!');
        }
        
        return back()->with('error', 'Kredensial admin tidak valid!')->withInput();
    }
    
    // ==================== LOGOUT ====================
    
    // LOGOUT DARI SISTEM - **FIXED VERSION**
    public function logout(Request $request)
    {
        try {
            // Clear semua session data
            session()->flush();
            
            // Regenerate session ID untuk keamanan
            $request->session()->regenerate();
            
            return redirect('/')->with('info', 'Anda telah logout dari sistem.');
            
        } catch (\Exception $e) {
            // Jika ada error, tetap clear session
            session()->flush();
            return redirect('/')->with('info', 'Anda telah logout.');
        }
    }
    
    // ==================== REGISTRASI ====================
    
    // TAMPILKAN HALAMAN REGISTRASI
    public function tampilkanRegistrasi()
    {
        // Jika sudah login, redirect ke home
        if (session()->has('user_id')) {
            return redirect('/home');
        }
        
       
        return view('auth.register');
    }
    
    // PROSES REGISTRASI PENGGUNA BARU
    public function registrasi(Request $request)
    {
       
        $validasi = $request->validate([
            'name' => 'required|string|max:255', 
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'required'
        ], [
            'name.required' => 'Nama lengkap harus diisi',
            'name.max' => 'Nama maksimal 255 karakter',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password minimal 6 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'password_confirmation.required' => 'Konfirmasi password harus diisi'
        ]);
        
        // Buat user baru
        $pengguna = User::create([
            'name' => $validasi['name'],
            'email' => $validasi['email'],
            'password' => Hash::make($validasi['password']),
            'role' => 'user',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        // Auto login setelah registrasi 
        session([
            'user_id' => $pengguna->id,
            'user_name' => $pengguna->name,
            'user_email' => $pengguna->email,
            'user_role' => 'user',
            'is_admin' => false
        ]);
        
        return redirect('/home')->with('success', 'Registrasi berhasil! Selamat datang ' . $pengguna->name);
    }
    
    // ==================== LUPA PASSWORD ====================
    
    // TAMPILKAN HALAMAN LUPA PASSWORD
    public function tampilkanLupaPassword()
    {
        return view('auth.lupa-password');
    }
    
    // PROSES LUPA PASSWORD
    public function kirimLinkReset(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ], [
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.exists' => 'Email tidak terdaftar di sistem'
        ]);
        
        return back()->with('status', 'Link reset password telah dikirim ke email Anda!');
    }
    
    // ==================== PROFIL USER ====================
    
    // TAMPILKAN HALAMAN PROFIL PENGGUNA
    public function tampilkanProfil()
    {
        // Cek login via session
        if (!session()->has('user_id')) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu');
        }
        
        $user_id = session('user_id');
        $pengguna = User::find($user_id);
        
        if (!$pengguna) {
            session()->flush();
            return redirect('/login')->with('error', 'Sesi telah berakhir');
        }
        
        return view('auth.profil', compact('pengguna'));
    }
    
    // UPDATE PROFIL PENGGUNA
    public function updateProfil(Request $request)
    {
        if (!session()->has('user_id')) {
            return redirect('/login');
        }
        
        $user_id = session('user_id');
        $pengguna = User::findOrFail($user_id);
        
        $validasi = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $pengguna->id
        ], [
            'nama.required' => 'Nama harus diisi',
            'email.required' => 'Email harus diisi',
            'email.unique' => 'Email sudah digunakan oleh pengguna lain'
        ]);
        
        $pengguna->update([
            'name' => $validasi['nama'],
            'email' => $validasi['email']
        ]);
        
        // Update session name
        session(['user_name' => $validasi['nama']]);
        
        return back()->with('success', 'Profil berhasil diperbarui!');
    }
    
    // ==================== GANTI PASSWORD ====================
    
    // GANTI PASSWORD
    public function gantiPassword(Request $request)
    {
        if (!session()->has('user_id')) {
            return redirect('/login');
        }
        
        $user_id = session('user_id');
        $pengguna = User::findOrFail($user_id);
        
        $validasi = $request->validate([
            'password_lama' => 'required',
            'password_baru' => 'required|min:6|confirmed',
            'password_baru_confirmation' => 'required'
        ], [
            'password_lama.required' => 'Password lama harus diisi',
            'password_baru.required' => 'Password baru harus diisi',
            'password_baru.min' => 'Password baru minimal 6 karakter',
            'password_baru.confirmed' => 'Konfirmasi password baru tidak cocok'
        ]);
        
        // Cek password lama
        if (!Hash::check($validasi['password_lama'], $pengguna->password)) {
            return back()->with('error', 'Password lama tidak sesuai!');
        }
        
        // Update password baru
        $pengguna->update([
            'password' => Hash::make($validasi['password_baru'])
        ]);
        
        return back()->with('success', 'Password berhasil diubah!');
    }
    
    // ==================== UTILITIES ====================
    
    // CEK STATUS LOGIN
    public function cekStatus()
    {
        if (session()->has('user_id')) {
            $user = User::find(session('user_id'));
            
            return response()->json([
                'login' => true,
                'user' => [
                    'nama' => session('user_name'),
                    'email' => session('user_email'),
                    'role' => session('user_role')
                ],
                'user_db' => $user ? $user->only(['id', 'name', 'email', 'role']) : null
            ]);
        }
        
        return response()->json(['login' => false]);
    }
    
    // HALAMAN UBAH ROLE (HANYA ADMIN)
    public function ubahRole(Request $request, $id)
    {
        if (!session()->has('is_admin') || !session('is_admin')) {
            return redirect()->back()->with('error', 'Akses ditolak!');
        }
        
        $pengguna = User::findOrFail($id);
        
        $request->validate([
            'role' => 'required|in:user,admin,moderator'
        ]);
        
        $pengguna->update([
            'role' => $request->role
        ]);
        
        return back()->with('success', 'Role pengguna berhasil diubah!');
    }
    
    // ==================== DEBUG ====================
    
    // DEBUG VIEW
    public function debugViews()
    {
        $views = [
            'user-login' => view()->exists('auth.user-login'),
            'admin-login' => view()->exists('auth.admin-login'),
            'register' => view()->exists('auth.register'), 
            'lupa-password' => view()->exists('auth.lupa-password'),
            'profil' => view()->exists('auth.profil'),
        ];
        
        return response()->json([
            'views' => $views,
            'session_data' => [
                'user_id' => session('user_id'),
                'user_name' => session('user_name'),
                'user_role' => session('user_role'),
                'is_admin' => session('is_admin')
            ],
            'session_all' => session()->all()
        ]);
    }
    
    // ==================== MANUAL AUTH HELPERS ====================
    
    // Helper untuk cek login
    public static function isLoggedIn()
    {
        return session()->has('user_id');
    }
    
    public static function currentUser()
    {
        if (session()->has('user_id')) {
            return User::find(session('user_id'));
        }
        return null;
    }
    
    // Helper untuk cek admin
    public static function isAdmin()
    {
        return session('is_admin', false);
    }
}