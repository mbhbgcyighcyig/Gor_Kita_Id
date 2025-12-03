<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LapanganController;
use App\Http\Controllers\PengaturanController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// ==================== PUBLIC ROUTES ====================
// Redirect ke login user
Route::get('/', function () {
    return redirect()->route('user.login');
});

// ==================== USER AUTH ROUTES ====================
Route::get('/login', [AuthController::class, 'tampilkanLoginPengguna'])->name('user.login');
Route::post('/login', [AuthController::class, 'loginPengguna']);

Route::get('/register', [AuthController::class, 'tampilkanRegistrasi'])->name('register');
// ✅ FIX: Tambahkan name untuk route POST register
Route::post('/register', [AuthController::class, 'registrasi'])->name('auth.registrasi');

Route::get('/admin/login', [AuthController::class, 'tampilkanLoginAdmin'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'loginAdmin']);

// ==================== LOGOUT ROUTES ====================
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout.post');

// ==================== USER PROTECTED ROUTES ====================
// Helper function untuk check auth user
$checkUserAuth = function () {
    if (!session()->has('user_id')) {
        return redirect()->route('user.login')->with('error', 'Silakan login terlebih dahulu!');
    }
    
    // Cek role, jika admin tidak boleh akses route user
    if (session('user_role') === 'admin') {
        return redirect('/admin/dashboard')->with('error', 'Anda login sebagai admin!');
    }
    
    return null;
};

// Home routes
Route::get('/home', function () use ($checkUserAuth) {
    if ($redirect = $checkUserAuth()) return $redirect;
    return app(HomeController::class)->index();
})->name('home');

Route::get('/about', function () use ($checkUserAuth) {
    if ($redirect = $checkUserAuth()) return $redirect;
    return app(HomeController::class)->about();
})->name('about');

// ==================== BOOKING ROUTES ====================
Route::prefix('booking')->name('booking.')->group(function () use ($checkUserAuth) {
    // ✅ LANGSUNG KE BADMINTON SAAT AKSES /booking
    Route::get('/', function () use ($checkUserAuth) {
        if ($redirect = $checkUserAuth()) return $redirect;
        // Langsung redirect ke badminton
        return redirect()->route('booking.select-field', ['type' => 'badminton']);
    })->name('index');
    
    // ✅ PILIH LAPANGAN BERDASARKAN JENIS OLAHRAGA
    Route::get('/field/{type}', function ($type) use ($checkUserAuth) {
        if ($redirect = $checkUserAuth()) return $redirect;
        return app(BookingController::class)->selectField($type);
    })->name('select-field');
    
    // ✅ PILIH WAKTU BERDASARKAN LAPANGAN
    Route::get('/time/{fieldId}', function ($fieldId) use ($checkUserAuth) {
        if ($redirect = $checkUserAuth()) return $redirect;
        return app(BookingController::class)->selectTime($fieldId, request());
    })->name('select-time');
    
    Route::get('/confirm/{fieldId}', function ($fieldId) use ($checkUserAuth) {
        if ($redirect = $checkUserAuth()) return $redirect;
        return app(BookingController::class)->confirmBooking($fieldId, request());
    })->name('confirm');
    
    Route::get('/confirm-detail/{fieldId}/{date}/{time}', function ($fieldId, $date, $time) use ($checkUserAuth) {
        if ($redirect = $checkUserAuth()) return $redirect;
        return app(BookingController::class)->showConfirm($fieldId, $date, $time);
    })->name('show-confirm');
    
    Route::post('/process', function (Request $request) use ($checkUserAuth) {
        if ($redirect = $checkUserAuth()) return $redirect;
        return app(BookingController::class)->processBooking($request);
    })->name('process');
    
    // ✅ PAYMENT ROUTES - MENGGUNAKAN BookingController
    Route::get('/{bookingId}/payment', function ($bookingId) use ($checkUserAuth) {
        if ($redirect = $checkUserAuth()) return $redirect;
        return app(BookingController::class)->showPaymentForm($bookingId);
    })->name('payment.form');
    
    Route::post('/{bookingId}/payment/process', function ($bookingId, Request $request) use ($checkUserAuth) {
        if ($redirect = $checkUserAuth()) return $redirect;
        return app(BookingController::class)->processPayment($request, $bookingId);
    })->name('payment.process');
    
    Route::get('/{bookingId}/payment/success', function ($bookingId) use ($checkUserAuth) {
        if ($redirect = $checkUserAuth()) return $redirect;
        return app(BookingController::class)->paymentSuccess($bookingId);
    })->name('payment.success');
    
    Route::get('/success/{bookingId}', function ($bookingId) use ($checkUserAuth) {
        if ($redirect = $checkUserAuth()) return $redirect;
        return app(BookingController::class)->success($bookingId);
    })->name('success');
    
    Route::get('/my-bookings', function () use ($checkUserAuth) {
        if ($redirect = $checkUserAuth()) return $redirect;
        return app(BookingController::class)->myBookings();
    })->name('my-bookings');
    
    Route::post('/{bookingId}/cancel', function ($bookingId, Request $request) use ($checkUserAuth) {
        if ($redirect = $checkUserAuth()) return $redirect;
        return app(BookingController::class)->cancelBooking($bookingId, $request);
    })->name('cancel');
    
    Route::get('/{bookingId}', function ($bookingId) use ($checkUserAuth) {
        if ($redirect = $checkUserAuth()) return $redirect;
        return app(BookingController::class)->show($bookingId);
    })->name('show');
});

