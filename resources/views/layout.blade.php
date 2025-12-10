<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'GotKita.ID')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        /* Animasi untuk navbar */
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Custom gradasi untuk navbar */
        .nav-gradient {
            background: linear-gradient(135deg, #059669 0%, #047857 50%, #065f46 100%);
        }
        
        /* Efek hover pada menu */
        .menu-item {
            position: relative;
            transition: all 0.3s ease;
        }
        
        .menu-item::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            background: #fff;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            transition: width 0.3s ease;
        }
        
        .menu-item:hover::after {
            width: 70%;
        }
        
        /* Animasi logo */
        .logo-icon {
            transition: transform 0.5s ease;
        }
        
        .logo-icon:hover {
            transform: rotate(360deg);
        }
        
        /* Custom badge untuk user */
        .user-badge {
            background: linear-gradient(90deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.1);
        }
        
        /* Efek glassmorphism untuk mobile menu */
        .glassmorphism {
            background: rgba(6, 95, 70, 0.85);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        /* Animasi ikon medsos*/
        .social-icon {
            transition: all 0.3s ease;
        }
        
        .social-icon:hover {
            transform: translateY(-3px) scale(1.1);
        }
    </style>
</head>
<body class="bg-gray-900 text-white">
    

    <nav class="nav-gradient shadow-2xl sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
            
                <div class="flex items-center space-x-4">
                    <a href="{{ route('home') }}" class="flex items-center space-x-4 group">
                        <div class="logo-icon bg-white p-3 rounded-2xl shadow-lg group-hover:shadow-emerald-500/30 transition-all duration-300">
                            <i class="fas fa-futbol text-emerald-600 text-2xl"></i>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-2xl font-bold tracking-tight">GotKita<span class="text-emerald-300">.ID</span></span>
                            <span class="text-xs text-emerald-200 opacity-80">Sport Booking Platform</span>
                        </div>
                    </a>
                </div>
                
                <!-- menu  -->
                <div class="hidden lg:flex items-center space-x-1">
                    <a href="{{ route('home') }}" class="menu-item px-5 py-3 rounded-xl font-medium flex items-center space-x-2 hover:bg-white/10 transition-all duration-300">
                        <i class="fas fa-home text-sm"></i>
                        <span>Home</span>
                    </a>
                    
                    <a href="{{ route('about') }}" class="menu-item px-5 py-3 rounded-xl font-medium flex items-center space-x-2 hover:bg-white/10 transition-all duration-300">
                        <i class="fas fa-info-circle text-sm"></i>
                        <span>About</span>
                    </a>
                    
                    
                    <a href="{{ route('booking.select-field', ['type' => 'badminton']) }}" class="menu-item px-5 py-3 rounded-xl font-medium flex items-center space-x-2 hover:bg-white/10 transition-all duration-300 bg-white/5">
                        <i class="fas fa-calendar-alt text-sm"></i>
                        <span>Booking</span>
                    </a>
                    
                    <a href="{{ route('booking.my-bookings') }}" class="menu-item px-5 py-3 rounded-xl font-medium flex items-center space-x-2 hover:bg-white/10 transition-all duration-300">
                        <i class="fas fa-list text-sm"></i>
                        <span>My Bookings</span>
                    </a>
                    
                    <a href="{{ route('rating.index') }}" class="menu-item px-5 py-3 rounded-xl font-medium flex items-center space-x-2 hover:bg-white/10 transition-all duration-300">
                        <i class="fas fa-star text-sm"></i>
                        <span>Reviews</span>
                    </a>
                </div>
                
                <!-- User Section -->
                <div class="flex items-center space-x-4">
                    @if(session()->has('user_id'))
                        <div class="flex items-center space-x-4">
                            <div class="user-badge px-4 py-2 rounded-xl flex items-center space-x-3">
                                <div class="w-8 h-8 rounded-full bg-emerald-500 flex items-center justify-center">
                                    <i class="fas fa-user text-sm"></i>
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-medium">{{ session('user_name', 'User') }}</span>
                                    <span class="text-xs text-emerald-300">Member</span>
                                </div>
                            </div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white px-5 py-2.5 rounded-xl font-medium flex items-center space-x-2 shadow-lg hover:shadow-red-500/30 transition-all duration-300">
                                    <i class="fas fa-sign-out-alt"></i>
                                    <span>Logout</span>
                                </button>
                            </form>
                        </div>
                    @else
                        <a href="{{ route('user.login') }}" class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-5 py-2.5 rounded-xl font-medium flex items-center space-x-2 shadow-lg hover:shadow-blue-500/30 transition-all duration-300">
                            <i class="fas fa-sign-in-alt"></i>
                            <span>Login</span>
                        </a>
                    @endif
                    
                    <!--menu button -->
                    <button id="mobile-menu-button" class="lg:hidden flex items-center justify-center w-10 h-10 rounded-xl bg-white/10 hover:bg-white/20 transition-all duration-300">
                        <i class="fas fa-bars text-lg"></i>
                    </button>
                </div>
            </div>
            
            <!-- navugasi menu  -->
            <div id="mobile-menu" class="hidden lg:hidden glassmorphism rounded-2xl mt-2 p-4 shadow-2xl animate-slideDown">
                <div class="flex flex-col space-y-2">
                    <a href="{{ route('home') }}" class="px-4 py-3 rounded-xl font-medium flex items-center space-x-3 hover:bg-white/10 transition-all duration-300">
                        <i class="fas fa-home text-emerald-300"></i>
                        <span>Home</span>
                    </a>
                    
                    <a href="{{ route('about') }}" class="px-4 py-3 rounded-xl font-medium flex items-center space-x-3 hover:bg-white/10 transition-all duration-300">
                        <i class="fas fa-info-circle text-emerald-300"></i>
                        <span>About</span>
                    </a>
                    
                    <!-- menu -->
                    <a href="{{ route('booking.select-field', ['type' => 'badminton']) }}" class="px-4 py-3 rounded-xl font-medium flex items-center space-x-3 hover:bg-white/10 transition-all duration-300 bg-white/5">
                        <i class="fas fa-calendar-alt text-emerald-300"></i>
                        <span>Booking</span>
                    </a>
                    
                    <a href="{{ route('booking.my-bookings') }}" class="px-4 py-3 rounded-xl font-medium flex items-center space-x-3 hover:bg-white/10 transition-all duration-300">
                        <i class="fas fa-list text-emerald-300"></i>
                        <span>My Bookings</span>
                    </a>
                    
                    <a href="{{ route('rating.index') }}" class="px-4 py-3 rounded-xl font-medium flex items-center space-x-3 hover:bg-white/10 transition-all duration-300">
                        <i class="fas fa-star text-emerald-300"></i>
                        <span>Reviews</span>
                    </a>
                    
                    @if(session()->has('user_id'))
                        <div class="border-t border-white/20 pt-4 mt-2">
                            <div class="px-4 py-3 rounded-xl bg-white/5 flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-full bg-emerald-500 flex items-center justify-center">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="flex-1">
                                    <div class="font-medium">{{ session('user_name', 'User') }}</div>
                                    <div class="text-xs text-emerald-300">Logged in</div>
                                </div>
                            </div>
                            <form method="POST" action="{{ route('logout') }}" class="mt-2">
                                @csrf
                                <button type="submit" class="w-full px-4 py-3 rounded-xl font-medium flex items-center justify-center space-x-3 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white transition-all duration-300">
                                    <i class="fas fa-sign-out-alt"></i>
                                    <span>Logout</span>
                                </button>
                            </form>
                        </div>
                    @else
                        <a href="{{ route('user.login') }}" class="px-4 py-3 rounded-xl font-medium flex items-center space-x-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white justify-center mt-4">
                            <i class="fas fa-sign-in-alt"></i>
                            <span>Login</span>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="min-h-screen">
        <!-- Alerts -->
        @if(session('error'))
        <div class="max-w-7xl mx-auto mt-6 px-4">
            <div class="bg-red-500/20 border border-red-500/30 rounded-xl p-4">
                <div class="flex items-center space-x-3">
                    <i class="fas fa-exclamation-triangle text-red-400"></i>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
        </div>
        @endif

        @if(session('success'))
        <div class="max-w-7xl mx-auto mt-6 px-4">
            <div class="bg-emerald-500/20 border border-emerald-500/30 rounded-xl p-4">
                <div class="flex items-center space-x-3">
                    <i class="fas fa-check-circle text-emerald-400"></i>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer dengan Media Sosial -->
    <footer class="bg-gray-800 mt-16 py-12">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- GK-->
                <div>
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="bg-white/10 p-3 rounded-xl">
                            <i class="fas fa-futbol text-emerald-400 text-2xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold">GotKita<span class="text-emerald-300">.ID</span></h3>
                    </div>
                    <p class="text-gray-400 mb-6">
                        Platform booking lapangan olahraga premium untuk komunitas sport Indonesia.
                    </p>
                    <!-- Rating -->
                    <div class="flex items-center space-x-2">
                        <div class="flex text-amber-400">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                        <span class="text-gray-400 text-sm">4.8/5 (1.2k reviews)</span>
                    </div>
                </div>
                
                <!-- Contact -->
                <div>
                    <h3 class="text-xl font-bold mb-6">Kontak Kami</h3>
                    <div class="space-y-4 text-gray-400">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-phone text-emerald-400"></i>
                            <span>0882-1001-7726</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-envelope text-emerald-400"></i>
                            <span>alfreandra@gmail.com</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-map-marker-alt text-emerald-400"></i>
                            <span>Kedep, Gunung Putri</span>
                        </div>
                    </div>
                    
                    <!-- Media Sosial -->
                    <div class="mt-8">
                        <h4 class="text-lg font-bold mb-4">Follow Kami</h4>
                        <div class="flex space-x-4">
                            <!-- Facebook -->
                            <a href="#" target="_blank" class="social-icon w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center hover:bg-blue-700 transition-all">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            
                            <!-- Instagram-->
                            <a href="https://www.instagram.com/gorkita.id?igsh=ZnIxZXpwYW10ZGp2" target="_blank" class="social-icon w-10 h-10 rounded-full bg-gradient-to-r from-purple-600 to-pink-500 flex items-center justify-center hover:from-purple-700 hover:to-pink-600 transition-all">
                                <i class="fab fa-instagram"></i>
                            </a>
                            
                            <!-- Twitter -->
                            <a href="#" target="_blank" class="social-icon w-10 h-10 rounded-full bg-black flex items-center justify-center hover:bg-gray-800 transition-all">
                                <i class="fab fa-twitter"></i>
                            </a>
                            
                            <!-- WhatsApp -->
                            <a href="#" target="_blank" class="social-icon w-10 h-10 rounded-full bg-green-500 flex items-center justify-center hover:bg-green-600 transition-all">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                            
                           <!-- YouTube -->
                            <a href="https://www.youtube.com/channel/UC2fzyif7gPufGoGKCRYzRGA" target="_blank" class="social-icon w-10 h-10 rounded-full bg-red-600 flex items-center justify-center hover:bg-red-700 transition-all">
                                <i class="fab fa-youtube"></i>
                            </a>
                        </div>
                    </div>
                </div>
                
                
                <div>
                    <h3 class="text-xl font-bold mb-6">Quick Links</h3>
                    <div class="space-y-3 text-gray-400">
                        <a href="{{ route('home') }}" class="block hover:text-emerald-400 transition-colors">
                            <i class="fas fa-chevron-right text-emerald-400 mr-2"></i>
                            Home
                        </a>
                        <a href="{{ route('booking.select-field', ['type' => 'badminton']) }}" class="block hover:text-emerald-400 transition-colors">
                            <i class="fas fa-chevron-right text-emerald-400 mr-2"></i>
                            Booking Lapangan
                        </a>
                        <a href="{{ route('booking.my-bookings') }}" class="block hover:text-emerald-400 transition-colors">
                            <i class="fas fa-chevron-right text-emerald-400 mr-2"></i>
                            Booking Saya
                        </a>
                        <a href="{{ route('rating.index') }}" class="block hover:text-emerald-400 transition-colors">
                            <i class="fas fa-chevron-right text-emerald-400 mr-2"></i>
                            Ulasan & Rating
                        </a>
                        <a href="{{ route('about') }}" class="block hover:text-emerald-400 transition-colors">
                            <i class="fas fa-chevron-right text-emerald-400 mr-2"></i>
                            Tentang Kami
                        </a>
                    </div>
                </div>
                
                <div>
                    <h3 class="text-xl font-bold mb-6">Jam Operasional</h3>
                    <div class="text-gray-400 mb-8">
                        @php
                            $pengaturan = \App\Models\Pengaturan::first();
                            $jamBuka = $pengaturan->jam_buka ?? '08:00';
                            $jamTutup = $pengaturan->jam_tutup ?? '22:00';
                        @endphp
                        
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span>Setiap Hari:</span>
                                <span class="text-emerald-400">{{ $jamBuka }} - {{ $jamTutup }}</span>
                            </div>
                            <p class="text-amber-300 text-sm mt-4">
                                <i class="fas fa-star mr-2"></i>
                                Buka Setiap Hari, Kiamat Libur
                            </p>
                        </div>
                    </div>
                    
            
  
            <div class="border-t border-gray-700 mt-12 pt-8">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <p class="text-gray-500 mb-4 md:mb-0">
                        &copy; {{ date('Y') }} <span class="text-emerald-300 font-semibold">GotKita.ID</span> - All rights reserved.
                    </p>
                    <div class="flex space-x-6">
                        <a href="#" class="text-gray-400 hover:text-emerald-400 transition-colors text-sm">Privacy Policy</a>
                        <a href="#" class="text-gray-400 hover:text-emerald-400 transition-colors text-sm">Terms of Service</a>
                        <a href="#" class="text-gray-400 hover:text-emerald-400 transition-colors text-sm">FAQ</a>
                        <a href="#" class="text-gray-400 hover:text-emerald-400 transition-colors text-sm">Support</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Simple alert close
        function closeAlert() {
            const alerts = document.querySelectorAll('[id^="alert"]');
            alerts.forEach(alert => {
                alert.remove();
            });
        }
        
        // Auto close alerts after 6 seconds
        setTimeout(() => {
            closeAlert();
        }, 6000);
        
        // Enhanced mobile menu toggle
        document.addEventListener('DOMContentLoaded', function() {
            const menuBtn = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            
            if (menuBtn && mobileMenu) {
                menuBtn.addEventListener('click', function() {
                    mobileMenu.classList.toggle('hidden');
                    mobileMenu.classList.toggle('animate-slideDown');
                    
                    // Ubah ikon tombol menu
                    const icon = menuBtn.querySelector('i');
                    if (mobileMenu.classList.contains('hidden')) {
                        icon.classList.remove('fa-times');
                        icon.classList.add('fa-bars');
                    } else {
                        icon.classList.remove('fa-bars');
                        icon.classList.add('fa-times');
                    }
                });
                
                // Tutup menu mobile saat klik di luar
                document.addEventListener('click', function(event) {
                    if (!menuBtn.contains(event.target) && !mobileMenu.contains(event.target)) {
                        mobileMenu.classList.add('hidden');
                        const icon = menuBtn.querySelector('i');
                        icon.classList.remove('fa-times');
                        icon.classList.add('fa-bars');
                    }
                });
            }
            
            // Efek scroll navbar
            window.addEventListener('scroll', function() {
                const nav = document.querySelector('nav');
                if (window.scrollY > 50) {
                    nav.classList.add('shadow-lg');
                    nav.classList.add('bg-emerald-800');
                    nav.classList.remove('nav-gradient');
                } else {
                    nav.classList.remove('shadow-lg');
                    nav.classList.remove('bg-emerald-800');
                    nav.classList.add('nav-gradient');
                }
            });
            
            // Animasi ikon media sosial saat hover
            const socialIcons = document.querySelectorAll('.social-icon');
            socialIcons.forEach(icon => {
                icon.addEventListener('mouseenter', function() {
                    this.style.boxShadow = '0 10px 20px rgba(0,0,0,0.3)';
                });
                
                icon.addEventListener('mouseleave', function() {
                    this.style.boxShadow = 'none';
                });
            });
        });
    </script>

    @stack('scripts')
    @yield('scripts')
</body>
</html>