@extends('layout')

@section('title', 'Pembayaran Berhasil - Booking #' . $booking->id)

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-950 to-black py-8">
    <div class="max-w-4xl mx-auto px-4">
        <!-- Header Success -->
        <div class="text-center mb-12">
            <div class="inline-flex items-center justify-center w-24 h-24 bg-gradient-to-br from-emerald-500 to-cyan-500 rounded-full border-4 border-emerald-400/30 mb-6 mx-auto animate-pulse">
                <i class="fas fa-check-circle text-white text-4xl"></i>
            </div>
            
            <div class="inline-flex items-center space-x-3 bg-emerald-500/10 backdrop-blur-xl border border-emerald-500/20 rounded-2xl px-6 py-3 mb-6">
                <div class="w-2 h-2 bg-emerald-400 rounded-full"></div>
                <span class="text-sm font-bold tracking-wider text-emerald-200">PAYMENT SUCCESSFUL</span>
                <div class="w-2 h-2 bg-emerald-400 rounded-full"></div>
            </div>
            
            <h1 class="text-4xl lg:text-5xl font-black text-white mb-4 leading-tight">
                Payment <span class="bg-gradient-to-r from-emerald-300 to-cyan-400 bg-clip-text text-transparent">Completed</span> ✅
            </h1>
            <p class="text-xl text-gray-400 max-w-2xl mx-auto">Pembayaran Anda telah berhasil diproses dan sedang menunggu verifikasi admin</p>
        </div>

        <!-- Status Timeline -->
        <div class="bg-gradient-to-br from-gray-800/40 to-gray-900/40 rounded-3xl p-8 border border-emerald-500/20 backdrop-blur-sm mb-8">
            <h2 class="text-2xl font-black text-white mb-8 flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-500 rounded-2xl flex items-center justify-center border border-purple-400/30">
                    <i class="fas fa-tasks text-white"></i>
                </div>
                <span>Status Timeline</span>
            </h2>
            
            <div class="relative">
                <!-- Timeline Line -->
                <div class="absolute left-6 top-0 bottom-0 w-0.5 bg-gray-700"></div>
                
                <!-- Steps -->
                <div class="space-y-8 relative">
                    <!-- Step 1: Completed -->
                    <div class="flex items-start space-x-6">
                        <div class="relative">
                            <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-cyan-500 rounded-2xl flex items-center justify-center border-2 border-emerald-400 z-10 relative">
                                <i class="fas fa-check text-white"></i>
                            </div>
                            <div class="absolute inset-0 bg-emerald-500/20 rounded-2xl blur-lg"></div>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-xl font-black text-white mb-2">Booking Created</h3>
                            <p class="text-gray-400">Booking Anda telah berhasil dibuat</p>
                            <p class="text-emerald-300 text-sm mt-1">
                                {{ \Carbon\Carbon::parse($booking->created_at)->format('d M Y, H:i') }}
                            </p>
                        </div>
                    </div>
                    
                    <!-- Step 2: Completed -->
                    <div class="flex items-start space-x-6">
                        <div class="relative">
                            <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-cyan-500 rounded-2xl flex items-center justify-center border-2 border-emerald-400 z-10 relative">
                                <i class="fas fa-credit-card text-white"></i>
                            </div>
                            <div class="absolute inset-0 bg-emerald-500/20 rounded-2xl blur-lg"></div>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-xl font-black text-white mb-2">Payment Processed</h3>
                            <p class="text-gray-400">Pembayaran telah berhasil diproses</p>
                            <p class="text-emerald-300 text-sm mt-1">
                                {{ $paymentData['date'] ?? now()->format('d M Y, H:i') }}
                            </p>
                        </div>
                    </div>
                    
                    <!-- Step 3: Current (Pending Verification) -->
                    <div class="flex items-start space-x-6">
                        <div class="relative">
                            <div class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-orange-500 rounded-2xl flex items-center justify-center border-2 border-yellow-400 z-10 relative animate-pulse">
                                <i class="fas fa-user-shield text-white"></i>
                            </div>
                            <div class="absolute inset-0 bg-yellow-500/20 rounded-2xl blur-lg animate-pulse"></div>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-xl font-black text-white mb-2">Admin Verification</h3>
                            <p class="text-gray-400">Menunggu verifikasi dari admin sistem</p>
                            <p class="text-yellow-300 text-sm mt-1">Estimated: 15 minutes</p>
                        </div>
                    </div>
                    
                    <!-- Step 4: Pending -->
                    <div class="flex items-start space-x-6">
                        <div class="relative">
                            <div class="w-12 h-12 bg-gray-700 rounded-2xl flex items-center justify-center border-2 border-gray-600 z-10 relative">
                                <i class="fas fa-calendar-check text-gray-400"></i>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-xl font-black text-gray-400 mb-2">Booking Confirmed</h3>
                            <p class="text-gray-500">Booking akan aktif setelah verifikasi</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid lg:grid-cols-2 gap-8">
            <!-- Left Column: Booking Details -->
            <div>
                <div class="bg-gradient-to-br from-gray-800/40 to-gray-900/40 rounded-3xl p-8 border border-blue-500/20 backdrop-blur-sm h-full">
                    <h2 class="text-2xl font-black text-white mb-8 flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-2xl flex items-center justify-center border border-blue-400/30">
                            <i class="fas fa-calendar-alt text-white"></i>
                        </div>
                        <span>Detail Booking</span>
                    </h2>
                    
                    <div class="space-y-6">
                        <div class="bg-white/5 rounded-2xl p-6 border border-white/10">
                            <div class="flex items-center space-x-4 mb-4">
                                <div class="w-14 h-14 bg-gradient-to-br from-blue-500/20 to-cyan-500/20 rounded-2xl flex items-center justify-center border border-blue-400/30">
                                    <i class="fas fa-hashtag text-blue-400 text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-gray-400 text-sm">Booking ID</p>
                                    <p class="text-3xl font-black text-white">#{{ $booking->id }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-white/5 rounded-2xl p-6 border border-white/10">
                            <p class="text-gray-400 mb-2">Lapangan</p>
                            <p class="text-2xl font-black text-white flex items-center">
                                <i class="fas fa-map-marker-alt text-emerald-400 mr-3"></i>
                                {{ $booking->lapangan->name ?? 'Lapangan' }}
                            </p>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-white/5 rounded-2xl p-6 border border-white/10">
                                <p class="text-gray-400 mb-2">Tanggal</p>
                                <p class="text-xl font-bold text-white">
                                    {{ \Carbon\Carbon::parse($booking->tanggal_booking)->format('d M Y') }}
                                </p>
                            </div>
                            
                            <div class="bg-white/5 rounded-2xl p-6 border border-white/10">
                                <p class="text-gray-400 mb-2">Waktu</p>
                                <p class="text-xl font-bold text-cyan-300">
                                    {{ $booking->jam_mulai }} - {{ $booking->jam_selesai }}
                                </p>
                            </div>
                        </div>
                        
                        <div class="bg-white/5 rounded-2xl p-6 border border-white/10">
                            <div class="flex justify-between items-center mb-4">
                                <div>
                                    <p class="text-gray-400">Durasi</p>
                                    <p class="text-2xl font-black text-white">{{ $booking->duration }} jam</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-gray-400">Total</p>
                                    <p class="text-3xl font-black text-emerald-300">
                                        Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Payment Details -->
            <div>
                <div class="bg-gradient-to-br from-gray-800/40 to-gray-900/40 rounded-3xl p-8 border border-purple-500/20 backdrop-blur-sm h-full">
                    <h2 class="text-2xl font-black text-white mb-8 flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-500 rounded-2xl flex items-center justify-center border border-purple-400/30">
                            <i class="fas fa-credit-card text-white"></i>
                        </div>
                        <span>Detail Pembayaran</span>
                    </h2>
                    
                    <div class="space-y-6">
                        <!-- Status Badge -->
                        <div class="bg-gradient-to-r from-yellow-500/10 to-orange-500/10 rounded-2xl p-6 border border-yellow-500/30">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <div class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-orange-500 rounded-2xl flex items-center justify-center border border-yellow-400/30 animate-pulse">
                                        <i class="fas fa-clock text-white"></i>
                                    </div>
                                    <div>
                                        <p class="text-gray-300 text-sm">Status Pembayaran</p>
                                        <p class="text-2xl font-black text-yellow-300">Pending Verification</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Payment Method -->
                        <div class="bg-white/5 rounded-2xl p-6 border border-white/10">
                            <p class="text-gray-400 mb-4">Metode Pembayaran</p>
                            <div class="flex items-center space-x-4">
                                <div class="w-16 h-16 bg-gradient-to-br from-blue-500/20 to-purple-500/20 rounded-2xl flex items-center justify-center border border-blue-400/30">
                                    <i class="fas fa-university text-blue-400 text-2xl"></i>
                                </div>
                                <div>
                                    <p class="text-xl font-black text-white">{{ $paymentData['bank'] ?? 'BCA' }}</p>
                                    <p class="text-gray-400">Virtual Account</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Virtual Account -->
                        <div class="bg-gradient-to-r from-emerald-500/10 to-cyan-500/10 rounded-2xl p-6 border border-emerald-500/30">
                            <p class="text-gray-300 mb-2">Virtual Account Number</p>
                            <div class="flex items-center justify-between">
                                <code class="text-2xl font-black tracking-wider text-white bg-black/50 px-4 py-3 rounded-xl">
                                    {{ $paymentData['va_number'] ?? '888' . str_pad($booking->id, 12, '0', STR_PAD_LEFT) }}
                                </code>
                                <button onclick="copyVA()" class="bg-emerald-500/20 hover:bg-emerald-500/30 border border-emerald-500/30 text-emerald-300 px-4 py-2 rounded-xl transition-colors">
                                    <i class="fas fa-copy mr-2"></i>Copy
                                </button>
                            </div>
                        </div>
                        
                        <!-- PIN -->
                        <div class="bg-white/5 rounded-2xl p-6 border border-white/10">
                            <p class="text-gray-400 mb-2">Security PIN</p>
                            <div class="flex items-center space-x-4">
                                <div class="flex space-x-2">
                                    @for($i = 0; $i < 6; $i++)
                                    <div class="w-12 h-12 bg-gradient-to-br from-red-500/20 to-pink-500/20 rounded-xl flex items-center justify-center border border-red-400/30">
                                        <span class="text-white font-bold text-xl">
                                            {{ str_split($paymentData['pin'] ?? '123456')[$i] ?? '●' }}
                                        </span>
                                    </div>
                                    @endfor
                                </div>
                            </div>
                            <p class="text-gray-400 text-sm mt-4">
                                <i class="fas fa-info-circle text-yellow-400 mr-2"></i>
                                Simpan PIN ini untuk keperluan verifikasi
                            </p>
                        </div>
                        
                        <!-- Payment Info -->
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-white/5 rounded-2xl p-4 border border-white/10">
                                <p class="text-gray-400 text-sm">Waktu Bayar</p>
                                <p class="text-white font-bold">{{ $paymentData['date'] ?? now()->format('d/m/Y H:i:s') }}</p>
                            </div>
                            
                            <div class="bg-white/5 rounded-2xl p-4 border border-white/10">
                                <p class="text-gray-400 text-sm">Batas Verifikasi</p>
                                <p class="text-yellow-300 font-bold">15 menit</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Instructions & Actions -->
        <div class="mt-8">
            <div class="bg-gradient-to-br from-gray-800/40 to-gray-900/40 rounded-3xl p-8 border border-yellow-500/20 backdrop-blur-sm">
                <div class="grid lg:grid-cols-3 gap-8">
                    <!-- Instructions -->
                    <div class="lg:col-span-2">
                        <h2 class="text-2xl font-black text-white mb-6 flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-yellow-500 to-orange-500 rounded-2xl flex items-center justify-center border border-yellow-400/30">
                                <i class="fas fa-info-circle text-white"></i>
                            </div>
                            <span>Instruksi Selanjutnya</span>
                        </h2>
                        
                        <div class="space-y-4">
                            <div class="flex items-start space-x-4 p-4 bg-white/5 rounded-2xl border border-white/10">
                                <div class="w-8 h-8 bg-yellow-500/20 rounded-xl flex items-center justify-center border border-yellow-400/30 flex-shrink-0 mt-1">
                                    <span class="text-yellow-400 font-bold">1</span>
                                </div>
                                <div>
                                    <p class="text-white font-bold mb-1">Tunggu Verifikasi Admin</p>
                                    <p class="text-gray-400">Admin akan memverifikasi pembayaran Anda dalam waktu 15 menit</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start space-x-4 p-4 bg-white/5 rounded-2xl border border-white/10">
                                <div class="w-8 h-8 bg-yellow-500/20 rounded-xl flex items-center justify-center border border-yellow-400/30 flex-shrink-0 mt-1">
                                    <span class="text-yellow-400 font-bold">2</span>
                                </div>
                                <div>
                                    <p class="text-white font-bold mb-1">Cek Status Booking</p>
                                    <p class="text-gray-400">Anda dapat memantau status booking di halaman "Booking Saya"</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start space-x-4 p-4 bg-white/5 rounded-2xl border border-white/10">
                                <div class="w-8 h-8 bg-yellow-500/20 rounded-xl flex items-center justify-center border border-yellow-400/30 flex-shrink-0 mt-1">
                                    <span class="text-yellow-400 font-bold">3</span>
                                </div>
                                <div>
                                    <p class="text-white font-bold mb-1">Notifikasi Email</p>
                                    <p class="text-gray-400">Anda akan menerima email konfirmasi setelah booking diverifikasi</p>
                                </div>
                            </div>
                            
                            <div class="bg-gradient-to-r from-emerald-500/10 to-cyan-500/10 rounded-2xl p-6 border border-emerald-500/30 mt-6">
                                <div class="flex items-center space-x-4">
                                    <i class="fas fa-shield-alt text-emerald-400 text-2xl"></i>
                                    <div>
                                        <p class="text-emerald-300 font-bold">Pembayaran Aman & Terjamin</p>
                                        <p class="text-gray-400 text-sm">Sistem kami menggunakan enkripsi SSL untuk melindungi data pembayaran Anda</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div>
                        <div class="sticky top-8">
                            <div class="bg-gradient-to-br from-blue-500/10 to-purple-500/10 rounded-3xl p-6 border border-blue-500/20">
                                <h3 class="text-xl font-black text-white mb-6">Aksi Cepat</h3>
                                
                                <div class="space-y-4">
                                    <a href="{{ route('booking.my-bookings') }}" 
                                       class="block w-full bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-500 hover:to-purple-500 text-white font-bold py-4 px-6 rounded-2xl transition-all duration-300 transform hover:-translate-y-1 border border-blue-500/30 flex items-center justify-center space-x-3 group">
                                        <i class="fas fa-list-alt group-hover:scale-110 transition-transform"></i>
                                        <span>Booking Saya</span>
                                        <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                                    </a>
                                    
                                    <a href="{{ route('booking.index') }}" 
                                       class="block w-full bg-gradient-to-r from-emerald-600 to-cyan-600 hover:from-emerald-500 hover:to-cyan-500 text-white font-bold py-4 px-6 rounded-2xl transition-all duration-300 transform hover:-translate-y-1 border border-emerald-500/30 flex items-center justify-center space-x-3 group">
                                        <i class="fas fa-plus-circle group-hover:scale-110 transition-transform"></i>
                                        <span>Booking Baru</span>
                                        <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                                    </a>
                                    
                                    <a href="{{ route('booking.show', $booking->id) }}" 
                                       class="block w-full bg-gradient-to-r from-gray-700 to-gray-800 hover:from-gray-600 hover:to-gray-700 text-white font-bold py-4 px-6 rounded-2xl transition-all duration-300 transform hover:-translate-y-1 border border-gray-600/30 flex items-center justify-center space-x-3 group">
                                        <i class="fas fa-eye group-hover:scale-110 transition-transform"></i>
                                        <span>Detail Booking</span>
                                        <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                                    </a>
                                    
                                    <button onclick="downloadReceipt()" 
                                            class="block w-full bg-gradient-to-r from-yellow-600 to-orange-600 hover:from-yellow-500 hover:to-orange-500 text-white font-bold py-4 px-6 rounded-2xl transition-all duration-300 transform hover:-translate-y-1 border border-yellow-500/30 flex items-center justify-center space-x-3 group">
                                        <i class="fas fa-download group-hover:scale-110 transition-transform"></i>
                                        <span>Download Receipt</span>
                                        <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                                    </button>
                                </div>
                                
                                <!-- QR Code -->
                                <div class="mt-8 pt-6 border-t border-gray-700">
                                    <p class="text-gray-400 text-sm mb-4 text-center">Scan untuk bukti pembayaran</p>
                                    <div class="flex justify-center">
                                        <div class="bg-white p-4 rounded-2xl inline-block border-2 border-emerald-500/30">
                                            <div class="w-40 h-40 bg-gradient-to-br from-gray-100 to-gray-200 rounded-xl flex items-center justify-center border border-gray-300">
                                                <div class="text-center">
                                                    <div class="text-5xl mb-2">💰</div>
                                                    <div class="text-xs text-gray-600 font-bold">PAYMENT RECEIPT</div>
                                                    <div class="text-xs text-gray-500">#{{ $booking->id }}</div>
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
    </div>
