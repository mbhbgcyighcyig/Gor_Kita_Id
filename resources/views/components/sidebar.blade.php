<aside class="sidebar">
    <div class="logo">
        <span>🏟️</span> GorKita.id
    </div>

    <nav class="sidebar-nav">
        <a href="/admin/dashboard" class="nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}">
            <i class="fas fa-home mr-2"></i> Dashboard
        </a>
        
        <a href="{{ route('admin.lapangan.index') }}" class="nav-link {{ request()->is('admin/lapangan*') ? 'active' : '' }}">
            <i class="fas fa-futbol mr-2"></i> Lapangan
        </a>
        
        <a href="{{ route('admin.book') }}" class="nav-link {{ request()->is('admin/book*') ? 'active' : '' }}">
            <i class="fas fa-calendar-alt mr-2"></i> Booking
        </a>
        
        <a href="{{ route('admin.pengguna') }}" class="nav-link {{ request()->is('admin/pengguna*') ? 'active' : '' }}">
            <i class="fas fa-users mr-2"></i> Pengguna
        </a>
        
        <a href="{{ route('admin.pembayaran') }}" class="nav-link {{ request()->is('admin/pembayaran*') ? 'active' : '' }}">
            <i class="fas fa-credit-card mr-2"></i> Pembayaran
        </a>
        
        <a href="{{ route('admin.laporan') }}" class="nav-link {{ request()->is('admin/laporan*') ? 'active' : '' }}">
            <i class="fas fa-chart-bar mr-2"></i> Laporan
        </a>
        
        <a href="{{ route('admin.pengaturan') }}" class="nav-link {{ request()->is('admin/pengaturan*') ? 'active' : '' }}">
            <i class="fas fa-cog mr-2"></i> Pengaturan
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="user-info">
            <div class="user-avatar">
                <i class="fas fa-user-circle"></i>
            </div>
            <div class="user-details">
                <div class="user-name">{{ session('user_name', 'Admin') }}</div>
                <div class="user-role">Administrator</div>
            </div>
        </div>
        
        {{-- LOGOUT LINK --}}
        <a href="{{ route('logout') }}" 
           onclick="return confirm('Yakin ingin logout?')"
           class="logout-btn">
            <i class="fas fa-sign-out-alt mr-2"></i> Logout
        </a>
    </div>
</aside>

<style>
.sidebar {
    width: 260px;
    height: 100vh;
    background: linear-gradient(180deg, #1a1f2e 0%, #0f1320 100%);
    border-right: 1px solid rgba(255, 255, 255, 0.1);
    display: flex;
    flex-direction: column;
    position: fixed;
    left: 0;
    top: 0;
    z-index: 1000;
    box-shadow: 4px 0 15px rgba(0, 0, 0, 0.3);
}

.logo {
    padding: 25px 20px;
    font-size: 24px;
    font-weight: 800;
    color: white;
    text-align: center;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    background: rgba(16, 185, 129, 0.1);
}

.logo span {
    font-size: 28px;
    margin-right: 10px;
    background: linear-gradient(45deg, #10b981, #0ea5e9);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.sidebar-nav {
    flex: 1;
    padding: 25px 15px;
    overflow-y: auto;
}

.nav-link {
    display: flex;
    align-items: center;
    padding: 14px 18px;
    color: #cbd5e1;
    text-decoration: none;
    border-radius: 12px;
    margin-bottom: 10px;
    transition: all 0.3s ease;
    font-size: 15px;
    font-weight: 500;
    border: 1px solid transparent;
}

.nav-link:hover {
    background: rgba(16, 185, 129, 0.15);
    color: white;
    border-color: rgba(16, 185, 129, 0.3);
    transform: translateX(5px);
}

.nav-link.active {
    background: linear-gradient(90deg, rgba(16, 185, 129, 0.2), rgba(14, 165, 233, 0.2));
    color: white;
    border: 1px solid rgba(16, 185, 129, 0.4);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
}

.nav-link i {
    width: 20px;
    text-align: center;
    font-size: 16px;
}

.sidebar-footer {
    padding: 20px;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    background: rgba(0, 0, 0, 0.2);
}

.user-info {
    display: flex;
    align-items: center;
    margin-bottom: 20px;
    padding: 12px;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 10px;
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.user-avatar {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #10b981, #0ea5e9);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 12px;
    font-size: 20px;
    color: white;
}

.user-details {
    flex: 1;
}

.user-name {
    font-weight: 600;
    color: white;
    font-size: 14px;
}

.user-role {
    font-size: 12px;
    color: #94a3b8;
    margin-top: 2px;
}

.logout-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    padding: 14px;
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: white;
    border: none;
    border-radius: 12px;
    font-weight: 600;
    font-size: 15px;
    cursor: pointer;
    text-decoration: none;
    transition: all 0.3s ease;
    border: 1px solid rgba(239, 68, 68, 0.3);
}

.logout-btn:hover {
    background: linear-gradient(135deg, #dc2626, #b91c1c);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(220, 38, 38, 0.3);
    text-decoration: none;
    color: white;
}

/* Scrollbar styling */
.sidebar-nav::-webkit-scrollbar {
    width: 5px;
}

.sidebar-nav::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.05);
    border-radius: 10px;
}

.sidebar-nav::-webkit-scrollbar-thumb {
    background: rgba(16, 185, 129, 0.5);
    border-radius: 10px;
}

.sidebar-nav::-webkit-scrollbar-thumb:hover {
    background: rgba(16, 185, 129, 0.8);
}

/* Responsive */
@media (max-width: 768px) {
    .sidebar {
        width: 70px;
        overflow: hidden;
    }
    
    .logo span {
        font-size: 24px;
        margin-right: 0;
    }
    
    .logo {
        font-size: 0;
        padding: 25px 10px;
    }
    
    .nav-link {
        padding: 15px;
        justify-content: center;
    }
    
    .nav-link span {
        display: none;
    }
    
    .nav-link i {
        margin-right: 0;
        font-size: 18px;
    }
    
    .sidebar-footer {
        padding: 15px 10px;
    }
    
    .user-info {
        flex-direction: column;
        text-align: center;
        padding: 10px;
    }
    
    .user-avatar {
        margin-right: 0;
        margin-bottom: 8px;
    }
    
    .user-details {
        display: none;
    }
    
    .logout-btn span {
        display: none;
    }
    
    .logout-btn i {
        margin-right: 0;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Active link highlight
    const currentPath = window.location.pathname;
    const navLinks = document.querySelectorAll('.nav-link');
    
    navLinks.forEach(link => {
        if (link.getAttribute('href') === currentPath) {
            link.classList.add('active');
        }
        
        // For nested routes
        const href = link.getAttribute('href');
        if (href && currentPath.startsWith(href) && href !== '/') {
            link.classList.add('active');
        }
    });
    
    // Logout confirmation
    const logoutBtn = document.querySelector('.logout-btn');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', function(e) {
            if (!confirm('Yakin ingin logout dari sistem?')) {
                e.preventDefault();
            }
        });
    }
});
</script>