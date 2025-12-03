<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Rating;
use App\Models\Lapangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class RatingController extends Controller
{
    /**
     * Tampilkan semua rating publik (halaman utama rating)
     */
    public function index()
    {
        // Tampilkan semua rating untuk semua orang (public)
        $ratings = Rating::with(['user', 'field', 'booking'])
                        ->latest()
                        ->whereNotNull('review')
                        ->where('review', '!=', '')
                        ->paginate(12);
        
        // Hitung statistik
        $totalRatings = Rating::count();
        $averageRating = Rating::avg('rating');
        $recentRatings = Rating::with('user')
                              ->latest()
                              ->take(5)
                              ->get();
        
        return view('rating.index', compact('ratings', 'totalRatings', 'averageRating', 'recentRatings'));
    }
    
    /**
     * Tampilkan rating saya (hanya untuk user login)
     */
    public function myRatings()
    {
        $userId = Auth::check() ? Auth::id() : session('user_id');
        
        if (!$userId) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu untuk melihat rating Anda.');
        }
        
        $ratings = Rating::with(['field', 'booking'])
                        ->where('user_id', $userId)
                        ->latest()
                        ->paginate(10);
        
        return view('rating.my-ratings', compact('ratings'));
    }
    
    /**
     * Tampilkan rating untuk lapangan tertentu
     */
    public function fieldRatings($fieldId)
    {
        $ratings = Rating::with(['user', 'field'])
                        ->where('field_id', $fieldId)
                        ->whereNotNull('review')
                        ->where('review', '!=', '')
                        ->latest()
                        ->paginate(10);
        
        $field = Lapangan::findOrFail($fieldId);
        
        return view('rating.field-ratings', compact('ratings', 'field'));
    }
    
    /**
     * Tampilkan form buat rating
     */
    public function create($bookingId)
    {
        try {
            $booking = Booking::with('lapangan')->findOrFail($bookingId);
            
            $userId = Auth::check() ? Auth::id() : (session('user_id') ?? null);
            
            if (!$userId) {
                return redirect()->route('login')
                    ->with('error', 'Silakan login terlebih dahulu.');
            }
            
            // Validasi apakah booking bisa di-rating
            $canRate = $this->canRateBooking($booking);
            if (!$canRate['can_rate']) {
                return redirect()->route('rating.index')
                    ->with('error', $canRate['message']);
            }
            
            // Cek rating existing
            $existingRating = Rating::where('booking_id', $bookingId)->first();
            if ($existingRating) {
                return redirect()->route('rating.my-ratings')
                    ->with('info', 'Anda sudah memberikan rating untuk booking ini.');
            }
            
            return view('rating.create', compact('booking'));
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('rating.index')
                ->with('error', 'Booking tidak ditemukan.');
        } catch (\Exception $e) {
            Log::error('Rating create error: ' . $e->getMessage());
            return redirect()->route('rating.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    /**
     * Simpan rating baru
     */
    public function store(Request $request, $bookingId)
    {
        try {
            $booking = Booking::with('lapangan')->findOrFail($bookingId);
            
            $userId = Auth::check() ? Auth::id() : (session('user_id') ?? null);
            
            if (!$userId) {
                return redirect()->route('login')
                    ->with('error', 'Silakan login terlebih dahulu.');
            }
            
            Log::info('RATING STORE:', [
                'booking_id' => $bookingId,
                'user_id' => $userId,
                'rating' => $request->rating,
                'review_length' => strlen($request->review ?? '')
            ]);
            
            // Validasi apakah booking bisa di-rating
            $canRate = $this->canRateBooking($booking);
            if (!$canRate['can_rate']) {
                return back()->with('error', $canRate['message']);
            }
            
            // Cek rating existing
            $existingRating = Rating::where('booking_id', $bookingId)->first();
            if ($existingRating) {
                return back()->with('error', 'Anda sudah memberikan rating untuk booking ini.');
            }
            
            // Validasi input
            $request->validate([
                'rating' => 'required|integer|min:1|max:5',
                'review' => 'nullable|string|max:500',
            ]);
            
            // Data untuk rating
            $ratingData = [
                'booking_id' => $bookingId,
                'user_id' => $userId,
                'field_id' => $booking->lapangan_id,
                'rating' => $request->rating,
                'review' => $request->review ?? '',
            ];
            
            // Create rating
            $rating = Rating::create($ratingData);
            
            // Update average rating di lapangan
            $this->updateLapanganRating($booking->lapangan_id);
            
            Log::info('Rating created successfully:', [
                'rating_id' => $rating->id,
                'booking_id' => $bookingId,
                'field_id' => $booking->lapangan_id
            ]);
            
            return redirect()->route('booking.my-bookings')
                ->with('success', 'Terima kasih atas rating Anda! 🎉');
                
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return back()->with('error', 'Booking tidak ditemukan.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors());
        } catch (\Exception $e) {
            Log::error('Rating store error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    /**
     * Tampilkan form edit rating
     */
    public function edit($id)
    {
        try {
            $rating = Rating::with(['booking', 'field'])->findOrFail($id);
            
            $userId = Auth::check() ? Auth::id() : (session('user_id') ?? null);
            
            if (!$userId) {
                return redirect()->route('login')
                    ->with('error', 'Silakan login terlebih dahulu.');
            }
            
            // Validasi: hanya user yang membuat rating bisa edit
            if ($rating->user_id != $userId) {
                return redirect()->route('rating.my-ratings')
                    ->with('error', 'Anda tidak memiliki akses untuk mengedit rating ini.');
            }
            
            return view('rating.edit', compact('rating'));
            
        } catch (\Exception $e) {
            Log::error('Rating edit error: ' . $e->getMessage());
            return redirect()->route('rating.my-ratings')
                ->with('error', 'Rating tidak ditemukan.');
        }
    }
    
    /**
     * Update rating yang sudah ada
     */
    public function update(Request $request, $id)
    {
        try {
            $rating = Rating::with('booking')->findOrFail($id);
            
            $userId = Auth::check() ? Auth::id() : (session('user_id') ?? null);
            
            if (!$userId) {
                return redirect()->route('login')
                    ->with('error', 'Silakan login terlebih dahulu.');
            }
            
            // Validasi: hanya user yang membuat rating bisa update
            if ($rating->user_id != $userId) {
                return back()->with('error', 'Anda tidak memiliki akses untuk mengupdate rating ini.');
            }
            
            $request->validate([
                'rating' => 'required|integer|min:1|max:5',
                'review' => 'nullable|string|max:500',
            ]);
            
            // Update rating
            $rating->update([
                'rating' => $request->rating,
                'review' => $request->review ?? '',
            ]);
            
            // Update average rating di lapangan
            $this->updateLapanganRating($rating->field_id);
            
            return redirect()->route('rating.my-ratings')
                ->with('success', 'Rating berhasil diperbarui!');
                
        } catch (\Exception $e) {
            Log::error('Rating update error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan.');
        }
    }
    
    /**
     * Hapus rating
     */
    public function destroy($id)
    {
        try {
            $rating = Rating::findOrFail($id);
            
            $userId = Auth::check() ? Auth::id() : (session('user_id') ?? null);
            
            if (!$userId) {
                return response()->json(['error' => 'Silakan login terlebih dahulu.'], 401);
            }
            
            if ($rating->user_id != $userId) {
                return response()->json(['error' => 'Anda tidak memiliki akses.'], 403);
            }
            
            $fieldId = $rating->field_id;
            $rating->delete();
            
            // Update average rating di lapangan
            $this->updateLapanganRating($fieldId);
            
            return response()->json([
                'success' => true,
                'message' => 'Rating berhasil dihapus'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Rating delete error: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan.'], 500);
        }
    }
    
    /**
     * Admin ratings page
     */
    public function adminIndex()
    {
        // Hanya untuk admin
        if (session('user_role') !== 'admin') {
            return redirect()->route('rating.index')
                ->with('error', 'Anda tidak memiliki akses ke halaman admin.');
        }
        
        $ratings = Rating::with(['user', 'field'])
                        ->latest()
                        ->paginate(20);
        
        return view('rating.admin-index', compact('ratings'));
    }
    
    /**
     * Cek apakah booking bisa di-rating
     */
    private function canRateBooking(Booking $booking): array
    {
        // 1. Cek status booking
        $allowedStatuses = ['completed', 'confirmed'];
        if (!in_array($booking->status, $allowedStatuses)) {
            return [
                'can_rate' => false,
                'message' => 'Hanya booking yang sudah selesai atau confirmed yang bisa diberi rating.'
            ];
        }
        
        // 2. Cek payment status untuk confirmed bookings
        if ($booking->status === 'confirmed' && $booking->payment_status !== 'paid') {
            return [
                'can_rate' => false,
                'message' => 'Harap selesaikan pembayaran sebelum memberi rating.'
            ];
        }
        
        // 3. Cek waktu untuk confirmed bookings
        if ($booking->status === 'confirmed') {
            try {
                $endTime = Carbon::parse($booking->tanggal_booking . ' ' . $booking->jam_selesai);
                $now = Carbon::now();
                
                // Jika waktu belum lewat, tidak bisa rating
                if (!$endTime->isPast()) {
                    $timeRemaining = $endTime->diffForHumans($now, ['parts' => 2]);
                    return [
                        'can_rate' => false,
                        'message' => "Anda bisa memberi rating setelah booking selesai (tersisa $timeRemaining)."
                    ];
                }
            } catch (\Exception $e) {
                Log::error('Error parsing booking time: ' . $e->getMessage());
                return [
                    'can_rate' => false,
                    'message' => 'Data waktu booking tidak valid.'
                ];
            }
        }
        
        // 4. Cek jika booking cancelled
        if ($booking->status === 'cancelled') {
            return [
                'can_rate' => false,
                'message' => 'Booking yang dibatalkan tidak bisa diberi rating.'
            ];
        }
        
        // 5. Cek jika booking expired
        if ($booking->status === 'expired') {
            return [
                'can_rate' => false,
                'message' => 'Booking yang expired tidak bisa diberi rating.'
            ];
        }
        
        return [
            'can_rate' => true,
            'message' => ''
        ];
    }
    
    /**
     * Update rating rata-rata lapangan
     */
    private function updateLapanganRating($lapanganId): void
    {
        try {
            $lapangan = Lapangan::find($lapanganId);
            
            if (!$lapangan) {
                Log::warning("Lapangan ID {$lapanganId} not found");
                return;
            }
            
            $ratings = Rating::where('field_id', $lapanganId);
            $averageRating = $ratings->avg('rating');
            $totalRatings = $ratings->count();
            
            $lapangan->update([
                'average_rating' => $averageRating ? round($averageRating, 1) : 0,
                'total_ratings' => $totalRatings,
            ]);
            
        } catch (\Exception $e) {
            Log::error('Update lapangan rating error: ' . $e->getMessage());
        }
    }
}