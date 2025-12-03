<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\TransactionLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    /**
     * TAMPILKAN FORM PEMBAYARAN
     */
    public function showPaymentForm($bookingId)
    {
        // Cek login
        if (!session()->has('user_id')) {
            return redirect('/login')->with('error', 'Silakan login dulu!');
        }
        
        // Cari booking
        $booking = Booking::with('lapangan')->findOrFail($bookingId);
        
        // Cek punya user
        if ($booking->user_id != session('user_id')) {
            return redirect('/home')->with('error', 'Bukan booking kamu!');
        }
        
        // Cek status booking
        if ($booking->status != 'pending' || $booking->payment_status != 'pending') {
            return redirect()->route('booking.show', $booking->id)
                ->with('error', 'Booking sudah diproses atau dibatalkan.');
        }
        
        // Cek kadaluarsa
        if ($booking->payment_expiry && now()->gt($booking->payment_expiry)) {
            $booking->update([
                'status' => 'cancelled',
                'payment_status' => 'expired'
            ]);
            return redirect('/home')->with('error', 'Waktu pembayaran telah habis!');
        }
        
        // Generate VA numbers
        $vaNumbers = $this->generateVANumbers($booking->id);
        
        return view('booking.payment', [
            'booking' => $booking,
            'vaNumbers' => $vaNumbers,
            'remainingTime' => $this->calculateRemainingTime($booking->payment_expiry)
        ]);
    }
    
    /**
     * PROSES PEMBAYARAN DENGAN PIN
     */
    public function processPayment(Request $request, $bookingId)
    {
        try {
            DB::beginTransaction();
            
            // Validasi
            $request->validate([
                'bank_name' => 'required|in:BCA,BRI,Mandiri,BNI,CIMB',
                'pin' => 'required|digits:6|numeric'
            ]);
            
            // Cek login
            if (!session()->has('user_id')) {
                return redirect('/login');
            }
            
            // Cari booking
            $booking = Booking::findOrFail($bookingId);
            
            // Cek punya user
            if ($booking->user_id != session('user_id')) {
                return back()->with('error', 'Akses ditolak!');
            }
            
            // Cek status
            if ($booking->status != 'pending' || $booking->payment_status != 'pending') {
                return back()->with('error', 'Booking sudah diproses!');
            }
            
            // Cek kadaluarsa
            if ($booking->payment_expiry && now()->gt($booking->payment_expiry)) {
                $booking->update([
                    'status' => 'cancelled',
                    'payment_status' => 'expired'
                ]);
                return redirect('/home')->with('error', 'Waktu pembayaran habis!');
            }
            
            // VALIDASI PIN (DEMO: 123456)
            if ($request->pin !== '123456') {
                // Hitung attempt
                $attempts = session("pin_attempts_{$bookingId}", 0) + 1;
                session(["pin_attempts_{$bookingId}" => $attempts]);
                
                if ($attempts >= 3) {
                    $booking->update([
                        'status' => 'cancelled',
                        'payment_status' => 'cancelled',
                        'cancelled_at' => now()
                    ]);
                    session()->forget("pin_attempts_{$bookingId}");
                    return redirect('/home')->with('error', 'PIN salah 3x. Booking dibatalkan!');
                }
                
                return back()->with('error', "PIN salah! Percobaan {$attempts}/3. PIN demo: 123456");
            }
            
            // RESET ATTEMPTS JIKA BERHASIL
            session()->forget("pin_attempts_{$bookingId}");
            
            // GENERATE VIRTUAL ACCOUNT
            $vaNumber = $this->generateVA($booking->id, $request->bank_name);
            
            // UPDATE BOOKING SETELAH BAYAR
            $booking->update([
                'payment_status' => 'pending_verification',
                'status' => 'pending_verification',
                'bank_name' => $request->bank_name,
                'virtual_account' => $vaNumber,
                'payment_pin' => Hash::make($request->pin),
                'pin_verified' => true,
                'paid_at' => now(),
                'payment_method' => 'virtual_account',
                'payment_expiry' => now()->addMinutes(15) // Reset expiry untuk verifikasi admin
            ]);
            
            // LOG TRANSAKSI
            TransactionLog::create([
                'booking_id' => $booking->id,
                'user_id' => session('user_id'),
                'action' => 'payment_submitted',
                'description' => "Pembayaran via {$request->bank_name} - VA: {$vaNumber}",
                'amount' => $booking->total_price,
                'status' => 'pending',
                'metadata' => [
                    'bank' => $request->bank_name,
                    'va_number' => $vaNumber,
                    'pin_verified' => true,
                    'timestamp' => now()->toDateTimeString()
                ]
            ]);
            
            DB::commit();
            
            // REDIRECT KE STATUS PEMBAYARAN
            return redirect()->route('payment.status', $booking->id)
                ->with('success', 'Pembayaran berhasil! Menunggu verifikasi admin.')
                ->with('va_info', [
                    'bank' => $request->bank_name,
                    'va_number' => $vaNumber,
                    'amount' => 'Rp ' . number_format($booking->total_price, 0, ',', '.')
                ]);
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
        }
    }
    
    /**
     * STATUS PEMBAYARAN
     */
    public function showStatus($bookingId)
    {
        if (!session()->has('user_id')) {
            return redirect('/login');
        }
        
        $booking = Booking::with(['lapangan', 'verifier'])->findOrFail($bookingId);
        
        if ($booking->user_id != session('user_id')) {
            return redirect('/home')->with('error', 'Akses ditolak!');
        }
        
        $vaInfo = session('va_info');
        
        return view('booking.payment-status', [
            'booking' => $booking,
            'vaInfo' => $vaInfo
        ]);
    }
    
    /**
     * BATALKAN PEMBAYARAN
     */
    public function cancelPayment($bookingId)
    {
        try {
            DB::beginTransaction();
            
            $booking = Booking::findOrFail($bookingId);
            
            if ($booking->user_id != session('user_id')) {
                return back()->with('error', 'Akses ditolak!');
            }
            
            if ($booking->payment_status != 'pending_verification') {
                return back()->with('error', 'Hanya bisa batalkan pembayaran yang pending!');
            }
            
            $booking->update([
                'status' => 'cancelled',
                'payment_status' => 'cancelled',
                'cancelled_at' => now()
            ]);
            
            TransactionLog::create([
                'booking_id' => $booking->id,
                'user_id' => session('user_id'),
                'action' => 'payment_cancelled',
                'description' => 'Pembayaran dibatalkan oleh user',
                'amount' => $booking->total_price,
                'status' => 'cancelled'
            ]);
            
            DB::commit();
            
            return redirect('/home')->with('info', 'Pembayaran dibatalkan.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membatalkan!');
        }
    }
    
    /**
     * ADMIN: VERIFIKASI PEMBAYARAN
     */
    public function adminVerify(Request $request, $bookingId)
    {
        if (session('user_role') != 'admin') {
            abort(403);
        }
        
        try {
            DB::beginTransaction();
            
            $booking = Booking::findOrFail($bookingId);
            
            $booking->update([
                'payment_status' => 'paid',
                'status' => 'confirmed',
                'verified_at' => now(),
                'verified_by' => session('user_id'),
                'notes' => $request->notes ?? $booking->notes
            ]);
            
            TransactionLog::create([
                'booking_id' => $booking->id,
                'user_id' => session('user_id'),
                'action' => 'payment_verified',
                'description' => 'Pembayaran diverifikasi admin',
                'amount' => $booking->total_price,
                'status' => 'success',
                'metadata' => [
                    'verified_by' => session('user_name'),
                    'notes' => $request->notes
                ]
            ]);
            
            DB::commit();
            
            return back()->with('success', 'Pembayaran berhasil diverifikasi!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memverifikasi!');
        }
    }
    
    /**
     * ADMIN: TOLAK PEMBAYARAN
     */
    public function adminReject(Request $request, $bookingId)
    {
        if (session('user_role') != 'admin') {
            abort(403);
        }
        
        $request->validate([
            'reason' => 'required|min:10'
        ]);
        
        try {
            DB::beginTransaction();
            
            $booking = Booking::findOrFail($bookingId);
            
            $booking->update([
                'payment_status' => 'rejected',
                'status' => 'pending',
                'rejected_at' => now(),
                'rejected_by' => session('user_id'),
                'rejection_reason' => $request->reason,
                'payment_expiry' => now()->addMinutes(15) // Beri waktu untuk bayar ulang
            ]);
            
            TransactionLog::create([
                'booking_id' => $booking->id,
                'user_id' => session('user_id'),
                'action' => 'payment_rejected',
                'description' => "Pembayaran ditolak: {$request->reason}",
                'amount' => $booking->total_price,
                'status' => 'rejected'
            ]);
            
            DB::commit();
            
            return back()->with('success', 'Pembayaran ditolak!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menolak pembayaran!');
        }
    }
    
    /**
     * ADMIN: DASHBOARD PEMBAYARAN
     */
    public function adminDashboard(Request $request)
    {
        if (session('user_role') != 'admin') {
            abort(403);
        }
        
        $query = Booking::with(['user', 'lapangan', 'verifier'])
                        ->whereNotNull('paid_at')
                        ->orderBy('paid_at', 'desc');
        
        // Filter
        if ($request->status) {
            $query->where('payment_status', $request->status);
        }
        
        if ($request->date) {
            $query->whereDate('paid_at', $request->date);
        }
        
        $payments = $query->paginate(20);
        
        // Stats
        $stats = [
            'pending' => Booking::where('payment_status', 'pending_verification')->count(),
            'paid' => Booking::where('payment_status', 'paid')->count(),
            'rejected' => Booking::where('payment_status', 'rejected')->count(),
            'revenue_today' => Booking::whereDate('paid_at', today())
                                   ->where('payment_status', 'paid')
                                   ->sum('total_price'),
            'total_revenue' => Booking::where('payment_status', 'paid')
                                   ->sum('total_price')
        ];
        
        return view('admin.payments.index', compact('payments', 'stats'));
    }
    
    /**
     * HELPER: GENERATE VA NUMBERS
     */
    private function generateVANumbers($bookingId)
    {
        return [
            'BCA' => '8880' . str_pad($bookingId, 12, '0', STR_PAD_LEFT),
            'BRI' => '8881' . str_pad($bookingId, 12, '0', STR_PAD_LEFT),
            'Mandiri' => '8882' . str_pad($bookingId, 12, '0', STR_PAD_LEFT),
            'BNI' => '8883' . str_pad($bookingId, 12, '0', STR_PAD_LEFT),
            'CIMB' => '8884' . str_pad($bookingId, 12, '0', STR_PAD_LEFT)
        ];
    }
    
    /**
     * HELPER: GENERATE SINGLE VA
     */
    private function generateVA($bookingId, $bank)
    {
        $prefixes = [
            'BCA' => '8880',
            'BRI' => '8881',
            'Mandiri' => '8882',
            'BNI' => '8883',
            'CIMB' => '8884'
        ];
        
        $prefix = $prefixes[$bank] ?? '8880';
        return $prefix . str_pad($bookingId, 12, '0', STR_PAD_LEFT);
    }
    
    /**
     * HELPER: CALCULATE REMAINING TIME
     */
    private function calculateRemainingTime($expiry)
    {
        if (!$expiry) return null;
        
        $remaining = now()->diff($expiry);
        
        if ($remaining->invert) {
            return 'EXPIRED';
        }
        
        return sprintf('%02d:%02d', $remaining->i, $remaining->s);
    }
}