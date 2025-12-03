@extends('layout')

@section('title', 'Dashboard - GotKita.ID')

@php
    // DEFAULT VALUES JIKA VARIABLE TIDAK ADA
    $stats = $stats ?? [
        'total_bookings' => 0,
        'active_bookings' => 0,
        'completed_bookings' => 0
    ];
    
    $recentRatings = $recentRatings ?? collect();
    $futsalFields = $futsalFields ?? collect();
    $badmintonFields = $badmintonFields ?? collect();
    $miniSoccerFields = $miniSoccerFields ?? collect();
    
    // Static ratings untuk Joni (5/5)
    $joniRatings = [
        ['time' => '4 hours ago', 'comment' => 'Lapangan sangat bagus, fasilitas lengkap!'],
        ['time' => '4 hours ago', 'comment' => 'Pelayanan memuaskan, lapangan bersih'],
        ['time' => '1 day ago', 'comment' => 'Cocok untuk latihan tim, lighting bagus'],
        ['time' => '4 days ago', 'comment' => 'Harga worth it dengan kualitas yang diberikan'],
        ['time' => '4 days ago', 'comment' => 'Parkir luas, toilet bersih, recommended!'],
        ['time' => '4 days ago', 'comment' => 'Booking mudah melalui aplikasi, proses cepat'],
    ]