// ==================== RATING ROUTES ====================
// ✅ FIX: HAPUS ROUTES RATING YANG DUPLIKAT DAN BUAT YANG BENAR
Route::prefix('rating')->name('rating.')->group(function () use ($checkUserAuth) {
    // ✅ CREATE RATING FORM
    Route::get('/create/{bookingId}', function ($bookingId) use ($checkUserAuth) {
        if ($redirect = $checkUserAuth()) return $redirect;
        return app(RatingController::class)->create($bookingId);
    })->name('create');
    
    // ✅ STORE RATING (Parameter harus Request $request, $bookingId)
    Route::post('/store/{bookingId}', function ($bookingId, Request $request) use ($checkUserAuth) {
        if ($redirect = $checkUserAuth()) return $redirect;
        return app(RatingController::class)->store($request, $bookingId);
    })->name('store');
    
    // ✅ LIST ALL RATINGS
    Route::get('/', function () use ($checkUserAuth) {
        if ($redirect = $checkUserAuth()) return $redirect;
        return app(RatingController::class)->index();
    })->name('index');
    
    // ✅ MY RATINGS
    Route::get('/my-ratings', function () use ($checkUserAuth) {
        if ($redirect = $checkUserAuth()) return $redirect;
        return app(RatingController::class)->myRatings();
    })->name('my-ratings');
    
    // ✅ EDIT RATING
    Route::get('/edit/{id}', function ($id) use ($checkUserAuth) {
        if ($redirect = $checkUserAuth()) return $redirect;
        return app(RatingController::class)->edit($id);
    })->name('edit');
    
    // ✅ UPDATE RATING
    Route::post('/update/{id}', function ($id, Request $request) use ($checkUserAuth) {
        if ($redirect = $checkUserAuth()) return $redirect;
        return app(RatingController::class)->update($request, $id);
    })->name('update');
    
    // ✅ DELETE RATING
    Route::post('/delete/{id}', function ($id, Request $request) use ($checkUserAuth) {
        if ($redirect = $checkUserAuth()) return $redirect;
        return app(RatingController::class)->destroy($id);
    })->name('delete');
});

