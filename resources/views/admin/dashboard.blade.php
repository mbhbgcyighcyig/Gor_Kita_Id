<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - GorKita.ID</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        .admin-container {
            display: flex;
            min-height: 100vh;
            background: #f8fafc;
        }
        
        .sidebar {
            width: 280px;
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
            color: white;
            position: fixed;
            height: 100vh;
            left: 0;
            top: 0;
            overflow-y: auto;
            box-shadow: 4px 0 10px rgba(0,0,0,0.1);
        }
        
        .main-content {
            flex: 1;
            margin-left: 280px;
            padding: 30px;
            min-height: 100vh;
        }
        
        .stat-card {
            transition: all 0.3s ease;
            border-radius: 16px;
            overflow: hidden;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.15);
        }
        
        .chart-container {
            background: white;
            border-radius: 16px;
            padding: 25px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="sidebar">
            @include('admin.partials.sidebar')
        </div>
        
        <div class="main-content">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Dashboard Admin</h1>
                    <p class="text-gray-600">Selamat datang di GorKita.ID Admin Panel</p>
                </div>
                <div class="text-right">
                    <p class="text-2xl font-bold text-blue-600">Hi, Admin GOR! 👋</p>
                    <p class="text-gray-500">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                @php
                    $totalBookings = \App\Models\Booking::count();
                    $todayBookings = \App\Models\Booking::whereDate('tanggal_booking', today())->count();
                    
                    $weeklyBookings = \App\Models\Booking::whereBetween('tanggal_booking', [now()->startOfWeek(), now()->endOfWeek()])
                        ->where('status', 'confirmed')
                        ->with('lapangan')
                        ->get();
                    
                    $weeklyRevenue = 0;
                    foreach ($weeklyBookings as $booking) {
                        if ($booking->total_price && $booking->total_price > 0) {
                            $weeklyRevenue += $booking->total_price;
                        } else {
                            try {
                                $start = \Carbon\Carbon::parse($booking->jam_mulai);
                                $end = \Carbon\Carbon::parse($booking->jam_selesai);
                                $duration = $start->diffInHours($end);
                                
                                if ($booking->lapangan && $booking->lapangan->price_per_hour) {
                                    $calculatedPrice = $duration * $booking->lapangan->price_per_hour;
                                    $weeklyRevenue += $calculatedPrice;
                                } else {
                                    $defaultPrice = \App\Models\Lapangan::first()->price_per_hour ?? 40000;
                                    $weeklyRevenue += $duration * $defaultPrice;
                                }
                            } catch (\Exception $e) {
                                if ($booking->lapangan && $booking->lapangan->price_per_hour) {
                                    $weeklyRevenue += $booking->lapangan->price_per_hour * 1;
                                } else {
                                    $defaultPrice = \App\Models\Lapangan::first()->price_per_hour ?? 40000;
                                    $weeklyRevenue += $defaultPrice * 1;
                                }
                            }
                        }
                    }
                    
                    $confirmedBookings = \App\Models\Booking::where('status', 'confirmed')->count();
                    
                    $allBookings = \App\Models\Booking::where('status', 'confirmed')
                        ->with('lapangan')
                        ->get();
                    $totalRevenue = 0;
                    foreach ($allBookings as $booking) {
                        if ($booking->total_price && $booking->total_price > 0) {
                            $totalRevenue += $booking->total_price;
                        } else {
                            try {
                                $start = \Carbon\Carbon::parse($booking->jam_mulai);
                                $end = \Carbon\Carbon::parse($booking->jam_selesai);
                                $duration = $start->diffInHours($end);
                                
                                if ($booking->lapangan && $booking->lapangan->price_per_hour) {
                                    $totalRevenue += $duration * $booking->lapangan->price_per_hour;
                                } else {
                                    $defaultPrice = \App\Models\Lapangan::first()->price_per_hour ?? 40000;
                                    $totalRevenue += $duration * $defaultPrice;
                                }
                            } catch (\Exception $e) {
                                if ($booking->lapangan && $booking->lapangan->price_per_hour) {
                                    $totalRevenue += $booking->lapangan->price_per_hour * 1;
                                } else {
                                    $defaultPrice = \App\Models\Lapangan::first()->price_per_hour ?? 40000;
                                    $totalRevenue += $defaultPrice * 1;
                                }
                            }
                        }
                    }
                @endphp
                
                <div class="stat-card bg-gradient-to-r from-blue-500 to-cyan-500 p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Total Booking</p>
                            <p class="text-3xl font-bold">{{ $totalBookings }}</p>
                            <p class="text-sm opacity-80 mt-2">Semua waktu</p>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full p-4">
                            <i class="fas fa-calendar-check text-2xl"></i>
                        </div>
                    </div>
                </div>
                
                <div class="stat-card bg-gradient-to-r from-green-500 to-emerald-500 p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Booking Hari Ini</p>
                            <p class="text-3xl font-bold">{{ $todayBookings }}</p>
                            <p class="text-sm opacity-80 mt-2">{{ \Carbon\Carbon::now()->format('d M Y') }}</p>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full p-4">
                            <i class="fas fa-clock text-2xl"></i>
                        </div>
                    </div>
                </div>
                
                <div class="stat-card bg-gradient-to-r from-purple-500 to-pink-500 p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Pendapatan Minggu Ini</p>
                            <p class="text-3xl font-bold">Rp {{ number_format($weeklyRevenue, 0, ',', '.') }}</p>
                            <p class="text-sm opacity-80 mt-2">Senin - Minggu</p>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full p-4">
                            <i class="fas fa-money-bill-wave text-2xl"></i>
                        </div>
                    </div>
                </div>
                
                <div class="stat-card bg-gradient-to-r from-orange-500 to-red-500 p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Total Pendapatan</p>
                            <p class="text-3xl font-bold">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                            <p class="text-sm opacity-80 mt-2">Pendapatan keseluruhan</p>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full p-4">
                            <i class="fas fa-chart-line text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <div class="chart-container">
                    <h3 class="text-xl font-bold text-gray-800 mb-6">Statistik Booking Minggu Ini</h3>
                    <canvas id="weeklyChart" height="250"></canvas>
                </div>

                <div class="chart-container">
                    <h3 class="text-xl font-bold text-gray-800 mb-6">Booking Hari Ini</h3>
                    <div class="space-y-4">
                        @php
                            $todayBookingsList = \App\Models\Booking::with(['user', 'lapangan'])
                                ->whereDate('tanggal_booking', today())
                                ->orderBy('jam_mulai')
                                ->get();
                        @endphp
                        
                        @if($todayBookingsList->count() > 0)
                            @foreach($todayBookingsList as $booking)
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border">
                                <div class="flex items-center space-x-4">
                                    <div class="bg-blue-100 rounded-full p-3">
                                        <i class="fas fa-user text-blue-600"></i>
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-800">{{ $booking->user->name }}</p>
                                        <p class="text-sm text-gray-600">
                                            {{ $booking->lapangan->name ?? 'Court #' . $booking->lapangan_id }} • {{ $booking->jam_mulai }} - {{ $booking->jam_selesai }}
                                        </p>
                                        <p class="text-xs text-green-600 font-bold">
                                            @php
                                                if ($booking->total_price && $booking->total_price > 0) {
                                                    $displayPrice = $booking->total_price;
                                                } else {
                                                    try {
                                                        $start = \Carbon\Carbon::parse($booking->jam_mulai);
                                                        $end = \Carbon\Carbon::parse($booking->jam_selesai);
                                                        $duration = $start->diffInHours($end);
                                                        
                                                        if ($booking->lapangan && $booking->lapangan->price_per_hour) {
                                                            $displayPrice = $duration * $booking->lapangan->price_per_hour;
                                                        } else {
                                                            $defaultPrice = \App\Models\Lapangan::first()->price_per_hour ?? 40000;
                                                            $displayPrice = $duration * $defaultPrice;
                                                        }
                                                    } catch (\Exception $e) {
                                                        if ($booking->lapangan && $booking->lapangan->price_per_hour) {
                                                            $displayPrice = $booking->lapangan->price_per_hour * 1;
                                                        } else {
                                                            $defaultPrice = \App\Models\Lapangan::first()->price_per_hour ?? 40000;
                                                            $displayPrice = $defaultPrice * 1;
                                                        }
                                                    }
                                                }
                                            @endphp
                                            Rp {{ number_format($displayPrice, 0, ',', '.') }}
                                        </p>
                                    </div>
                                </div>
                                <span class="px-3 py-1 rounded-full text-sm font-bold
                                    @if($booking->status == 'confirmed') bg-green-100 text-green-800
                                    @elseif($booking->status == 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($booking->status == 'completed') bg-blue-100 text-blue-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ $booking->status }}
                                </span>
                            </div>
                            @endforeach
                        @else
                            <div class="text-center py-8">
                                <i class="fas fa-calendar-times text-gray-400 text-4xl mb-3"></i>
                                <p class="text-gray-500">Tidak ada booking hari ini</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="chart-container">
                    <h3 class="text-xl font-bold text-gray-800 mb-6">Booking Terbaru</h3>
                    <div class="space-y-4">
                        @php
                            $recentBookings = \App\Models\Booking::with(['user', 'lapangan'])
                                ->latest()
                                ->limit(5)
                                ->get();
                        @endphp
                        
                        @foreach($recentBookings as $booking)
                        <div class="flex items-center justify-between p-4 bg-white border rounded-lg hover:shadow-md transition">
                            <div class="flex items-center space-x-4">
                                <div class="bg-purple-100 rounded-full p-3">
                                    <i class="fas fa-running text-purple-600"></i>
                                </div>
                                <div>
                                    <p class="font-bold text-gray-800">{{ $booking->user->name }}</p>
                                    <p class="text-sm text-gray-600">
                                        {{ $booking->lapangan->name ?? 'Court #' . $booking->lapangan_id }} - {{ \Carbon\Carbon::parse($booking->tanggal_booking)->format('d M Y') }}
                                    </p>
                                    <p class="text-xs text-gray-500">{{ $booking->jam_mulai }} - {{ $booking->jam_selesai }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-green-600">
                                    @php
                                        if ($booking->total_price && $booking->total_price > 0) {
                                            $displayPrice = $booking->total_price;
                                        } else {
                                            try {
                                                $start = \Carbon\Carbon::parse($booking->jam_mulai);
                                                $end = \Carbon\Carbon::parse($booking->jam_selesai);
                                                $duration = $start->diffInHours($end);
                                                
                                                if ($booking->lapangan && $booking->lapangan->price_per_hour) {
                                                    $displayPrice = $duration * $booking->lapangan->price_per_hour;
                                                } else {
                                                    $defaultPrice = \App\Models\Lapangan::first()->price_per_hour ?? 40000;
                                                    $displayPrice = $duration * $defaultPrice;
                                                }
                                            } catch (\Exception $e) {
                                                if ($booking->lapangan && $booking->lapangan->price_per_hour) {
                                                    $displayPrice = $booking->lapangan->price_per_hour * 1;
                                                } else {
                                                    $defaultPrice = \App\Models\Lapangan::first()->price_per_hour ?? 40000;
                                                    $displayPrice = $defaultPrice * 1;
                                                }
                                            }
                                        }
                                    @endphp
                                    Rp {{ number_format($displayPrice, 0, ',', '.') }}
                                </p>
                                <span class="text-xs px-2 py-1 rounded-full 
                                    @if($booking->status == 'confirmed') bg-green-100 text-green-800
                                    @elseif($booking->status == 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($booking->status == 'completed') bg-blue-100 text-blue-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ $booking->status }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="chart-container">
                    <h3 class="text-xl font-bold text-gray-800 mb-6">Quick Overview</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-blue-50 p-4 rounded-lg text-center">
                            <i class="fas fa-users text-blue-600 text-2xl mb-2"></i>
                            <p class="font-bold text-gray-800">{{ \App\Models\User::count() }}</p>
                            <p class="text-sm text-gray-600">Total Users</p>
                        </div>
                        <div class="bg-green-50 p-4 rounded-lg text-center">
                            <i class="fas fa-map-marker-alt text-green-600 text-2xl mb-2"></i>
                            <p class="font-bold text-gray-800">{{ \App\Models\Lapangan::count() }}</p>
                            <p class="text-sm text-gray-600">Total Lapangan</p>
                        </div>
                        <div class="bg-purple-50 p-4 rounded-lg text-center">
                            <i class="fas fa-check-circle text-purple-600 text-2xl mb-2"></i>
                            <p class="font-bold text-gray-800">{{ \App\Models\Booking::where('status', 'confirmed')->count() }}</p>
                            <p class="text-sm text-gray-600">Confirmed</p>
                        </div>
                        <div class="bg-yellow-50 p-4 rounded-lg text-center">
                            <i class="fas fa-clock text-yellow-600 text-2xl mb-2"></i>
                            <p class="font-bold text-gray-800">{{ \App\Models\Booking::where('status', 'pending')->count() }}</p>
                            <p class="text-sm text-gray-600">Pending</p>
                        </div>
                    </div>
                </div>
            </div>

            @php
                $weeklyData = [];
                $weeklyLabels = [];
                
                for ($i = 6; $i >= 0; $i--) {
                    $date = now()->subDays($i);
                    $dayName = $date->translatedFormat('D');
                    $formattedDate = $date->format('Y-m-d');
                    
                    $bookingCount = \App\Models\Booking::whereDate('tanggal_booking', $formattedDate)->count();
                    
                    $weeklyLabels[] = $dayName;
                    $weeklyData[] = $bookingCount;
                }
                
                $maxValue = count($weeklyData) > 0 ? max($weeklyData) : 1;
            @endphp

            <script>
            const ctx = document.getElementById('weeklyChart').getContext('2d');

            const weeklyChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($weeklyLabels) !!},
                    datasets: [{
                        label: 'Jumlah Booking',
                        data: {!! json_encode($weeklyData) !!},
                        backgroundColor: 'rgba(59, 130, 246, 0.8)',
                        borderColor: 'rgba(59, 130, 246, 1)',
                        borderWidth: 2,
                        borderRadius: 8,
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return `Booking: ${context.parsed.y}`;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            suggestedMax: {{ $maxValue + 1 }},
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)'
                            },
                            title: {
                                display: true,
                                text: 'Jumlah Booking'
                            },
                            ticks: {
                                stepSize: 1,
                                precision: 0
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
            </script>
        </div>
    </div>
</body>
</html>