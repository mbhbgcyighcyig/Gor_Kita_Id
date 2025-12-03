<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Lapangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class BookingController extends Controller
{
    /**
     * Tampilkan halaman booking index
     */
    public function index()
    {
        $userRole = session('user_role');
        
        $sportTypes = [
            'futsal' => [
                'name' => 'Futsal',
                'icon' => 'futbol',
                'description' => 'Lapangan futsal standar internasional'
            ],
            'badminton' => [
                'name' => 'Badminton',
                'icon' => 'table-tennis-paddle-ball',
                'description' => 'Lapangan badminton indoor premium'
            ],
            'minisoccer' => [
                'name' => 'Mini Soccer',
                'icon' => 'futbol',
                'description' => 'Lapangan mini soccer 7v7'
            ]
        ];
        
        return view('booking.index', compact('sportTypes'));
    }
    
    /**
     * Tampilkan pilihan lapangan berdasarkan jenis olahraga
     */
    public function selectField($type, Request $request = null)
    {
        $selectedDate = $request->date ?? date('Y-m-d');
        
        $validTypes = ['futsal', 'badminton', 'minisoccer'];
        if (!in_array($type, $validTypes)) {
            return redirect()->route('booking.index')->with('error', 'Jenis olahraga tidak valid');
        }
        
        $fields = Lapangan::where('type', $type)
                         ->where('is_active', true)
                         ->orderBy('price_per_hour', 'asc')
                         ->get();
        
        return view('booking.select-field', compact('type', 'fields', 'selectedDate'));
    }
    
    /**
     * Tampilkan pilihan waktu untuk lapangan tertentu - LOGIKA EXPIRED DIPERBAIKI
     */
    public function selectTime($fieldId, Request $request = null)
    {
        Log::info('=== SELECT TIME DEBUG START ===');
        
        $field = Lapangan::find($fieldId);
        
        if (!$field) {
            return redirect()->route('booking.index')
                           ->with('error', 'Lapangan tidak ditemukan');
        }
        
        $selectedDate = $request->date ?? date('Y-m-d');
        
        // Waktu sekarang di WIB
        $nowWIB = Carbon::now()->timezone('Asia/Jakarta');
        $selectedDateTime = Carbon::parse($selectedDate)->timezone('Asia/Jakarta');
        $isToday = $selectedDateTime->isToday();
        
        Log::info('=== WIB TIME INFO ===');
        Log::info('Now WIB (Asia/Jakarta):', [
            'full' => $nowWIB->format('Y-m-d H:i:s'),
            'date' => $nowWIB->format('Y-m-d'),
            'time' => $nowWIB->format('H:i:s'),
            'hour' => (int)$nowWIB->format('H'),
            'minute' => (int)$nowWIB->format('i')
        ]);
        Log::info('Selected Date:', [
            'date' => $selectedDate,
            'is_today' => $isToday
        ]);
        
        // Cek apakah tanggal sudah lewat
        $todayWIB = $nowWIB->format('Y-m-d');
        if ($selectedDate < $todayWIB) {
            Log::info('Tanggal sudah lewat');
            return back()->with('error', 'Tidak bisa booking tanggal yang sudah lewat');
        }
        
        // Generate semua slot waktu dari 08:00-09:00 sampai 21:00-22:00
        $allSlots = [];
        for ($hour = 8; $hour <= 21; $hour++) {
            $startHour = str_pad($hour, 2, '0', STR_PAD_LEFT);
            $endHour = str_pad($hour + 1, 2, '0', STR_PAD_LEFT);
            $allSlots[] = "{$startHour}:00-{$endHour}:00";
        }
        
        // Ambil booking yang sudah ada untuk tanggal ini
        $bookings = Booking::where('lapangan_id', $fieldId)
            ->where('tanggal_booking', $selectedDate)
            ->where('status', '!=', 'cancelled')
            ->get();
        
        $bookedSlots = [];
        foreach ($bookings as $booking) {
            $start = Carbon::parse($booking->jam_mulai);
            $end = Carbon::parse($booking->jam_selesai);
            
            $current = $start->copy();
            while ($current->lt($end)) {
                $slotStart = $current->format('H:i');
                $slotEnd = $current->copy()->addHour()->format('H:i');
                $bookedSlots[] = $slotStart . '-' . $slotEnd;
                $current->addHour();
            }
        }
        
        Log::info('Booked Slots:', $bookedSlots);
        
        $availableSlots = [
            'all' => $allSlots,
            'available' => [],
            'booked' => $bookedSlots,
            'expired' => []
        ];
        
        // PERBAIKAN UTAMA: LOGIKA EXPIRED YANG BENAR
        foreach ($allSlots as $slot) {
            // Parse waktu slot
            $startTime = explode('-', $slot)[0];
            $slotDateTime = Carbon::parse("{$selectedDate} {$startTime}:00")->timezone('Asia/Jakarta');
            
            // Hitung jam dan menit slot
            $slotHour = (int)explode(':', $startTime)[0];
            $slotMinute = (int)explode(':', $startTime)[1];
            $currentHour = (int)$nowWIB->format('H');
            $currentMinute = (int)$nowWIB->format('i');
            
            // Debug info
            Log::info('Slot Analysis:', [
                'slot' => $slot,
                'start_time' => $startTime,
                'slot_datetime' => $slotDateTime->format('Y-m-d H:i:s'),
                'now_wib' => $nowWIB->format('Y-m-d H:i:s'),
                'is_today' => $isToday,
                'slot_hour' => $slotHour,
                'slot_minute' => $slotMinute,
                'current_hour' => $currentHour,
                'current_minute' => $currentMinute,
                'hour_comparison' => 'Slot: ' . $slotHour . ':00 vs Now: ' . $currentHour . ':' . $currentMinute
            ]);
            
            // LOGIKA EXPIRED: Cek apakah slot sudah lewat (HARI INI dan waktu sudah lewat)
            if ($isToday) {
                // Hitung apakah slot sudah lewat
                $isPast = false;
                
                // Cara 1: Gunakan Carbon comparison (lebih akurat)
                $isPast = $slotDateTime->lt($nowWIB);
                
                // Cara 2: Manual comparison (fallback)
                if (!$isPast) {
                    // Jika jam slot < jam sekarang → pasti lewat
                    if ($slotHour < $currentHour) {
                        $isPast = true;
                    }
                    // Jika jam slot sama dengan jam sekarang, cek menit
                    elseif ($slotHour == $currentHour && $slotMinute <= $currentMinute) {
                        $isPast = true;
                    }
                }
                
                if ($isPast) {
                    Log::info('✅ SLOT EXPIRED - Added to expired list:', [
                        'slot' => $slot,
                        'reason' => 'Waktu sudah lewat',
                        'slot_time' => $slotHour . ':' . str_pad($slotMinute, 2, '0', STR_PAD_LEFT),
                        'current_time' => $currentHour . ':' . str_pad($currentMinute, 2, '0', STR_PAD_LEFT)
                    ]);
                    $availableSlots['expired'][] = $slot;
                    continue; // Skip ke slot berikutnya
                }
            }
            
            // Cek apakah slot sudah dipesan
            if (in_array($slot, $bookedSlots)) {
                Log::info('Slot BOOKED:', ['slot' => $slot]);
                continue;
            }
            
            // Slot tersedia
            Log::info('Slot AVAILABLE:', ['slot' => $slot]);
            $availableSlots['available'][] = $slot;
        }
        
        // Debug summary
        Log::info('=== FINAL SUMMARY ===');
        Log::info('Total Slots:', ['count' => count($availableSlots['all'])]);
        Log::info('Available Slots:', [
            'count' => count($availableSlots['available']),
            'slots' => $availableSlots['available']
        ]);
        Log::info('Booked Slots:', [
            'count' => count($availableSlots['booked']),
            'slots' => $availableSlots['booked']
        ]);
        Log::info('Expired Slots:', [
            'count' => count($availableSlots['expired']),
            'slots' => $availableSlots['expired']
        ]);
        Log::info('Current WIB Time:', ['time' => $nowWIB->format('H:i:s')]);
        
        Log::info('=== SELECT TIME DEBUG END ===');
        
        return view('booking.select-time', compact('field', 'selectedDate', 'availableSlots'));
    }
    
    /**
     * Tampilkan halaman konfirmasi booking - DITAMBAH VALIDASI EXPIRED
     */
    public function confirmBooking($fieldId, Request $request)
    {
        Log::info('=== CONFIRM BOOKING DEBUG START ===');
        
        $field = Lapangan::find($fieldId);
        
        if (!$field) {
            return redirect()->route('booking.index')
                           ->with('error', 'Lapangan tidak ditemukan');
        }
        
        $selectedDate = $request->date ?? date('Y-m-d');
        $timeSlot = $request->time ?? '10:00-11:00';
        
        if (!$selectedDate || !$timeSlot) {
            return redirect()->route('booking.select-time', $fieldId)
                           ->with('error', 'Parameter tidak lengkap');
        }
        
        $timeParts = explode('-', $timeSlot);
        $startTime = trim($timeParts[0] ?? $timeSlot);
        $endTime = trim($timeParts[1] ?? '');
        
        if (!$endTime) {
            $endTime = Carbon::parse($startTime)->addHour()->format('H:i');
        }
        
        // VALIDASI: Cek apakah waktu sudah lewat (WIB)
        $startDateTime = Carbon::parse($selectedDate . ' ' . $startTime)->timezone('Asia/Jakarta');
        $nowWIB = Carbon::now()->timezone('Asia/Jakarta');
        
        Log::info('Time Validation:', [
            'start_datetime' => $startDateTime->format('Y-m-d H:i:s'),
            'now_wib' => $nowWIB->format('Y-m-d H:i:s'),
            'is_past' => $startDateTime->lt($nowWIB) ? 'YES' : 'NO'
        ]);
        
        // Jika waktu sudah lewat, redirect kembali dengan error
        if ($startDateTime->lt($nowWIB)) {
            Log::warning('Slot waktu sudah lewat:', [
                'slot' => $timeSlot,
                'start_time' => $startDateTime->format('H:i:s'),
                'current_time' => $nowWIB->format('H:i:s')
            ]);
            
            return redirect()->route('booking.select-time', $fieldId)
                           ->with('error', 'Slot waktu ' . $timeSlot . ' sudah lewat! Silakan pilih waktu lain.')
                           ->withInput();
        }
        
        $end = Carbon::parse($selectedDate . ' ' . $endTime)->timezone('Asia/Jakarta');
        $duration = $startDateTime->diffInHours($end);
        
        $totalPrice = $field->price_per_hour * $duration;
        
        // Cek ketersediaan
        $isAvailable = $this->checkAvailability($fieldId, $selectedDate, $startTime, $endTime);
        
        if (!$isAvailable) {
            return redirect()->route('booking.select-time', $fieldId)
                           ->with('error', 'Slot waktu sudah tidak tersedia!')
                           ->withInput();
        }
        
        Log::info('=== CONFIRM BOOKING DEBUG END ===');
        
        return view('booking.confirm', compact(
            'field', 'selectedDate', 'timeSlot', 
            'startTime', 'endTime', 'duration', 'totalPrice'
        ));
    }
    
    /**
     * Tampilkan detail konfirmasi (alternatif method)
     */
    public function showConfirm($fieldId, $date, $time)
    {
        $request = new Request([
            'date' => $date,
            'time' => $time
        ]);
        
        return $this->confirmBooking($fieldId, $request);
    }
    
    /**
     * Proses booking baru - DITAMBAH VALIDASI WAKTU LEWAT
     */
    public function processBooking(Request $request)
    {
        try {
            DB::beginTransaction();
            
            Log::info('=== PROCESS BOOKING START ===');
            
            $validated = $request->validate([
                'field_id' => 'required|integer|exists:fields,id',
                'booking_date' => 'required|date',
                'start_time' => 'required|date_format:H:i',
                'end_time' => 'required|date_format:H:i',
                'duration' => 'required|integer|min:1|max:8',
                'total_price' => 'required|numeric|min:0',
                'notes' => 'nullable|string|max:500'
            ]);
            
            $fieldId = $validated['field_id'];
            $bookingDate = $validated['booking_date'];
            $startTime = $validated['start_time'];
            $endTime = $validated['end_time'];
            $duration = $validated['duration'];
            $totalPrice = $validated['total_price'];
            $notes = $validated['notes'] ?? null;
            
            // VALIDASI WAKTU: Cek apakah waktu booking sudah lewat (WIB)
            $startDateTime = Carbon::parse($bookingDate . ' ' . $startTime)->timezone('Asia/Jakarta');
            $nowWIB = Carbon::now()->timezone('Asia/Jakarta');
            
            Log::info('Time Check in Process Booking:', [
                'start_time' => $startDateTime->format('Y-m-d H:i:s'),
                'now_wib' => $nowWIB->format('Y-m-d H:i:s'),
                'is_past' => $startDateTime->lt($nowWIB) ? 'YES' : 'NO'
            ]);
            
            if ($startDateTime->lt($nowWIB)) {
                Log::warning('Trying to book expired slot:', [
                    'date' => $bookingDate,
                    'time' => $startTime,
                    'current_time' => $nowWIB->format('H:i:s')
                ]);
                
                return back()->with('error', 'Waktu booking sudah lewat! Silakan pilih waktu lain.')
                             ->withInput();
            }
            
            // Cek lapangan
            $field = Lapangan::find($fieldId);
            if (!$field) {
                return back()->with('error', 'Lapangan tidak ditemukan!')
                             ->withInput();
            }
            
            // Cek ketersediaan
            $isAvailable = $this->checkAvailability($fieldId, $bookingDate, $startTime, $endTime);
            
            if (!$isAvailable) {
                return back()->with('error', 'Slot waktu sudah tidak tersedia!')
                             ->withInput();
            }
            
            $endDateTime = Carbon::parse($bookingDate . ' ' . $endTime)->timezone('Asia/Jakarta');
            
            if ($endDateTime->lte($startDateTime)) {
                return back()->with('error', 'Waktu selesai harus setelah waktu mulai!')
                             ->withInput();
            }
            
            $actualDuration = $startDateTime->diffInHours($endDateTime);
            if ($actualDuration > 8) {
                return back()->with('error', 'Durasi maksimal 8 jam!')
                             ->withInput();
            }
            
            // Buat booking
            $bookingData = [
                'user_id' => session('user_id'),
                'lapangan_id' => $fieldId,
                'tanggal_booking' => $bookingDate,
                'jam_mulai' => $startTime,
                'jam_selesai' => $endTime,
                'duration' => $duration,
                'total_price' => $totalPrice,
                'status' => 'pending',
                'payment_status' => 'pending',
                'notes' => $notes,
                'payment_expiry' => Carbon::now()->addMinutes(15),
                'created_at' => now(),
                'updated_at' => now()
            ];
            
            $booking = Booking::create($bookingData);
            
            DB::commit();
            
            return redirect()->route('booking.payment.form', $booking->id)
                ->with('success', 'Booking berhasil dibuat! Silakan lakukan pembayaran.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Booking process failed: ' . $e->getMessage());
            
            return back()->with('error', 'Gagal membuat booking: ' . $e->getMessage())
                         ->withInput();
        }
    }
    
    /**
     * Tampilkan form payment
     */
    public function showPaymentForm($bookingId)
    {
        $booking = Booking::where('id', $bookingId)
                         ->where('user_id', session('user_id'))
                         ->first();
        
        if (!$booking) {
            return redirect()->route('booking.index')
                           ->with('error', 'Booking tidak ditemukan!');
        }
        
        // Cek status
        if ($booking->payment_status === 'paid') {
            return redirect()->route('booking.show', $bookingId)
                           ->with('info', 'Booking ini sudah dibayar.');
        }
        
        if ($booking->status === 'cancelled') {
            return redirect()->route('booking.index')
                           ->with('error', 'Booking ini sudah dibatalkan.');
        }
        
        // Hitung remaining time dengan WIB
        $remainingTime = '15:00:00';
        
        if ($booking->payment_expiry) {
            $expiryTime = Carbon::parse($booking->payment_expiry)->timezone('Asia/Jakarta');
            $nowWIB = Carbon::now()->timezone('Asia/Jakarta');
            
            if ($nowWIB->gt($expiryTime)) {
                $remainingTime = 'EXPIRED';
                
                // Auto cancel jika expired
                DB::beginTransaction();
                $booking->update([
                    'status' => 'cancelled',
                    'payment_status' => 'expired',
                    'cancelled_at' => now(),
                    'cancelled_by' => session('user_id'),
                    'cancellation_reason' => 'Waktu pembayaran habis'
                ]);
                DB::commit();
                
                return redirect()->route('booking.index')
                               ->with('error', 'Waktu pembayaran telah habis! Booking dibatalkan.');
            } else {
                $diff = $nowWIB->diff($expiryTime);
                $remainingTime = sprintf('%02d:%02d:%02d', 
                    $diff->h + ($diff->days * 24), 
                    $diff->i, 
                    $diff->s
                );
            }
        }
        
        // Virtual account boongan
        $vaNumbers = [
            'BCA' => '888' . str_pad($booking->id, 12, '0', STR_PAD_LEFT),
            'BRI' => '8881' . str_pad($booking->id, 11, '0', STR_PAD_LEFT),
            'Mandiri' => '8882' . str_pad($booking->id, 11, '0', STR_PAD_LEFT),
            'BNI' => '8883' . str_pad($booking->id, 11, '0', STR_PAD_LEFT),
            'CIMB' => '8884' . str_pad($booking->id, 11, '0', STR_PAD_LEFT)
        ];
        
        $fieldName = $booking->lapangan ? 
            ($booking->lapangan->name ?? "Lapangan") : 
            "Lapangan";
        
        return view('booking.payment', compact('booking', 'vaNumbers', 'fieldName', 'remainingTime'));
    }
    
    /**
     * Proses pembayaran boongan
     */
    public function processPayment(Request $request, $bookingId)
    {
        $booking = Booking::where('id', $bookingId)
                         ->where('user_id', session('user_id'))
                         ->firstOrFail();
        
        // Validasi input PIN boongan (6 digit)
        $validated = $request->validate([
            'pin' => 'required|digits:6',
            'bank' => 'required|string|in:BCA,BRI,Mandiri,BNI,CIMB'
        ]);
        
        // PIN demo: 123456
        if ($validated['pin'] !== '123456') {
            return back()->with('error', 'PIN salah! Coba: 123456')
                         ->withInput();
        }
        
        try {
            DB::beginTransaction();
            
            // Buat virtual account number
            $vaNumber = '888' . str_pad($booking->id, 12, '0', STR_PAD_LEFT);
            
            $booking->update([
                'payment_status' => 'pending_verification',
                'status' => 'pending_verification',
                'payment_method' => 'virtual_account',
                'payment_pin' => $validated['pin'],
                'pin_verified' => true,
                'bank_name' => $validated['bank'],
                'virtual_account' => $vaNumber,
                'paid_at' => now(),
                'payment_expiry' => Carbon::now()->addMinutes(15),
                'updated_at' => now()
            ]);
            
            DB::commit();
            
            // Simpan di session untuk ditampilkan
            session()->flash('payment_success', [
                'booking_id' => $booking->id,
                'amount' => 'Rp ' . number_format($booking->total_price, 0, ',', '.'),
                'bank' => $validated['bank'],
                'va_number' => $vaNumber,
                'pin' => $validated['pin'],
                'expiry' => Carbon::now()->timezone('Asia/Jakarta')->addMinutes(15)->format('H:i'),
                'date' => Carbon::now()->timezone('Asia/Jakarta')->format('d/m/Y H:i:s')
            ]);
            
            return redirect()->route('booking.payment.success', $bookingId)
                           ->with('success', 'Pembayaran berhasil! Silakan tunggu verifikasi admin.');
                           
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage())
                         ->withInput();
        }
    }
    
    /**
     * Halaman sukses pembayaran
     */
    public function paymentSuccess($bookingId)
    {
        $booking = Booking::where('id', $bookingId)
                         ->where('user_id', session('user_id'))
                         ->firstOrFail();
        
        // Ambil data dari session atau buat default
        $paymentData = session('payment_success', [
            'booking_id' => $booking->id,
            'amount' => 'Rp ' . number_format($booking->total_price, 0, ',', '.'),
            'bank' => $booking->bank_name ?? 'BCA',
            'va_number' => $booking->virtual_account ?? '888' . str_pad($booking->id, 12, '0', STR_PAD_LEFT),
            'pin' => $booking->payment_pin ?? '123456',
            'expiry' => $booking->payment_expiry ? $booking->payment_expiry->timezone('Asia/Jakarta')->format('H:i') : Carbon::now()->timezone('Asia/Jakarta')->addMinutes(15)->format('H:i'),
            'date' => $booking->paid_at ? $booking->paid_at->timezone('Asia/Jakarta')->format('d/m/Y H:i:s') : Carbon::now()->timezone('Asia/Jakarta')->format('d/m/Y H:i:s')
        ]);
        
        return view('booking.payment-success', compact('booking', 'paymentData'));
    }
    
    /**
     * Tampilkan halaman sukses
     */
    public function success($bookingId)
    {
        $booking = Booking::where('id', $bookingId)
                         ->where('user_id', session('user_id'))
                         ->firstOrFail();
        
        return view('booking.success-pending', compact('booking'));
    }
    
    /**
     * Tampilkan semua booking user
     */
    public function myBookings()
    {
        $userId = session('user_id');
        
        $bookings = Booking::with(['lapangan', 'rating'])
                          ->where('user_id', $userId)
                          ->orderBy('created_at', 'desc')
                          ->get();
        
        return view('booking.my-bookings', compact('bookings'));
    }
    
    /**
     * Tampilkan detail booking
     */
    public function show($bookingId)
    {
        $booking = Booking::with(['lapangan', 'rating'])
                         ->where('id', $bookingId)
                         ->where('user_id', session('user_id'))
                         ->firstOrFail();
        
        return view('booking.show', compact('booking'));
    }
    
    /**
     * Batalkan booking
     */
    public function cancelBooking($bookingId, Request $request)
    {
        try {
            DB::beginTransaction();
            
            $booking = Booking::where('id', $bookingId)
                             ->where('user_id', session('user_id'))
                             ->firstOrFail();
            
            $cancelableStatuses = ['pending', 'pending_verification'];
            if (!in_array($booking->status, $cancelableStatuses)) {
                return back()->with('error', 'Booking tidak dapat dibatalkan karena status ' . $booking->status);
            }
            
            $booking->update([
                'status' => 'cancelled',
                'payment_status' => 'cancelled',
                'cancelled_at' => now(),
                'cancelled_by' => session('user_id'),
                'cancellation_reason' => $request->reason ?? 'Dibatalkan oleh user'
            ]);
            
            DB::commit();
            
            return redirect()->route('booking.my-bookings')
                           ->with('success', 'Booking berhasil dibatalkan.');
                           
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membatalkan booking: ' . $e->getMessage());
        }
    }
    
    /**
     * Helper: Cek ketersediaan slot waktu dengan WIB
     */
    private function checkAvailability($fieldId, $date, $startTime, $endTime)
    {
        Log::info('=== CHECK AVAILABILITY DEBUG ===');
        
        // Cek apakah waktu sudah lewat (WIB)
        $startDateTime = Carbon::parse($date . ' ' . $startTime)->timezone('Asia/Jakarta');
        $nowWIB = Carbon::now()->timezone('Asia/Jakarta');
        
        if ($startDateTime->lt($nowWIB)) {
            Log::info('❌ Slot TIDAK TERSEDIA karena waktu sudah lewat');
            return false;
        }
        
        // Cek semua booking di lapangan dan tanggal yang sama
        $existingBookings = Booking::where('lapangan_id', $fieldId)
                                  ->where('tanggal_booking', $date)
                                  ->where('status', '!=', 'cancelled')
                                  ->get(['jam_mulai', 'jam_selesai']);
        
        foreach ($existingBookings as $booking) {
            $existingStart = $booking->jam_mulai;
            $existingEnd = $booking->jam_selesai;
            
            // Check if time overlaps
            if ($startTime < $existingEnd && $endTime > $existingStart) {
                Log::info('OVERLAP DETECTED!');
                return false;
            }
        }
        
        Log::info('✅ Slot TERSEDIA');
        return true;
    }
    
    /**
     * Helper: Dapatkan slot yang sudah dibooking
     */
    private function getBookedSlots($fieldId, $date)
    {
        $bookings = Booking::where('lapangan_id', $fieldId)
                          ->where('tanggal_booking', $date)
                          ->where('status', '!=', 'cancelled')
                          ->select('jam_mulai', 'jam_selesai')
                          ->get();
        
        $bookedSlots = [];
        
        foreach ($bookings as $booking) {
            $start = Carbon::parse($booking->jam_mulai);
            $end = Carbon::parse($booking->jam_selesai);
            
            $current = $start->copy();
            while ($current->lt($end)) {
                $slotStart = $current->format('H:i');
                $slotEnd = $current->copy()->addHour()->format('H:i');
                $bookedSlots[] = $slotStart . '-' . $slotEnd;
                $current->addHour();
            }
        }
        
        return array_unique($bookedSlots);
    }
    
    /**
     * API: Cek ketersediaan
     */
    public function checkAvailabilityApi(Request $request)
    {
        try {
            $request->validate([
                'field_id' => 'required|integer|exists:fields,id',
                'date' => 'required|date',
                'start_time' => 'required|date_format:H:i',
                'end_time' => 'required|date_format:H:i'
            ]);
            
            $isAvailable = $this->checkAvailability(
                $request->field_id,
                $request->date,
                $request->start_time,
                $request->end_time
            );
            
            return response()->json([
                'success' => true,
                'available' => $isAvailable,
                'message' => $isAvailable ? 'Slot tersedia' : 'Slot sudah dipesan atau waktu telah lewat'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * API: Dapatkan waktu tersedia dengan logika WIB
     */
    public function getAvailableTimes($fieldId, $date)
    {
        try {
            // Waktu sekarang di WIB
            $nowWIB = Carbon::now()->timezone('Asia/Jakarta');
            $selectedDate = Carbon::parse($date)->timezone('Asia/Jakarta');
            $isToday = $selectedDate->isToday();
            
            // Generate semua slot waktu
            $allSlots = [];
            for ($hour = 8; $hour <= 21; $hour++) {
                $startHour = str_pad($hour, 2, '0', STR_PAD_LEFT);
                $endHour = str_pad($hour + 1, 2, '0', STR_PAD_LEFT);
                $allSlots[] = [
                    'slot' => "{$startHour}:00-{$endHour}:00",
                    'start_time' => "{$startHour}:00",
                    'end_time' => "{$endHour}:00"
                ];
            }
            
            $bookedSlots = $this->getBookedSlots($fieldId, $date);
            
            // Filter slot berdasarkan waktu WIB
            $availableSlots = [];
            $expiredSlots = [];
            
            foreach ($allSlots as $slotData) {
                $slot = $slotData['slot'];
                $startTime = $slotData['start_time'];
                
                // Parse waktu slot dalam WIB
                $slotDateTime = Carbon::parse("{$date} {$startTime}:00")->timezone('Asia/Jakarta');
                
                // Cek apakah slot sudah lewat (hanya jika hari ini)
                if ($isToday && $slotDateTime->lt($nowWIB)) {
                    $expiredSlots[] = $slot;
                    continue;
                }
                
                // Cek apakah slot sudah dipesan
                if (in_array($slot, $bookedSlots)) {
                    continue;
                }
                
                // Slot tersedia
                $availableSlots[] = $slot;
            }
            
            return response()->json([
                'success' => true,
                'data' => [
                    'all_slots' => array_column($allSlots, 'slot'),
                    'available_slots' => $availableSlots,
                    'booked_slots' => $bookedSlots,
                    'expired_slots' => $expiredSlots,
                    'date' => $date,
                    'field_id' => $fieldId,
                    'current_time_wib' => $nowWIB->format('H:i:s'),
                    'is_today' => $isToday
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * API: Hitung harga
     */
    public function calculatePrice($fieldId, $duration)
    {
        try {
            $field = Lapangan::find($fieldId);
            
            if (!$field) {
                return response()->json([
                    'success' => false,
                    'message' => 'Field tidak ditemukan'
                ], 404);
            }
            
            $totalPrice = $field->price_per_hour * $duration;
            
            return response()->json([
                'success' => true,
                'data' => [
                    'price_per_hour' => $field->price_per_hour,
                    'duration' => $duration,
                    'total_price' => $totalPrice,
                    'formatted_price' => 'Rp ' . number_format($totalPrice, 0, ',', '.')
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Export bookings (untuk admin)
     */
    public function exportBookings(Request $request)
    {
        if (session('user_role') !== 'admin') {
            abort(403);
        }
        
        $query = Booking::with(['user', 'lapangan']);
        
        if ($request->start_date) {
            $query->whereDate('tanggal_booking', '>=', $request->start_date);
        }
        
        if ($request->end_date) {
            $query->whereDate('tanggal_booking', '<=', $request->end_date);
        }
        
        if ($request->status) {
            $query->where('status', $request->status);
        }
        
        $bookings = $query->orderBy('tanggal_booking', 'desc')
                         ->orderBy('jam_mulai')
                         ->get();
        
        $csv = "ID,User,Lapangan,Tanggal,Waktu,Durasi,Total,Status,Pembayaran,Bank,VA,Paid At\n";
        
        foreach ($bookings as $booking) {
            $fieldName = $booking->lapangan ? 
                ($booking->lapangan->name ?? "Unknown") : 
                "Unknown";
            
            $csv .= sprintf(
                '%s,%s,%s,%s,%s,%s jam,Rp %s,%s,%s,%s,%s,%s',
                $booking->id,
                $booking->user->name ?? 'Unknown',
                $fieldName,
                $booking->tanggal_booking,
                $booking->jam_mulai . '-' . $booking->jam_selesai,
                $booking->duration,
                number_format($booking->total_price, 0, ',', '.'),
                $booking->status,
                $booking->payment_status,
                $booking->bank_name ?? '-',
                $booking->virtual_account ?? '-',
                $booking->paid_at ? $booking->paid_at->format('Y-m-d H:i:s') : '-'
            ) . "\n";
        }
        
        $filename = 'bookings_' . date('Ymd_His') . '.csv';
        
        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}