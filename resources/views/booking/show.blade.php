@extends('layout')

@section('title', 'Detail Booking #' . $booking->id)

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-950 to-black py-8">
    <div class="max-w-4xl mx-auto px-4">
        <!-- Header -->
        <div class="text-center mb-12">
            <div class="inline-flex items-center space-x-3 bg-white/5 backdrop-blur-xl border border-emerald-500/20 rounded-2xl px-6 py-3 mb-6">
                <div class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></div>
                <span class="text-sm font-bold tracking-wider text-emerald-200">BOOKING DETAILS</span>
                <div class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></div>
            </div>
            <h1 class="text-4xl lg:text-5xl font-black text-white mb-4 leading-tight">
                Booking <span class="bg-gradient-to-r from-emerald-300 to-cyan-400 bg-clip-text text-transparent">Details</span> 📋
            </h1>
            <p class="text-xl text-gray-400 max-w-2xl mx-auto">Detail lengkap reservasi lapangan olahraga Anda</p>
        </div>

        <!-- Main Content -->
        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Left Column: Booking Details -->
            <div class="lg:col-span-2">
                <div class="bg-gradient-to-br from-gray-800/40 to-gray-900/40 rounded-3xl p-8 border border-emerald-500/20 backdrop-blur-sm mb-8">
                    <!-- Header dengan ID & Status -->
                    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8">
                        <div>
                            <h2 class="text-2xl font-black text-white mb-2">Booking #{{ $booking->id }}</h2>
                            <p class="text-gray-400">Dibuat pada {{ \Carbon\Carbon::parse($booking->created_at)->translatedFormat('d F Y, H:i') }}</p>
                        </div>
                        
                        <!-- Status Badge -->
                        <div class="mt-4 md:mt-0">
                            <span class="px-4 py-2 rounded-full text-sm font-bold
                                @if($booking->status == 'confirmed') bg-green-500/20 text-green-300 border border-green-500/30
                                @elseif($booking->status == 'pending') bg-yellow-500/20 text-yellow-300 border border-yellow-500/30
                                @elseif($booking->status == 'completed') bg-blue-500/20 text-blue-300 border border-blue-500/30
                                @elseif($booking->status == 'cancelled') bg-red-500/20 text-red-300 border border-red-500/30
                                @else bg-gray-500/20 text-gray-300 border border-gray-500/30 @endif">
                                {{ strtoupper($booking->status) }}
                            </span>
                        </div>
                    </div>

                    <!-- Lapangan Info -->
                    <div class="mb-8">
                        <h3 class="text-xl font-black text-white mb-6 flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-cyan-500 rounded-2xl flex items-center justify-center border border-emerald-400/30">
                                <i class="fas fa-map-marker-alt text-white"></i>
                            </div>
                            <span>Lapangan</span>
                        </h3>
                        <div class="bg-white/5 rounded-2xl p-6 border border-white/10">
                            <div class="flex flex-col md:flex-row md:items-center space-y-4 md:space-y-0 md:space-x-6">
                                <div class="w-20 h-20 
                                    @if(str_contains(strtolower($booking->lapangan->type ?? ''), 'futsal') || str_contains(strtolower($booking->lapangan->type ?? ''), 'soccer'))
                                        bg-gradient-to-br from-green-500/20 to-emerald-500/20 border border-green-400/30
                                    @elseif(str_contains(strtolower($booking->lapangan->type ?? ''), 'badminton'))
                                        bg-gradient-to-br from-blue-500/20 to-cyan-500/20 border border-blue-400/30
                                    @else
                                        bg-gradient-to-br from-purple-500/20 to-pink-500/20 border border-purple-400/30
                                    @endif
                                    rounded-2xl flex items-center justify-center">
                                    <i class="fas 
                                        @if(str_contains(strtolower($booking->lapangan->type ?? ''), 'futsal') || str_contains(strtolower($booking->lapangan->type ?? ''), 'soccer')) fa-futbol
                                        @elseif(str_contains(strtolower($booking->lapangan->type ?? ''), 'badminton')) fa-table-tennis-paddle-ball
                                        @else fa-running @endif
                                        text-white text-2xl">
                                    </i>
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-2xl font-black text-white mb-2">{{ $booking->lapangan->nama_lapangan ?? 'Unknown' }}</h4>
                                    <div class="flex flex-wrap gap-4">
                                        <div class="flex items-center space-x-2">
                                            <i class="fas fa-tag text-emerald-400"></i>
                                            <span class="text-gray-300">{{ $booking->lapangan->type ?? 'Sports Field' }}</span>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <i class="fas fa-money-bill-wave text-green-400"></i>
                                            <span class="text-gray-300">Rp {{ number_format($booking->lapangan->price_per_hour ?? 0, 0, ',', '.') }}/jam</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Waktu & Tanggal -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div class="bg-white/5 rounded-2xl p-6 border border-white/10">
                            <h4 class="text-lg font-bold text-white mb-4 flex items-center space-x-2">
                                <i class="fas fa-calendar text-emerald-400"></i>
                                <span>Tanggal Booking</span>
                            </h4>
                            <p class="text-2xl font-black text-emerald-300">
                                {{ \Carbon\Carbon::parse($booking->tanggal_booking)->translatedFormat('d F Y') }}
                            </p>
                            <p class="text-gray-400 mt-2">{{ \Carbon\Carbon::parse($booking->tanggal_booking)->translatedFormat('l') }}</p>
                        </div>
                        
                        <div class="bg-white/5 rounded-2xl p-6 border border-white/10">
                            <h4 class="text-lg font-bold text-white mb-4 flex items-center space-x-2">
                                <i class="fas fa-clock text-cyan-400"></i>
                                <span>Waktu Booking</span>
                            </h4>
                            <p class="text-2xl font-black text-cyan-300">{{ $booking->jam_mulai }} - {{ $booking->jam_selesai }}</p>
                            <div class="flex items-center space-x-2 mt-2">
                                <i class="fas fa-hourglass-half text-gray-400"></i>
                                <span class="text-gray-300">{{ $booking->duration ?? 1 }} jam</span>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Details -->
                    <div class="mb-8">
                        <h3 class="text-xl font-black text-white mb-6 flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-500 rounded-2xl flex items-center justify-center border border-purple-400/30">
                                <i class="fas fa-credit-card text-white"></i>
                            </div>
                            <span>Detail Pembayaran</span>
                        </h3>
                        
                        <div class="bg-white/5 rounded-2xl p-6 border border-white/10">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Status Pembayaran -->
                                <div>
                                    <p class="text-gray-400 mb-2">Status Pembayaran</p>
                                    <div class="flex items-center space-x-3">
                                        <div class="w-3 h-3 rounded-full 
                                            @if($booking->payment_status == 'paid') bg-green-500
                                            @elseif($booking->payment_status == 'pending_verification') bg-yellow-500
                                            @elseif($booking->payment_status == 'pending') bg-orange-500
                                            @elseif($booking->payment_status == 'rejected') bg-red-500
                                            @else bg-gray-500 @endif
                                            animate-pulse">
                                        </div>
                                        <span class="text-xl font-bold 
                                            @if($booking->payment_status == 'paid') text-green-300
                                            @elseif($booking->payment_status == 'pending_verification') text-yellow-300
                                            @elseif($booking->payment_status == 'pending') text-orange-300
                                            @elseif($booking->payment_status == 'rejected') text-red-300
                                            @else text-gray-300 @endif">
                                            {{ strtoupper($booking->payment_status ?? 'PENDING') }}
                                        </span>
                                    </div>
                                </div>
                                
                                <!-- Total Harga -->
                                <div>
                                    <p class="text-gray-400 mb-2">Total Pembayaran</p>
                                    @php
                                        // Hitung harga
                                        $totalPrice = $booking->total_price;
                                        if (!$totalPrice || $totalPrice == 0) {
                                            $fieldPrice = $booking->lapangan ? $booking->lapangan->price_per_hour : 0;
                                            $duration = $booking->duration ?? 1;
                                            $totalPrice = $fieldPrice * $duration;
                                        }
                                    @endphp
                                    <p class="text-3xl font-black text-emerald-300">
                                        Rp {{ number_format($totalPrice, 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                            
                            <!-- Detail Pembayaran -->
                            @if($booking->payment_method || $booking->bank_name || $booking->virtual_account)
                            <div class="mt-6 pt-6 border-t border-gray-700">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @if($booking->payment_method)
                                    <div>
                                        <p class="text-gray-400 text-sm">Metode Pembayaran</p>
                                        <p class="text-white font-semibold">{{ ucfirst($booking->payment_method) }}</p>
                                    </div>
                                    @endif
                                    
                                    @if($booking->bank_name)
                                    <div>
                                        <p class="text-gray-400 text-sm">Bank</p>
                                        <p class="text-white font-semibold">{{ $booking->bank_name }}</p>
                                    </div>
                                    @endif
                                    
                                    @if($booking->virtual_account)
                                    <div class="md:col-span-2">
                                        <p class="text-gray-400 text-sm">Virtual Account</p>
                                        <div class="flex items-center justify-between mt-2">
                                            <code class="text-lg font-bold text-yellow-300 bg-black/50 px-4 py-2 rounded-xl">
                                                {{ $booking->virtual_account }}
                                            </code>
                                            <button onclick="copyToClipboard('{{ $booking->virtual_account }}')" 
                                                    class="text-yellow-400 hover:text-yellow-300 px-4 py-2 border border-yellow-500/30 rounded-xl hover:bg-yellow-500/10 transition-colors">
                                                <i class="fas fa-copy mr-2"></i>Copy
                                            </button>
                                        </div>
                                    </div>
                                    @endif
                                    
                                    @if($booking->paid_at)
                                    <div>
                                        <p class="text-gray-400 text-sm">Waktu Bayar</p>
                                        <p class="text-white font-semibold">
                                            {{ \Carbon\Carbon::parse($booking->paid_at)->translatedFormat('d F Y, H:i') }}
                                        </p>
                                    </div>
                                    @endif
                                    
                                    @if($booking->payment_expiry)
                                    <div>
                                        <p class="text-gray-400 text-sm">Batas Pembayaran</p>
                                        <p class="text-yellow-300 font-semibold">
                                            {{ \Carbon\Carbon::parse($booking->payment_expiry)->format('H:i') }}
                                        </p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Notes -->
                    @if($booking->notes)
                    <div class="mb-8">
                        <h3 class="text-xl font-black text-white mb-6 flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-2xl flex items-center justify-center border border-blue-400/30">
                                <i class="fas fa-sticky-note text-white"></i>
                            </div>
                            <span>Catatan</span>
                        </h3>
                        <div class="bg-white/5 rounded-2xl p-6 border border-white/10">
                            <p class="text-gray-300 whitespace-pre-line">{{ $booking->notes }}</p>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Rating Section -->
                @if($booking->status === 'completed')
                <div class="bg-gradient-to-br from-gray-800/40 to-gray-900/40 rounded-3xl p-8 border 
                    @if($booking->rating) border-green-500/20 @else border-yellow-500/20 @endif backdrop-blur-sm">
                    <h3 class="text-xl font-black text-white mb-6 flex items-center space-x-3">
                        <div class="w-10 h-10 
                            @if($booking->rating) bg-gradient-to-br from-green-500 to-emerald-500 @else bg-gradient-to-br from-yellow-500 to-orange-500 @endif
                            rounded-2xl flex items-center justify-center border 
                            @if($booking->rating) border-green-400/30 @else border-yellow-400/30 @endif">
                            <i class="fas fa-star text-white"></i>
                        </div>
                        <span>Rating & Ulasan</span>
                    </h3>
                    
                    @if($booking->rating)
                    <div class="bg-white/5 rounded-2xl p-6 border border-white/10">
                        <div class="flex items-center space-x-4 mb-4">
                            <div class="flex items-center">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star 
                                        @if($i <= $booking->rating->rating) text-yellow-400 @else text-gray-600 @endif
                                        text-xl mr-1"></i>
                                @endfor
                            </div>
                            <span class="text-2xl font-black text-yellow-300">{{ $booking->rating->rating }}/5</span>
                        </div>
                        
                        @if($booking->rating->comment)
                        <div class="mt-4">
                            <p class="text-gray-300">{{ $booking->rating->comment }}</p>
                        </div>
                        @endif
                        
                        <p class="text-gray-400 text-sm mt-4">
                            <i class="fas fa-clock mr-2"></i>
                            Diberikan pada {{ \Carbon\Carbon::parse($booking->rating->created_at)->translatedFormat('d F Y') }}
                        </p>
                    </div>
                    @else
                    <div class="bg-gradient-to-r from-yellow-500/10 to-orange-500/10 rounded-2xl p-6 border border-yellow-500/30">
                        <p class="text-gray-300 mb-6">Bagikan pengalaman Anda menggunakan lapangan ini!</p>
                        <a href="{{ route('rating.create', $booking->id) }}" 
                           class="inline-flex items-center space-x-3 bg-gradient-to-r from-yellow-600 to-orange-600 hover:from-yellow-500 hover:to-orange-500 text-white font-bold py-3 px-6 rounded-2xl transition-all duration-300 transform hover:-translate-y-1 border border-yellow-500/30">
                            <i class="fas fa-star"></i>
                            <span>Beri Rating</span>
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                    @endif
                </div>
                @endif
            </div>

            <!-- Right Column: Actions & Info -->
            <div>
                <div class="sticky top-8">
                    <!-- Action Buttons -->
                    <div class="bg-gradient-to-br from-gray-800/40 to-gray-900/40 rounded-3xl p-6 border border-emerald-500/20 backdrop-blur-sm mb-6">
                        <h3 class="text-xl font-black text-white mb-6">Aksi</h3>
                        
                        <div class="space-y-4">
                            @if($booking->status == 'pending' && $booking->payment_status == 'pending')
                            <a href="{{ route('booking.payment.form', $booking->id) }}" 
                               class="block w-full bg-gradient-to-r from-emerald-600 to-cyan-600 hover:from-emerald-500 hover:to-cyan-500 text-white font-bold py-4 px-6 rounded-2xl transition-all duration-300 transform hover:-translate-y-1 border border-emerald-500/30 flex items-center justify-center space-x-3 group">
                                <i class="fas fa-credit-card group-hover:scale-110 transition-transform"></i>
                                <span>Bayar Sekarang</span>
                                <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                            </a>
                            @endif
                            
                            @if($booking->payment_status == 'pending_verification')
                            <a href="{{ route('booking.payment.success', $booking->id) }}" 
                               class="block w-full bg-gradient-to-r from-yellow-600 to-orange-600 hover:from-yellow-500 hover:to-orange-500 text-white font-bold py-4 px-6 rounded-2xl transition-all duration-300 transform hover:-translate-y-1 border border-yellow-500/30 flex items-center justify-center space-x-3 group">
                                <i class="fas fa-clock group-hover:scale-110 transition-transform"></i>
                                <span>Status Pembayaran</span>
                                <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                            </a>
                            @endif
                            
                            @if($booking->payment_status == 'rejected')
                            <a href="{{ route('booking.payment.form', $booking->id) }}" 
                               class="block w-full bg-gradient-to-r from-red-600 to-pink-600 hover:from-red-500 hover:to-pink-500 text-white font-bold py-4 px-6 rounded-2xl transition-all duration-300 transform hover:-translate-y-1 border border-red-500/30 flex items-center justify-center space-x-3 group">
                                <i class="fas fa-redo group-hover:scale-110 transition-transform"></i>
                                <span>Bayar Ulang</span>
                                <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                            </a>
                            @endif
                            
                            @if(($booking->status == 'pending' || $booking->status == 'pending_verification') && $booking->payment_status != 'paid')
                            <form method="POST" action="{{ route('booking.cancel', $booking->id) }}" class="w-full">
                                @csrf
                                <button type="submit" 
                                        onclick="return confirm('Yakin ingin membatalkan booking? Aksi ini tidak dapat dibatalkan.')"
                                        class="w-full bg-gradient-to-r from-red-600 to-red-500 hover:from-red-500 hover:to-red-400 text-white font-bold py-4 px-6 rounded-2xl transition-all duration-300 transform hover:-translate-y-1 border border-red-500/30 flex items-center justify-center space-x-3 group">
                                    <i class="fas fa-times group-hover:scale-110 transition-transform"></i>
                                    <span>Batalkan Booking</span>
                                </button>
                            </form>
                            @endif
                            
                            <a href="{{ route('booking.my-bookings') }}" 
                               class="block w-full bg-gradient-to-r from-gray-700 to-gray-800 hover:from-gray-600 hover:to-gray-700 text-white font-bold py-4 px-6 rounded-2xl transition-all duration-300 transform hover:-translate-y-1 border border-gray-600/30 flex items-center justify-center space-x-3 group">
                                <i class="fas fa-arrow-left group-hover:scale-110 transition-transform"></i>
                                <span>Kembali ke Daftar</span>
                            </a>
                        </div>
                    </div>
                    
                    <!-- QR Code & Info -->
                    <div class="bg-gradient-to-br from-gray-800/40 to-gray-900/40 rounded-3xl p-6 border border-blue-500/20 backdrop-blur-sm">
                        <h3 class="text-xl font-black text-white mb-6">Informasi</h3>
                        
                        <div class="space-y-4">
                            <div class="bg-white/5 rounded-2xl p-4">
                                <p class="text-gray-400 text-sm mb-2">ID Booking</p>
                                <p class="text-2xl font-black text-white">#{{ $booking->id }}</p>
                            </div>
                            
                            <div class="bg-white/5 rounded-2xl p-4">
                                <p class="text-gray-400 text-sm mb-2">Durasi</p>
                                <p class="text-xl font-bold text-white">{{ $booking->duration ?? 1 }} jam</p>
                            </div>
                            
                            <div class="bg-white/5 rounded-2xl p-4">
                                <p class="text-gray-400 text-sm mb-2">Harga per Jam</p>
                                <p class="text-xl font-bold text-emerald-300">
                                    Rp {{ number_format($booking->lapangan->price_per_hour ?? 0, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                        
                        <!-- QR Code -->
                        <div class="mt-6 pt-6 border-t border-gray-700">
                            <p class="text-gray-400 text-sm mb-4 text-center">QR Code Booking</p>
                            <div class="flex justify-center">
                                <div class="bg-white p-4 rounded-2xl inline-block border-2 border-emerald-500/30">
                                    <div class="w-40 h-40 bg-gradient-to-br from-gray-100 to-gray-200 rounded-xl flex items-center justify-center border border-gray-300">
                                        <div class="text-center">
                                            <div class="text-5xl mb-2">🎯</div>
                                            <div class="text-xs text-gray-600 font-bold">BOOKING ID</div>
                                            <div class="text-xl font-black text-gray-800">#{{ $booking->id }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        // Show notification
        const notification = document.createElement('div');
        notification.className = 'fixed bottom-4 right-4 bg-emerald-500 text-white px-4 py-2 rounded-2xl shadow-lg border border-emerald-400/30 animate-fade-in-up z-50';
        notification.innerHTML = `
            <div class="flex items-center space-x-2">
                <i class="fas fa-check-circle"></i>
                <span>Copied to clipboard!</span>
            </div>
        `;
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.remove();
        }, 3000);
    }).catch(err => {
        console.error('Failed to copy: ', err);
    });
}
</script>

<style>
.sticky {
    position: sticky;
    top: 2rem;
}

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

/* Custom scrollbar */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: #1f2937;
}

::-webkit-scrollbar-thumb {
    background: linear-gradient(to bottom, #10b981, #3b82f6);
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(to bottom, #059669, #2563eb);
}
</style>
@endsection