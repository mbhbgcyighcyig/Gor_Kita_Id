<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\User;
use App\Models\Lapangan;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    /**
     * Dashboard admin
     */
    public function dashboard()
    {
        // Stats
        $totalBookings = Booking::count();
        $todayBookings = Booking::whereDate('tanggal_booking', today())->count();
        $totalUsers = User::where('role', 'user')->count();
        $totalFields = Lapangan::count();
        
        // Hitung pendapatan
        $confirmedBookings = Booking::where('status', 'confirmed')
            ->with('lapangan')
            ->get();
        
        $totalRevenue = 0;
        foreach ($confirmedBookings as $booking) {
            if ($booking->total_price && $booking->total_price > 0) {
                $totalRevenue += $booking->total_price;
            } else {
                // Kalkulasi manual kalo total_price kosong
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
                    // Fallback
                    if ($booking->lapangan && $booking->lapangan->price_per_hour) {
                        $totalRevenue += $booking->lapangan->price_per_hour;
                    } else {
                        $totalRevenue += 40000;
                    }
                }
            }
        }

        // Data chart mingguan
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

        // Booking terbaru
        $recentBookings = Booking::with(['user', 'lapangan'])
            ->latest()
            ->limit(5)
            ->get();

        // Booking hari ini
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
     * Halaman pembayaran
     */
    public function pembayaran()
    {
        $payments = Booking::with(['user', 'lapangan'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('admin.pembayaran', compact('payments'));
    }

    /**
     * Konfirmasi bayar
     */
    public function confirmPayment($id)
    {
        try {
            $booking = Booking::findOrFail($id);
            
            // Validasi
            if (in_array($booking->status, ['cancelled', 'expired', 'completed'])) {
                return redirect()->route('admin.pembayaran')
                    ->with('error', 'Booking ' . $booking->status . '! Ga bisa konfirm.');
            }
            
            if (!in_array($booking->payment_status, ['pending_verification', 'pending'])) {
                return redirect()->route('admin.pembayaran')
                    ->with('error', 'Status bayar ga valid! Status: ' . $booking->payment_status);
            }
            
            // Cek waktu booking lewat
            try {
                $bookingDateTime = Carbon::parse($booking->tanggal_booking . ' ' . $booking->jam_selesai);
                if ($bookingDateTime < now()) {
                    $booking->update([
                        'payment_status' => 'failed',
                        'status' => 'expired'
                    ]);
                    
                    return redirect()->route('admin.pembayaran')
                        ->with('warning', 'Booking expired! Status diubah.');
                }
            } catch (\Exception $e) {
                Log::error('Parse booking error: ' . $e->getMessage());
            }
            
            // Cek payment expired
            if ($booking->payment_expiry && $booking->payment_expiry < now()) {
                $booking->update([
                    'payment_status' => 'failed',
                    'status' => 'expired'
                ]);
                
                return redirect()->route('admin.pembayaran')
                    ->with('warning', 'Bayar expired! Status diubah.');
            }
            
            // Cek konflik jadwal
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
                    ->with('error', 'Lapangan udah dibooking!');
            }
            
            // Konfirmasi
            $booking->update([
                'payment_status' => 'paid',
                'status' => 'confirmed',
                'paid_at' => now()
            ]);

            return redirect()->route('admin.pembayaran')
                ->with('success', '✅ Bayar berhasil dikonfirm!');
                
        } catch (\Exception $e) {
            Log::error('Confirm bayar error: ' . $e->getMessage());
            
            return redirect()->route('admin.pembayaran')
                ->with('error', '❌ Gagal konfirm bayar: ' . $e->getMessage());
        }
    }

    /**
     * Tolak bayar
     */
    public function rejectPayment($id)
    {
        try {
            $booking = Booking::findOrFail($id);
            
            // Validasi
            $validStatuses = ['pending_verification', 'pending'];
            if (!in_array($booking->payment_status, $validStatuses)) {
                return redirect()->route('admin.pembayaran')
                    ->with('error', 'Bayar udah ' . $booking->payment_status . '! Ga bisa ditolak.');
            }
            
            $booking->update([
                'payment_status' => 'failed',
                'status' => 'cancelled'
            ]);

            return redirect()->route('admin.pembayaran')
                ->with('success', '❌ Bayar berhasil ditolak!');
                
        } catch (\Exception $e) {
            Log::error('Tolak bayar error: ' . $e->getMessage());
            return redirect()->route('admin.pembayaran')
                ->with('error', '❌ Gagal tolak bayar.');
        }
    }

    /**
     * Auto-expire bookings
     */
    public function autoExpireBookings()
    {
        try {
            $expiredCount = 0;
            $now = now();
            
            $expiredBookings = Booking::where(function($query) use ($now) {
                $query->where(function($q) use ($now) {
                    $q->whereNotNull('payment_expiry')
                      ->where('payment_expiry', '<', $now);
                })
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
     * Halaman bookings
     */
    public function bookings()
    {
        $bookings = Booking::with(['user', 'lapangan'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('admin.book', compact('bookings'));
    }

    /**
     * Selesai booking
     */
    public function completeBooking($id)
    {
        try {
            $booking = Booking::findOrFail($id);
            
            if ($booking->status !== 'confirmed') {
                return redirect()->route('admin.book')
                    ->with('error', 'Cuma booking confirmed yang bisa selesai!');
            }
            
            $booking->update(['status' => 'completed']);

            return redirect()->route('admin.book')
                ->with('success', '✅ Booking selesai!');
                
        } catch (\Exception $e) {
            return redirect()->route('admin.book')
                ->with('error', '❌ Gagal selesai booking.');
        }
    }

    /**
     * Konfirm booking (admin)
     */
    public function confirmBookingAdmin($id)
    {
        try {
            $booking = Booking::findOrFail($id);
            
            if (!in_array($booking->status, ['pending', 'pending_verification'])) {
                return redirect()->route('admin.book')
                    ->with('error', 'Cuma booking pending yang bisa dikonfirm!');
            }
            
            $booking->update(['status' => 'confirmed']);

            return redirect()->route('admin.book')
                ->with('success', '✅ Booking dikonfirm!');
                
        } catch (\Exception $e) {
            return redirect()->route('admin.book')
                ->with('error', '❌ Gagal konfirm booking.');
        }
    }

    /**
     * Batal booking (admin)
     */
    public function cancelBookingAdmin($id)
    {
        try {
            $booking = Booking::findOrFail($id);
            
            if (in_array($booking->status, ['completed', 'cancelled', 'expired'])) {
                return redirect()->route('admin.book')
                    ->with('error', 'Booking udah ' . $booking->status . '!');
            }
            
            $booking->update(['status' => 'cancelled']);

            return redirect()->route('admin.book')
                ->with('success', '❌ Booking dibatalkan!');
                
        } catch (\Exception $e) {
            return redirect()->route('admin.book')
                ->with('error', '❌ Gagal batal booking.');
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
        
        // Hitung pendapatan
        $allBookings = Booking::where('status', 'confirmed')
            ->with('lapangan')
            ->get();
        
        $totalRevenue = 0;
        foreach ($allBookings as $booking) {
            if ($booking->total_price && $booking->total_price > 0) {
                $totalRevenue += $booking->total_price;
            } else {
                // Kalkulasi manual
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
                    // Fallback
                    if ($booking->lapangan && $booking->lapangan->price_per_hour) {
                        $totalRevenue += $booking->lapangan->price_per_hour;
                    } else {
                        $totalRevenue += 40000;
                    }
                }
            }
        }
        
        $avgRevenue = $totalBookings > 0 ? $totalRevenue / $totalBookings : 0;
        
        // Stats booking
        $bookingStats = [
            'confirmed' => $confirmedBookings,
            'pending' => $pendingBookings,
            'completed' => Booking::where('status', 'completed')->count(),
            'cancelled' => Booking::where('status', 'cancelled')->count(),
            'expired' => Booking::where('status', 'expired')->count(),
        ];
        
        // Lapangan populer
        $topFields = Lapangan::withCount(['bookings' => function($query) {
            $query->where('status', 'confirmed');
        }])->orderBy('bookings_count', 'desc')->limit(5)->get();
        
        // Transaksi terbaru
        $recentBookings = Booking::with(['user', 'lapangan'])
            ->where('status', 'confirmed')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Chart pendapatan bulanan
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
                    // Kalkulasi manual
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
                        // Fallback
                        if ($booking->lapangan && $booking->lapangan->price_per_hour) {
                            $monthRevenue += $booking->lapangan->price_per_hour;
                        } else {
                            $monthRevenue += 40000;
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
     * Halaman users
     */
    public function users()
    {
        $users = User::where('role', 'user')
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('admin.users', compact('users'));
    }

    /**
     * Hapus user
     */
    public function deleteUser(User $user)
    {
        try {
            // Hapus booking user
            Booking::where('user_id', $user->id)->delete();
            $user->delete();

            return redirect()->route('admin.users')
                ->with('success', '✅ User dihapus!');
                
        } catch (\Exception $e) {
            return redirect()->route('admin.users')
                ->with('error', '❌ Gagal hapus user.');
        }
    }

    /**
     * Toggle status user
     */
    public function toggleUserStatus($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->update([
                'is_active' => !$user->is_active
            ]);

            $status = $user->is_active ? 'aktif' : 'nonaktif';
            return redirect()->route('admin.users')
                ->with('success', "✅ User {$status}!");
                
        } catch (\Exception $e) {
            return redirect()->route('admin.users')
                ->with('error', '❌ Gagal ubah status user.');
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
     * Update pengaturan
     */
    public function updateSettings(Request $request)
    {
        try {
            // Simpan pengaturan
            return redirect()->route('admin.pengaturan')
                ->with('success', '✅ Pengaturan diupdate!');
                
        } catch (\Exception $e) {
            return redirect()->route('admin.pengaturan')
                ->with('error', '❌ Gagal update pengaturan.');
        }
    }

    /**
     * API dashboard data
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
     * Halaman laporan lainnya
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
     * Get payment stats
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