// ==================== PROFILE ROUTES ====================
Route::prefix('profile')->name('profile.')->group(function () use ($checkUserAuth) {
    Route::get('/', function () use ($checkUserAuth) {
        if ($redirect = $checkUserAuth()) return $redirect;
        return app(UsersController::class)->profile();
    })->name('index');
    
    Route::get('/edit', function () use ($checkUserAuth) {
        if ($redirect = $checkUserAuth()) return $redirect;
        return app(UsersController::class)->editProfile();
    })->name('edit');
    
    Route::post('/update', function (Request $request) use ($checkUserAuth) {
        if ($redirect = $checkUserAuth()) return $redirect;
        return app(UsersController::class)->updateProfile($request);
    })->name('update');
    
    Route::get('/change-password', function () use ($checkUserAuth) {
        if ($redirect = $checkUserAuth()) return $redirect;
        return app(UsersController::class)->changePasswordForm();
    })->name('change.password.form');
    
    Route::post('/change-password', function (Request $request) use ($checkUserAuth) {
        if ($redirect = $checkUserAuth()) return $redirect;
        return app(UsersController::class)->changePassword($request);
    })->name('change.password');
});

// ==================== ADMIN PROTECTED ROUTES ====================
// Helper function untuk check auth admin
$checkAdminAuth = function () {
    if (!session()->has('user_id')) {
        return redirect()->route('admin.login')->with('error', 'Silakan login sebagai admin terlebih dahulu!');
    }
    
    if (session('user_role') !== 'admin') {
        return redirect('/home')->with('error', 'Akses ditolak! Hanya admin yang dapat mengakses.');
    }
    
    return null;
};

