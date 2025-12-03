<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Pembayaran - GorKita.ID</title>
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
                    <h1 class="text-3xl font-bold text-gray-800">Manajemen Pembayaran</h1>
                    <p class="text-gray-600">Kelola dan verifikasi pembayaran booking</p>
                </div>
                <div class="text-right">
                    <p class="text-2xl font-bold text-blue-600">Hi, Admin SportSpace! 👋</p>
                    <p class="text-gray-500">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
                </div>
            </div>

            <!-- Alert Messages -->
            @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 animate-pulse">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
            </div>
            @endif

            @if(session('warning'))
            <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded-lg mb-6">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                {{ session('warning') }}
            </div>
            @endif

            @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6 animate-pulse">
                <i class="fas fa-exclamation-circle mr-2"></i>
                {{ session('error') }}
            </div>
            @endif

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                @php
                    $pendingVerification = \App\Models\Booking::where('payment_status', 'pending_verification')->count();
                    $pendingPayments = \App\Models\Booking::where('payment_status', 'pending')->count();
                    $paidPayments = \App\Models\Booking::where('payment_status', 'paid')->count();
                    $failedPayments = \App\Models\Booking::where('payment_status', 'failed')->count();
                    $expiredPayments = \App\Models\Booking::where('status', 'expired')->count();
                    
                    $allBookings = \App\Models\Booking::where('payment_status', 'paid')
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
                
                <!-- Pending Verification -->
                <div class="stat-card bg-gradient-to-r from-yellow-500 to-orange-500 p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Pending Verification</p>
                            <p class="text-3xl font-bold">{{ $pendingVerification }}</p>
                            <p class="text-sm opacity-80 mt-2">Perlu konfirmasi</p>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full p-4">
                            <i class="fas fa-clock text-2xl"></i>
                        </div>
                    </div>
                </div>
                
                <!-- Paid Payments -->
                <div class="stat-card bg-gradient-to-r from-green-500 to-emerald-500 p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Pembayaran Sukses</p>
                            <p class="text-3xl font-bold">{{ $paidPayments }}</p>
                            <p class="text-sm opacity-80 mt-2">Terkonfirmasi</p>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full p-4">
                            <i class="fas fa-check-circle text-2xl"></i>
                        </div>
                    </div>
                </div>
                
                <!-- Failed/Expired -->
                <div class="stat-card bg-gradient-to-r from-red-500 to-pink-500 p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Gagal/Expired</p>
                            <p class="text-3xl font-bold">{{ $failedPayments + $expiredPayments }}</p>
                            <p class="text-sm opacity-80 mt-2">Dibatalkan/tolak</p>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full p-4">
                            <i class="fas fa-times-circle text-2xl"></i>
                        </div>
                    </div>
                </div>
                
                <!-- Total Revenue -->
                <div class="stat-card bg-gradient-to-r from-blue-500 to-cyan-500 p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Total Pendapatan</p>
                            <p class="text-3xl font-bold">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                            <p class="text-sm opacity-80 mt-2">Dari pembayaran sukses</p>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full p-4">
                            <i class="fas fa-money-bill-wave text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment List -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">Daftar Pembayaran</h2>
                    <div class="flex space-x-4">
                        <select id="statusFilter" class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Semua Status</option>
                            <option value="pending_verification">Pending Verification</option>
                            <option value="pending">Pending</option>
                            <option value="paid">Sukses</option>
                            <option value="failed">Gagal</option>
                            <option value="expired">Expired</option>
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
                                $payments = \App\Models\Booking::with(['user', 'lapangan'])
                                    ->orderBy('created_at', 'desc')
                                    ->get();
                            @endphp
                            
                            @foreach($payments as $payment)
                            @php
                                // Hitung harga untuk display
                                if ($payment->total_price && $payment->total_price > 0) {
                                    $displayPrice = $payment->total_price;
                                } else {
                                    try {
                                        $start = \Carbon\Carbon::parse($payment->jam_mulai);
                                        $end = \Carbon\Carbon::parse($payment->jam_selesai);
                                        $duration = $start->diffInHours($end);
                                        
                                        if ($payment->lapangan && $payment->lapangan->price_per_hour) {
                                            $displayPrice = $duration * $payment->lapangan->price_per_hour;
                                        } else {
                                            $defaultPrice = \App\Models\Lapangan::first()->price_per_hour ?? 40000;
                                            $displayPrice = $duration * $defaultPrice;
                                        }
                                    } catch (\Exception $e) {
                                        if ($payment->lapangan && $payment->lapangan->price_per_hour) {
                                            $displayPrice = $payment->lapangan->price_per_hour * 1;
                                        } else {
                                            $defaultPrice = \App\Models\Lapangan::first()->price_per_hour ?? 40000;
                                            $displayPrice = $defaultPrice * 1;
                                        }
                                    }
                                }
                                
                                // Cek apakah bisa di-action
                                $canConfirm = in_array($payment->payment_status, ['pending_verification', 'pending']) 
                                              && !in_array($payment->status, ['cancelled', 'expired', 'completed']);
                                
                                // Cek apakah expired
                                $isExpired = false;
                                if ($payment->payment_expiry && $payment->payment_expiry < now()) {
                                    $isExpired = true;
                                }
                                
                                // Cek tanggal booking sudah lewat
                                try {
                                    $bookingDateTime = \Carbon\Carbon::parse($payment->tanggal_booking . ' ' . $payment->jam_selesai);
                                    if ($bookingDateTime < now()) {
                                        $isExpired = true;
                                    }
                                } catch (\Exception $e) {}
                                
                                // Status badge class
                                $statusClass = '';
                                if ($payment->payment_status == 'paid') {
                                    $statusClass = 'bg-green-100 text-green-800';
                                } elseif ($payment->payment_status == 'pending_verification') {
                                    $statusClass = 'bg-yellow-100 text-yellow-800';
                                } elseif ($payment->payment_status == 'pending') {
                                    $statusClass = 'bg-blue-100 text-blue-800';
                                } else {
                                    $statusClass = 'bg-red-100 text-red-800';
                                }
                                
                                // Status booking class
                                $bookingStatusClass = '';
                                if ($payment->status == 'confirmed') {
                                    $bookingStatusClass = 'bg-green-100 text-green-800';
                                } elseif ($payment->status == 'pending_verification') {
                                    $bookingStatusClass = 'bg-yellow-100 text-yellow-800';
                                } elseif ($payment->status == 'pending') {
                                    $bookingStatusClass = 'bg-blue-100 text-blue-800';
                                } elseif ($payment->status == 'expired') {
                                    $bookingStatusClass = 'bg-red-100 text-red-800';
                                } elseif ($payment->status == 'cancelled') {
                                    $bookingStatusClass = 'bg-gray-100 text-gray-800';
                                } elseif ($payment->status == 'completed') {
                                    $bookingStatusClass = 'bg-purple-100 text-purple-800';
                                }
                            @endphp
                            <tr class="border-b hover:bg-gray-50 transition" 
                                data-status="{{ $payment->payment_status }}"
                                data-search="{{ strtolower($payment->user->name . ' ' . $payment->lapangan->name . ' #' . $payment->id) }}">
                                <td class="py-4 px-6">
                                    <div class="font-bold text-gray-800">#{{ $payment->id }}</div>
                                    <div class="text-sm text-gray-500">{{ $payment->created_at->format('d/m/Y H:i') }}</div>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="font-bold text-gray-800">{{ $payment->user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $payment->user->email }}</div>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="font-bold text-gray-800">{{ $payment->lapangan->name ?? 'Court #' . $payment->lapangan_id }}</div>
                                    <div class="text-sm text-gray-500">{{ $payment->lapangan->type ?? 'Tidak diketahui' }}</div>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="font-bold text-gray-800">{{ \Carbon\Carbon::parse($payment->tanggal_booking)->translatedFormat('d M Y') }}</div>
                                    <div class="text-sm text-gray-500">{{ $payment->jam_mulai }} - {{ $payment->jam_selesai }}</div>
                                    @if($isExpired && in_array($payment->payment_status, ['pending_verification', 'pending']))
                                    <div class="text-xs text-red-500 font-bold mt-1">
                                        <i class="fas fa-exclamation-triangle mr-1"></i> EXPIRED
                                    </div>
                                    @endif
                                </td>
                                <td class="py-4 px-6">
                                    <div class="font-bold text-green-600">
                                        Rp {{ number_format($displayPrice, 0, ',', '.') }}
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex flex-col space-y-1">
                                        <span class="px-3 py-1 rounded-full text-xs font-bold {{ $statusClass }}">
                                            Payment: {{ $payment->payment_status }}
                                        </span>
                                        <span class="px-3 py-1 rounded-full text-xs font-bold {{ $bookingStatusClass }}">
                                            Booking: {{ $payment->status }}
                                        </span>
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex space-x-2">
                                        @if($canConfirm && !$isExpired)
                                        <!-- Konfirmasi Button -->
                                        <form action="{{ route('admin.confirmPayment', $payment->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" 
                                                    class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition flex items-center"
                                                    onclick="return confirm('Konfirmasi pembayaran ini? Booking akan aktif.')">
                                                <i class="fas fa-check mr-2"></i> Setujui
                                            </button>
                                        </form>
                                        
                                        <!-- Tolak Button -->
                                        <form action="{{ route('admin.rejectPayment', $payment->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" 
                                                    class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition flex items-center"
                                                    onclick="return confirm('Yakin tolak pembayaran ini? Status akan menjadi cancelled.')">
                                                <i class="fas fa-times mr-2"></i> Tolak
                                            </button>
                                        </form>
                                        @elseif($isExpired && in_array($payment->payment_status, ['pending_verification', 'pending']))
                                        <!-- Auto Expire Button -->
                                        <form action="{{ route('admin.rejectPayment', $payment->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" 
                                                    class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition flex items-center"
                                                    onclick="return confirm('Tandai sebagai expired? Status akan otomatis expired.')">
                                                <i class="fas fa-clock mr-2"></i> Expire
                                            </button>
                                        </form>
                                        @else
                                        <!-- Status Info -->
                                        <div class="flex flex-col">
                                            @if($payment->payment_status == 'paid')
                                            <span class="px-3 py-1 bg-green-100 text-green-800 rounded-lg text-sm">
                                                <i class="fas fa-check-circle mr-1"></i> Sudah Dibayar
                                            </span>
                                            <span class="text-xs text-gray-500 mt-1">
                                                {{ $payment->paid_at ? $payment->paid_at->format('d/m/Y H:i') : '' }}
                                            </span>
                                            @elseif(in_array($payment->status, ['expired', 'cancelled']))
                                            <span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-lg text-sm">
                                                @if($payment->status == 'expired')
                                                <i class="fas fa-clock mr-1"></i> Expired
                                                @else
                                                <i class="fas fa-ban mr-1"></i> Ditolak/Batal
                                                @endif
                                            </span>
                                            @else
                                            <span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-lg text-sm">
                                                <i class="fas fa-info-circle mr-1"></i> Tidak Bisa Diproses
                                            </span>
                                            @endif
                                        </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($payments->count() == 0)
                <div class="text-center py-12">
                    <i class="fas fa-receipt text-gray-400 text-5xl mb-4"></i>
                    <p class="text-gray-500 text-lg">Belum ada data pembayaran</p>
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
            const status = row.getAttribute('data-status') || '';
            const searchText = row.getAttribute('data-search') || '';
            
            const statusMatch = !statusFilter || status.includes(statusFilter);
            const searchMatch = !searchInput || searchText.includes(searchInput);

            row.style.display = statusMatch && searchMatch ? '' : 'none';
        });
    }
    
    // Auto refresh setiap 30 detik untuk update status
    setInterval(function() {
        // Cek jika ada pending_verification, refresh halaman
        const hasPending = document.querySelector('[data-status="pending_verification"], [data-status="pending"]');
        if (hasPending) {
            // Hanya refresh jika user tidak sedang interaksi
            if (!document.querySelector('button:hover, form:hover')) {
                location.reload();
            }
        }
    }, 30000); // 30 detik
    
    // Debug function
    function debugBooking(id) {
        alert('Debug Booking #' + id);
        // Bisa ditambahkan fetch request untuk debug
    }
    </script>
</body>
</html>