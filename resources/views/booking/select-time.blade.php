@extends('layout')

@section('title', 'Pilih Waktu Booking - ' . $field->name)

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-950 to-black py-8">
    <div class="max-w-7xl mx-auto px-4">
        <!-- Timeline Header -->
        <div class="text-center mb-12">
            <div class="flex justify-center items-center space-x-4 mb-6">
                <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 bg-emerald-400 rounded-full"></div>
                    <span class="text-emerald-300 text-sm font-semibold">LANGKAH 1</span>
                </div>
                <div class="w-8 h-0.5 bg-emerald-400/30"></div>
                <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 bg-emerald-400 rounded-full animate-pulse"></div>
                    <span class="text-emerald-300 text-sm font-semibold">LANGKAH 2</span>
                </div>
                <div class="w-8 h-0.5 bg-gray-600"></div>
                <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 bg-gray-600 rounded-full"></div>
                    <span class="text-gray-500 text-sm font-semibold">LANGKAH 3</span>
                </div>
            </div>
            
            <h1 class="text-4xl lg:text-5xl font-black text-white mb-4">
                Jadwalkan <span class="bg-gradient-to-r from-emerald-300 to-cyan-400 bg-clip-text text-transparent">Sesi Anda</span>
            </h1>
            <p class="text-xl text-gray-400">Pilih jam bermain yang Anda inginkan untuk pengalaman terbaik</p>
        </div>

        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Field Overview Card -->
            <div class="lg:w-1/3">
                <div class="sticky top-8 space-y-6">
                    <!-- Field Card -->
                    <div class="bg-gradient-to-br from-gray-800/40 to-gray-900/40 rounded-3xl p-6 border border-emerald-500/20 backdrop-blur-sm">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center space-x-4">
                                <div class="w-14 h-14 bg-gradient-to-br from-emerald-500 to-cyan-500 rounded-2xl flex items-center justify-center border border-emerald-400/30">
                                    <i class="fas 
                                        @if($field->type == 'futsal') fa-futbol
                                        @elseif($field->type == 'badminton') fa-table-tennis-paddle-ball
                                        @else fa-futbol @endif
                                        text-white text-lg">
                                    </i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-black text-white">{{ $field->name }}</h3>
                                    <p class="text-emerald-300 text-sm font-semibold capitalize">{{ $field->type }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-2xl font-black text-emerald-300">
                                    Rp {{ number_format($field->price_per_hour, 0, ',', '.') }}
                                </div>
                                <div class="text-gray-400 text-sm">per jam</div>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4 pt-4 border-t border-gray-700/50">
                            <div class="text-center">
                                <div class="text-gray-400 text-sm mb-1">Kapasitas</div>
                                <div class="text-white font-semibold">{{ $field->capacity ?? 'Standar' }}</div>
                            </div>
                            <div class="text-center">
                                <div class="text-gray-400 text-sm mb-1">Ukuran</div>
                                <div class="text-white font-semibold">{{ $field->size ?? 'Standar' }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Date Selector -->
                    <div class="bg-gradient-to-br from-gray-800/40 to-gray-900/40 rounded-3xl p-6 border border-cyan-500/20 backdrop-blur-sm">
                        <div class="flex items-center space-x-3 mb-4">
                            <div class="w-10 h-10 bg-gradient-to-br from-cyan-500 to-emerald-500 rounded-2xl flex items-center justify-center">
                                <i class="fas fa-calendar text-white text-sm"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-black text-white">Tanggal Dipilih</h3>
                                <p class="text-cyan-300 font-semibold text-sm">
                                    {{ \Carbon\Carbon::parse($selectedDate)->translatedFormat('d M Y') }}
                                </p>
                            </div>
                        </div>

                        <form action="{{ route('booking.select-time', $field->id) }}" method="GET" class="space-y-4">
                            <div>
                                <label class="text-gray-400 text-sm font-semibold mb-2 block">Pilih tanggal lain:</label>
                                <input type="date" name="date" value="{{ $selectedDate }}" 
                                       min="{{ now()->format('Y-m-d') }}" 
                                       class="w-full bg-gray-700/50 border-2 border-gray-600 rounded-2xl px-4 py-3 text-white font-semibold focus:outline-none focus:border-cyan-400 transition">
                            </div>
                            <button type="submit" class="w-full bg-gradient-to-r from-cyan-600 to-emerald-600 hover:from-cyan-500 hover:to-emerald-500 text-white font-bold py-3 rounded-2xl transition-all duration-300 transform hover:-translate-y-1 border border-cyan-500/30 flex items-center justify-center space-x-2">
                                <i class="fas fa-redo"></i>
                                <span>Ganti Tanggal</span>
                            </button>
                        </form>
                    </div>

                    <!-- Availability Meter -->
                    <div class="bg-gradient-to-br from-gray-800/40 to-gray-900/40 rounded-3xl p-6 border border-emerald-500/20 backdrop-blur-sm">
                        <h3 class="text-lg font-black text-white mb-4">Status Ketersediaan</h3>
                        <div class="space-y-4">
                            @php
                                $totalSlots = count($availableSlots['all']);
                                $availableCount = count($availableSlots['available']);
                                $bookedCount = count($availableSlots['booked']);
                                $expiredCount = count($availableSlots['expired']);
                                $availabilityPercent = $totalSlots > 0 ? ($availableCount / $totalSlots) * 100 : 0;
                                
                                $nowWIB = \Carbon\Carbon::now()->timezone('Asia/Jakarta');
                                $isToday = \Carbon\Carbon::parse($selectedDate)->isToday();
                            @endphp
                            
                            <!-- Progress Bar -->
                            <div class="space-y-2">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-400">Ketersediaan Lapangan</span>
                                    <span class="text-emerald-300 font-semibold">{{ number_format($availabilityPercent, 0) }}%</span>
                                </div>
                                <div class="w-full bg-gray-700 rounded-full h-2">
                                    <div class="bg-gradient-to-r from-emerald-500 to-cyan-500 h-2 rounded-full transition-all duration-1000" 
                                         style="width: {{ $availabilityPercent }}%"></div>
                                </div>
                            </div>

                            <!-- Stats -->
                            <div class="grid grid-cols-3 gap-4 text-center">
                                <div>
                                    <div class="text-2xl font-black text-emerald-300">{{ $availableCount }}</div>
                                    <div class="text-gray-400 text-xs">Tersedia</div>
                                </div>
                                <div>
                                    <div class="text-2xl font-black text-red-300">{{ $bookedCount }}</div>
                                    <div class="text-gray-400 text-xs">Dipesan</div>
                                </div>
                                <div>
                                    <div class="text-2xl font-black text-gray-400">{{ $expiredCount }}</div>
                                    <div class="text-gray-400 text-xs">Kadaluarsa</div>
                                </div>
                            </div>
                            
                            <!-- Info -->
                            <div class="pt-4 border-t border-gray-700/50">
                                <div class="text-xs text-gray-500 space-y-1">
                                    <div class="flex items-center">
                                        <i class="fas fa-clock mr-2"></i>
                                        <span>Waktu WIB: <span id="currentTimeWIB">{{ $nowWIB->format('H:i:s') }}</span></span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-calendar mr-2"></i>
                                        <span>Status: {{ $isToday ? 'HARI INI' : 'BUKAN HARI INI' }}</span>
                                    </div>
                                    @if($isToday)
                                    <div class="text-amber-400 text-xs mt-2">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                        Slot yang sudah lewat ditandai <span class="text-gray-400">"Kadaluarsa"</span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Time Selection Area -->
            <div class="lg:w-2/3">
                @if(count($availableSlots['available']) > 0)
                    <!-- Time Slots Grid -->
                    <div class="bg-gradient-to-br from-gray-800/40 to-gray-900/40 rounded-3xl p-8 border border-emerald-500/20 backdrop-blur-sm">
                        <!-- Time Slots Header -->
                        <div class="flex items-center justify-between mb-8">
                            <div>
                                <h2 class="text-2xl font-black text-white">Pilihan Jam Tersedia</h2>
                                <p class="text-gray-400">Klik pada jam untuk melanjutkan booking</p>
                            </div>
                            <div class="text-right">
                                <div class="text-sm text-gray-400">Menampilkan</div>
                                <div class="text-xl font-black text-emerald-300">{{ $availableCount }} slot</div>
                            </div>
                        </div>

                        <!-- Time Slots Grid - LOGIKA SIMPLE -->
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                            @foreach($availableSlots['all'] as $timeSlot)
                                @php
                                    // Tentukan status slot dari controller
                                    $isAvailable = in_array($timeSlot, $availableSlots['available']);
                                    $isBooked = in_array($timeSlot, $availableSlots['booked']);
                                    $isExpired = in_array($timeSlot, $availableSlots['expired']);
                                @endphp

                                @if($isAvailable)
                                    <a href="{{ route('booking.confirm', $field->id) }}?date={{ $selectedDate }}&time={{ $timeSlot }}" 
                                       class="group relative bg-gradient-to-br from-emerald-500/10 to-cyan-500/10 rounded-2xl p-4 border-2 border-emerald-500/30 hover:border-emerald-400 transition-all duration-300 transform hover:scale-105 hover:shadow-2xl"
                                       title="Tersedia - Klik untuk booking">
                                @else
                                    <div class="relative bg-gradient-to-br 
                                        @if($isBooked) from-red-500/10 to-red-600/10 border-red-500/30
                                        @elseif($isExpired) from-gray-600/10 to-gray-700/10 border-gray-500/30
                                        @else from-gray-500/10 to-gray-600/10 border-gray-400/30 @endif
                                        rounded-2xl p-4 border-2 opacity-70 cursor-not-allowed"
                                        title="@if($isBooked) Sudah dipesan @elseif($isExpired) Waktu sudah lewat @endif">
                                @endif
                                
                                    <!-- Time -->
                                    <div class="text-center mb-2">
                                        <div class="text-lg font-black @if($isAvailable) text-white group-hover:text-emerald-300 transition-colors @elseif($isBooked) text-red-200 @else text-gray-400 @endif">
                                            {{ $timeSlot }}
                                        </div>
                                        <div class="text-xs @if($isAvailable) text-gray-400 @elseif($isBooked) text-red-300/70 @else text-gray-500 @endif">
                                            1 jam
                                            @if($isExpired)
                                                <div class="text-xs text-gray-500 mt-1">
                                                    <i class="fas fa-clock mr-1"></i>Lewat
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <!-- Status Indicator -->
                                    <div class="absolute top-2 right-2">
                                        @if($isAvailable)
                                            <div class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></div>
                                        @elseif($isBooked)
                                            <div class="w-2 h-2 bg-red-400 rounded-full"></div>
                                        @elseif($isExpired)
                                            <div class="w-2 h-2 bg-gray-400 rounded-full"></div>
                                        @endif
                                    </div>

                                    <!-- Price -->
                                    <div class="text-center">
                                        <div class="text-sm font-semibold 
                                            @if($isAvailable) text-emerald-300 
                                            @elseif($isBooked) text-red-300/70 
                                            @else text-gray-500 @endif">
                                            Rp {{ number_format($field->price_per_hour, 0, ',', '.') }}
                                        </div>
                                    </div>

                                    <!-- Hover Effect untuk slot tersedia -->
                                    @if($isAvailable)
                                        <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/5 to-cyan-500/5 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                                            <div class="text-emerald-300 text-sm font-semibold flex items-center space-x-1">
                                                <i class="fas fa-play text-xs"></i>
                                                <span>Pilih</span>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Badge untuk slot yang sudah dipesan -->
                                    @if($isBooked)
                                        <div class="absolute -top-2 -right-2 bg-gradient-to-r from-red-500 to-red-600 text-white text-xs px-2 py-1 rounded-full">
                                            <i class="fas fa-lock mr-1"></i>Booked
                                        </div>
                                    @endif

                                    <!-- Badge untuk slot kadaluarsa -->
                                    @if($isExpired)
                                        <div class="absolute -top-2 -right-2 bg-gradient-to-r from-gray-600 to-gray-700 text-gray-300 text-xs px-2 py-1 rounded-full">
                                            <i class="fas fa-clock mr-1"></i>Lewat
                                        </div>
                                    @endif

                                @if($isAvailable)
                                    </a>
                                @else
                                    </div>
                                @endif
                            @endforeach
                        </div>

                        <!-- Legend -->
                        <div class="mt-6 pt-6 border-t border-gray-700/50">
                            <div class="flex flex-wrap items-center justify-center gap-4 text-sm">
                                <div class="flex items-center space-x-2">
                                    <div class="w-3 h-3 bg-emerald-400 rounded-full animate-pulse"></div>
                                    <span class="text-gray-300">Tersedia</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <div class="w-3 h-3 bg-red-400 rounded-full"></div>
                                    <span class="text-gray-300">Dipesan</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <div class="w-3 h-3 bg-gray-400 rounded-full"></div>
                                    <span class="text-gray-300">Kadaluarsa</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <div class="w-3 h-3 bg-amber-400 rounded-full"></div>
                                    <span class="text-gray-300">WIB: <span id="currentTimeWIB2">{{ $nowWIB->format('H:i') }}</span></span>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Actions -->
                        <div class="mt-8 pt-6 border-t border-gray-700/50">
                            <div class="flex flex-col sm:flex-row gap-4 justify-between items-center">
                                <div class="text-gray-400 text-sm">
                                    Butuh jam lain? Hubungi kami untuk pengaturan khusus
                                </div>
                                <a href="{{ route('booking.select-field', $field->type) }}" 
                                   class="bg-gradient-to-r from-cyan-600 to-emerald-600 hover:from-cyan-500 hover:to-emerald-500 text-white px-6 py-3 rounded-2xl font-semibold transition-all duration-300 transform hover:-translate-y-1 border border-cyan-500/30 flex items-center space-x-2">
                                    <i class="fas fa-arrow-left"></i>
                                    <span>Kembali ke Lapangan</span>
                                </a>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- No Slots Available -->
                    <div class="bg-gradient-to-br from-gray-800/40 to-gray-900/40 rounded-3xl p-12 border border-emerald-500/20 backdrop-blur-sm text-center">
                        <div class="w-20 h-20 mx-auto mb-6 bg-gradient-to-br from-gray-700/40 to-gray-800/40 rounded-3xl flex items-center justify-center border border-gray-600/50">
                            <i class="fas fa-ban text-gray-400 text-3xl"></i>
                        </div>
                        <h3 class="text-2xl font-black text-white mb-4">Tidak Ada Slot Tersedia</h3>
                        <p class="text-gray-400 mb-8 max-w-md mx-auto leading-relaxed">
                            @if(count($availableSlots['expired']) > 0)
                                Semua slot waktu untuk tanggal {{ \Carbon\Carbon::parse($selectedDate)->translatedFormat('l, d F Y') }} sudah lewat.
                            @else
                                Semua jam pada tanggal {{ \Carbon\Carbon::parse($selectedDate)->translatedFormat('l, d F Y') }} telah dipesan.
                            @endif
                            Silakan pilih tanggal lain untuk menemukan jam yang tersedia.
                        </p>
                        <div class="flex flex-col sm:flex-row gap-4 justify-center">
                            <button onclick="history.back()" 
                                    class="bg-gray-700 hover:bg-gray-600 text-white px-6 py-3 rounded-2xl font-semibold transition-all duration-300 transform hover:-translate-y-1 border border-gray-600 flex items-center space-x-2">
                                <i class="fas fa-arrow-left"></i>
                                <span>Kembali</span>
                            </button>
                            <a href="{{ route('booking.select-field', $field->type) }}" 
                               class="bg-gradient-to-r from-emerald-600 to-cyan-600 hover:from-emerald-500 hover:to-cyan-500 text-white px-6 py-3 rounded-2xl font-semibold transition-all duration-300 transform hover:-translate-y-1 border border-emerald-500/30 flex items-center space-x-2">
                                <i class="fas fa-search"></i>
                                <span>Cari Lapangan Lain</span>
                            </a>
                        </div>
                    </div>
                @endif

                <!-- Tips Section -->
                <div class="mt-6 bg-gradient-to-br from-cyan-500/10 to-emerald-500/10 rounded-3xl p-6 border border-cyan-500/20 backdrop-blur-sm">
                    <div class="flex items-center space-x-3 mb-4">
                        <i class="fas fa-lightbulb text-cyan-400 text-xl"></i>
                        <h4 class="text-lg font-black text-white">Tips Booking</h4>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-clock text-emerald-400"></i>
                            <span class="text-gray-300">Booking lebih awal untuk jam favorit</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-users text-cyan-400"></i>
                            <span class="text-gray-300">Slot malam cepat penuh</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-calendar text-emerald-400"></i>
                            <span class="text-gray-300">Akhir pekan paling ramai</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-star text-cyan-400"></i>
                            <span class="text-gray-300">Jam pagi biasanya lebih murah</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* scroll tetap */
::-webkit-scrollbar {
    width: 6px;
}

::-webkit-scrollbar-track {
    background: #1f2937;
}

::-webkit-scrollbar-thumb {
    background: linear-gradient(to bottom, #10b981, #06b6d4);
    border-radius: 3px;
}

/* Transisi */
.transition-all {
    transition: all .3s cubic-bezier(.4,0,.2,1);
}

/* Animasi slot */
@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Animasi jam real-time */
@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.7; }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Update waktu WIB real-time
    function updateWIBTime() {
        const now = new Date();
        const options = { timeZone: 'Asia/Jakarta', hour12: false, hour: '2-digit', minute: '2-digit', second: '2-digit' };
        const timeString = now.toLocaleTimeString('id-ID', options);
        
        // Update semua elemen dengan ID currentTimeWIB
        const elements = ['currentTimeWIB', 'currentTimeWIB2'];
        elements.forEach(id => {
            const element = document.getElementById(id);
            if (element) {
                element.textContent = timeString;
                element.style.animation = 'pulse 1s ease-in-out';
                setTimeout(() => {
                    element.style.animation = '';
                }, 1000);
            }
        });
    }
    
    // Update setiap detik
    updateWIBTime();
    setInterval(updateWIBTime, 1000);
    
    // Animasi untuk slot waktu
    const timeSlots = document.querySelectorAll('.bg-gradient-to-br');
    
    timeSlots.forEach((slot, index) => {
        slot.style.animation = `fadeInUp 0.5s ease ${index * 0.05}s both`;
    });

    // Auto-refresh jika tanggal hari ini
    const selectedDate = "{{ $selectedDate }}";
    const today = new Date().toISOString().split('T')[0];
    
    if (selectedDate === today) {
        console.log('Tanggal hari ini, auto-refresh setiap 60 detik untuk update slot expired');
        setTimeout(() => {
            location.reload();
        }, 60000); // Refresh setiap 60 detik
    }
    
    // Alert jika mencoba klik slot yang tidak tersedia
    document.querySelectorAll('.cursor-not-allowed').forEach(slot => {
        slot.addEventListener('click', function(e) {
            e.preventDefault();
            const title = this.getAttribute('title');
            if (title) {
                alert(title);
            } else {
                alert('Slot waktu ini tidak tersedia!');
            }
        });
    });
});
</script>
@endsection