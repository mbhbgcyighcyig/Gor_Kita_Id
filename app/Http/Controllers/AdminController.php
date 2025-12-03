<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\User;
use App\Models\Lapangan;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    /**
     * Halaman dashboard admin
     */
    public function dashboard()
    {
        // Stats calculations
        $totalBookings = Booking::count();
        $todayBookings = Booking::whereDate('tanggal_booking', today())->count();
        $totalUsers = User::where('role', 'user')->count();
        $totalFields = Lapangan::count();
        
        // Revenue calculations with null checking
        $confirmedBookings = Booking::where('status', 'confirmed')
            ->with('lapangan')
            ->get();
        
        $totalRevenue = 0;
        foreach ($confirmedBookings as $booking) {
            if ($booking->total_price && $booking->total_price > 0) {
                $totalRevenue += $booking->total_price;
            } else {
                try {
                    $start = Carbon::parse($booking->jam_mulai);
                    $end = Carbon::parse($booking->jam_selesai);
                    $duration = $start->diffInHours($end);
                    
                    if ($booking->lapangan && $booking->lapangan->price_per_hour) {
                        $totalRevenue += $duration * $booking->lapangan->price_per_hour;
                    } else {
                        $defaultPrice = Lapangan::first()->price_per_hour ?? 40000;
                        $totalRevenue += $duration * $defaultPrice;
                    }
                } catch (\Exception $e) {
                    if ($booking->lapangan && $booking->lapangan->price_per_hour) {
                        $totalRevenue += $booking->lapangan->price_per_hour * 1;
                    } else {
                        $defaultPrice = Lapangan::first()->price_per_hour ?? 40000;
                        $totalRevenue += $defaultPrice * 1;
                    }
                }
            }
        }

        // Weekly bookings data for chart
        $weeklyData = [];
        $weeklyLabels = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $dayName = $date->translatedFormat('D');
            $formattedDate = $date->format('Y-m-d');
            
            $bookingCount = Booking::whereDate('tanggal_booking', $formattedDate)->count();
            
            $weeklyLabels[] = $dayName;
            $weeklyData[] = $bookingCount;
        }

        // Recent bookings
        $recentBookings = Booking::with(['user', 'lapangan'])
            ->latest()
            ->limit(5)
            ->get();

        // Today's bookings
        $todayBookingsList = Booking::with(['user', 'lapangan'])
            ->whereDate('tanggal_booking', today())
            ->orderBy('jam_mulai')
            ->get();

        return view('admin.dashboard', compact(
            'totalBookings',
            'todayBookings',
            'totalUsers',
            'totalFields',
            'totalRevenue',
            'weeklyData',
            'weeklyLabels',
            'recentBookings',
            'todayBookingsList'
        ));
    }

    /**
     * Halaman manajemen pembayaran
     */
    public function pembayaran()
    {
        $payments = Booking::with(['user', 'lapangan'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('admin.pembayaran', compact('payments'));
    }

    /**
     * Konfirmasi pembayaran
     */
    public function confirmPayment($id)
    {
        try {
            $booking = Booking::findOrFail($id);
            
            // ========== VALIDASI SEBELUM KONFIRMASI ==========
            
            // 1. Cek status booking sudah cancelled/expired/completed
            if (in_array($booking->status, ['cancelled', 'expired', 'completed'])) {
                return redirect()->route('admin.pembayaran')
                    ->with('error', 'Booking sudah ' . $booking->status . '! Tidak bisa dikonfirmasi.');
            }
            
            // 2. Cek payment_status harus pending_verification
            if (!in_array($booking->payment_status, ['pending_verification', 'pending'])) {
                return redirect()->route('admin.pembayaran')
                    ->with('error', 'Status pembayaran tidak valid untuk konfirmasi! Status: ' . $booking->payment_status);
            }
            
            // 3. Cek apakah tanggal booking sudah lewat
            try {
                $bookingDateTime = Carbon::parse($booking->tanggal_booking . ' ' . $booking->jam_selesai);
                if ($bookingDateTime < now()) {
                    // Auto reject jika sudah lewat
                    $booking->update([
                        'payment_status' => 'failed',
                        'status' => 'expired'
                    ]);
                    
                    return redirect()->route('admin.pembayaran')
                        ->with('warning', 'Booking sudah expired! Status diubah ke expired.');
                }
            } catch (\Exception $e) {
                Log::error('Error parsing booking datetime: ' . $e->getMessage());
            }
            
            // 4. Cek apakah payment sudah expired (payment_expiry)
            if ($booking->payment_expiry && $booking->payment_expiry < now()) {
                $booking->update([
                    'payment_status' => 'failed',
                    'status' => 'expired'
                ]);
                
                return redirect()->route('admin.pembayaran')
                    ->with('warning', 'Pembayaran sudah expired! Status diubah ke expired.');
            }
            
            // 5. Cek konflik jadwal (double booking)
            $conflictingBooking = Booking::where('lapangan_id', $booking->lapangan_id)
                ->where('tanggal_booking', $booking->tanggal_booking)
                ->where('status', 'confirmed')
                ->where('id', '!=', $booking->id)
                ->where(function($query) use ($booking) {
                    $query->where(function($q) use ($booking) {
                        $q->where('jam_mulai', '>=', $booking->jam_mulai)
                          ->where('jam_mulai', '<', $booking->jam_selesai);
                    })->orWhere(function($q) use ($booking) {
                        $q->where('jam_selesai', '>', $booking->jam_mulai)
                          ->where('jam_selesai', '<=', $booking->jam_selesai);
                    })->orWhere(function($q) use ($booking) {
                        $q->where('jam_mulai', '<=', $booking->jam_mulai)
                          ->where('jam_selesai', '>=', $booking->jam_selesai);
                    });
                })
                ->exists();
            
            if ($conflictingBooking) {
                return redirect()->route('admin.pembayaran')
                    ->with('error', 'Lapangan sudah dibooking di waktu tersebut!');
            }
            
            // 6. Cek apakah PIN sudah diverifikasi (jika menggunakan PIN)
            if ($booking->payment_pin && !$booking->pin_verified) {
                return redirect()->route('admin.pembayaran')
                    ->with('error', 'PIN pembayaran belum diverifikasi!');
            }
            
            // ========== SEMUA VALIDASI BERHASIL, KONFIRMASI ==========
            $booking->update([
                'payment_status' => 'paid',
                'status' => 'confirmed',
                'paid_at' => now()
            ]);

            return redirect()->route('admin.pembayaran')
                ->with('success', '✅ Pembayaran berhasil dikonfirmasi! Booking sekarang aktif.');
                
        } catch (\Exception $e) {
            Log::error('Confirm Payment Error: ' . $e->getMessage(), [
                'booking_id' => $id,
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('admin.pembayaran')
                ->with('error', '❌ Gagal mengkonfirmasi pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Tolak pembayaran
     */
    public function rejectPayment($id)
    {
        try {
            $booking = Booking::findOrFail($id);
            
            // Validasi sebelum reject
            $validStatuses = ['pending_verification', 'pending'];
            if (!in_array($booking->payment_status, $validStatuses)) {
                $statusText = $booking->payment_status;
                if ($booking->payment_status === 'paid') {
                    $statusText = 'sudah dibayar';
                } elseif ($booking->payment_status === 'failed') {
                    $statusText = 'sudah ditolak';
                } elseif ($booking->payment_status === 'expired') {
                    $statusText = 'sudah expired';
                }
                
                return redirect()->route('admin.pembayaran')
                    ->with('error', 'Pembayaran ' . $statusText . '! Tidak bisa ditolak.');
            }
            
            $booking->update([
                'payment_status' => 'failed',
                'status' => 'cancelled'
            ]);

            return redirect()->route('admin.pembayaran')
                ->with('success', '❌ Pembayaran berhasil ditolak!');
                
        } catch (\Exception $e) {
            Log::error('Reject Payment Error: ' . $e->getMessage());
            return redirect()->route('admin.pembayaran')
                ->with('error', '❌ Gagal menolak pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * AUTO-EXPIRED: Method untuk handle expired bookings (bisa dijalankan via cron job)
     */
    public function autoExpireBookings()
    {
        try {
            $expiredCount = 0;
            $now = now();
            
            // Cari booking yang payment_expiry sudah lewat
            $expiredBookings = Booking::where(function($query) use ($now) {
                // Cek berdasarkan payment_expiry
                $query->where(function($q) use ($now) {
                    $q->whereNotNull('payment_expiry')
                      ->where('payment_expiry', '<', $now);
                })
                // Atau cek berdasarkan tanggal booking sudah lewat
                ->orWhere(function($q) use ($now) {
                    $q->whereDate('tanggal_booking', '<', $now->toDateString());
                });
            })
            ->whereIn('payment_status', ['pending_verification', 'pending'])
            ->whereIn('status', ['pending_verification', 'pending'])
            ->get();
            
            foreach ($expiredBookings as $booking) {
                $booking->update([
                    'payment_status' => 'failed',
                    'status' => 'expired'
                ]);
                $expiredCount++;
            }
            
            Log::info("Auto-expired {$expiredCount} bookings");
            return response()->json(['expired_count' => $expiredCount]);
            
        } catch (\Exception $e) {
            Log::error('Auto-expire error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Halaman bookings admin
     */
    public function bookings()
    {
        $bookings = Booking::with(['user', 'lapangan'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('admin.book', compact('bookings'));
    }

    /**
     * Complete booking
     */
    public function completeBooking($id)
    {
        try {
            $booking = Booking::findOrFail($id);
            
            // Validasi: hanya booking confirmed yang bisa di-complete
            if ($booking->status !== 'confirmed') {
                return redirect()->route('admin.book')
                    ->with('error', 'Hanya booking yang sudah confirmed bisa diselesaikan!');
            }
            
            $booking->update(['status' => 'completed']);

            return redirect()->route('admin.book')
                ->with('success', '✅ Booking berhasil diselesaikan!');
                
        } catch (\Exception $e) {
            return redirect()->route('admin.book')
                ->with('error', '❌ Gagal menyelesaikan booking: ' . $e->getMessage());
        }
    }

    /**
     * Confirm booking (admin)
     */
    public function confirmBookingAdmin($id)
    {
        try {
            $booking = Booking::findOrFail($id);
            
            // Validasi: hanya booking pending yang bisa dikonfirmasi
            if (!in_array($booking->status, ['pending', 'pending_verification'])) {
                return redirect()->route('admin.book')
                    ->with('error', 'Hanya booking pending yang bisa dikonfirmasi!');
            }
            
            $booking->update(['status' => 'confirmed']);

            return redirect()->route('admin.book')
                ->with('success', '✅ Booking berhasil dikonfirmasi!');
                
        } catch (\Exception $e) {
            return redirect()->route('admin.book')
                ->with('error', '❌ Gagal mengkonfirmasi booking: ' . $e->getMessage());
        }
    }

    /**
     * Cancel booking (admin)
     */
    public function cancelBookingAdmin($id)
    {
        try {
            $booking = Booking::findOrFail($id);
            
            // Validasi: jangan cancel yang sudah completed/cancelled/expired
            if (in_array($booking->status, ['completed', 'cancelled', 'expired'])) {
                return redirect()->route('admin.book')
                    ->with('error', 'Booking sudah ' . $booking->status . '!');
            }
            
            $booking->update(['status' => 'cancelled']);

            return redirect()->route('admin.book')
                ->with('success', '❌ Booking berhasil dibatalkan!');
                
        } catch (\Exception $e) {
            return redirect()->route('admin.book')
                ->with('error', '❌ Gagal membatalkan booking: ' . $e->getMessage());
        }
    }

    /**
     * Halaman laporan
     */
    public function laporan()
    {
        $totalBookings = Booking::count();
        $confirmedBookings = Booking::where('status', 'confirmed')->count();
        $pendingBookings = Booking::where('status', 'pending')->orWhere('status', 'pending_verification')->count();
        
        // Hitung total pendapatan
        $allBookings = Booking::where('status', 'confirmed')
            ->with('lapangan')
            ->get();
        
        $totalRevenue = 0;
        foreach ($allBookings as $booking) {
            if ($booking->total_price && $booking->total_price > 0) {
                $totalRevenue += $booking->total_price;
            } else {
                try {
                    $start = Carbon::parse($booking->jam_mulai);
                    $end = Carbon::parse($booking->jam_selesai);
                    $duration = $start->diffInHours($end);
                    
                    if ($booking->lapangan && $booking->lapangan->price_per_hour) {
                        $totalRevenue += $duration * $booking->lapangan->price_per_hour;
                    } else {
                        $defaultPrice = Lapangan::first()->price_per_hour ?? 40000;
                        $totalRevenue += $duration * $defaultPrice;
                    }
                } catch (\Exception $e) {
                    if ($booking->lapangan && $booking->lapangan->price_per_hour) {
                        $totalRevenue += $booking->lapangan->price_per_hour * 1;
                    } else {
                        $defaultPrice = Lapangan::first()->price_per_hour ?? 40000;
                        $totalRevenue += $defaultPrice * 1;
                    }
                }
            }
        }
        
        $avgRevenue = $totalBookings > 0 ? $totalRevenue / $totalBookings : 0;
        
        // Data chart statistik booking
        $bookingStats = [
            'confirmed' => $confirmedBookings,
            'pending' => $pendingBookings,
            'completed' => Booking::where('status', 'completed')->count(),
            'cancelled' => Booking::where('status', 'cancelled')->count(),
            'expired' => Booking::where('status', 'expired')->count(),
        ];
        
        // Lapangan terpopuler
        $topFields = Lapangan::withCount(['bookings' => function($query) {
            $query->where('status', 'confirmed');
        }])->orderBy('bookings_count', 'desc')->limit(5)->get();
        
        // Transaksi terbaru
        $recentBookings = Booking::with(['user', 'lapangan'])
            ->where('status', 'confirmed')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Data chart pendapatan bulanan
        $monthlyRevenueData = [];
        $monthlyLabels = [];
        
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthName = $date->translatedFormat('M');
            $year = $date->format('Y');
            
            $monthBookings = Booking::where('status', 'confirmed')
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $date->month)
                ->with('lapangan')
                ->get();
            
            $monthRevenue = 0;
            foreach ($monthBookings as $booking) {
                if ($booking->total_price && $booking->total_price > 0) {
                    $monthRevenue += $booking->total_price;
                } else {
                    try {
                        $start = Carbon::parse($booking->jam_mulai);
                        $end = Carbon::parse($booking->jam_selesai);
                        $duration = $start->diffInHours($end);
                        
                        if ($booking->lapangan && $booking->lapangan->price_per_hour) {
                            $monthRevenue += $duration * $booking->lapangan->price_per_hour;
                        } else {
                            $defaultPrice = Lapangan::first()->price_per_hour ?? 40000;
                            $monthRevenue += $duration * $defaultPrice;
                        }
                    } catch (\Exception $e) {
                        if ($booking->lapangan && $booking->lapangan->price_per_hour) {
                            $monthRevenue += $booking->lapangan->price_per_hour * 1;
                        } else {
                            $defaultPrice = Lapangan::first()->price_per_hour ?? 40000;
                            $monthRevenue += $defaultPrice * 1;
                        }
                    }
                }
            }
            
            $monthlyLabels[] = $monthName . ' ' . $year;
            $monthlyRevenueData[] = $monthRevenue;
        }
        
        return view('admin.laporan', compact(
            'totalBookings',
            'confirmedBookings',
            'pendingBookings',
            'totalRevenue',
            'avgRevenue',
            'bookingStats',
            'topFields',
            'recentBookings',
            'monthlyRevenueData',
            'monthlyLabels'
        ));
    }

    /**
     * Halaman pengguna
     */
    public function users()
    {
        $users = User::where('role', 'user')
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('admin.users', compact('users'));
    }

    /**
     * Delete user
     */
    public function deleteUser(User $user)
    {
        try {
            // Hapus booking user terlebih dahulu
            Booking::where('user_id', $user->id)->delete();
            $user->delete();

            return redirect()->route('admin.users')
                ->with('success', '✅ User berhasil dihapus!');
                
        } catch (\Exception $e) {
            return redirect()->route('admin.users')
                ->with('error', '❌ Gagal menghapus user: ' . $e->getMessage());
        }
    }

    /**
     * Toggle user status
     */
    public function toggleUserStatus($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->update([
                'is_active' => !$user->is_active
            ]);

            $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';
            return redirect()->route('admin.users')
                ->with('success', "✅ User berhasil $status!");
                
        } catch (\Exception $e) {
            return redirect()->route('admin.users')
                ->with('error', '❌ Gagal mengubah status user: ' . $e->getMessage());
        }
    }

    /**
     * Halaman pengaturan
     */
    public function pengaturan()
    {
        return view('admin.pengaturan');
    }

    /**
     * Update settings
     */
    public function updateSettings(Request $request)
    {
        try {
            // Logic untuk update settings
            return redirect()->route('admin.pengaturan')
                ->with('success', '✅ Pengaturan berhasil diperbarui!');
                
        } catch (\Exception $e) {
            return redirect()->route('admin.pengaturan')
                ->with('error', '❌ Gagal memperbarui pengaturan: ' . $e->getMessage());
        }
    }

    /**
     * API Methods for dashboard data
     */
    public function getDashboardData()
    {
        $totalBookings = Booking::count();
        $todayBookings = Booking::whereDate('tanggal_booking', today())->count();
        $totalUsers = User::where('role', 'user')->count();
        $totalRevenue = Booking::where('status', 'confirmed')->sum('total_price');

        return response()->json([
            'totalBookings' => $totalBookings,
            'todayBookings' => $todayBookings,
            'totalUsers' => $totalUsers,
            'totalRevenue' => $totalRevenue
        ]);
    }

    /**
     * Laporan methods
     */
    public function laporanHarian()
    {
        return view('admin.laporan-harian');
    }

    public function laporanMingguan()
    {
        return view('admin.laporan-mingguan');
    }

    public function laporanBulanan()
    {
        return view('admin.laporan-bulanan');
    }

    public function laporanTahunan()
    {
        return view('admin.laporan-tahunan');
    }

    /**
     * Get payment statistics
     */
    public function getPaymentStats()
    {
        $stats = [
            'pending_verification' => Booking::where('payment_status', 'pending_verification')->count(),
            'pending' => Booking::where('payment_status', 'pending')->count(),
            'paid' => Booking::where('payment_status', 'paid')->count(),
            'failed' => Booking::where('payment_status', 'failed')->count(),
            'expired' => Booking::where('status', 'expired')->count(),
        ];
        
        return response()->json($stats);
    }
}