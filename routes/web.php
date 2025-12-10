<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LapanganController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// ==================== PUBLIC ROUTES ====================
Route::get('/', function () {
    return redirect()->route('user.login');
});

// ==================== AUTH ROUTES ====================
Route::get('/login', [AuthController::class, 'tampilkanLoginPengguna'])->name('user.login');
Route::post('/login', [AuthController::class, 'loginPengguna']);

Route::get('/register', [AuthController::class, 'tampilkanRegistrasi'])->name('register');
Route::post('/register', [AuthController::class, 'registrasi'])->name('auth.registrasi');

Route::get('/admin/login', [AuthController::class, 'tampilkanLoginAdmin'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'loginAdmin']);

Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout.post');

// ==================== USER ROUTES ====================
$checkUserAuth = function () {
    if (!session()->has('user_id')) {
        return redirect()->route('user.login')->with('error', 'Login dulu!');
    }
    
    if (session('user_role') === 'admin') {
        return redirect('/admin/dashboard')->with('error', 'Lu admin!');
    }
    
    return null;
};

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
    Route::get('/', function () use ($checkUserAuth) {
        if ($redirect = $checkUserAuth()) return $redirect;
        return redirect()->route('booking.select-field', ['type' => 'badminton']);
    })->name('index');
    
    Route::get('/field/{type}', function ($type) use ($checkUserAuth) {
        if ($redirect = $checkUserAuth()) return $redirect;
        return app(BookingController::class)->selectField($type);
    })->name('select-field');
    
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
Route::prefix('rating')->name('rating.')->group(function () use ($checkUserAuth) {
    Route::get('/create/{bookingId}', function ($bookingId) use ($checkUserAuth) {
        if ($redirect = $checkUserAuth()) return $redirect;
        return app(RatingController::class)->create($bookingId);
    })->name('create');
    
    Route::post('/store/{bookingId}', function ($bookingId, Request $request) use ($checkUserAuth) {
        if ($redirect = $checkUserAuth()) return $redirect;
        return app(RatingController::class)->store($request, $bookingId);
    })->name('store');
    
    Route::get('/', function () use ($checkUserAuth) {
        if ($redirect = $checkUserAuth()) return $redirect;
        return app(RatingController::class)->index();
    })->name('index');
    
    Route::get('/my-ratings', function () use ($checkUserAuth) {
        if ($redirect = $checkUserAuth()) return $redirect;
        return app(RatingController::class)->myRatings();
    })->name('my-ratings');
    
    Route::get('/edit/{id}', function ($id) use ($checkUserAuth) {
        if ($redirect = $checkUserAuth()) return $redirect;
        return app(RatingController::class)->edit($id);
    })->name('edit');
    
    Route::post('/update/{id}', function ($id, Request $request) use ($checkUserAuth) {
        if ($redirect = $checkUserAuth()) return $redirect;
        return app(RatingController::class)->update($request, $id);
    })->name('update');
    
    Route::post('/delete/{id}', function ($id, Request $request) use ($checkUserAuth) {
        if ($redirect = $checkUserAuth()) return $redirect;
        return app(RatingController::class)->destroy($id);
    })->name('delete');
});

// ==================== ADMIN ROUTES ====================
$checkAdminAuth = function () {
    if (!session()->has('user_id')) {
        return redirect()->route('admin.login')->with('error', 'Login admin dulu!');
    }
    
    if (session('user_role') !== 'admin') {
        return redirect('/home')->with('error', 'Lu bukan admin!');
    }
    
    return null;
};

Route::prefix('admin')->name('admin.')->group(function () use ($checkAdminAuth) {
    Route::get('/dashboard', function () use ($checkAdminAuth) {
        if ($redirect = $checkAdminAuth()) return $redirect;
        return app(AdminController::class)->dashboard();
    })->name('dashboard');
    
    // ========== BOOKING ==========
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
    
    // ========== BAYAR ==========
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
    
    Route::put('/pembayaran/reset/{id}', function ($id) use ($checkAdminAuth) {
        if ($redirect = $checkAdminAuth()) return $redirect;
        try {
            $booking = \App\Models\Booking::findOrFail($id);
            $booking->update([
                'payment_status' => 'pending_verification',
                'status' => 'pending_verification'
            ]);
            
            return redirect()->route('admin.pembayaran')
                ->with('success', 'Status bayar #' . $id . ' direset!');
        } catch (\Exception $e) {
            return redirect()->route('admin.pembayaran')
                ->with('error', 'Gagal reset: ' . $e->getMessage());
        }
    })->name('resetPayment');
    
    // ========== LAPANGAN ==========
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
    
    // ========== USER ==========
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
    
    // ========== LAPORAN ==========
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
    
    // ========== PENGATURAN ==========
    Route::get('/pengaturan', function () use ($checkAdminAuth) {
        if ($redirect = $checkAdminAuth()) return $redirect;
        return app(AdminController::class)->pengaturan();
    })->name('pengaturan');
    
    Route::post('/pengaturan', function (Request $request) use ($checkAdminAuth) {
        if ($redirect = $checkAdminAuth()) return $redirect;
        return app(AdminController::class)->updateSettings($request);
    })->name('pengaturan.update');
    
    // ========== API ==========
    Route::get('/api/dashboard-data', function () use ($checkAdminAuth) {
        if ($redirect = $checkAdminAuth()) return $redirect;
        return app(AdminController::class)->getDashboardData();
    })->name('api.dashboard.data');
});

// ==================== API ROUTES ====================
Route::prefix('api')->name('api.')->group(function () {
    Route::get('/check-availability', function (Request $request) {
        return app(BookingController::class)->checkAvailabilityApi($request);
    })->name('check.availability');
    
    Route::get('/available-times/{fieldId}/{date}', function ($fieldId, $date) {
        return app(BookingController::class)->getAvailableTimes($fieldId, $date);
    })->name('available.times');
    
    Route::get('/calculate-price/{fieldId}/{duration}', function ($fieldId, $duration) {
        return app(BookingController::class)->calculatePrice($fieldId, $duration);
    })->name('calculate.price');
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

// ==================== FALLBACK ROUTE ====================
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
})->name('fallback');