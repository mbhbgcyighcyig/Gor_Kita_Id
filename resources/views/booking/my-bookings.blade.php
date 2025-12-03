@extends('layout')

@section('title', 'Booking Saya - GorKita.ID')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-950 to-black py-8">
    <div class="max-w-7xl mx-auto px-4">
        <!-- Header -->
        <div class="text-center mb-12">
            <div class="inline-flex items-center space-x-2 bg-gray-800/50 rounded-full px-6 py-3 border border-emerald-500/30 mb-6">
                <div class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></div>
                <span class="text-emerald-300 text-sm font-semibold">HISTORI BOOKING SAYA</span>
            </div>
            
            <h1 class="text-4xl lg:text-5xl font-black text-white mb-4">
                <span class="bg-gradient-to-r from-emerald-300 to-cyan-400 bg-clip-text text-transparent">Booking Saya</span>
            </h1>
            <p class="text-xl text-gray-400">Kelola dan lacak reservasi lapangan olahraga Anda</p>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-12">
            <div class="bg-gradient-to-br from-gray-800/40 to-gray-900/40 rounded-3xl p-6 text-center border border-emerald-500/20 backdrop-blur-sm">
                <div class="text-3xl font-black text-emerald-300 mb-2">{{ $bookings->count() }}</div>
                <div class="text-gray-400 text-sm font-semibold">Total Booking</div>
            </div>
            <div class="bg-gradient-to-br from-gray-800/40 to-gray-900/40 rounded-3xl p-6 text-center border border-green-500/20 backdrop-blur-sm">
                <div class="text-3xl font-black text-green-300 mb-2">{{ $bookings->where('status', 'confirmed')->count() }}</div>
                <div class="text-gray-400 text-sm font-semibold">Terkonfirmasi</div>
            </div>
            <div class="bg-gradient-to-br from-gray-800/40 to-gray-900/40 rounded-3xl p-6 text-center border border-yellow-500/20 backdrop-blur-sm">
                <div class="text-3xl font-black text-yellow-300 mb-2">{{ $bookings->where('status', 'pending')->count() + $bookings->where('status', 'pending_verification')->count() }}</div>
                <div class="text-gray-400 text-sm font-semibold">Menunggu</div>
            </div>
            <div class="bg-gradient-to-br from-gray-800/40 to-gray-900/40 rounded-3xl p-6 text-center border border-blue-500/20 backdrop-blur-sm">
                <div class="text-3xl font-black text-blue-300 mb-2">{{ $bookings->where('status', 'completed')->count() }}</div>
                <div class="text-gray-400 text-sm font-semibold">Selesai</div>
            </div>
            <div class="bg-gradient-to-br from-gray-800/40 to-gray-900/40 rounded-3xl p-6 text-center border border-red-500/20 backdrop-blur-sm">
                <div class="text-3xl font-black text-red-300 mb-2">{{ $bookings->where('status', 'cancelled')->count() + $bookings->where('status', 'expired')->count() }}</div>
                <div class="text-gray-400 text-sm font-semibold">Dibatalkan</div>
            </div>
        </div>

        @if($bookings->isEmpty())
            <!-- Empty State -->
            <div class="bg-gradient-to-br from-gray-800/40 to-gray-900/40 rounded-3xl p-12 text-center border border-emerald-500/20 backdrop-blur-sm max-w-2xl mx-auto">
                <div class="w-24 h-24 mx-auto mb-6 bg-gradient-to-br from-gray-700/40 to-gray-800/40 rounded-3xl flex items-center justify-center border border-gray-600/50">
                    <i class="fas fa-calendar-times text-gray-400 text-4xl"></i>
                </div>
                <h2 class="text-3xl font-black text-white mb-4">Belum Ada Booking</h2>
                <p class="text-gray-400 text-lg mb-8">Mulai perjalanan olahragamu dengan membuat booking pertama!</p>
                <a href="{{ route('booking.index') }}" 
                   class="bg-gradient-to-r from-emerald-600 to-cyan-600 hover:from-emerald-500 hover:to-cyan-500 text-white font-bold py-4 px-8 rounded-2xl transition-all duration-300 transform hover:-translate-y-1 border border-emerald-500/30 inline-flex items-center space-x-3">
                    <i class="fas fa-plus-circle"></i>
                    <span>Buat Booking Pertama</span>
                </a>
            </div>
        @else
       
            <!-- Bookings Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6 mb-12">
                @foreach($bookings as $booking)
                @php
                    // HITUNG HARGA DENGAN BENAR
                    $totalPrice = $booking->total_price;
                    
                    // Jika total_price kosong atau 0, hitung dari lapangan
                    if (!$totalPrice || $totalPrice == 0) {
                        $fieldPrice = $booking->lapangan ? $booking->lapangan->price_per_hour : 0;
                        
                        // Hitung duration dari jam
                        try {
                            $start = \Carbon\Carbon::parse($booking->jam_mulai);
                            $end = \Carbon\Carbon::parse($booking->jam_selesai);
                            $duration = $start->diffInHours($end);
                        } catch (\Exception $e) {
                            $duration = $booking->duration ?? 1;
                        }
                        
                        $totalPrice = $fieldPrice * $duration;
                    }
                    
                    // ====================== LOGIC RATING FIX ======================
                    $canRate = false;
                    $hasRating = false;
                    $ratingDebug = [];
                    
                    // Syarat untuk bisa rating:
                    // 1. Status 'completed' (sudah selesai), ATAU
                    // 2. Status 'confirmed' DAN waktu booking sudah lewat DAN payment_status 'paid'
                    
                    if ($booking->status === 'completed') {
                        $canRate = true;
                        $ratingDebug[] = "✅ Status completed";
                    } 
                    elseif ($booking->status === 'confirmed' && $booking->payment_status === 'paid') {
                        // Cek apakah waktu booking sudah lewat
                        try {
                            $bookingDateTime = \Carbon\Carbon::parse($booking->tanggal_booking . ' ' . $booking->jam_selesai);
                            if ($bookingDateTime->isPast()) {
                                $canRate = true;
                                $ratingDebug[] = "✅ Terkonfirmasi + Lunas + Waktu sudah lewat";
                            } else {
                                $ratingDebug[] = "❌ Waktu booking belum lewat (selesai: " . $bookingDateTime->format('d M Y H:i') . ")";
                            }
                        } catch (\Exception $e) {
                            $ratingDebug[] = "❌ Error parsing waktu: " . $e->getMessage();
                        }
                    } else {
                        if ($booking->status !== 'confirmed') {
                            $ratingDebug[] = "❌ Status bukan confirmed (status: {$booking->status})";
                        }
                        if ($booking->payment_status !== 'paid') {
                            $ratingDebug[] = "❌ Pembayaran belum lunas (payment: {$booking->payment_status})";
                        }
                    }
                    
                    // ✅ PERBAIKAN: Cek rating HANYA dari database (jangan pake relationship karena field_id)
                    if ($canRate) {
                        try {
                            // CEK LANGSUNG KE DATABASE dengan booking_id
                            $hasRating = \App\Models\Rating::where('booking_id', $booking->id)->exists();
                            
                            if ($hasRating) {
                                $ratingDebug[] = "✅ Sudah ada rating di database";
                            } else {
                                $ratingDebug[] = "✅ Belum ada rating - BISA BERI RATING!";
                            }
                        } catch (\Exception $e) {
                            \Log::error("Error cek rating booking {$booking->id}: " . $e->getMessage());
                            $ratingDebug[] = "❌ Error cek rating: " . $e->getMessage();
                        }
                    }
                    
                    // Tentukan icon berdasarkan tipe lapangan
                    $fieldType = strtolower($booking->lapangan->type ?? '');
                    $fieldIcon = 'fa-running'; // default
                    
                    if (str_contains($fieldType, 'futsal') || str_contains($fieldType, 'soccer')) {
                        $fieldIcon = 'fa-futbol';
                    } elseif (str_contains($fieldType, 'badminton') || str_contains($fieldType, 'tennis')) {
                        $fieldIcon = 'fa-table-tennis-paddle-ball';
                    } elseif (str_contains($fieldType, 'basket')) {
                        $fieldIcon = 'fa-basketball';
                    } elseif (str_contains($fieldType, 'volley')) {
                        $fieldIcon = 'fa-volleyball';
                    }
                    
                    // Format tanggal
                    $formattedDate = $booking->formatted_date ?? \Carbon\Carbon::parse($booking->tanggal_booking)->translatedFormat('d F Y');
                    
                    // Format waktu
                    $formattedTime = $booking->formatted_time ?? $booking->jam_mulai . ' - ' . $booking->jam_selesai;
                    
                    // Hitung sisa waktu jika belum lewat
                    $timeRemaining = null;
                    $isPast = false;
                    try {
                        $bookingDateTime = \Carbon\Carbon::parse($booking->tanggal_booking . ' ' . $booking->jam_selesai);
                        $isPast = $bookingDateTime->isPast();
                        if (!$isPast) {
                            $timeRemaining = $bookingDateTime->diffForHumans(\Carbon\Carbon::now(), ['parts' => 2]);
                        }
                    } catch (\Exception $e) {
                        // ignore
                    }
                @endphp
                
                <div class="bg-gradient-to-br from-gray-800/40 to-gray-900/40 rounded-3xl p-6 border border-emerald-500/20 backdrop-blur-sm transition-all duration-300 hover:transform hover:-translate-y-2 hover:border-emerald-500/40 group" 
                     data-booking-id="{{ $booking->id }}">
                    <!-- Header -->
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 
                                @if($booking->status == 'pending' || $booking->status == 'pending_verification') bg-gradient-to-br from-yellow-500 to-orange-500
                                @elseif($booking->status == 'confirmed') bg-gradient-to-br from-emerald-500 to-cyan-500
                                @elseif($booking->status == 'completed') bg-gradient-to-br from-blue-500 to-cyan-500
                                @elseif($booking->status == 'cancelled') bg-gradient-to-br from-red-500 to-pink-500
                                @elseif($booking->status == 'expired') bg-gradient-to-br from-gray-600 to-gray-700
                                @else bg-gradient-to-br from-gray-600 to-gray-700 @endif
                                rounded-2xl flex items-center justify-center border border-emerald-400/30">
                                <i class="fas {{ $fieldIcon }} text-white text-lg"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-black text-white group-hover:text-emerald-300 transition-colors">
                                    {{ $booking->lapangan->name ?? $booking->lapangan->nama_lapangan ?? 'Lapangan #' . $booking->lapangan_id }}
                                </h3>
                                <p class="text-emerald-300 text-sm font-semibold">{{ $booking->lapangan->type ?? 'Lapangan Olahraga' }}</p>
                            </div>
                        </div>
                        
                        <!-- Status Badge -->
                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                            @if($booking->status == 'confirmed') bg-green-500/20 text-green-300 border border-green-500/30
                            @elseif($booking->status == 'pending' || $booking->status == 'pending_verification') bg-yellow-500/20 text-yellow-300 border border-yellow-500/30
                            @elseif($booking->status == 'completed') bg-blue-500/20 text-blue-300 border border-blue-500/30
                            @elseif($booking->status == 'cancelled') bg-red-500/20 text-red-300 border border-red-500/30
                            @elseif($booking->status == 'expired') bg-gray-500/20 text-gray-300 border border-gray-500/30
                            @else bg-gray-500/20 text-gray-300 border border-gray-500/30 @endif">
                            @if($booking->status == 'confirmed')
                                Terkonfirmasi
                            @elseif($booking->status == 'pending')
                                Menunggu
                            @elseif($booking->status == 'pending_verification')
                                Verifikasi
                            @elseif($booking->status == 'completed')
                                Selesai
                            @elseif($booking->status == 'cancelled')
                                Dibatalkan
                            @elseif($booking->status == 'expired')
                                Kadaluarsa
                            @else
                                {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                            @endif
                        </span>
                    </div>

                    <!-- Booking Details -->
                    <div class="space-y-3 mb-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2 text-gray-300">
                                <i class="fas fa-calendar text-emerald-400"></i>
                                <span class="text-sm">{{ $formattedDate }}</span>
                            </div>
                            <div class="flex items-center space-x-2 text-gray-300">
                                <i class="fas fa-clock text-cyan-400"></i>
                                <span class="text-sm">{{ $formattedTime }}</span>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2 text-gray-300">
                                <i class="fas fa-hourglass-half text-cyan-400"></i>
                                @php
                                    try {
                                        $start = \Carbon\Carbon::parse($booking->jam_mulai);
                                        $end = \Carbon\Carbon::parse($booking->jam_selesai);
                                        $duration = $start->diffInHours($end);
                                    } catch (\Exception $e) {
                                        $duration = $booking->duration ?? 1;
                                    }
                                @endphp
                                <span class="text-sm">{{ $duration }} Jam</span>
                            </div>
                            @if($booking->lapangan && $booking->lapangan->price_per_hour)
                            <div class="flex items-center space-x-2 text-gray-300">
                                <i class="fas fa-tag text-emerald-400"></i>
                                <span class="text-sm">Rp {{ number_format($booking->lapangan->price_per_hour, 0, ',', '.') }}/jam</span>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Payment Status -->
                    <div class="mb-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-credit-card 
                                    @if($booking->payment_status == 'paid') text-green-400
                                    @elseif($booking->payment_status == 'pending_verification') text-yellow-400
                                    @elseif($booking->payment_status == 'pending') text-orange-400
                                    @elseif($booking->payment_status == 'failed') text-red-400
                                    @else text-gray-400 @endif">
                                </i>
                                <span class="text-sm font-semibold 
                                    @if($booking->payment_status == 'paid') text-green-300
                                    @elseif($booking->payment_status == 'pending_verification') text-yellow-300
                                    @elseif($booking->payment_status == 'pending') text-orange-300
                                    @elseif($booking->payment_status == 'failed') text-red-300
                                    @else text-gray-300 @endif">
                                    @if($booking->payment_status == 'paid')
                                        LUNAS
                                    @elseif($booking->payment_status == 'pending_verification')
                                        VERIFIKASI
                                    @elseif($booking->payment_status == 'pending')
                                        MENUNGGU
                                    @elseif($booking->payment_status == 'failed')
                                        GAGAL
                                    @else
                                        {{ strtoupper(str_replace('_', ' ', $booking->payment_status ?? 'PENDING')) }}
                                    @endif
                                </span>
                            </div>
                            
                            @if($booking->paid_at)
                            <span class="text-xs text-gray-400">
                                Dibayar: {{ \Carbon\Carbon::parse($booking->paid_at)->format('H:i') }}
                            </span>
                            @endif
                        </div>
                    </div>

                    <!-- Price & Actions -->
                    <div class="flex items-center justify-between pt-4 border-t border-gray-700/50">
                        <div>
                            <div class="text-2xl font-black text-emerald-300">
                                Rp {{ number_format($totalPrice, 0, ',', '.') }}
                            </div>
                            <div class="text-gray-400 text-xs">Total Biaya</div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="flex space-x-2">
                            @if(($booking->status == 'pending' || $booking->status == 'pending_verification') && $booking->payment_status == 'pending')
                            <!-- TOMBOL BAYAR -->
                            <a href="{{ route('booking.payment.form', $booking->id) }}" 
                               class="bg-gradient-to-r from-emerald-600 to-green-600 hover:from-emerald-500 hover:to-green-500 text-white px-4 py-2 rounded-xl text-sm font-semibold transition-all duration-300 transform hover:-translate-y-1 border border-emerald-500/30 flex items-center space-x-2">
                                <i class="fas fa-credit-card text-xs"></i>
                                <span>Bayar</span>
                            </a>
                            @elseif($booking->payment_status == 'pending_verification')
                            <!-- STATUS PEMBAYARAN -->
                            <a href="{{ route('booking.payment.success', $booking->id) }}" 
                               class="bg-gradient-to-r from-yellow-600 to-orange-600 hover:from-yellow-500 hover:to-orange-500 text-white px-4 py-2 rounded-xl text-sm font-semibold transition-all duration-300 transform hover:-translate-y-1 border border-yellow-500/30 flex items-center space-x-2">
                                <i class="fas fa-clock text-xs"></i>
                                <span>Status</span>
                            </a>
                            @elseif($booking->payment_status == 'failed' || $booking->status == 'cancelled')
                            <!-- BAYAR ULANG -->
                            <a href="{{ route('booking.payment.form', $booking->id) }}" 
                               class="bg-gradient-to-r from-red-600 to-pink-600 hover:from-red-500 hover:to-pink-500 text-white px-4 py-2 rounded-xl text-sm font-semibold transition-all duration-300 transform hover:-translate-y-1 border border-red-500/30 flex items-center space-x-2">
                                <i class="fas fa-redo text-xs"></i>
                                <span>Bayar Ulang</span>
                            </a>
                            @endif
                            
                            @if($booking->status == 'pending' || $booking->status == 'pending_verification')
                            <!-- TOMBOL BATAL -->
                            <form method="POST" action="{{ route('booking.cancel', $booking->id) }}" class="inline">
                                @csrf
                                <button type="submit" 
                                        onclick="return confirm('Yakin ingin membatalkan booking?')"
                                        class="bg-gradient-to-r from-red-600 to-red-500 hover:from-red-500 hover:to-red-400 text-white px-4 py-2 rounded-xl text-sm font-semibold transition-all duration-300 transform hover:-translate-y-1 border border-red-500/30">
                                    Batal
                                </button>
                            </form>
                            @endif
                            
                            <!-- DETAILS BUTTON -->
                            <button onclick="viewDetails({{ $booking->id }})" 
                                    class="bg-gradient-to-r from-cyan-600 to-emerald-600 hover:from-cyan-500 hover:to-emerald-500 text-white px-4 py-2 rounded-xl text-sm font-semibold transition-all duration-300 transform hover:-translate-y-1 border border-cyan-500/30">
                                Detail
                            </button>
                        </div>
                    </div>

                    <!-- Additional Info -->
                    <div class="mt-4 pt-4 border-t border-gray-700/50 text-xs text-gray-400">
                        <div class="flex justify-between">
                            <span>ID: #{{ $booking->id }}</span>
                            <span>{{ \Carbon\Carbon::parse($booking->created_at)->diffForHumans() }}</span>
                        </div>
                        @if($booking->payment_method)
                        <div class="mt-1 flex justify-between">
                            <span>Metode: {{ ucfirst($booking->payment_method) }}</span>
                            @if($booking->bank_name)
                            <span>Bank: {{ $booking->bank_name }}</span>
                            @endif
                        </div>
                        @endif
                        
                        <!-- Debug info untuk rating -->
                        @if((env('APP_DEBUG') || request()->has('debug')) && $booking->status == 'confirmed' && $booking->payment_status == 'paid')
                        <div class="mt-2 pt-2 border-t border-gray-700/30 text-[10px] text-gray-500">
                            @foreach($ratingDebug as $debug)
                            <div>{{ $debug }}</div>
                            @endforeach
                        </div>
                        @endif
                    </div>

                    <!-- Virtual Account Info (jika sudah bayar) -->
                    @if($booking->virtual_account && $booking->payment_status == 'pending_verification')
                    <div class="mt-4 p-3 bg-gradient-to-r from-yellow-500/10 to-orange-500/10 rounded-xl border border-yellow-500/20">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs text-gray-400">Virtual Account</p>
                                <p class="text-sm font-bold text-yellow-300">{{ $booking->virtual_account }}</p>
                            </div>
                            <button onclick="copyToClipboard('{{ $booking->virtual_account }}')" 
                                    class="text-yellow-400 hover:text-yellow-300">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                        @if($booking->payment_expiry)
                        <p class="text-xs text-red-300 mt-2">
                            <i class="fas fa-clock mr-1"></i>
                            Kadaluarsa: {{ \Carbon\Carbon::parse($booking->payment_expiry)->format('H:i') }}
                        </p>
                        @endif
                    </div>
                    @endif

                    <!-- ====================== RATING SECTION FIX ====================== -->
                    @if($canRate && !$hasRating)
                    <div class="mt-4 pt-4 border-t border-yellow-500/20">
                        <a href="{{ route('rating.create', $booking->id) }}" 
                           class="w-full bg-gradient-to-r from-yellow-600 to-amber-600 hover:from-yellow-500 hover:to-amber-500 text-white font-bold py-3 rounded-xl text-center transition-all duration-300 transform hover:-translate-y-1 border border-yellow-500/30 inline-flex items-center justify-center space-x-2">
                            <i class="fas fa-star"></i>
                            <span>Beri Rating</span>
                        </a>
                        @if(env('APP_DEBUG') || request()->has('debug'))
                        <div class="mt-2 text-center text-[10px] text-yellow-300">
                            ✅ BISA RATING - {{ $booking->status }} / {{ $booking->payment_status }}
                        </div>
                        @endif
                    </div>
                    @elseif($hasRating)
                    <div class="mt-4 pt-4 border-t border-green-500/20">
                        <div class="flex items-center justify-center text-green-300 font-semibold">
                            <i class="fas fa-check-circle mr-2"></i>
                            <span>Sudah diberi rating</span>
                        </div>
                        <!-- Tombol lihat rating jika sudah ada -->
                        @php
                            $existingRating = \App\Models\Rating::where('booking_id', $booking->id)->first();
                        @endphp
                        @if($existingRating)
                        <div class="mt-2 text-center">
                            <span class="text-yellow-300 text-sm">
                                <i class="fas fa-star mr-1"></i>
                                {{ $existingRating->rating }}/5
                            </span>
                            @if($existingRating->review)
                            <div class="text-gray-400 text-xs mt-1 italic">
                                "{{ Str::limit($existingRating->review, 60) }}"
                            </div>
                            @endif
                        </div>
                        @endif
                    </div>
                    @elseif($booking->status === 'confirmed' && $booking->payment_status === 'paid' && !$isPast)
                    <!-- Info waktu booking belum lewat -->
                    <div class="mt-4 pt-4 border-t border-gray-700/30">
                        <div class="text-center text-gray-400 text-sm">
                            <i class="fas fa-clock mr-1"></i>
                            Bisa rating setelah: <span class="text-cyan-300">{{ $timeRemaining }}</span>
                        </div>
                    </div>
                    @elseif($booking->status === 'confirmed' && $booking->payment_status !== 'paid')
                    <!-- Info payment belum paid -->
                    <div class="mt-4 pt-4 border-t border-gray-700/30">
                        <div class="text-center text-gray-400 text-sm">
                            <i class="fas fa-info-circle mr-1"></i>
                            Selesaikan pembayaran untuk bisa rating
                        </div>
                    </div>
                    @endif
                    <!-- ====================== END RATING SECTION ====================== -->
                </div>
                @endforeach
            </div>

            <!-- Booking Summary -->
            <div class="bg-gradient-to-br from-gray-800/40 to-gray-900/40 rounded-3xl p-6 border border-emerald-500/20 backdrop-blur-sm">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                    <div>
                        <div class="text-2xl font-black text-emerald-300">{{ $bookings->where('payment_status', 'pending')->count() }}</div>
                        <div class="text-gray-400 text-sm">Belum Bayar</div>
                    </div>
                    <div>
                        <div class="text-2xl font-black text-yellow-300">{{ $bookings->where('payment_status', 'pending_verification')->count() }}</div>
                        <div class="text-gray-400 text-sm">Verifikasi</div>
                    </div>
                    <div>
                        <div class="text-2xl font-black text-green-300">{{ $bookings->where('payment_status', 'paid')->count() }}</div>
                        <div class="text-gray-400 text-sm">Lunas</div>
                    </div>
                    <div>
                        <div class="text-2xl font-black text-red-300">{{ $bookings->where('payment_status', 'failed')->count() }}</div>
                        <div class="text-gray-400 text-sm">Gagal</div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Modal Details -->
<div id="bookingModal" class="fixed inset-0 bg-black/70 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
    <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-3xl p-8 max-w-md w-full border border-emerald-500/30">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-black text-white">Detail Booking</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-white text-2xl">&times;</button>
        </div>
        <div id="modalContent" class="text-gray-300">
            <!-- Content akan diisi oleh JavaScript -->
        </div>
    </div>
</div>

<script>
// View Details Function
async function viewDetails(bookingId) {
    const modal = document.getElementById('bookingModal');
    const content = document.getElementById('modalContent');
    
    content.innerHTML = `
        <div class="text-center py-8">
            <div class="w-16 h-16 bg-gradient-to-br from-emerald-500 to-cyan-500 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-emerald-400/30">
                <i class="fas fa-spinner fa-spin text-white text-2xl"></i>
            </div>
            <p class="text-gray-400">Memuat detail booking...</p>
        </div>
    `;
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    
    try {
        // Fetch booking details via API atau langsung dari data yang ada
        // Karena API mungkin belum ada, kita pakai cara sederhana
        const bookingCard = document.querySelector(`[data-booking-id="${bookingId}"]`);
        
        if (!bookingCard) {
            throw new Error('Booking tidak ditemukan');
        }
        
        // Ambil data dari card yang ada
        const fieldName = bookingCard.querySelector('h3').textContent.trim();
        const fieldType = bookingCard.querySelector('.text-emerald-300').textContent.trim();
        const date = bookingCard.querySelectorAll('.text-gray-300 span')[0].textContent.trim();
        const time = bookingCard.querySelectorAll('.text-gray-300 span')[1].textContent.trim();
        const status = bookingCard.querySelector('.px-3.py-1').textContent.trim();
        const price = bookingCard.querySelector('.text-emerald-300.text-2xl').textContent.trim();
        const paymentStatus = bookingCard.querySelector('.text-sm.font-semibold').textContent.trim();
        
        // Format the details
        content.innerHTML = `
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-gray-800/50 p-4 rounded-xl">
                        <p class="text-gray-400 text-sm">ID Booking</p>
                        <p class="text-lg font-bold text-white">#${bookingId}</p>
                    </div>
                    <div class="bg-gray-800/50 p-4 rounded-xl">
                        <p class="text-gray-400 text-sm">Status</p>
                        <p class="text-lg font-bold 
                            ${status === 'Terkonfirmasi' || status === 'Confirmed' ? 'text-green-400' : 
                              status === 'Menunggu' || status === 'Verifikasi' || status === 'Pending' ? 'text-yellow-400' :
                              status === 'Dibatalkan' || status === 'Cancelled' ? 'text-red-400' : 'text-white'}">
                            ${status.toUpperCase()}
                        </p>
                    </div>
                </div>
                
                <div class="bg-gray-800/50 p-4 rounded-xl">
                    <p class="text-gray-400 text-sm mb-2">Lapangan</p>
                    <p class="text-lg font-bold text-white">${fieldName}</p>
                    <p class="text-sm text-emerald-300">${fieldType}</p>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-gray-800/50 p-4 rounded-xl">
                        <p class="text-gray-400 text-sm">Tanggal</p>
                        <p class="text-white font-semibold">${date}</p>
                    </div>
                    <div class="bg-gray-800/50 p-4 rounded-xl">
                        <p class="text-gray-400 text-sm">Waktu</p>
                        <p class="text-emerald-300 font-semibold">${time}</p>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-gray-800/50 p-4 rounded-xl">
                        <p class="text-gray-400 text-sm">Status Pembayaran</p>
                        <p class="font-bold 
                            ${paymentStatus.includes('LUNAS') ? 'text-green-400' :
                              paymentStatus.includes('VERIFIKASI') || paymentStatus.includes('MENUNGGU') ? 'text-yellow-400' :
                              paymentStatus.includes('GAGAL') ? 'text-red-400' :
                              'text-white'}">
                            ${paymentStatus}
                        </p>
                    </div>
                    <div class="bg-gray-800/50 p-4 rounded-xl">
                        <p class="text-gray-400 text-sm">Total</p>
                        <p class="text-emerald-300 font-bold text-lg">${price}</p>
                    </div>
                </div>
                
                <div class="pt-4 border-t border-gray-700">
                    <button onclick="closeModal()" 
                            class="w-full bg-gradient-to-r from-cyan-600 to-emerald-600 hover:from-cyan-500 hover:to-emerald-500 text-white font-bold py-3 rounded-xl transition-all duration-300 transform hover:-translate-y-1 border border-cyan-500/30">
                        Tutup
                    </button>
                </div>
            </div>
        `;
        
    } catch (error) {
        console.error('Error:', error);
        content.innerHTML = `
            <div class="text-center py-8">
                <div class="w-16 h-16 bg-gradient-to-br from-red-500 to-pink-500 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-red-400/30">
                    <i class="fas fa-exclamation-triangle text-white text-2xl"></i>
                </div>
                <p class="text-red-300 font-bold mb-2">Gagal Memuat Detail</p>
                <p class="text-gray-400">Gagal memuat detail booking</p>
                <button onclick="closeModal()" 
                        class="mt-4 bg-gray-700 hover:bg-gray-600 text-white px-6 py-2 rounded-xl">
                    Tutup
                </button>
            </div>
        `;
    }
}

function closeModal() {
    const modal = document.getElementById('bookingModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

// Copy to Clipboard
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        // Show notification
        const notification = document.createElement('div');
        notification.className = 'fixed bottom-4 right-4 bg-emerald-500 text-white px-4 py-2 rounded-xl shadow-lg border border-emerald-400/30 animate-fade-in-up z-50';
        notification.innerHTML = `
            <div class="flex items-center space-x-2">
                <i class="fas fa-check-circle"></i>
                <span>Disalin ke clipboard!</span>
            </div>
        `;
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.remove();
        }, 2000);
    }).catch(err => {
        console.error('Gagal menyalin: ', err);
    });
}

// Animate cards on scroll
document.addEventListener('DOMContentLoaded', function() {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry, index) => {
            if (entry.isIntersecting) {
                setTimeout(() => {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }, index * 100);
            }
        });
    }, { threshold: 0.1 });
    
    document.querySelectorAll('.bg-gradient-to-br').forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = `opacity 0.5s ease ${index * 0.1}s, transform 0.5s ease ${index * 0.1}s`;
        observer.observe(card);
    });
});

// Debug function untuk testing rating
function testRating(bookingId) {
    console.log('Testing rating untuk booking:', bookingId);
    
    // Coba akses route rating
    fetch(`/rating/${bookingId}/create`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        if (response.redirected) {
            console.log('Redirected to:', response.url);
            window.location.href = response.url;
        }
        return response.text();
    })
    .then(data => {
        console.log('Response data (first 500 chars):', data.substring(0, 500));
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error testing rating: ' + error.message);
    });
}
</script>

<style>
.animate-fade-in-up {
    animation: fadeInUp 0.3s ease-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
@endsection