Route::prefix('admin')->name('admin.')->group(function () use ($checkAdminAuth) {
    // Admin dashboard
    Route::get('/dashboard', function () use ($checkAdminAuth) {
        if ($redirect = $checkAdminAuth()) return $redirect;
        return app(AdminController::class)->dashboard();
    })->name('dashboard');
    
    // ========== BOOKINGS MANAGEMENT ==========
    Route::get('/bookings', function () use ($checkAdminAuth) {
        if ($redirect = $checkAdminAuth()) return $redirect;
        return app(AdminController::class)->bookings();
    })->name('book');
    
    Route::post('/bookings/{id}/complete', function ($id) use ($checkAdminAuth) {
        if ($redirect = $checkAdminAuth()) return $redirect;
        return app(AdminController::class)->completeBooking($id);
    })->name('bookings.complete');
    
    Route::post('/bookings/{id}/confirm', function ($id) use ($checkAdminAuth) {
        if ($redirect = $checkAdminAuth()) return $redirect;
        return app(AdminController::class)->confirmBookingAdmin($id);
    })->name('bookings.confirm');
    
    Route::post('/bookings/{id}/cancel', function ($id) use ($checkAdminAuth) {
        if ($redirect = $checkAdminAuth()) return $redirect;
        return app(AdminController::class)->cancelBookingAdmin($id);
    })->name('bookings.cancel');
    
    // ========== PAYMENT MANAGEMENT ==========
    Route::get('/pembayaran', function () use ($checkAdminAuth) {
        if ($redirect = $checkAdminAuth()) return $redirect;
        return app(AdminController::class)->pembayaran();
    })->name('pembayaran');
    
    Route::put('/pembayaran/confirm/{id}', function ($id) use ($checkAdminAuth) {
        if ($redirect = $checkAdminAuth()) return $redirect;
        return app(AdminController::class)->confirmPayment($id);
    })->name('confirmPayment');
    
    Route::put('/pembayaran/reject/{id}', function ($id) use ($checkAdminAuth) {
        if ($redirect = $checkAdminAuth()) return $redirect;
        return app(AdminController::class)->rejectPayment($id);
    })->name('rejectPayment');
    
    // 🔥 TAMBAH: Debug route untuk reset payment
    Route::put('/pembayaran/reset/{id}', function ($id) use ($checkAdminAuth) {
        if ($redirect = $checkAdminAuth()) return $redirect;
        try {
            $booking = \App\Models\Booking::findOrFail($id);
            $booking->update([
                'payment_status' => 'pending_verification',
                'status' => 'pending_verification'
            ]);
            
            return redirect()->route('admin.pembayaran')
                ->with('success', '✅ Status pembayaran #' . $id . ' berhasil direset!');
        } catch (\Exception $e) {
            return redirect()->route('admin.pembayaran')
                ->with('error', '❌ Gagal reset: ' . $e->getMessage());
        }
    })->name('resetPayment');
    
    // 🔥 TAMBAH: Debug info route
    Route::get('/pembayaran/debug/{id}', function ($id) use ($checkAdminAuth) {
        if ($redirect = $checkAdminAuth()) return $redirect;
        $booking = \App\Models\Booking::with(['user', 'lapangan'])->findOrFail($id);
        
        return response()->json([
            'id' => $booking->id,
            'status' => $booking->status,
            'payment_status' => $booking->payment_status,
            'payment_expiry' => $booking->payment_expiry,
            'tanggal_booking' => $booking->tanggal_booking,
            'jam_mulai' => $booking->jam_mulai,
            'jam_selesai' => $booking->jam_selesai,
            'is_expired' => $booking->is_expired,
            'can_be_confirmed' => $booking->can_be_confirmed,
            'can_be_rejected' => $booking->can_be_rejected,
            'now' => now(),
            'booking_datetime' => \Carbon\Carbon::parse($booking->tanggal_booking . ' ' . $booking->jam_selesai),
            'is_booking_past' => \Carbon\Carbon::parse($booking->tanggal_booking . ' ' . $booking->jam_selesai) < now()
        ]);
    })->name('debugPayment');
    
    // 🔥 TAMBAH: Auto-expire route (bisa diakses manual)
    Route::get('/pembayaran/auto-expire', function () use ($checkAdminAuth) {
        if ($redirect = $checkAdminAuth()) return $redirect;
        return app(AdminController::class)->autoExpireBookings();
    })->name('autoExpirePayments');
    
    // ========== LAPANGAN MANAGEMENT ==========
    Route::prefix('lapangan')->name('lapangan.')->group(function () use ($checkAdminAuth) {
        Route::get('/', function () use ($checkAdminAuth) {
            if ($redirect = $checkAdminAuth()) return $redirect;
            return app(LapanganController::class)->index();
        })->name('index');
        
        Route::get('/create', function () use ($checkAdminAuth) {
            if ($redirect = $checkAdminAuth()) return $redirect;
            return app(LapanganController::class)->create();
        })->name('create');
        
        Route::post('/', function (Request $request) use ($checkAdminAuth) {
            if ($redirect = $checkAdminAuth()) return $redirect;
            return app(LapanganController::class)->store($request);
        })->name('store');
        
        Route::get('/{id}/edit', function ($id) use ($checkAdminAuth) {
            if ($redirect = $checkAdminAuth()) return $redirect;
            return app(LapanganController::class)->edit($id);
        })->name('edit');
        
        Route::put('/{id}', function ($id, Request $request) use ($checkAdminAuth) {
            if ($redirect = $checkAdminAuth()) return $redirect;
            return app(LapanganController::class)->update($request, $id);
        })->name('update');
        
        Route::delete('/{id}', function ($id, Request $request) use ($checkAdminAuth) {
            if ($redirect = $checkAdminAuth()) return $redirect;
            return app(LapanganController::class)->destroy($id);
        })->name('destroy');
    });
    
    // ========== USER MANAGEMENT ==========
    Route::get('/users', function () use ($checkAdminAuth) {
        if ($redirect = $checkAdminAuth()) return $redirect;
        return app(UsersController::class)->index();
    })->name('users.index');
    
    Route::get('/users/create', function () use ($checkAdminAuth) {
        if ($redirect = $checkAdminAuth()) return $redirect;
        return app(UsersController::class)->create();
    })->name('users.create');
    
    Route::post('/users', function (Request $request) use ($checkAdminAuth) {
        if ($redirect = $checkAdminAuth()) return $redirect;
        return app(UsersController::class)->store($request);
    })->name('users.store');
    
    Route::get('/users/{user}', function ($user) use ($checkAdminAuth) {
        if ($redirect = $checkAdminAuth()) return $redirect;
        return app(UsersController::class)->show($user);
    })->name('users.show');
    
    Route::get('/users/{user}/edit', function ($user) use ($checkAdminAuth) {
        if ($redirect = $checkAdminAuth()) return $redirect;
        return app(UsersController::class)->edit($user);
    })->name('users.edit');
    
    Route::put('/users/{user}', function ($user, Request $request) use ($checkAdminAuth) {
        if ($redirect = $checkAdminAuth()) return $redirect;
        return app(UsersController::class)->update($request, $user);
    })->name('users.update');
    
    Route::delete('/users/{user}', function ($user, Request $request) use ($checkAdminAuth) {
        if ($redirect = $checkAdminAuth()) return $redirect;
        return app(UsersController::class)->destroy($user);
    })->name('users.destroy');
    
    // ========== REPORT MANAGEMENT ==========
    Route::get('/laporan', function () use ($checkAdminAuth) {
        if ($redirect = $checkAdminAuth()) return $redirect;
        return app(AdminController::class)->laporan();
    })->name('laporan');
    
    Route::get('/laporan/harian', function () use ($checkAdminAuth) {
        if ($redirect = $checkAdminAuth()) return $redirect;
        return app(AdminController::class)->laporanHarian();
    })->name('laporan.harian');
    
    Route::get('/laporan/mingguan', function () use ($checkAdminAuth) {
        if ($redirect = $checkAdminAuth()) return $redirect;
        return app(AdminController::class)->laporanMingguan();
    })->name('laporan.mingguan');
    
    Route::get('/laporan/bulanan', function () use ($checkAdminAuth) {
        if ($redirect = $checkAdminAuth()) return $redirect;
        return app(AdminController::class)->laporanBulanan();
    })->name('laporan.bulanan');
    
    Route::get('/laporan/tahunan', function () use ($checkAdminAuth) {
        if ($redirect = $checkAdminAuth()) return $redirect;
        return app(AdminController::class)->laporanTahunan();
    })->name('laporan.tahunan');
    
    // ========== USER MANAGEMENT (Alternative) ==========
    Route::get('/pengguna', function () use ($checkAdminAuth) {
        if ($redirect = $checkAdminAuth()) return $redirect;
        return app(AdminController::class)->users();
    })->name('pengguna');
    
    Route::delete('/pengguna/{user}', function ($user) use ($checkAdminAuth) {
        if ($redirect = $checkAdminAuth()) return $redirect;
        return app(AdminController::class)->deleteUser($user);
    })->name('pengguna.destroy');
    
    Route::post('/users/{id}/toggle-status', function ($id) use ($checkAdminAuth) {
        if ($redirect = $checkAdminAuth()) return $redirect;
        return app(AdminController::class)->toggleUserStatus($id);
    })->name('users.toggle-status');
    
    // ========== SETTINGS MANAGEMENT ==========
    Route::get('/pengaturan', function () use ($checkAdminAuth) {
        if ($redirect = $checkAdminAuth()) return $redirect;
        return app(PengaturanController::class)->pengaturan();
    })->name('pengaturan');
    
    Route::post('/pengaturan', function (Request $request) use ($checkAdminAuth) {
        if ($redirect = $checkAdminAuth()) return $redirect;
        return app(PengaturanController::class)->updateSettings($request);
    })->name('pengaturan.update');
    
    // ========== ADMIN API ==========
    Route::get('/api/dashboard-data', function () use ($checkAdminAuth) {
        if ($redirect = $checkAdminAuth()) return $redirect;
        return app(AdminController::class)->getDashboardData();
    })->name('api.dashboard.data');
    
    // 🔥 TAMBAH: API untuk payment stats
    Route::get('/api/payment-stats', function () use ($checkAdminAuth) {
        if ($redirect = $checkAdminAuth()) return $redirect;
        return app(AdminController::class)->getPaymentStats();
    })->name('api.payment.stats');
});

