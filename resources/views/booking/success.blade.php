@extends('layout')

@section('title', 'Booking Berhasil')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-950 to-black py-8">
    <div class="max-w-2xl mx-auto px-4">
        <!-- Success Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center space-x-2 bg-gray-800/50 rounded-full px-6 py-3 border border-emerald-500/30 mb-6">
                <div class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></div>
                <span class="text-emerald-300 text-sm font-semibold">BOOKING BERHASIL</span>
            </div>
            
            <div class="w-24 h-24 mx-auto mb-6 bg-gradient-to-br from-emerald-500 to-cyan-500 rounded-3xl flex items-center justify-center border border-emerald-400/30 shadow-2xl">
                <i class="fas fa-check-circle text-white text-3xl"></i>
            </div>
            
            <h1 class="text-4xl lg:text-5xl font-black text-white mb-4">
                Booking <span class="bg-gradient-to-r from-emerald-300 to-cyan-400 bg-clip-text text-transparent">Berhasil!</span>
            </h1>
            <p class="text-xl text-gray-400">Lapangan kamu sudah dipesan. Bersiaplah untuk permainan yang seru!</p>
        </div>

        <!-- Main Card -->
        <div class="bg-gradient-to-br from-gray-800/40 to-gray-900/40 rounded-3xl p-8 border border-emerald-500/20 backdrop-blur-sm">
            
            <!-- Payment Status -->
            @if($booking->payment_status === 'paid')
            <div class="bg-gradient-to-br from-emerald-500/10 to-green-500/10 rounded-2xl p-6 border border-emerald-500/30 mb-6">
                <div class="flex items-center space-x-3">
                    <i class="fas fa-check-circle text-emerald-400 text-2xl"></i>
                    <div>
                        <div class="text-white font-bold text-lg">Pembayaran Terkonfirmasi</div>
                        <div class="text-gray-300">Pembayaran berhasil via {{ strtoupper($booking->payment_method ?? 'Transfer') }}</div>
                    </div>
                </div>
            </div>
            @elseif($booking->payment_status === 'pending')
            <div class="bg-gradient-to-br from-yellow-500/10 to-orange-500/10 rounded-2xl p-6 border border-yellow-500/30 mb-6">
                <div class="flex items-center space-x-3">
                    <i class="fas fa-clock text-yellow-400 text-2xl"></i>
                    <div>
                        <div class="text-white font-bold text-lg">Menunggu Pembayaran</div>
                        <div class="text-gray-300">Menunggu konfirmasi pembayaran via {{ strtoupper($booking->payment_method ?? 'Transfer') }}</div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Booking Details -->
            <div class="space-y-6 mb-8">
                <h2 class="text-2xl font-black text-white mb-6">Detail Booking</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Date -->
                    <div class="bg-white/5 rounded-2xl p-4 border border-gray-700/50">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-emerald-500/20 rounded-2xl flex items-center justify-center">
                                <i class="fas fa-calendar text-emerald-400"></i>
                            </div>
                            <div>
                                <div class="text-gray-400 text-sm">Tanggal Booking</div>
                                <div class="text-white font-bold text-lg">
                                    {{ \Carbon\Carbon::parse($booking->tanggal_booking)->format('d M Y') }}
                                </div>
                                <div class="text-gray-400 text-sm">{{ \Carbon\Carbon::parse($booking->tanggal_booking)->format('l') }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Time -->
                    <div class="bg-white/5 rounded-2xl p-4 border border-gray-700/50">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-cyan-500/20 rounded-2xl flex items-center justify-center">
                                <i class="fas fa-clock text-cyan-400"></i>
                            </div>
                            <div>
                                <div class="text-gray-400 text-sm">Waktu Bermain</div>
                                <div class="text-white font-bold text-lg">
                                    {{ \Carbon\Carbon::parse($booking->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->jam_selesai)->format('H:i') }}
                                </div>
                                <div class="text-gray-400 text-sm">
                                    @php
                                        $start = \Carbon\Carbon::parse($booking->jam_mulai);
                                        $end = \Carbon\Carbon::parse($booking->jam_selesai);
                                        $duration = $start->diffInHours($end);
                                    @endphp
                                    {{ $duration }} jam sesi
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Field -->
                    <div class="bg-white/5 rounded-2xl p-4 border border-gray-700/50">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-emerald-500/20 rounded-2xl flex items-center justify-center">
                                <i class="fas fa-map-marker-alt text-emerald-400"></i>
                            </div>
                            <div>
                                <div class="text-gray-400 text-sm">Lapangan</div>
                                @if($booking->field)
                                    <div class="text-white font-bold text-lg">{{ $booking->field->name }}</div>
                                    <div class="text-gray-400 text-sm">{{ $booking->field->description ?? 'Tidak ada deskripsi' }}</div>
                                @else
                                    <div class="text-white font-bold text-lg">Lapangan #{{ $booking->lapangan_id }}</div>
                                    <div class="text-gray-400 text-sm">Informasi lapangan tidak tersedia</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Status & Price -->
                    <div class="bg-white/5 rounded-2xl p-4 border border-gray-700/50">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-cyan-500/20 rounded-2xl flex items-center justify-center">
                                <i class="fas fa-info-circle text-cyan-400"></i>
                            </div>
                            <div>
                                <div class="text-gray-400 text-sm">Status</div>
                                <div class="text-white font-bold text-lg capitalize">{{ $booking->status ?? 'pending' }}</div>
                                <div class="text-gray-400 text-sm">
                                    @if(isset($booking->total_price) && $booking->total_price > 0)
                                        Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                                    @else
                                        Harga belum ditentukan
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Info Card -->
            <div class="bg-gradient-to-br from-blue-500/10 to-purple-500/10 rounded-2xl p-6 border border-blue-500/30 mb-6">
                <div class="flex items-center space-x-3">
                    <i class="fas fa-info-circle text-blue-400 text-xl"></i>
                    <div>
                        <div class="text-white font-bold mb-2">Informasi Penting</div>
                        <ul class="text-gray-300 text-sm space-y-1">
                            <li>• Harap datang 15 menit sebelum waktu bermain</li>
                            <li>• Bawa peralatan pribadi (sepatu, raket, dll.)</li>
                            <li>• Pembatalan harus dilakukan minimal 2 jam sebelum</li>
                            <li>• Hubungi admin jika ada pertanyaan</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="{{ route('booking.my-bookings') }}" 
                   class="bg-gradient-to-r from-emerald-600 to-cyan-600 hover:from-emerald-500 hover:to-cyan-500 text-white font-bold py-4 px-8 rounded-2xl transition-all duration-300 transform hover:-translate-y-1 border border-emerald-500/30 flex items-center justify-center space-x-3 flex-1 group">
                    <i class="fas fa-list group-hover:scale-110 transition-transform"></i>
                    <span>Lihat Booking Saya</span>
                </a>
                
                <a href="{{ route('home') }}" 
                   class="bg-gradient-to-r from-gray-700 to-gray-600 hover:from-gray-600 hover:to-gray-500 text-white font-bold py-4 px-8 rounded-2xl transition-all duration-300 transform hover:-translate-y-1 border border-gray-600 flex items-center justify-center space-x-3 flex-1 group">
                    <i class="fas fa-home group-hover:scale-110 transition-transform"></i>
                    <span>Kembali ke Beranda</span>
                </a>
            </div>

            <!-- Additional Info -->
            <div class="mt-8 pt-6 border-t border-gray-700/50">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-center">
                    <div class="flex items-center justify-center space-x-2">
                        <i class="fas fa-shield-alt text-emerald-400"></i>
                        <span class="text-gray-400 text-sm">Booking Aman</span>
                    </div>
                    <div class="flex items-center justify-center space-x-2">
                        <i class="fas fa-clock text-cyan-400"></i>
                        <span class="text-gray-400 text-sm">Konfirmasi Instan</span>
                    </div>
                    <div class="flex items-center justify-center space-x-2">
                        <i class="fas fa-star text-emerald-400"></i>
                        <span class="text-gray-400 text-sm">Kualitas Premium</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
