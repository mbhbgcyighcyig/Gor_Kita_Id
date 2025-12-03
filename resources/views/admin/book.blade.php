<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Booking - GorKita.ID</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
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
    </style>
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <div class="sidebar">
            @include('admin.partials.sidebar')
        </div>
        
        <!-- Main Content -->
        <div class="main-content">
            <!-- Header -->
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Manajemen Booking</h1>
                    <p class="text-gray-600">Kelola semua booking lapangan</p>
                </div>
                <div class="text-right">
                    <p class="text-2xl font-bold text-blue-600">Hi, Admin GOR! 👋</p>
                    <p class="text-gray-500">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
                </div>
            </div>

            <!-- Alert Messages -->
            @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                <i class="fas fa-exclamation-circle mr-2"></i>
                {{ session('error') }}
            </div>
            @endif

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                @php
                    $totalBookings = \App\Models\Booking::count();
                    $todayBookings = \App\Models\Booking::whereDate('tanggal_booking', today())->count();
                    $pendingBookings = \App\Models\Booking::where('status', 'pending')->count();
                    $confirmedBookings = \App\Models\Booking::where('status', 'confirmed')->count();
                @endphp
                
                <!-- Total Booking -->
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
                
                <!-- Booking Hari Ini -->
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
                
                <!-- Menunggu Konfirmasi -->
                <div class="stat-card bg-gradient-to-r from-yellow-500 to-orange-500 p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Menunggu Konfirmasi</p>
                            <p class="text-3xl font-bold">{{ $pendingBookings }}</p>
                            <p class="text-sm opacity-80 mt-2">Perlu tindakan</p>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full p-4">
                            <i class="fas fa-hourglass-half text-2xl"></i>
                        </div>
                    </div>
                </div>
                
                <!-- Terkonfirmasi -->
                <div class="stat-card bg-gradient-to-r from-purple-500 to-pink-500 p-6 text-white">
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
            </div>

            <!-- Booking List -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">Daftar Booking</h2>
                    <div class="flex space-x-4">
                        <select id="statusFilter" class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Semua Status</option>
                            <option value="pending">Menunggu</option>
                            <option value="confirmed">Terkonfirmasi</option>
                            <option value="completed">Selesai</option>
                            <option value="cancelled">Dibatalkan</option>
                        </select>
                        <input type="text" id="searchInput" placeholder="Cari booking..." class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 w-64">
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50 border-b">
                                <th class="py-4 px-6 text-left text-gray-600 font-bold">Booking ID</th>
                                <th class="py-4 px-6 text-left text-gray-600 font-bold">Customer</th>
                                <th class="py-4 px-6 text-left text-gray-600 font-bold">Lapangan</th>
                                <th class="py-4 px-6 text-left text-gray-600 font-bold">Tanggal & Waktu</th>
                                <th class="py-4 px-6 text-left text-gray-600 font-bold">Total</th>
                                <th class="py-4 px-6 text-left text-gray-600 font-bold">Status</th>
                                <th class="py-4 px-6 text-left text-gray-600 font-bold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                // ✅ PERBAIKAN: 'field' -> 'lapangan'
                                $bookings = \App\Models\Booking::with(['user', 'lapangan'])
                                    ->orderBy('created_at', 'desc')
                                    ->get();
                            @endphp
                            
                            @foreach($bookings as $booking)
                            <tr class="border-b hover:bg-gray-50 transition">
                                <td class="py-4 px-6">
                                    <div class="font-bold text-gray-800">#{{ $booking->id }}</div>
                                    <div class="text-sm text-gray-500">{{ $booking->created_at->format('d/m/Y H:i') }}</div>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="font-bold text-gray-800">{{ $booking->user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $booking->user->email }}</div>
                                </td>
                                <td class="py-4 px-6">
                                    <!-- ✅ PERBAIKAN: 'field' -> 'lapangan' -->
                                    @if($booking->lapangan)
                                        <p class="font-bold text-gray-800">{{ $booking->lapangan->name }}</p>
                                        <p class="text-gray-500 text-sm capitalize">{{ $booking->lapangan->type ?? 'Tidak diketahui' }}</p>
                                        <p class="text-xs text-gray-400">Rp {{ number_format($booking->lapangan->price_per_hour, 0, ',', '.') }}/jam</p>
                                    @else
                                        <p class="font-bold text-gray-800">Court #{{ $booking->lapangan_id }}</p>
                                        <p class="text-gray-500 text-sm">Informasi lapangan tidak tersedia</p>
                                        <p class="text-xs text-gray-400">-</p>
                                    @endif
                                </td>
                                <td class="py-4 px-6">
                                    <div class="font-bold text-gray-800">
                                        {{ \Carbon\Carbon::parse($booking->tanggal_booking)->format('d M Y') }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $booking->jam_mulai }} - {{ $booking->jam_selesai }}
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="font-bold text-green-600">
                                        @php
                                            // Hitung harga untuk display
                                            if ($booking->total_price && $booking->total_price > 0) {
                                                $displayPrice = $booking->total_price;
                                            } else {
                                                try {
                                                    $start = \Carbon\Carbon::parse($booking->jam_mulai);
                                                    $end = \Carbon\Carbon::parse($booking->jam_selesai);
                                                    $duration = $start->diffInHours($end);
                                                    
                                                    // ✅ PERBAIKAN: 'field' -> 'lapangan'
                                                    if ($booking->lapangan && $booking->lapangan->price_per_hour) {
                                                        $displayPrice = $duration * $booking->lapangan->price_per_hour;
                                                    } else {
                                                        // ✅ PERBAIKAN: Lapangan bukan Field
                                                        $defaultPrice = \App\Models\Lapangan::first()->price_per_hour ?? 40000;
                                                        $displayPrice = $duration * $defaultPrice;
                                                    }
                                                } catch (\Exception $e) {
                                                    // ✅ PERBAIKAN: 'field' -> 'lapangan'
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
                                <td class="py-4 px-6">
                                    <span class="px-3 py-1 rounded-full text-sm font-bold
                                        @if($booking->status == 'confirmed') bg-green-100 text-green-800
                                        @elseif($booking->status == 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($booking->status == 'completed') bg-blue-100 text-blue-800
                                        @else bg-red-100 text-red-800 @endif">
                                        {{ $booking->status }}
                                    </span>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex space-x-2">
                                        @if($booking->status == 'pending')
                                        <form action="{{ route('admin.bookings.confirm', $booking->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded-lg text-sm transition"
                                                    onclick="return confirm('Konfirmasi booking ini?')">
                                                <i class="fas fa-check mr-1"></i> Confirm
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.bookings.cancel', $booking->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-lg text-sm transition"
                                                    onclick="return confirm('Batalkan booking ini?')">
                                                <i class="fas fa-times mr-1"></i> Cancel
                                            </button>
                                        </form>
                                        @elseif($booking->status == 'confirmed')
                                        <form action="{{ route('admin.bookings.complete', $booking->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-lg text-sm transition"
                                                    onclick="return confirm('Tandai booking sebagai selesai?')">
                                                <i class="fas fa-flag-checkered mr-1"></i> Complete
                                            </button>
                                        </form>
                                        @else
                                        <span class="text-gray-500 text-sm">
                                            @if($booking->status == 'completed')
                                                Selesai
                                            @else
                                                Dibatalkan
                                            @endif
                                        </span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($bookings->count() == 0)
                <div class="text-center py-12">
                    <i class="fas fa-calendar-times text-gray-400 text-5xl mb-4"></i>
                    <p class="text-gray-500 text-lg">Belum ada data booking</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <script>
    // Filter functionality
    document.getElementById('statusFilter').addEventListener('change', function() {
        filterTable();
    });

    document.getElementById('searchInput').addEventListener('input', function() {
        filterTable();
    });

    function filterTable() {
        const statusFilter = document.getElementById('statusFilter').value.toLowerCase();
        const searchInput = document.getElementById('searchInput').value.toLowerCase();
        const rows = document.querySelectorAll('tbody tr');

        rows.forEach(row => {
            const status = row.querySelector('td:nth-child(6)').textContent.toLowerCase();
            const customer = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
            const field = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
            
            const statusMatch = !statusFilter || status.includes(statusFilter);
            const searchMatch = !searchInput || 
                               customer.includes(searchInput) || 
                               field.includes(searchInput) ||
                               row.textContent.toLowerCase().includes(searchInput);

            row.style.display = statusMatch && searchMatch ? '' : 'none';
        });
    }
    </script>
</body>
</html>