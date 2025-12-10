@extends('layout')

@section('title', 'Status Pembayaran - Booking #' . $booking->id)

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-950 to-black py-8">
    <div class="max-w-4xl mx-auto px-4">
        <div class="text-center mb-12">
            @if($booking->payment_status == 'pending_verification')
            <div class="inline-flex items-center space-x-3 bg-yellow-500/10 backdrop-blur-xl border border-yellow-500/20 rounded-2xl px-6 py-3 mb-6">
                <div class="w-2 h-2 bg-yellow-400 rounded-full animate-pulse"></div>
                <span class="text-sm font-bold tracking-wider text-yellow-200">MENUNGGU VERIFIKASI</span>
                <div class="w-2 h-2 bg-yellow-400 rounded-full animate-pulse"></div>
            </div>
            @elseif($booking->payment_status == 'paid')
            <div class="inline-flex items-center space-x-3 bg-emerald-500/10 backdrop-blur-xl border border-emerald-500/20 rounded-2xl px-6 py-3 mb-6">
                <div class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></div>
                <span class="text-sm font-bold tracking-wider text-emerald-200">PEMBAYARAN BERHASIL</span>
                <div class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></div>
            </div>
            @elseif($booking->payment_status == 'rejected')
            <div class="inline-flex items-center space-x-3 bg-red-500/10 backdrop-blur-xl border border-red-500/20 rounded-2xl px-6 py-3 mb-6">
                <div class="w-2 h-2 bg-red-400 rounded-full animate-pulse"></div>
                <span class="text-sm font-bold tracking-wider text-red-200">PEMBAYARAN DITOLAK</span>
                <div class="w-2 h-2 bg-red-400 rounded-full animate-pulse"></div>
            </div>
            @endif
            
            <h1 class="text-4xl lg:text-5xl font-black text-white mb-4 leading-tight">
                Status <span class="bg-gradient-to-r from-emerald-300 to-cyan-400 bg-clip-text text-transparent">Pembayaran</span> 🏁
            </h1>
            <p class="text-xl text-gray-400 max-w-2xl mx-auto">Lacak status pembayaran booking Anda</p>
        </div>

        @if($vaInfo)
        <div class="bg-gradient-to-r from-emerald-500/10 to-cyan-500/10 rounded-3xl p-8 mb-8 border border-emerald-500/30 backdrop-blur-sm">
            <div class="flex flex-col lg:flex-row items-center justify-between gap-8">
                <div class="flex items-center space-x-6">
                    <div class="w-20 h-20 bg-gradient-to-br from-emerald-500 to-cyan-500 rounded-3xl flex items-center justify-center border border-emerald-400/30 shadow-2xl">
                        <i class="fas fa-check-circle text-white text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-black text-white mb-2">Pembayaran Dikirim!</h3>
                        <p class="text-emerald-300 text-xl">Transfer berhasil diproses</p>
                    </div>
                </div>
                
                <div class="bg-black/50 rounded-2xl p-6 border border-white/10">
                    <p class="text-gray-400 text-sm mb-2">Virtual Account</p>
                    <div class="flex items-center space-x-4">
                        <code class="text-2xl font-black text-white bg-black/70 px-4 py-3 rounded-xl">{{ $vaInfo['va_number'] }}</code>
                        <button onclick="copyToClipboard('{{ $vaInfo['va_number'] }}')" 
                                class="bg-white/10 hover:bg-white/20 border border-white/20 rounded-2xl px-4 py-3 text-white transition">
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>
                    <p class="text-gray-400 text-sm mt-3">Bank: <span class="text-white font-bold">{{ $vaInfo['bank'] }}</span></p>
                </div>
            </div>
        </div>
        @endif

        <div class="grid lg:grid-cols-2 gap-8">
            <div>
                <div class="bg-gradient-to-br from-gray-800/40 to-gray-900/40 rounded-3xl p-8 border border-emerald-500/20 backdrop-blur-sm h-full">
                    <h2 class="text-2xl font-black text-white mb-8 flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-500 rounded-2xl flex items-center justify-center border border-purple-400/30">
                            <i class="fas fa-history text-white"></i>
                        </div>
                        <span>Timeline Status</span>
                    </h2>
                    
      
                    <div class="space-y-8">
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-cyan-500 rounded-2xl flex items-center justify-center border border-emerald-400/30 flex-shrink-0">
                                <i class="fas fa-calendar-plus text-white"></i>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-black text-white mb-1">Booking Dibuat</h3>
                                <p class="text-gray-400">{{ $booking->created_at->format('d M Y, H:i') }}</p>
                                <p class="text-gray-300 mt-2">Booking berhasil dibuat dengan status pending</p>
                            </div>
                            <div class="w-8 h-8 bg-emerald-500 rounded-full flex items-center justify-center border border-emerald-400/30">
                                <i class="fas fa-check text-white text-xs"></i>
                            </div>
                        </div>
                        
                        @if($booking->paid_at)
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-500 rounded-2xl flex items-center justify-center border border-blue-400/30 flex-shrink-0">
                                <i class="fas fa-credit-card text-white"></i>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-black text-white mb-1">Pembayaran Dikirim</h3>
                                <p class="text-gray-400">{{ $booking->paid_at->format('d M Y, H:i') }}</p>
                                <p class="text-gray-300 mt-2">Transfer via {{ $booking->bank_name }} berhasil dikirim</p>
                                @if($booking->virtual_account)
                                <p class="text-sm text-blue-300 mt-1">VA: {{ $booking->virtual_account }}</p>
                                @endif
                            </div>
                            <div class="w-8 h-8 bg-emerald-500 rounded-full flex items-center justify-center border border-emerald-400/30">
                                <i class="fas fa-check text-white text-xs"></i>
                            </div>
                        </div>
                        @endif
                        
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-gradient-to-br {{ $booking->payment_status == 'pending_verification' ? 'from-yellow-500 to-orange-500' : 'from-emerald-500 to-cyan-500' }} rounded-2xl flex items-center justify-center border {{ $booking->payment_status == 'pending_verification' ? 'border-yellow-400/30' : 'border-emerald-400/30' }} flex-shrink-0">
                                <i class="fas {{ $booking->payment_status == 'pending_verification' ? 'fa-hourglass-half' : 'fa-check-circle' }} text-white"></i>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-black text-white mb-1">
                                    {{ $booking->payment_status == 'pending_verification' ? 'Menunggu Verifikasi' : 'Terverifikasi' }}
                                </h3>
                                @if($booking->verified_at)
                                <p class="text-gray-400">{{ $booking->verified_at->format('d M Y, H:i') }}</p>
                                <p class="text-gray-300 mt-2">Diverifikasi oleh admin</p>
                                @if($booking->verifier)
                                <p class="text-sm text-emerald-300 mt-1">Oleh: {{ $booking->verifier->name }}</p>
                                @endif
                                @else
                                <p class="text-gray-400">-</p>
                                <p class="text-yellow-300 mt-2">Menunggu verifikasi admin (1x24 jam)</p>
                                @endif
                            </div>
                            @if($booking->payment_status == 'pending_verification')
                            <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center border border-yellow-400/30">
                                <i class="fas fa-clock text-white text-xs"></i>
                            </div>
                            @else
                            <div class="w-8 h-8 bg-emerald-500 rounded-full flex items-center justify-center border border-emerald-400/30">
                                <i class="fas fa-check text-white text-xs"></i>
                            </div>
                            @endif
                        </div>
                        
                        @if($booking->payment_status == 'rejected')
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-pink-500 rounded-2xl flex items-center justify-center border border-red-400/30 flex-shrink-0">
                                <i class="fas fa-times-circle text-white"></i>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-black text-white mb-1">Pembayaran Ditolak</h3>
                                @if($booking->rejected_at)
                                <p class="text-gray-400">{{ $booking->rejected_at->format('d M Y, H:i') }}</p>
                                @endif
                                @if($booking->rejection_reason)
                                <p class="text-red-300 mt-2">{{ $booking->rejection_reason }}</p>
                                @endif
                            </div>
                            <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center border border-red-400/30">
                                <i class="fas fa-times text-white text-xs"></i>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div>
                <div class="bg-gradient-to-br from-gray-800/40 to-gray-900/40 rounded-3xl p-8 border border-emerald-500/20 backdrop-blur-sm h-full">
                    <h2 class="text-2xl font-black text-white mb-8 flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-cyan-500 to-blue-500 rounded-2xl flex items-center justify-center border border-cyan-400/30">
                            <i class="fas fa-info-circle text-white"></i>
                        </div>
                        <span>Detail Booking</span>
                    </h2>
                    
                    <!-- Booking Info -->
                    <div class="space-y-6 mb-8">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-400">ID Booking</span>
                            <span class="text-xl font-black text-white">#{{ $booking->id }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-400">Lapangan</span>
                            <span class="text-xl font-bold text-white">{{ $booking->lapangan->nama_lapangan }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-400">Tanggal</span>
                            <span class="text-xl font-bold text-white">{{ $booking->formatted_date }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-400">Waktu</span>
                            <span class="text-xl font-bold text-emerald-300">{{ $booking->formatted_time }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-400">Durasi</span>
                            <span class="text-xl font-bold text-white">{{ $booking->duration }} jam</span>
                        </div>
                        <div class="flex justify-between items-center pt-6 border-t border-gray-700/50">
                            <span class="text-xl font-black text-white">Total Pembayaran</span>
                            <span class="text-3xl font-black text-emerald-300">
                                Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 mb-8">
                        <div class="bg-white/5 rounded-2xl p-4 border border-white/10 text-center">
                            <p class="text-gray-400 text-sm mb-1">Status Booking</p>
                            <p class="text-lg font-bold 
                                @if($booking->status == 'pending') text-yellow-400
                                @elseif($booking->status == 'confirmed') text-emerald-400
                                @elseif($booking->status == 'cancelled') text-red-400
                                @else text-white @endif">
                                {{ strtoupper($booking->status) }}
                            </p>
                        </div>
                        <div class="bg-white/5 rounded-2xl p-4 border border-white/10 text-center">
                            <p class="text-gray-400 text-sm mb-1">Status Pembayaran</p>
                            <p class="text-lg font-bold 
                                @if($booking->payment_status == 'pending') text-yellow-400
                                @elseif($booking->payment_status == 'pending_verification') text-yellow-400
                                @elseif($booking->payment_status == 'paid') text-emerald-400
                                @elseif($booking->payment_status == 'rejected') text-red-400
                                @else text-white @endif">
                                {{ strtoupper($booking->payment_status) }}
                            </p>
                        </div>
                    </div>
                    
                    <!-- Actions -->
                    <div class="space-y-4">
                        @if($booking->payment_status == 'pending_verification')
                        <form action="{{ route('payment.cancel', $booking->id) }}" method="POST" 
                              onsubmit="return confirm('Batalkan pembayaran?')">
                            @csrf
                            <button type="submit" 
                                    class="w-full bg-gradient-to-r from-red-600 to-pink-600 hover:from-red-500 hover:to-pink-500 text-white font-bold py-4 px-8 rounded-2xl transition-all duration-300 transform hover:-translate-y-1 border border-red-500/30 flex items-center justify-center space-x-3">
                                <i class="fas fa-times"></i>
                                <span>BATALKAN PEMBAYARAN</span>
                            </button>
                        </form>
                        @endif
                        
                        @if($booking->payment_status == 'paid')
                        <button onclick="window.print()" 
                                class="w-full bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-500 hover:to-blue-500 text-white font-bold py-4 px-8 rounded-2xl transition-all duration-300 transform hover:-translate-y-1 border border-cyan-500/30 flex items-center justify-center space-x-3">
                            <i class="fas fa-print"></i>
                            <span>CETAK BUKTI</span>
                        </button>
                        @endif
                        
                        <a href="{{ route('booking.my-bookings') }}" 
                           class="w-full bg-gradient-to-r from-gray-700 to-gray-800 hover:from-gray-600 hover:to-gray-700 text-white font-bold py-4 px-8 rounded-2xl transition-all duration-300 transform hover:-translate-y-1 border border-gray-600/30 flex items-center justify-center space-x-3">
                            <i class="fas fa-list"></i>
                            <span>LIHAT SEMUA BOOKING</span>
                        </a>
                        
                        @if($booking->payment_status == 'rejected')
                        <a href="{{ route('payment.form', $booking->id) }}" 
                           class="w-full bg-gradient-to-r from-emerald-600 to-cyan-600 hover:from-emerald-500 hover:to-cyan-500 text-white font-bold py-4 px-8 rounded-2xl transition-all duration-300 transform hover:-translate-y-1 border border-emerald-500/30 flex items-center justify-center space-x-3">
                            <i class="fas fa-credit-card"></i>
                            <span>BAYAR ULANG</span>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
    // notif
        const toast = document.createElement('div');
        toast.className = 'fixed bottom-4 right-4 bg-emerald-500 text-white px-6 py-3 rounded-2xl shadow-2xl border border-emerald-400/30 z-50 animate-fade-in-up';
        toast.innerHTML = '<i class="fas fa-check-circle mr-2"></i>VA Number disalin!';
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.remove();
        }, 2000);
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