// ==================== API ROUTES ====================
Route::prefix('api')->name('api.')->group(function () {
    // Check booking availability
    Route::get('/check-availability', function (Request $request) {
        return app(BookingController::class)->checkAvailabilityApi($request);
    })->name('check.availability');
    
    // Get available times
    Route::get('/available-times/{fieldId}/{date}', function ($fieldId, $date) {
        return app(BookingController::class)->getAvailableTimes($fieldId, $date);
    })->name('available.times');
    
    // Calculate price
    Route::get('/calculate-price/{fieldId}/{duration}', function ($fieldId, $duration) {
        return app(BookingController::class)->calculatePrice($fieldId, $duration);
    })->name('calculate.price');
});

// ==================== UTILITY ROUTES ====================
// Clear cache
Route::get('/clear-cache', function() {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('view:clear');
    Artisan::call('route:clear');
    
    return response()->json([
        'status' => 'success',
        'message' => 'Cache cleared successfully!'
    ]);
})->name('clear.cache');

// Clear route cache khusus
Route::get('/clear-route', function() {
    Artisan::call('route:clear');
    return response()->json([
        'status' => 'success',
        'message' => 'Route cache cleared!'
    ]);
})->name('clear.route');

// Health check
Route::get('/health', function() {
    return response()->json([
        'status' => 'OK',
        'timestamp' => now(),
        'php_version' => PHP_VERSION,
        'laravel_version' => app()->version()
    ]);
});