</div>

<script>
function copyVA() {
    const vaNumber = '{{ $paymentData['va_number'] ?? '888' . str_pad($booking->id, 12, '0', STR_PAD_LEFT) }}';
    navigator.clipboard.writeText(vaNumber).then(() => {
        // Show toast notification
        const toast = document.createElement('div');
        toast.className = 'fixed top-4 right-4 bg-emerald-500 text-white px-6 py-3 rounded-2xl shadow-lg z-50 animate-fade-in';
        toast.innerHTML = `
            <div class="flex items-center space-x-3">
                <i class="fas fa-check-circle"></i>
                <span>Virtual Account copied to clipboard!</span>
            </div>
        `;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.remove();
        }, 3000);
    });
}

function downloadReceipt() {
    // Show loading
    const btn = event.currentTarget;
    const originalHTML = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Generating Receipt...';
    btn.disabled = true;
    
    // Simulate download
    setTimeout(() => {
        alert('Receipt downloaded! (Demo mode)');
        btn.innerHTML = originalHTML;
        btn.disabled = false;
    }, 1500);
}

// Add fade-in animation
const style = document.createElement('style');
style.textContent = `
    @keyframes fade-in {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fade-in 0.3s ease-out;
    }
`;
document.head.appendChild(style);
</script>

<style>
.sticky {
    position: sticky;
    top: 2rem;
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