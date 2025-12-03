@extends('layout')

@section('title', 'Pilih Lapangan - ' . ucfirst($type))

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-950 to-black py-8">
    <div class="max-w-7xl mx-auto px-4">
        <!-- Header Section -->
        <div class="text-center mb-12">
            <div class="inline-flex items-center space-x-3 bg-white/5 backdrop-blur-xl border border-emerald-500/20 rounded-2xl px-6 py-3 mb-6">
                <div class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></div>
                <span class="text-sm font-bold tracking-wider text-emerald-200">BOOK YOUR VICTORY</span>
                <div class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></div>
            </div>
            <h1 class="text-4xl lg:text-5xl font-black text-white mb-4 leading-tight">
                Pilih <span class="bg-gradient-to-r from-emerald-300 to-cyan-400 bg-clip-text text-transparent">Lapangan Favorit</span> 🏆
            </h1>
            <p class="text-xl text-gray-400 max-w-2xl mx-auto">Temukan lapangan perfect untuk sesi olahraga championship-grade Anda</p>
        </div>

        <!-- Sport Type Selection -->
        <div class="bg-gradient-to-br from-gray-800/40 to-gray-900/40 rounded-3xl p-8 mb-8 border border-emerald-500/20 backdrop-blur-sm">
            <h2 class="text-2xl font-black text-white mb-6 text-center flex items-center justify-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-cyan-500 rounded-2xl flex items-center justify-center border border-emerald-400/30">
                    <i class="fas fa-trophy text-white"></i>
                </div>
                <span>Pilih Jenis Olahraga</span>
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Badminton -->
                <a href="{{ route('booking.select-field', ['type' => 'badminton', 'date' => $selectedDate]) }}" 
                   class="group relative overflow-hidden rounded-3xl p-8 text-center transition-all duration-500 transform hover:-translate-y-3 border-2 backdrop-blur-sm
                          {{ $type == 'badminton' ? 'bg-gradient-to-br from-emerald-500/20 to-cyan-500/20 border-emerald-500/40 scale-105' : 'bg-white/5 border-white/10 hover:border-emerald-500/30' }}">
                    <div class="w-24 h-24 mx-auto mb-6 bg-gradient-to-br from-emerald-500 to-cyan-500 rounded-3xl flex items-center justify-center border border-emerald-400/30 group-hover:scale-110 transition-transform duration-300 shadow-2xl">
                        <i class="fas fa-table-tennis-paddle-ball text-white text-3xl"></i>
                    </div>
                    <h3 class="text-2xl font-black text-white mb-3">Badminton</h3>
                    <p class="text-gray-400 mb-4">Lapangan indoor premium</p>
                    @if($type == 'badminton')
                    <div class="absolute top-4 right-4 w-8 h-8 bg-emerald-500 rounded-full flex items-center justify-center border border-emerald-400/30 shadow-lg">
                        <i class="fas fa-check text-white text-xs"></i>
                    </div>
                    @endif
                </a>

                <!-- Futsal -->
                <a href="{{ route('booking.select-field', ['type' => 'futsal', 'date' => $selectedDate]) }}" 
                   class="group relative overflow-hidden rounded-3xl p-8 text-center transition-all duration-500 transform hover:-translate-y-3 border-2 backdrop-blur-sm
                          {{ $type == 'futsal' ? 'bg-gradient-to-br from-cyan-500/20 to-emerald-500/20 border-cyan-500/40 scale-105' : 'bg-white/5 border-white/10 hover:border-cyan-500/30' }}">
                    <div class="w-24 h-24 mx-auto mb-6 bg-gradient-to-br from-cyan-500 to-emerald-500 rounded-3xl flex items-center justify-center border border-cyan-400/30 group-hover:scale-110 transition-transform duration-300 shadow-2xl">
                        <i class="fas fa-futbol text-white text-3xl"></i>
                    </div>
                    <h3 class="text-2xl font-black text-white mb-3">Futsal</h3>
                    <p class="text-gray-400 mb-4">Lapangan standar nasional</p>
                    @if($type == 'futsal')
                    <div class="absolute top-4 right-4 w-8 h-8 bg-cyan-500 rounded-full flex items-center justify-center border border-cyan-400/30 shadow-lg">
                        <i class="fas fa-check text-white text-xs"></i>
                    </div>
                    @endif
                </a>

                <!-- Mini Soccer - SUDAH DIPERBAIKI -->
                <a href="{{ route('booking.select-field', ['type' => 'minisoccer', 'date' => $selectedDate]) }}" 
                   class="group relative overflow-hidden rounded-3xl p-8 text-center transition-all duration-500 transform hover:-translate-y-3 border-2 backdrop-blur-sm
                          {{ $type == 'minisoccer' ? 'bg-gradient-to-br from-emerald-500/20 to-cyan-500/20 border-emerald-500/40 scale-105' : 'bg-white/5 border-white/10 hover:border-emerald-500/30' }}">
                    <div class="w-24 h-24 mx-auto mb-6 bg-gradient-to-br from-emerald-500 to-cyan-500 rounded-3xl flex items-center justify-center border border-emerald-400/30 group-hover:scale-110 transition-transform duration-300 shadow-2xl">
                        <i class="fas fa-futbol text-white text-3xl"></i>
                    </div>
                    <h3 class="text-2xl font-black text-white mb-3">Mini Soccer</h3>
                    <p class="text-gray-400 mb-4">Lapangan 7v7 premium</p>
                    @if($type == 'minisoccer')
                    <div class="absolute top-4 right-4 w-8 h-8 bg-emerald-500 rounded-full flex items-center justify-center border border-emerald-400/30 shadow-lg">
                        <i class="fas fa-check text-white text-xs"></i>
                    </div>
                    @endif
                </a>
            </div>
        </div>

        <!-- Selected Date Card -->
        <div class="bg-gradient-to-br from-emerald-500/10 to-cyan-500/10 rounded-3xl p-8 mb-8 border border-emerald-500/30 backdrop-blur-sm">
            <div class="flex flex-col lg:flex-row items-center justify-between gap-8">
                <div class="flex items-center space-x-6">
                    <div class="w-20 h-20 bg-gradient-to-br from-emerald-500 to-cyan-500 rounded-3xl flex items-center justify-center border border-emerald-400/30 shadow-2xl">
                        <i class="fas fa-calendar-alt text-white text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-black text-white mb-2">Tanggal Dipilih</h3>
                        <p class="text-emerald-300 text-2xl font-bold">
                            {{ \Carbon\Carbon::parse($selectedDate)->translatedFormat('l, d F Y') }}
                        </p>
                    </div>
                </div>

                <!-- Date Picker Form -->
                <form action="{{ route('booking.select-field', ['type' => $type]) }}" method="GET" class="flex flex-col sm:flex-row gap-4 items-center">
                    <div class="relative">
                        <input type="date" 
                               name="date" 
                               value="{{ $selectedDate }}"
                               min="{{ now()->format('Y-m-d') }}"
                               class="bg-white/10 border border-white/20 rounded-2xl px-6 py-4 text-white font-semibold text-lg focus:outline-none focus:border-emerald-400 transition w-full sm:w-64 backdrop-blur-sm">
                        <i class="fas fa-calendar absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                    <button type="submit" class="bg-gradient-to-r from-emerald-600 to-cyan-600 hover:from-emerald-500 hover:to-cyan-500 text-white font-bold py-4 px-8 rounded-2xl transition-all duration-300 transform hover:-translate-y-1 border border-emerald-500/30 flex items-center space-x-3">
                        <i class="fas fa-sync-alt"></i>
                        <span>Update Tanggal</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Fields Grid -->
        @if($fields->count() > 0)
        <div class="mb-12">
            <div class="text-center mb-10">
                <h2 class="text-4xl lg:text-5xl font-black text-white mb-4">
                    <span class="bg-gradient-to-r from-emerald-300 to-cyan-400 bg-clip-text text-transparent">
                        Lapangan {{ ucfirst($type) }} Tersedia
                    </span>
                </h2>
                <p class="text-xl text-gray-400">Temukan {{ $fields->count() }} lapangan premium untuk pengalaman terbaik</p>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-8">
                @foreach($fields as $field)
                @php
                    // Sistem gambar berdasarkan type lapangan
                    $fieldImage = null;
                    
                    // Cek apakah ada gambar dari database
                    if (!empty($field->image) && file_exists(public_path($field->image))) {
                        $fieldImage = asset($field->image);
                    } else {
                        // Fallback ke gambar lokal berdasarkan tipe
                        $localImages = [
                            'badminton' => [
                                asset('badminton/bad1.jpg'),
                                asset('badminton/76.jpg'),
                                asset('badminton/5.jpg'),
                                asset('badminton/bad4.jpg'),
                            ],
                            'futsal' => [
                                asset('futsal/sal.jpg'),
                                asset('futsal/2.jpg'),
                                asset('futsal/3.jpg'),
                            ],
                            'minisoccer' => [
                                asset('soccer/zz.jpg'),
                                asset('img/fields/mini-soccer/soccer-2.jpg'),
                                asset('img/fields/mini-soccer/soccer-3.jpg'),
                            ]
                        ];
                        
                        $imageList = $localImages[$type] ?? [asset('img/fields/default-field.jpg')];
                        $fieldImage = $imageList[$loop->index % count($imageList)];
                    }
                    
                    // Tentukan ikon berdasarkan tipe
                    $fieldIcon = 'fa-running';
                    if ($type == 'badminton') {
                        $fieldIcon = 'fa-table-tennis-paddle-ball';
                    } elseif ($type == 'futsal' || $type == 'minisoccer') {
                        $fieldIcon = 'fa-futbol';
                    }
                    
                    // Tentukan warna berdasarkan tipe
                    $gradientFrom = 'from-emerald-500';
                    $gradientTo = 'to-cyan-500';
                    if ($type == 'futsal') {
                        $gradientFrom = 'from-cyan-500';
                        $gradientTo = 'to-emerald-500';
                    }
                @endphp
                
                <div class="group relative overflow-hidden rounded-3xl bg-gradient-to-br from-gray-800/40 to-gray-900/40 border border-emerald-500/20 backdrop-blur-sm transition-all duration-500 transform hover:-translate-y-3 hover:border-emerald-500/40">
                    <!-- Field Image -->
                    <div class="relative h-56 overflow-hidden">
                        <img src="{{ $fieldImage }}" 
                             alt="{{ $field->name }}"
                             class="w-full h-full object-cover transition duration-500 group-hover:scale-110"
                             onerror="this.src='{{ asset('img/fields/default-field.jpg') }}'">
                        <div class="absolute top-4 left-4 bg-black/80 text-white px-4 py-2 rounded-2xl text-sm font-bold backdrop-blur-sm">
                            {{ ucfirst($field->type) }}
                        </div>
                        <div class="absolute top-4 right-4 bg-gradient-to-r {{ $gradientFrom }} {{ $gradientTo }} text-white px-4 py-2 rounded-2xl text-sm font-bold flex items-center space-x-2 shadow-lg">
                            <div class="w-2 h-2 bg-white rounded-full animate-pulse"></div>
                            <span>Tersedia</span>
                        </div>
                        <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/90 to-transparent p-6">
                            <h3 class="text-2xl font-black text-white group-hover:text-emerald-300 transition-colors">
                                @if($field->name == 'Lapangan 1' || $field->name == 'Lapangan 2' || $field->name == 'Lapangan 3')
                                    Lapangan {{ $loop->iteration }}
                                @else
                                    {{ $field->name }}
                                @endif
                            </h3>
                        </div>
                    </div>

                    <!-- Field Info -->
                    <div class="p-6">
                        <p class="text-gray-400 mb-6 leading-relaxed">
                            @if($field->description)
                                {{ $field->description }}
                            @elseif($field->name == 'Lapangan 1' || $field->name == 'Lapangan 2' || $field->name == 'Lapangan 3')
                                Lapangan berkualitas premium dengan fasilitas championship-grade
                            @else
                                Lapangan berkualitas premium dengan fasilitas championship-grade
                            @endif
                        </p>
                        
                        <!-- Field Details -->
                        <div class="space-y-3 mb-6">
                            @if($type == 'badminton')
                            <div class="flex items-center space-x-2 text-gray-300">
                                <i class="fas fa-table-tennis-paddle-ball text-cyan-400 text-sm"></i>
                                <span class="text-sm">Type: {{ $field->court_type ?? 'Indoor' }}</span>
                            </div>
                            <div class="flex items-center space-x-2 text-gray-300">
                                <i class="fas fa-floor text-emerald-400 text-sm"></i>
                                <span class="text-sm">Premium indoor</span>
                            </div>
                            @elseif($type == 'futsal')
                            <div class="flex items-center space-x-2 text-gray-300">
                                <i class="fas fa-ruler-combined text-emerald-400 text-sm"></i>
                                <span class="text-sm">Size: {{ $field->size ?? 'Standard' }}</span>
                            </div>
                            <div class="flex items-center space-x-2 text-gray-300">
                                <i class="fas fa-bolt text-cyan-400 text-sm"></i>
                                <span class="text-sm">Finil Stadar Internasional</span>
                            </div>
                            @elseif($type == 'minisoccer')
                            <div class="flex items-center space-x-2 text-gray-300">
                                <i class="fas fa-expand text-emerald-400 text-sm"></i>
                                <span class="text-sm">kapasitas: {{ $field->capacity ?? '7v7' }}</span>
                            </div>
                            <div class="flex items-center space-x-2 text-gray-300">
                                <i class="fas fa-grass text-cyan-400 text-sm"></i>
                                <span class="text-sm">Rumput sintetis</span>
                            </div>
                            @endif
                        </div>

                        <!-- Price & Action -->
                        <div class="flex items-center justify-between pt-6 border-t border-gray-700/50">
                            <div>
                                @php
                                    // Tentukan harga berdasarkan tipe
                                    $price = $field->price_per_hour ?? 40000;
                                    if ($type == 'futsal') {
                                        $price = $field->price_per_hour ?? 150000;
                                    } elseif ($type == 'minisoccer') {
                                        $price = $field->price_per_hour ?? 200000;
                                    }
                                @endphp
                                <div class="text-3xl font-black text-emerald-300">
                                    Rp {{ number_format($price, 0, ',', '.') }}
                                </div>
                                <div class="text-gray-500 text-sm font-semibold">/jam</div>
                            </div>
                            
                            <a href="{{ route('booking.select-time', $field) }}?date={{ $selectedDate }}" 
                               class="bg-gradient-to-r from-emerald-600 to-cyan-600 hover:from-emerald-500 hover:to-cyan-500 text-white px-8 py-4 rounded-2xl font-bold transition-all duration-300 transform hover:scale-105 border border-emerald-500/30 flex items-center space-x-3 group">
                                <span>Pilih Waktu</span>
                                <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @else
        <!-- Empty State -->
        <div class="bg-gradient-to-br from-gray-800/40 to-gray-900/40 rounded-3xl p-16 text-center border border-emerald-500/20 backdrop-blur-sm">
            <div class="w-32 h-32 mx-auto mb-8 bg-gradient-to-br from-emerald-500/20 to-cyan-500/20 rounded-3xl flex items-center justify-center border border-emerald-500/30">
                <i class="fas fa-times-circle text-gray-400 text-5xl"></i>
            </div>
            <h2 class="text-3xl lg:text-4xl font-black text-white mb-6">Tidak Ada Lapangan Tersedia</h2>
            <p class="text-gray-400 text-xl mb-8 max-w-md mx-auto leading-relaxed">
                Semua lapangan {{ $type }} sedang penuh atau dalam perawatan premium. 
                Coba pilih jenis olahraga lain atau tanggal yang berbeda.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('booking.index') }}" 
                   class="bg-gradient-to-r from-emerald-600 to-cyan-600 hover:from-emerald-500 hover:to-cyan-500 text-white px-8 py-4 rounded-2xl font-bold transition-all duration-300 transform hover:-translate-y-1 border border-emerald-500/30 inline-flex items-center space-x-3">
                    <i class="fas fa-arrow-left"></i>
                    <span>Kembali ke Home</span>
                </a>
                <a href="{{ route('booking.select-field', ['type' => 'badminton', 'date' => $selectedDate]) }}" 
                   class="bg-gradient-to-r from-cyan-600 to-emerald-600 hover:from-cyan-500 hover:to-emerald-500 text-white px-8 py-4 rounded-2xl font-bold transition-all duration-300 transform hover:-translate-y-1 border border-cyan-500/30 inline-flex items-center space-x-3">
                    <i class="fas fa-table-tennis-paddle-ball"></i>
                    <span>Coba Badminton</span>
                </a>
            </div>
        </div>
        @endif

        <!-- Additional Info -->
        <div class="bg-gradient-to-br from-gray-800/40 to-gray-900/40 rounded-3xl p-8 border border-cyan-500/20 backdrop-blur-sm">
            <h3 class="text-2xl lg:text-3xl font-black text-white mb-8 text-center flex items-center justify-center space-x-3">
                <div class="w-12 h-12 bg-gradient-to-br from-cyan-500 to-emerald-500 rounded-2xl flex items-center justify-center border border-cyan-400/30">
                    <i class="fas fa-crown text-white"></i>
                </div>
                <span>Mengapa Memilih GotKita.ID?</span>
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center group">
                    <div class="w-20 h-20 bg-gradient-to-br from-emerald-500/20 to-cyan-500/20 rounded-3xl flex items-center justify-center mx-auto mb-6 border border-emerald-500/30 group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-shield-alt text-emerald-400 text-2xl"></i>
                    </div>
                    <h4 class="text-xl font-black text-white mb-3">Terpercaya</h4>
                    <p class="text-gray-400 leading-relaxed">Ribuan member telah mempercayakan booking championship mereka kepada kami</p>
                </div>
                <div class="text-center group">
                    <div class="w-20 h-20 bg-gradient-to-br from-cyan-500/20 to-emerald-500/20 rounded-3xl flex items-center justify-center mx-auto mb-6 border border-cyan-500/30 group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-clock text-cyan-400 text-2xl"></i>
                    </div>
                    <h4 class="text-xl font-black text-white mb-3">24/7 Booking</h4>
                    <p class="text-gray-400 leading-relaxed">Booking kapan saja, di mana saja melalui platform online premium kami</p>
                </div>
                <div class="text-center group">
                    <div class="w-20 h-20 bg-gradient-to-br from-emerald-500/20 to-cyan-500/20 rounded-3xl flex items-center justify-center mx-auto mb-6 border border-emerald-500/30 group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-star text-emerald-400 text-2xl"></i>
                    </div>
                    <h4 class="text-xl font-black text-white mb-3">Rating Tinggi</h4>
                    <p class="text-gray-400 leading-relaxed">Fasilitas championship-grade dengan rating 4.8+ dari seluruh atlet</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Enhanced animations with stagger
    document.addEventListener('DOMContentLoaded', function() {
        // Scroll animations with stagger
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry, index) => {
                if (entry.isIntersecting) {
                    setTimeout(() => {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }, index * 100);
                }
            });
        }, observerOptions);

        // Observe all groups with stagger
        document.querySelectorAll('.group').forEach((el, index) => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(30px)';
            el.style.transition = `opacity 0.6s ease ${index * 0.1}s, transform 0.6s ease ${index * 0.1}s`;
            observer.observe(el);
        });

        // Add hover effects to cards
        const cards = document.querySelectorAll('.bg-gradient-to-br');
        cards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-10px) scale(1.02)';
            });
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });
    });
</script>
@endsection