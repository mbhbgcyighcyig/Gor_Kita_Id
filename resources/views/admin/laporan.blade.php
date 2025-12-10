<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan - GorKita.ID</title>
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
                    <h1 class="text-3xl font-bold text-gray-800">Laporan & Analitik</h1>
                    <p class="text-gray-600">Analisis data dan statistik bisnis</p>
                </div>
                <div class="text-right">
                    <p class="text-2xl font-bold text-blue-600">Hi, Admin GOR! 👋</p>
                    <p class="text-gray-500">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                @php
                    $totalBookings = \App\Models\Booking::count();
                    $confirmedBookings = \App\Models\Booking::where('status', 'confirmed')->count();
                    $pendingBookings = \App\Models\Booking::where('status', 'pending')->count();
                    
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
                    
                    $avgRevenue = $totalBookings > 0 ? $totalRevenue / $totalBookings : 0;
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
                            <p class="text-sm opacity-90">Terkonfirmasi</p>
                            <p class="text-3xl font-bold">{{ $confirmedBookings }}</p>
                            <p class="text-sm opacity-80 mt-2">Booking aktif</p>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full p-4">
                            <i class="fas fa-check-circle text-2xl"></i>
                        </div>
                    </div>
                </div>
                
                <div class="stat-card bg-gradient-to-r from-yellow-500 to-orange-500 p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Menunggu</p>
                            <p class="text-3xl font-bold">{{ $pendingBookings }}</p>
                            <p class="text-sm opacity-80 mt-2">Perlu konfirmasi</p>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full p-4">
                            <i class="fas fa-clock text-2xl"></i>
                        </div>
                    </div>
                </div>
                
                <div class="stat-card bg-gradient-to-r from-purple-500 to-pink-500 p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Total Pendapatan</p>
                            <p class="text-3xl font-bold">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                            <p class="text-sm opacity-80 mt-2">Rata²: Rp {{ number_format($avgRevenue, 0, ',', '.') }}</p>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full p-4">
                            <i class="fas fa-money-bill-wave text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <div class="chart-container">
                    <h3 class="text-xl font-bold text-gray-800 mb-6">Pendapatan Bulanan</h3>
                    <canvas id="revenueChart" height="250"></canvas>
                </div>

                <div class="chart-container">
                    <h3 class="text-xl font-bold text-gray-800 mb-6">Statistik Booking</h3>
                    <canvas id="bookingChart" height="250"></canvas>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
                <div class="chart-container">
                    <h3 class="text-xl font-bold text-gray-800 mb-6">Lapangan Terpopuler</h3>
                    <div class="space-y-4">
                        @php
                            $topFields = \App\Models\Lapangan::withCount(['bookings' => function($query) {
                                $query->where('status', 'confirmed');
                            }])->orderBy('bookings_count', 'desc')->limit(5)->get();
                        @endphp
                        
                        @foreach($topFields as $field)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-map-marker-alt text-white text-sm"></i>
                                </div>
                                <div>
                                    <p class="font-bold text-gray-800">{{ $field->name }}</p>
                                    <p class="text-sm text-gray-600">{{ $field->type }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-blue-600">{{ $field->bookings_count }}</p>
                                <p class="text-xs text-gray-500">booking</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="chart-container lg:col-span-2">
                    <h3 class="text-xl font-bold text-gray-800 mb-6">Transaksi Terbaru</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-gray-50 border-b">
                                    <th class="py-3 px-4 text-left text-gray-600 font-bold">Booking ID</th>
                                    <th class="py-3 px-4 text-left text-gray-600 font-bold">Customer</th>
                                    <th class="py-3 px-4 text-left text-gray-600 font-bold">Lapangan</th>
                                    <th class="py-3 px-4 text-left text-gray-600 font-bold">Tanggal</th>
                                    <th class="py-3 px-4 text-left text-gray-600 font-bold">Total</th>
                                    <th class="py-3 px-4 text-left text-gray-600 font-bold">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $recentBookings = \App\Models\Booking::with(['user', 'lapangan'])
                                        ->where('status', 'confirmed')
                                        ->orderBy('created_at', 'desc')
                                        ->limit(5)
                                        ->get();
                                @endphp
                                
                                @foreach($recentBookings as $booking)
                                <tr class="border-b hover:bg-gray-50 transition">
                                    <td class="py-3 px-4">
                                        <div class="font-bold text-gray-800">#{{ $booking->id }}</div>
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="font-bold text-gray-800">{{ $booking->user->name }}</div>
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="text-gray-800">{{ $booking->lapangan->name ?? 'Court #' . $booking->lapangan_id }}</div>
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="text-gray-800">{{ \Carbon\Carbon::parse($booking->tanggal_booking)->format('d/m/Y') }}</div>
                                        <div class="text-sm text-gray-500">{{ $booking->jam_mulai }}</div>
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="font-bold text-green-600">
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
                                        </div>
                                    </td>
                                    <td class="py-3 px-4">
                                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-bold">
                                            {{ $booking->status }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="chart-container">
                <h3 class="text-xl font-bold text-gray-800 mb-6">Ekspor Laporan</h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <button class="bg-blue-500 hover:bg-blue-600 text-white py-3 px-4 rounded-lg transition flex items-center justify-center space-x-2">
                        <i class="fas fa-file-pdf"></i>
                        <span>PDF Harian</span>
                    </button>
                    <button class="bg-green-500 hover:bg-green-600 text-white py-3 px-4 rounded-lg transition flex items-center justify-center space-x-2">
                        <i class="fas fa-file-excel"></i>
                        <span>Excel Mingguan</span>
                    </button>
                    <button class="bg-purple-500 hover:bg-purple-600 text-white py-3 px-4 rounded-lg transition flex items-center justify-center space-x-2">
                        <i class="fas fa-file-csv"></i>
                        <span>CSV Bulanan</span>
                    </button>
                    <button class="bg-orange-500 hover:bg-orange-600 text-white py-3 px-4 rounded-lg transition flex items-center justify-center space-x-2">
                        <i class="fas fa-print"></i>
                        <span>Print Laporan</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    @php
        $monthlyRevenueData = [];
        $monthlyLabels = [];
        
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthName = $date->translatedFormat('M');
            $year = $date->format('Y');
            
            $monthBookings = \App\Models\Booking::where('status', 'confirmed')
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $date->month)
                ->with('lapangan')
                ->get();
            
            $monthRevenue = 0;
            foreach ($monthBookings as $booking) {
                if ($booking->total_price && $booking->total_price > 0) {
                    $monthRevenue += $booking->total_price;
                } else {
                    try {
                        $start = \Carbon\Carbon::parse($booking->jam_mulai);
                        $end = \Carbon\Carbon::parse($booking->jam_selesai);
                        $duration = $start->diffInHours($end);
                        
                        if ($booking->lapangan && $booking->lapangan->price_per_hour) {
                            $monthRevenue += $duration * $booking->lapangan->price_per_hour;
                        } else {
                            $defaultPrice = \App\Models\Lapangan::first()->price_per_hour ?? 40000;
                            $monthRevenue += $duration * $defaultPrice;
                        }
                    } catch (\Exception $e) {
                        if ($booking->lapangan && $booking->lapangan->price_per_hour) {
                            $monthRevenue += $booking->lapangan->price_per_hour * 1;
                        } else {
                            $defaultPrice = \App\Models\Lapangan::first()->price_per_hour ?? 40000;
                            $monthRevenue += $defaultPrice * 1;
                        }
                    }
                }
            }
            
            $monthlyLabels[] = $monthName . ' ' . $year;
            $monthlyRevenueData[] = $monthRevenue;
        }

        $bookingStats = [
            'confirmed' => \App\Models\Booking::where('status', 'confirmed')->count(),
            'pending' => \App\Models\Booking::where('status', 'pending')->count(),
            'completed' => \App\Models\Booking::where('status', 'completed')->count(),
            'cancelled' => \App\Models\Booking::where('status', 'cancelled')->count(),
        ];
    @endphp

    <script>
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    const revenueChart = new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($monthlyLabels) !!},
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: {!! json_encode($monthlyRevenueData) !!},
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                borderColor: 'rgba(59, 130, 246, 1)',
                borderWidth: 3,
                tension: 0.4,
                fill: true
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
                            return 'Pendapatan: Rp ' + context.parsed.y.toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)'
                    },
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
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

    const bookingCtx = document.getElementById('bookingChart').getContext('2d');
    const bookingChart = new Chart(bookingCtx, {
        type: 'doughnut',
        data: {
            labels: ['Terkonfirmasi', 'Menunggu', 'Selesai', 'Dibatalkan'],
            datasets: [{
                data: [
                    {{ $bookingStats['confirmed'] }},
                    {{ $bookingStats['pending'] }},
                    {{ $bookingStats['completed'] }},
                    {{ $bookingStats['cancelled'] }}
                ],
                backgroundColor: [
                    'rgba(34, 197, 94, 0.8)',
                    'rgba(234, 179, 8, 0.8)',
                    'rgba(59, 130, 246, 0.8)',
                    'rgba(239, 68, 68, 0.8)'
                ],
                borderColor: [
                    'rgb(34, 197, 94)',
                    'rgb(234, 179, 8)',
                    'rgb(59, 130, 246)',
                    'rgb(239, 68, 68)'
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
    </script>
</body>
</html>