@endphp

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-950 to-black">
    <!-- Welcome Section -->
    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- 🖼️ LOGO UTAMA - Taruh logo kamu di sini -->
        <div class="flex justify-center mb-6">
            <img src="{{ asset('img/logo/logo-gorkita.png') }}" 
                 alt="GorKita.ID Logo" 
                 class="h-24 w-auto opacity-90 hover:opacity-100 transition-opacity duration-300"
                 onerror="this.style.display='none'">
        </div>

        <div class="text-center mb-10">
            <div class="inline-flex items-center space-x-3 bg-white/5 backdrop-blur-xl border border-emerald-500/20 rounded-2xl px-6 py-3 mb-6">
                <div class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></div>
                <span class="text-sm font-bold tracking-wider text-emerald-200">
                    {{ session()->has('user_id') ? 'Hallo Pejuang' : 'Welcome to GorKita.ID' }}
                </span>
                <div class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></div>
            </div>
            <h1 class="text-4xl lg:text-5xl font-black text-white mb-4 leading-tight">
                Hello, <span class="bg-gradient-to-r from-emerald-300 to-cyan-400 bg-clip-text text-transparent">
                    {{ session()->has('user_name') ? session('user_name') : 'Guest' }}
                </span>! 👋
            </h1>
            <p class="text-xl text-gray-400 max-w-2xl mx-auto">Kamu siap jadi pemenang? GorKita.ID arena nya para pejuang. kamu mau jadi pemenang, yuuu explore lapangan terbaikmu di GorKita.ID</p>
        </div>

        <!-- Enhanced Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
            <!-- Total Bookings -->
            <div class="group bg-gradient-to-br from-emerald-600/20 to-cyan-600/20 rounded-3xl p-6 shadow-2xl border border-emerald-500/20 backdrop-blur-sm transition-all duration-500 transform hover:-translate-y-2">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-cyan-500 rounded-2xl flex items-center justify-center border border-emerald-400/30">
                        <i class="fas fa-calendar-check text-white text-xl"></i>
                    </div>
                    <span class="text-emerald-300/70 text-sm font-semibold">Total</span>
                </div>
                <p class="text-4xl font-black text-emerald-300 mb-2">{{ $stats['total_bookings'] }}</p>
                <p class="text-gray-400">Total Booking Kamu</p>
                <div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-emerald-500 to-cyan-500 rounded-b-3xl"></div>
            </div>

            <!-- Active Bookings -->
            <div class="group bg-gradient-to-br from-cyan-600/20 to-emerald-600/20 rounded-3xl p-6 shadow-2xl border border-cyan-500/20 backdrop-blur-sm transition-all duration-500 transform hover:-translate-y-2">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-cyan-500 to-emerald-500 rounded-2xl flex items-center justify-center border border-cyan-400/30">
                        <i class="fas fa-clock text-white text-xl"></i>
                    </div>
                    <span class="text-cyan-300/70 text-sm font-semibold">Active</span>
                </div>
                <p class="text-4xl font-black text-cyan-300 mb-2">{{ $stats['active_bookings'] }}</p>
                <p class="text-gray-400">Sedang Berlangsung</p>
                <div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-cyan-500 to-emerald-500 rounded-b-3xl"></div>
            </div>

            <!-- Completed -->
            <div class="group bg-gradient-to-br from-emerald-600/20 to-cyan-600/20 rounded-3xl p-6 shadow-2xl border border-emerald-500/20 backdrop-blur-sm transition-all duration-500 transform hover:-translate-y-2">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-cyan-500 rounded-2xl flex items-center justify-center border border-emerald-400/30">
                        <i class="fas fa-check-circle text-white text-xl"></i>
                    </div>
                    <span class="text-emerald-300/70 text-sm font-semibold">Completed</span>
                </div>
                <p class="text-4xl font-black text-emerald-300 mb-2">{{ $stats['completed_bookings'] }}</p>
                <p class="text-gray-400">Game Selesai</p>
                <div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-emerald-500 to-cyan-500 rounded-b-3xl"></div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Recent Ratings Section -->
            <div class="bg-gradient-to-br from-gray-800/40 to-gray-900/40 rounded-3xl p-6 shadow-2xl border border-emerald-500/20 backdrop-blur-sm">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-black text-white flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-yellow-500 to-amber-500 rounded-2xl flex items-center justify-center border border-yellow-400/30">
                            <i class="fas fa-star text-white"></i>
                        </div>
                        <span>Rating</span>
                    </h2>
                    <a href="{{ route('rating.index') }}" class="text-emerald-300 hover:text-cyan-300 text-sm font-semibold flex items-center space-x-2 transition-colors">
                        <span>Lihat Semua Rating</span>
                        <i class="fas fa-arrow-right text-xs"></i>
                    </a>
                </div>
                
                <!-- STATIC RATINGS - JONI 5/5 -->
                <div class="space-y-4">
                    @foreach($joniRatings as $rating)
                    <div class="group bg-white/5 rounded-2xl p-4 border border-white/10 hover:border-emerald-500/30 transition-all duration-300 backdrop-blur-sm">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <!-- 🖼️ AVATAR USER -->
                                <img src="{{ asset('img/users/default-avatar.png') }}" 
                                     alt="Joni"
                                     class="w-12 h-12 rounded-2xl object-cover border border-emerald-400/30"
                                     onerror="this.src='{{ asset('img/users/default-avatar.png') }}'">
                                <div>
                                    <p class="font-semibold text-white">Joni</p>
                                    <div class="flex items-center space-x-1">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star text-yellow-400 text-sm"></i>
                                        @endfor
                                        <span class="text-gray-400 text-sm ml-2">5/5</span>
                                    </div>
                                </div>
                            </div>
                            <span class="text-gray-500 text-sm">{{ $rating['time'] }}</span>
                        </div>
                        @if(!empty($rating['comment']))
                            <p class="text-gray-300 mt-3 text-sm leading-relaxed">{{ $rating['comment'] }}</p>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-gradient-to-br from-gray-800/40 to-gray-900/40 rounded-3xl p-6 shadow-2xl border border-cyan-500/20 backdrop-blur-sm">
                <h2 class="text-2xl font-black text-white mb-6 flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-cyan-500 to-emerald-500 rounded-2xl flex items-center justify-center border border-cyan-400/30">
                        <i class="fas fa-bolt text-white"></i>
                    </div>
                    <span>Jelajahi</span>
                </h2>
                <div class="grid grid-cols-2 gap-4">
                    <!-- ✅ PERBAIKAN: tambahkan parameter type -->
                    <a href="{{ route('booking.select-field', ['type' => 'badminton']) }}" class="group bg-gradient-to-br from-emerald-600 to-cyan-600 hover:from-emerald-500 hover:to-cyan-500 text-white p-5 rounded-2xl text-center transition-all duration-300 transform hover:-translate-y-2 border border-emerald-500/30 backdrop-blur-sm">
                        <i class="fas fa-calendar-plus text-2xl mb-3 group-hover:scale-110 transition-transform"></i>
                        <p class="font-bold">New Booking</p>
                        <p class="text-emerald-100 text-xs mt-1">Booking yuu</p>
                    </a>
                    <a href="{{ route('booking.my-bookings') }}" class="group bg-gradient-to-br from-cyan-600 to-emerald-600 hover:from-cyan-500 hover:to-emerald-500 text-white p-5 rounded-2xl text-center transition-all duration-300 transform hover:-translate-y-2 border border-cyan-500/30 backdrop-blur-sm">
                        <i class="fas fa-list text-2xl mb-3 group-hover:scale-110 transition-transform"></i>
                        <p class="font-bold">My Booking</p>
                        <p class="text-cyan-100 text-xs mt-1">Liat semua Booking</p>
                    </a>
                    <a href="{{ route('rating.index') }}" class="group bg-gradient-to-br from-emerald-600 to-cyan-600 hover:from-emerald-500 hover:to-cyan-500 text-white p-5 rounded-2xl text-center transition-all duration-300 transform hover:-translate-y-2 border border-emerald-500/30 backdrop-blur-sm">
                        <i class="fas fa-star text-2xl mb-3 group-hover:scale-110 transition-transform"></i>
                        <p class="font-bold">Rating</p>
                        <p class="text-emerald-100 text-xs mt-1">Berbagi Pengalaman</p>
                    </a>
                    <a href="{{ route('about') }}" class="group bg-gradient-to-br from-cyan-600 to-emerald-600 hover:from-cyan-500 hover:to-emerald-500 text-white p-5 rounded-2xl text-center transition-all duration-300 transform hover:-translate-y-2 border border-cyan-500/30 backdrop-blur-sm">
                        <i class="fas fa-info-circle text-2xl mb-3 group-hover:scale-110 transition-transform"></i>
                        <p class="font-bold">Tentang Kami</p>
                        <p class="text-cyan-100 text-xs mt-1">Mariii</p>
                    </a>
                </div>
            </div>
        </div>

        <!-- Available Fields Section -->
        <div class="mt-12">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-3xl lg:text-4xl font-black text-white mb-2">Lapangan Ellite Premium</h2>
                    <p class="text-gray-400 text-lg">Lapangan Premium Untuk miningkatkan Performa Kamu dilapangan</p>
                </div>
                <div class="hidden lg:block">
                    <div class="flex items-center space-x-2 text-emerald-400">
                        <i class="fas fa-shield-alt"></i>
                        <span class="text-sm font-semibold">Kualitas Premium</span>
                    </div>
                </div>
            </div>

            <!-- Futsal Fields -->
            <div class="mb-12">
                <div class="flex items-center space-x-4 mb-8">
                    <div class="w-14 h-14 bg-gradient-to-br from-emerald-500 to-cyan-500 rounded-2xl flex items-center justify-center border border-emerald-400/30 shadow-lg">
                        <i class="fas fa-futbol text-white text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-black text-white">Lapangan Futsal</h3>
                        <p class="text-gray-400">Dilengkapi finil berstandar nasional</p>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($futsalFields as $index => $field)
                    <div class="group bg-gradient-to-br from-gray-800/40 to-gray-900/40 rounded-3xl p-6 shadow-2xl border border-emerald-500/20 backdrop-blur-sm transition-all duration-500 transform hover:-translate-y-3">
                        <div class="relative mb-5 overflow-hidden rounded-2xl">
                            <!-- 🖼️ GAMBAR FUTSAL - Taruh gambar futsal kamu di sini -->
                            @php
                                // Ganti dengan gambar futsal milikmu
                                $localFutsalImages = [
                                    asset('futsal/sal.jpg'),
                                    asset('futsal/2.jpg'),
                                    asset('futsal/3.jpg'),
                                ];
                                $localFutsalImage = $localFutsalImages[$index % count($localFutsalImages)];
                            @endphp
                            <img src="{{ $field->image_url ?: $localFutsalImage }}" 
                                 alt="{{ $field->name }}"
                                 class="w-full h-48 object-cover transition duration-500 group-hover:scale-110"
                                 onerror="this.src='{{ asset('img/fields/default-field.jpg') }}'">
                            <div class="absolute top-3 right-3 bg-gradient-to-r from-emerald-500 to-cyan-500 text-white px-3 py-1 rounded-full text-sm font-bold shadow-lg">
                                Available
                            </div>
                            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-4">
                                <h4 class="text-xl font-black text-white">{{ $field->name }}</h4>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <div class="flex items-center space-x-2 text-gray-300">
                                <i class="fas fa-ruler-combined text-emerald-400 text-sm"></i>
                                <span class="text-sm">Size: {{ $field->size ?? 'Standard' }}</span>
                            </div>
                            <div class="flex items-center space-x-2 text-gray-300">
                                <i class="fas fa-bolt text-cyan-400 text-sm"></i>
                                <span class="text-sm">pencahayaan yang berkualitas</span>
                            </div>
                        </div>
                        <div class="flex justify-between items-center mt-4 pt-4 border-t border-gray-700/50">
                            <div>
                                <span class="text-2xl font-black text-emerald-300">Rp {{ number_format($field->price_per_hour ?? 150000, 0, ',', '.') }}</span>
                                <span class="text-gray-400 text-sm">/jam</span>
                            </div>
                            <!-- ✅ PERBAIKAN: link ke booking.index -->
                            <a href="{{ route('booking.index') }}" 
                               class="bg-gradient-to-r from-emerald-600 to-cyan-600 hover:from-emerald-500 hover:to-cyan-500 text-white px-4 py-2 rounded-xl font-bold transition-all duration-300 transform hover:scale-105 border border-emerald-500/30 text-sm">
                                Book sekarang
                            </a>
                        </div>
                    </div>
                    @empty
                    <!-- Temporary Dummy Futsal Fields dengan Gambar Lokal -->
                    @php
                        $localDummyFutsalFields = [
                            [
                                'name' => 'Futsal Arena Pro',
                                'image' => asset('img/fields/futsal/field-1.jpg'), // 🖼️ Ganti dengan gambar futsal 1
                                'price' => 150000,
                                'size' => 'International'
                            ],
                            [
                                'name' => 'Champion Futsal Court', 
                                'image' => asset('img/fields/futsal/field-2.jpg'), // 🖼️ Ganti dengan gambar futsal 2
                                'price' => 120000,
                                'size' => 'Standard'
                            ],
                            [
                                'name' => 'Premium Futsal Hall',
                                'image' => asset('img/fields/futsal/field-3.jpg'), // 🖼️ Ganti dengan gambar futsal 3
                                'price' => 180000,
                                'size' => 'Olympic'
                            ]
                        ];
                    @endphp
                    @foreach($localDummyFutsalFields as $dummyField)
                    <div class="group bg-gradient-to-br from-gray-800/40 to-gray-900/40 rounded-3xl p-6 shadow-2xl border border-emerald-500/20 backdrop-blur-sm transition-all duration-500 transform hover:-translate-y-3">
                        <div class="relative mb-5 overflow-hidden rounded-2xl">
                            <img src="{{ $dummyField['image'] }}" 
                                 alt="{{ $dummyField['name'] }}"
                                 class="w-full h-48 object-cover transition duration-500 group-hover:scale-110">
                            <div class="absolute top-3 right-3 bg-gradient-to-r from-emerald-500 to-cyan-500 text-white px-3 py-1 rounded-full text-sm font-bold shadow-lg">
                                Available
                            </div>
                            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-4">
                                <h4 class="text-xl font-black text-white">{{ $dummyField['name'] }}</h4>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <div class="flex items-center space-x-2 text-gray-300">
                                <i class="fas fa-ruler-combined text-emerald-400 text-sm"></i>
                                <span class="text-sm">Size: {{ $dummyField['size'] }}</span>
                            </div>
                            <div class="flex items-center space-x-2 text-gray-300">
                                <i class="fas fa-bolt text-cyan-400 text-sm"></i>
                                <span class="text-sm">pencahayaan yang berkualitas</span>
                            </div>
                        </div>
                        <div class="flex justify-between items-center mt-4 pt-4 border-t border-gray-700/50">
                            <div>
                                <span class="text-2xl font-black text-emerald-300">Rp {{ number_format($dummyField['price'], 0, ',', '.') }}</span>
                                <span class="text-gray-400 text-sm">/jam</span>
                            </div>
                            <!-- ✅ PERBAIKAN: link ke booking.index -->
                            <a href="{{ route('booking.index') }}" 
                               class="bg-gradient-to-r from-emerald-600 to-cyan-600 hover:from-emerald-500 hover:to-cyan-500 text-white px-4 py-2 rounded-xl font-bold transition-all duration-300 transform hover:scale-105 border border-emerald-500/30 text-sm">
                                Booking Sekarang
                            </a>
                        </div>
                    </div>
                    @endforeach
                    @endforelse
                </div>
            </div>

            <!-- Badminton Fields -->
            <div class="mb-12">
                <div class="flex items-center space-x-4 mb-8">
                    <div class="w-14 h-14 bg-gradient-to-br from-cyan-500 to-emerald-500 rounded-2xl flex items-center justify-center border border-cyan-400/30 shadow-lg">
                        <i class="fas fa-table-tennis-paddle-ball text-white text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-black text-white">Lapangan Badminton</h3>
                        <p class="text-gray-400">Lapangan Standar International</p>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($badmintonFields as $index => $field)
                    <div class="group bg-gradient-to-br from-gray-800/40 to-gray-900/40 rounded-3xl p-6 shadow-2xl border border-cyan-500/20 backdrop-blur-sm transition-all duration-500 transform hover:-translate-y-3">
                        <div class="relative mb-5 overflow-hidden rounded-2xl">
                            <!-- 🖼️ GAMBAR BADMINTON - Taruh gambar badminton kamu di sini -->
                            @php
                                // Ganti dengan gambar badminton milikmu
                                $localBadmintonImages = [
                                    asset('badminton/bad1.jpg'),
                                    asset('badminton/76.jpg'),
                                    asset('badminton/5.jpg'),
                                    asset('badminton/bad4.jpg'),
                                   
                                ];
                                $localBadmintonImage = $localBadmintonImages[$index % count($localBadmintonImages)];
                            @endphp
                            <img src="{{ $field->image_url ?: $localBadmintonImage }}" 
                                 alt="{{ $field->name }}"
                                 class="w-full h-48 object-cover transition duration-500 group-hover:scale-110"
                                 onerror="this.src='{{ asset('img/fields/default-field.jpg') }}'">
                            <div class="absolute top-3 right-3 bg-gradient-to-r from-cyan-500 to-emerald-500 text-white px-3 py-1 rounded-full text-sm font-bold shadow-lg">
                                Available
                            </div>
                            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-4">
                                <h4 class="text-xl font-black text-white">{{ $field->name }}</h4>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <div class="flex items-center space-x-2 text-gray-300">
                                <i class="fas fa-table-tennis-paddle-ball text-cyan-400 text-sm"></i>
                                <span class="text-sm">Type: {{ $field->court_type ?? 'Indoor' }}</span>
                            </div>
                            <div class="flex items-center space-x-2 text-gray-300">
                                <i class="fas fa-floor text-emerald-400 text-sm"></i>
                                <span class="text-sm">premium outdor</span>
                            </div>
                        </div>
                        <div class="flex justify-between items-center mt-4 pt-4 border-t border-gray-700/50">
                            <div>
                                <span class="text-2xl font-black text-cyan-300">Rp {{ number_format($field->price_per_hour ?? 100000, 0, ',', '.') }}</span>
                                <span class="text-gray-400 text-sm">/jam</span>
                            </div>
                            <!-- ✅ PERBAIKAN: link ke booking.index -->
                            <a href="{{ route('booking.index') }}" 
                               class="bg-gradient-to-r from-cyan-600 to-emerald-600 hover:from-cyan-500 hover:to-emerald-500 text-white px-4 py-2 rounded-xl font-bold transition-all duration-300 transform hover:scale-105 border border-cyan-500/30 text-sm">
                                Booking Sekarang
                            </a>
                        </div>
                    </div>
                    @empty
                    <!-- Temporary Dummy Badminton Fields dengan Gambar Lokal -->
                    @php
                        $localDummyBadmintonFields = [
                            [
                                'name' => 'Olympic Badminton Hall',
                                'image' => asset('img/fields/badminton/court-1.jpg'), // 🖼️ Ganti dengan gambar badminton 1
                                'price' => 100000,
                                'type' => 'Indoor'
                            ],
                            [
                                'name' => 'Pro Badminton Court',
                                'image' => asset('img/fields/badminton/court-2.jpg'), // 🖼️ Ganti dengan gambar badminton 2
                                'price' => 120000,
                                'type' => 'Tournament'
                            ],
                            [
                                'name' => 'Champion Badminton Arena',
                                'image' => asset('img/fields/badminton/court-3.jpg'), // 🖼️ Ganti dengan gambar badminton 3
                                'price' => 140000,
                                'type' => 'Professional'
                            ]
                        ];
                    @endphp
                    @foreach($localDummyBadmintonFields as $dummyField)
                    <div class="group bg-gradient-to-br from-gray-800/40 to-gray-900/40 rounded-3xl p-6 shadow-2xl border border-cyan-500/20 backdrop-blur-sm transition-all duration-500 transform hover:-translate-y-3">
                        <div class="relative mb-5 overflow-hidden rounded-2xl">
                            <img src="{{ $dummyField['image'] }}" 
                                 alt="{{ $dummyField['name'] }}"
                                 class="w-full h-48 object-cover transition duration-500 group-hover:scale-110">
                            <div class="absolute top-3 right-3 bg-gradient-to-r from-cyan-500 to-emerald-500 text-white px-3 py-1 rounded-full text-sm font-bold shadow-lg">
                            Tersedia
                            </div>
                            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-4">
                                <h4 class="text-xl font-black text-white">{{ $dummyField['name'] }}</h4>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <div class="flex items-center space-x-2 text-gray-300">
                                <i class="fas fa-table-tennis-paddle-ball text-cyan-400 text-sm"></i>
                                <span class="text-sm">Type: {{ $dummyField['type'] }}</span>
                            </div>
                            <div class="flex items-center space-x-2 text-gray-300">
                                <i class="fas fa-floor text-emerald-400 text-sm"></i>
                                <span class="text-sm">Lapangan dengan rumput sintetis yang premium</span>
                            </div>
                        </div>
                        <div class="flex justify-between items-center mt-4 pt-4 border-t border-gray-700/50">
                            <div>
                                <span class="text-2xl font-black text-cyan-300">Rp {{ number_format($dummyField['price'], 0, ',', '.') }}</span>
                                <span class="text-gray-400 text-sm">/jam</span>
                            </div>
                            <!-- ✅ PERBAIKAN: link ke booking.index -->
                            <a href="{{ route('booking.index') }}" 
                               class="bg-gradient-to-r from-cyan-600 to-emerald-600 hover:from-cyan-500 hover:to-emerald-500 text-white px-4 py-2 rounded-xl font-bold transition-all duration-300 transform hover:scale-105 border border-cyan-500/30 text-sm">
                                Booking Sekarang
                            </a>
                        </div>
                    </div>
                    @endforeach
                    @endforelse
                </div>
            </div>

            <!-- Mini Soccer Fields -->
            <div class="mb-12">
                <div class="flex items-center space-x-4 mb-8">
                    <div class="w-14 h-14 bg-gradient-to-br from-emerald-500 to-cyan-500 rounded-2xl flex items-center justify-center border border-emerald-400/30 shadow-lg">
                        <i class="fas fa-futbol text-white text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-black text-white">Lapangan Mini Soccer</h3>
                        <p class="text-gray-400">Mini Soccer dengan Rumput sintetis</p>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($miniSoccerFields as $index => $field)
                    <div class="group bg-gradient-to-br from-gray-800/40 to-gray-900/40 rounded-3xl p-6 shadow-2xl border border-emerald-500/20 backdrop-blur-sm transition-all duration-500 transform hover:-translate-y-3">
                        <div class="relative mb-5 overflow-hidden rounded-2xl">
                            <!-- 🖼️ GAMBAR MINI SOCCER - Taruh gambar mini soccer kamu di sini -->
                            @php
                                // Ganti dengan gambar mini soccer milikmu
                                $localSoccerImages = [
                                    asset('soccer/zz.jpg'),
                                    asset('img/fields/mini-soccer/soccer-2.jpg'),
                                    asset('img/fields/mini-soccer/soccer-3.jpg'),
                                ];
                                $localSoccerImage = $localSoccerImages[$index % count($localSoccerImages)];
                            @endphp
                            <img src="{{ $field->image_url ?: $localSoccerImage }}" 
                                 alt="{{ $field->name }}"
                                 class="w-full h-48 object-cover transition duration-500 group-hover:scale-110"
                                 onerror="this.src='{{ asset('img/fields/default-field.jpg') }}'">
                            <div class="absolute top-3 right-3 bg-gradient-to-r from-emerald-500 to-cyan-500 text-white px-3 py-1 rounded-full text-sm font-bold shadow-lg">
                                 Tersedia
                            </div>
                            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-4">
                                <h4 class="text-xl font-black text-white">{{ $field->name }}</h4>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <div class="flex items-center space-x-2 text-gray-300">
                                <i class="fas fa-expand text-emerald-400 text-sm"></i>
                                <span class="text-sm">Capacity: {{ $field->capacity ?? '7v7' }}</span>
                            </div>
                            <div class="flex items-center space-x-2 text-gray-300">
                                <i class="fas fa-grass text-cyan-400 text-sm"></i>
                                <span class="text-sm">Rumput sintetis</s></span>
                            </div>
                        </div>
                        <div class="flex justify-between items-center mt-4 pt-4 border-t border-gray-700/50">
                            <div>
                                <span class="text-2xl font-black text-emerald-300">Rp {{ number_format($field->price_per_hour ?? 200000, 0, ',', '.') }}</span>
                                <span class="text-gray-400 text-sm">/hour</span>
                            </div>
                            <!-- ✅ PERBAIKAN: link ke booking.index -->
                            <a href="{{ route('booking.index') }}" 
                               class="bg-gradient-to-r from-emerald-600 to-cyan-600 hover:from-emerald-500 hover:to-cyan-500 text-white px-4 py-2 rounded-xl font-bold transition-all duration-300 transform hover:scale-105 border border-emerald-500/30 text-sm">
                                Booking Sekarang
                            </a>
                        </div>
                    </div>
                    @empty
                    <!-- Temporary Dummy Mini Soccer Fields dengan Gambar Lokal -->
                    @php
                        $localDummySoccerFields = [
                            [
                                'name' => 'Soccer Field Pro',
                                'image' => asset('soccer/zz.jpg'), // 🖼️ Ganti dengan gambar mini soccer 1
                                'price' => 200000,
                                'capacity' => '7v7'
                            ],
                            [
                                'name' => 'Champion Soccer Arena',
                                'image' => asset('img/fields/mini-soccer/soccer-2.jpg'), // 🖼️ Ganti dengan gambar mini soccer 2
                                'price' => 350000,
                                'capacity' => '7v7'
                            ],
                            [
                                'name' => 'Elite Soccer Field',
                                'image' => asset('img/fields/mini-soccer/soccer-3.jpg'), // 🖼️ Ganti dengan gambar mini soccer 3
                                'price' => 180000,
                                'capacity' => '5v5'
                            ]
                        ];
                    @endphp
                    @foreach($localDummySoccerFields as $dummyField)
                    <div class="group bg-gradient-to-br from-gray-800/40 to-gray-900/40 rounded-3xl p-6 shadow-2xl border border-emerald-500/20 backdrop-blur-sm transition-all duration-500 transform hover:-translate-y-3">
                        <div class="relative mb-5 overflow-hidden rounded-2xl">
                            <img src="{{ $dummyField['image'] }}" 
                                 alt="{{ $dummyField['name'] }}"
                                 class="w-full h-48 object-cover transition duration-500 group-hover:scale-110">
                            <div class="absolute top-3 right-3 bg-gradient-to-r from-emerald-500 to-cyan-500 text-white px-3 py-1 rounded-full text-sm font-bold shadow-lg">
                                Tersedia
                            </div>
                            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-4">
                                <h4 class="text-xl font-black text-white">{{ $dummyField['name'] }}</h4>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <div class="flex items-center space-x-2 text-gray-300">
                                <i class="fas fa-expand text-emerald-400 text-sm"></i>
                                <span class="text-sm">Capacity: {{ $dummyField['capacity'] }}</span>
                            </div>
                            <div class="flex items-center space-x-2 text-gray-300">
                                <i class="fas fa-grass text-cyan-400 text-sm"></i>
                                <span class="text-sm">Rumput Sintetis</span>
                            </div>
                        </div>
                        <div class="flex justify-between items-center mt-4 pt-4 border-t border-gray-700/50">
                            <div>
                                <span class="text-2xl font-black text-emerald-300">Rp {{ number_format($dummyField['price'], 0, ',', '.') }}</span>
                                <span class="text-gray-400 text-sm">/jam</span>
                            </div>
                            <!-- ✅ PERBAIKAN: link ke booking.index -->
                            <a href="{{ route('booking.index') }}" 
                               class="bg-gradient-to-r from-emerald-600 to-cyan-600 hover:from-emerald-500 hover:to-cyan-500 text-white px-4 py-2 rounded-xl font-bold transition-all duration-300 transform hover:scale-105 border border-emerald-500/30 text-sm">
                                Booking sekarang
                            </a>
                        </div>
                    </div>
                    @endforeach
                    @endforelse
                </div>
            </div>
        </div>

        <!-- 🖼️ FOOTER DENGAN LOGO -->
        <div class="mt-16 pt-8 border-t border-emerald-500/20">
            <div class="flex flex-col items-center justify-center text-center">
                <img src="{{ asset('img/logo/logo-gorkita.png') }}" 
                     alt="GorKita.ID Logo" 
                     class="h-16 w-auto mb-4 opacity-90 hover:opacity-100 transition-opacity"
                     onerror="this.style.display='none'">
                <h3 class="text-2xl font-black bg-gradient-to-r from-emerald-300 to-cyan-400 bg-clip-text text-transparent mb-2">
                    GorKita.ID
                </h3>
                <p class="text-gray-400 max-w-md mx-auto mb-6">
                    Arena premium untuk para pejuang. Temukan lapangan terbaik untuk meningkatkan performa Anda.
                </p>
                <div class="flex items-center space-x-4 text-sm text-gray-500">
                    <span>© 2024 GorKita.ID</span>
                    <span>•</span>
                    <span>All rights reserved</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Enhanced animations
    document.addEventListener('DOMContentLoaded', function() {
        // Scroll animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Observe elements for animation
        document.querySelectorAll('.group').forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(30px)';
            el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(el);
        });

        // Add floating animation to cards
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