// ==================== DEBUG ROUTES ====================
Route::get('/debug/auth', function() {
    return [
        'is_logged_in' => session()->has('user_id'),
        'user_id' => session('user_id'),
        'user_name' => session('user_name'),
        'user_email' => session('user_email'),
        'user_role' => session('user_role'),
        'is_admin' => session('is_admin'),
        'session' => session()->all()
    ];
})->name('debug.auth');

Route::get('/debug/routes', function() {
    $routes = collect(Route::getRoutes())->map(function ($route) {
        return [
            'method' => implode('|', $route->methods()),
            'uri' => $route->uri(),
            'name' => $route->getName(),
            'action' => $route->getActionName()
        ];
    });
    
    return response()->json($routes);
})->name('debug.routes');

Route::get('/test-route', function() {
    return response()->json([
        'status' => 'OK',
        'auth' => session()->has('user_id') ? 'Logged in' : 'Not logged in',
        'user' => session()->has('user_id') ? [
            'id' => session('user_id'),
            'name' => session('user_name'),
            'role' => session('user_role')
        ] : null,
        'timestamp' => now()->format('Y-m-d H:i:s')
    ]);
})->name('test.route');

// 🔥 TAMBAH: Debug route khusus untuk rating
Route::get('/debug/rating/{bookingId}', function($bookingId) {
    if (!session()->has('user_id')) {
        return "User not logged in";
    }
    
    $booking = \App\Models\Booking::with(['user', 'lapangan'])->find($bookingId);
    
    if (!$booking) {
        return "Booking #{$bookingId} not found";
    }
    
    $userIsOwner = $booking->user_id == session('user_id');
    $existingRating = \App\Models\Rating::where('booking_id', $bookingId)->first();
    
    return [
        'booking_id' => $bookingId,
        'booking_status' => $booking->status,
        'user_id' => $booking->user_id,
        'current_user_id' => session('user_id'),
        'user_is_owner' => $userIsOwner,
        'booking_completed' => $booking->status === 'completed',
        'existing_rating' => $existingRating ? 'Yes (ID: ' . $existingRating->id . ')' : 'No',
        'rating_create_url' => url("/rating/create/{$bookingId}"),
        'rating_store_url' => url("/rating/store/{$bookingId}"),
        'can_rate' => $booking->status === 'completed' && $userIsOwner && !$existingRating
    ];
})->name('debug.rating');

// 🔥 TAMBAH: Test rating langsung
Route::get('/test-rating/{bookingId}', function($bookingId) {
    if (!session()->has('user_id')) {
        return redirect()->route('user.login');
    }
    
    return redirect()->route('rating.create', ['bookingId' => $bookingId]);
})->name('test.rating');

// ==================== FALLBACK ROUTE ====================
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
})->name('fallback');