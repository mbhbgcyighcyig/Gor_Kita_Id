<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Rating;
use App\Models\Lapangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RatingController extends Controller
{
    /**
     * Tampil semua rating (public)
     */
    public function index()
    {
        // Ambil rating yang ada review
        $ratings = Rating::with(['user', 'field', 'booking'])
                        ->latest()
                        ->whereNotNull('review')
                        ->where('review', '!=', '')
                        ->paginate(12);
        
        // Statistik
        $totalRatings = Rating::count();
        $averageRating = Rating::avg('rating');
        $recentRatings = Rating::with('user')
                              ->latest()
                              ->take(5)
                              ->get();
        
        return view('rating.index', compact('ratings', 'totalRatings', 'averageRating', 'recentRatings'));
    }
    
    /**
     * Rating saya (user login)
     */
    public function myRatings()
    {
        $userId = session('user_id');
        
        if (!$userId) {
            return redirect()->route('login')
                ->with('error', 'Login dulu bro!');
        }
        
        $ratings = Rating::with(['field', 'booking'])
                        ->where('user_id', $userId)
                        ->latest()
                        ->paginate(10);
        
        return view('rating.my-ratings', compact('ratings'));
    }
    
    /**
     * Rating per lapangan
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
     * Form buat rating
     */
    public function create($bookingId)
    {
        try {
            $booking = Booking::with('lapangan')->findOrFail($bookingId);
            
            $userId = session('user_id');
            
            if (!$userId) {
                return redirect()->route('login')
                    ->with('error', 'Login dulu!');
            }
            
            // Cek bisa rating atau nggak
            $canRate = $this->canRateBooking($booking);
            if (!$canRate['can_rate']) {
                return redirect()->route('rating.index')
                    ->with('error', $canRate['message']);
            }
            
            // Cek rating udah ada
            $existingRating = Rating::where('booking_id', $bookingId)->first();
            if ($existingRating) {
                return redirect()->route('rating.my-ratings')
                    ->with('info', 'Udah kasih rating buat booking ini.');
            }
            
            return view('rating.create', compact('booking'));
            
        } catch (\Exception $e) {
            Log::error('Rating create error: ' . $e->getMessage());
            return redirect()->route('rating.index')
                ->with('error', 'Booking ga ketemu.');
        }
    }
    
    /**
     * Simpan rating
     */
    public function store(Request $request, $bookingId)
    {
        try {
            $booking = Booking::with('lapangan')->findOrFail($bookingId);
            
            $userId = session('user_id');
            
            if (!$userId) {
                return redirect()->route('login')
                    ->with('error', 'Login dulu!');
            }
            
            // Cek bisa rating
            $canRate = $this->canRateBooking($booking);
            if (!$canRate['can_rate']) {
                return back()->with('error', $canRate['message']);
            }
            
            // Cek rating udah ada
            $existingRating = Rating::where('booking_id', $bookingId)->first();
            if ($existingRating) {
                return back()->with('error', 'Udah kasih rating buat booking ini.');
            }
            
            // Validasi input
            $request->validate([
                'rating' => 'required|integer|min:1|max:5',
                'review' => 'nullable|string|max:500',
            ]);
            
            // Buat rating
            $ratingData = [
                'booking_id' => $bookingId,
                'user_id' => $userId,
                'field_id' => $booking->lapangan_id,
                'rating' => $request->rating,
                'review' => $request->review ?? '',
            ];
            
            $rating = Rating::create($ratingData);
            
            // Update rating lapangan
            $this->updateLapanganRating($booking->lapangan_id);
            
            return redirect()->route('booking.my-bookings')
                ->with('success', 'Thanks for rating!');
                
        } catch (\Exception $e) {
            Log::error('Rating store error: ' . $e->getMessage());
            return back()->with('error', 'Gagal simpan rating.');
        }
    }
    
    /**
     * Form edit rating
     */
    public function edit($id)
    {
        try {
            $rating = Rating::with(['booking', 'field'])->findOrFail($id);
            
            $userId = session('user_id');
            
            if (!$userId) {
                return redirect()->route('login')
                    ->with('error', 'Login dulu!');
            }
            
            // Cek punya rating
            if ($rating->user_id != $userId) {
                return redirect()->route('rating.my-ratings')
                    ->with('error', 'Bukan rating lu!');
            }
            
            return view('rating.edit', compact('rating'));
            
        } catch (\Exception $e) {
            return redirect()->route('rating.my-ratings')
                ->with('error', 'Rating ga ketemu.');
        }
    }
    
    /**
     * Update rating
     */
    public function update(Request $request, $id)
    {
        try {
            $rating = Rating::with('booking')->findOrFail($id);
            
            $userId = session('user_id');
            
            if (!$userId) {
                return redirect()->route('login')
                    ->with('error', 'Login dulu!');
            }
            
            // Cek punya rating
            if ($rating->user_id != $userId) {
                return back()->with('error', 'Bukan rating lu!');
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
            
            // Update rating lapangan
            $this->updateLapanganRating($rating->field_id);
            
            return redirect()->route('rating.my-ratings')
                ->with('success', 'Rating berhasil diupdate!');
                
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal update.');
        }
    }
    
    /**
     * Hapus rating
     */
    public function destroy($id)
    {
        try {
            $rating = Rating::findOrFail($id);
            
            $userId = session('user_id');
            
            if (!$userId) {
                return response()->json(['error' => 'Login dulu!'], 401);
            }
            
            if ($rating->user_id != $userId) {
                return response()->json(['error' => 'Bukan rating lu!'], 403);
            }
            
            $fieldId = $rating->field_id;
            $rating->delete();
            
            // Update rating lapangan
            $this->updateLapanganRating($fieldId);
            
            return response()->json([
                'success' => true,
                'message' => 'Rating dihapus'
            ]);
            
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal hapus.'], 500);
        }
    }
    
    /**
     * Admin page rating
     */
    public function adminIndex()
    {
        if (session('user_role') !== 'admin') {
            return redirect()->route('rating.index')
                ->with('error', 'Bukan admin!');
        }
        
        $ratings = Rating::with(['user', 'field'])
                        ->latest()
                        ->paginate(20);
        
        return view('rating.admin-index', compact('ratings'));
    }
    
    /**
     * Cek bisa rating booking
     */
    private function canRateBooking(Booking $booking): array
    {
        // Status yang bisa rating
        $allowedStatuses = ['completed', 'confirmed'];
        if (!in_array($booking->status, $allowedStatuses)) {
            return [
                'can_rate' => false,
                'message' => 'Cuma booking yang udah selesai bisa rating.'
            ];
        }
        
        // Cek payment
        if ($booking->status === 'confirmed' && $booking->payment_status !== 'paid') {
            return [
                'can_rate' => false,
                'message' => 'Bayar dulu baru rating.'
            ];
        }
        
        // Cek waktu
        if ($booking->status === 'confirmed') {
            $endTime = \Carbon\Carbon::parse($booking->tanggal_booking . ' ' . $booking->jam_selesai);
            
            if (!$endTime->isPast()) {
                return [
                    'can_rate' => false,
                    'message' => 'Rating setelah booking selesai.'
                ];
            }
        }
      
        if ($booking->status === 'cancelled' || $booking->status === 'expired') {
            return [
                'can_rate' => false,
                'message' => 'Booking batal/expired ga bisa rating.'
            ];
        }
        
        return [
            'can_rate' => true,
            'message' => ''
        ];
    }
    
    /**
     * Update rating lapangan
     */
    private function updateLapanganRating($lapanganId): void
    {
        try {
            $lapangan = Lapangan::find($lapanganId);
            
            if (!$lapangan) return;
            
            $ratings = Rating::where('field_id', $lapanganId);
            $averageRating = $ratings->avg('rating');
            $totalRatings = $ratings->count();
            
            $lapangan->update([
                'average_rating' => $averageRating ? round($averageRating, 1) : 0,
                'total_ratings' => $totalRatings,
            ]);
            
        } catch (\Exception $e) {
            Log::error('Update rating lapangan error: ' . $e->getMessage());
        }
    }
}