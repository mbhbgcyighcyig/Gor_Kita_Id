<!-- Sidebar -->
<aside class="sidebar w-64 min-h-screen text-white p-6 fixed">
    <div class="logo mb-10">
        <div class="flex items-center space-x-3 text-2xl font-bold">
            <span class="text-3xl">🏟️</span> 
            <span>GorKita.ID</span>
        </div>
    </div>
    
    <nav class="space-y-3">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 p-3 rounded-lg transition {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="fas fa-home w-6 text-center"></i>
            <span>Dashboard</span>
        </a>
        
        <a href="{{ route('admin.lapangan.index') }}" class="flex items-center space-x-3 p-3 rounded-lg transition hover:bg-red-500/10 {{ request()->routeIs('admin.lapangan.*') ? 'active' : '' }}">
            <i class="fas fa-futbol w-6 text-center"></i>
            <span>Lapangan</span>
        </a>
        
        <a href="{{ route('admin.book') }}" class="flex items-center space-x-3 p-3 rounded-lg transition hover:bg-red-500/10 {{ request()->routeIs('admin.book') ? 'active' : '' }}">
            <i class="fas fa-calendar-alt w-6 text-center"></i>
            <span>Booking</span>
        </a>
        
        <a href="{{ route('admin.users.index') }}" class="flex items-center space-x-3 p-3 rounded-lg transition hover:bg-red-500/10 {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <i class="fas fa-users w-6 text-center"></i>
            <span>Pengguna</span>
        </a>
        
        <a href="{{ route('admin.pembayaran') }}" class="flex items-center space-x-3 p-3 rounded-lg transition hover:bg-red-500/10 {{ request()->routeIs('admin.pembayaran') ? 'active' : '' }}">
            <i class="fas fa-credit-card w-6 text-center"></i>
            <span>Pembayaran</span>
        </a>
        
        <a href="{{ route('admin.laporan') }}" class="flex items-center space-x-3 p-3 rounded-lg transition hover:bg-red-500/10 {{ request()->routeIs('admin.laporan') ? 'active' : '' }}">
            <i class="fas fa-chart-bar w-6 text-center"></i>
            <span>Laporan</span>
        </a>
        
        <a href="{{ route('admin.pengaturan') }}" class="flex items-center space-x-3 p-3 rounded-lg transition hover:bg-red-500/10 {{ request()->routeIs('admin.pengaturan') ? 'active' : '' }}">
            <i class="fas fa-cog w-6 text-center"></i>
            <span>Pengaturan</span>
        </a>
    </nav>

    <form method="POST" action="{{ route('logout') }}" class="mt-10">
        @csrf
        <button type="submit" class="flex items-center space-x-3 p-3 rounded-lg transition w-full text-left hover:bg-red-500/20">
            <i class="fas fa-sign-out-alt w-6 text-center"></i>
            <span>Logout</span>
        </button>
    </form>
</aside>

<style>
    .sidebar {
        background: linear-gradient(180deg, #1f2937 0%, #111827 100%);
    }
    .sidebar a.active {
        background: rgba(239, 68, 68, 0.2);
        border-left: 4px solid #ef4444;
        color: #ef4444;
    }
    .sidebar a:hover {
        background: rgba(239, 68, 68, 0.1);
    }
</style>
