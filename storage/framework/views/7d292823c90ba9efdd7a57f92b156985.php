<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SportSpace</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');
        body {
            font-family: 'Inter', sans-serif;
        }
        .gradient-bg {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
        }
        .sport-pattern {
            background-color: black;
        }
        
        /* Animasi Kustom */
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(5deg); }
        }
        @keyframes glow {
            0%, 100% { box-shadow: 0 0 20px rgba(16, 185, 129, 0.3); }
            50% { box-shadow: 0 0 40px rgba(16, 185, 129, 0.6); }
        }
        @keyframes bounceIn {
            0% { transform: scale(0.3); opacity: 0; }
            50% { transform: scale(1.05); }
            70% { transform: scale(0.9); }
            100% { transform: scale(1); opacity: 1; }
        }
        @keyframes slideInLeft {
            from { transform: translateX(-100px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes slideInRight {
            from { transform: translateX(100px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes pulse-glow {
            0%, 100% { 
                transform: scale(1);
                box-shadow: 0 0 20px rgba(16, 185, 129, 0.3);
            }
            50% { 
                transform: scale(1.1);
                box-shadow: 0 0 40px rgba(16, 185, 129, 0.6);
            }
        }
        @keyframes typewriter {
            from { width: 0; }
            to { width: 100%; }
        }
        @keyframes blink-cursor {
            from, to { border-color: transparent; }
            50% { border-color: #10b981; }
        }

        .float-animation {
            animation: float 6s ease-in-out infinite;
        }
        .glow-animation {
            animation: glow 3s ease-in-out infinite;
        }
        .bounce-in {
            animation: bounceIn 1s ease-out;
        }
        .slide-in-left {
            animation: slideInLeft 1s ease-out;
        }
        .slide-in-right {
            animation: slideInRight 1s ease-out;
        }
        .pulse-glow {
            animation: pulse-glow 2s ease-in-out infinite;
        }
        .typewriter {
            overflow: hidden;
            border-right: 3px solid #10b981;
            white-space: nowrap;
            animation: typewriter 3s steps(40) 1s both, blink-cursor 0.75s step-end infinite;
        }
        
        /* Fix untuk checkbox */
        .checkbox-container input:checked + .checkbox-toggle {
            background-color: rgba(16, 185, 129, 0.2);
            border-color: #10b981;
        }
        .checkbox-container input:checked + .checkbox-toggle + .checkbox-checked {
            opacity: 1;
            transform: scale(1);
        }
    </style>
</head>
<body class="gradient-bg sport-pattern min-h-screen flex items-center justify-center p-4 overflow-hidden">
    <!-- Animated Background Elements -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-emerald-500/10 rounded-full blur-3xl float-animation"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-cyan-500/10 rounded-full blur-3xl float-animation" style="animation-delay: 2s;"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-emerald-400/5 rounded-full blur-3xl float-animation" style="animation-delay: 4s;"></div>
        
        <!-- Moving Sports Balls -->
        <div class="absolute top-20 left-10 w-12 h-12 bg-white rounded-full shadow-lg bounce-in">
            <div class="w-full h-full bg-gradient-to-br from-emerald-400 to-cyan-400 rounded-full flex items-center justify-center">
                <div class="w-4 h-4 bg-white rounded-full opacity-30"></div>
            </div>
        </div>
        <div class="absolute top-40 right-20 w-10 h-10 bg-white rounded-full shadow-lg bounce-in" style="animation-delay: 0.5s;">
            <div class="w-full h-full bg-gradient-to-br from-cyan-400 to-emerald-400 rounded-full flex items-center justify-center">
                <div class="w-3 h-3 bg-white rounded-full opacity-30"></div>
            </div>
        </div>
        <div class="absolute bottom-32 left-20 w-14 h-14 bg-white rounded-full shadow-lg bounce-in" style="animation-delay: 1s;">
            <div class="w-full h-full bg-gradient-to-br from-emerald-400 to-cyan-400 rounded-full flex items-center justify-center">
                <div class="w-5 h-5 bg-white rounded-full opacity-30"></div>
            </div>
        </div>
    </div>

    <!-- Main Container -->
    <div class="relative w-full max-w-6xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
            
            <!-- Left Side - Hero Section -->
            <div class="text-white space-y-8 slide-in-left">
                <!-- Logo & Brand -->
                <div class="flex items-center space-x-4 mb-12 bounce-in">
                    <div class="w-16 h-16 bg-gradient-to-br from-emerald-500 to-cyan-500 rounded-2xl flex items-center justify-center border border-emerald-400/30 shadow-2xl pulse-glow">
                        <i class="fas fa-futbol text-white text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-4xl font-black bg-gradient-to-r from-emerald-300 to-cyan-400 bg-clip-text text-transparent">GorKita.ID</h1>
                        <p class="text-emerald-200 font-semibold">Championship Experience</p>
                    </div>
                </div>

                <!-- Main Heading -->
                <div class="space-y-6">
                    <h2 class="text-5xl lg:text-6xl font-black leading-tight">
                        <span class="typewriter">Kemenanganmu</span><br>
                        <span class="bg-gradient-to-r from-emerald-300 to-cyan-400 bg-clip-text text-transparent typewriter" style="animation-delay: 3.5s;">Dimulai</span><br>
                        <span class="typewriter" style="animation-delay: 5s;">Dari Sini</span>
                    </h2>
                    <p class="text-xl text-gray-300 leading-relaxed slide-in-left" style="animation-delay: 6.5s;">
                        Akses lapangan premium untuk sesi olahraga grade Sang Juara. 
                        Booking mudah, bermain maksimal.
                    </p>
                </div>

                <!-- Features -->
                <div class="grid grid-cols-2 gap-6 pt-8">
                    <div class="flex items-center space-x-3 bounce-in" style="animation-delay: 7s;">
                        <div class="w-10 h-10 bg-emerald-500/20 rounded-2xl flex items-center justify-center border border-emerald-500/30 hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-calendar-check text-emerald-400"></i>
                        </div>
                        <span class="text-emerald-200 font-semibold">Booking Mudah</span>
                    </div>
                    <div class="flex items-center space-x-3 bounce-in" style="animation-delay: 7.2s;">
                        <div class="w-10 h-10 bg-cyan-500/20 rounded-2xl flex items-center justify-center border border-cyan-500/30 hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-trophy text-cyan-400"></i>
                        </div>
                        <span class="text-cyan-200 font-semibold">Lapangan Berkualitas</span>
                    </div>
                    <div class="flex items-center space-x-3 bounce-in" style="animation-delay: 7.4s;">
                        <div class="w-10 h-10 bg-emerald-500/20 rounded-2xl flex items-center justify-center border border-emerald-500/30 hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-shield-alt text-emerald-400"></i>
                        </div>
                        <span class="text-emerald-200 font-semibold">Pembayaran aman</span>
                    </div>
                    <div class="flex items-center space-x-3 bounce-in" style="animation-delay: 7.6s;">
                        <div class="w-10 h-10 bg-cyan-500/20 rounded-2xl flex items-center justify-center border border-cyan-500/30 hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-star text-cyan-400"></i>
                        </div>
                        <span class="text-cyan-200 font-semibold">5★ Rating</span>
                    </div>
                </div>

                <!-- Sports Icons -->
                <div class="flex items-center space-x-6 pt-12">
                    <div class="text-center group bounce-in" style="animation-delay: 8s;">
                        <div class="w-16 h-16 bg-gradient-to-br from-emerald-500/20 to-cyan-500/20 rounded-2xl flex items-center justify-center border border-emerald-500/30 group-hover:scale-110 transition-transform duration-300 mb-2 pulse-glow">
                            <i class="fas fa-futbol text-emerald-400 text-xl"></i>
                        </div>
                        <span class="text-sm text-gray-400 group-hover:text-emerald-300 transition-colors">Futsal</span>
                    </div>
                    <div class="text-center group bounce-in" style="animation-delay: 8.2s;">
                        <div class="w-16 h-16 bg-gradient-to-br from-cyan-500/20 to-emerald-500/20 rounded-2xl flex items-center justify-center border border-cyan-500/30 group-hover:scale-110 transition-transform duration-300 mb-2 pulse-glow">
                            <i class="fas fa-table-tennis-paddle-ball text-cyan-400 text-xl"></i>
                        </div>
                        <span class="text-sm text-gray-400 group-hover:text-cyan-300 transition-colors">Badminton</span>
                    </div>
                    <div class="text-center group bounce-in" style="animation-delay: 8.4s;">
                        <div class="w-16 h-16 bg-gradient-to-br from-emerald-500/20 to-cyan-500/20 rounded-2xl flex items-center justify-center border border-emerald-500/30 group-hover:scale-110 transition-transform duration-300 mb-2 pulse-glow">
                            <i class="fas fa-futbol text-emerald-400 text-xl"></i>
                        </div>
                        <span class="text-sm text-gray-400 group-hover:text-emerald-300 transition-colors">Mini Soccer</span>
                    </div>
                </div>
            </div>

            <!-- Right Side - Login Card -->
            <div class="relative slide-in-right">
                <div class="bg-gray-900/80 backdrop-blur-xl rounded-3xl border border-emerald-500/20 p-8 shadow-2xl hover:shadow-emerald-500/20 transition-all duration-500">
                    <!-- Header -->
                    <div class="text-center mb-8 bounce-in">
                        <div class="w-20 h-20 bg-gradient-to-br from-emerald-500 to-cyan-500 rounded-3xl flex items-center justify-center mx-auto mb-4 border border-emerald-400/30 shadow-2xl pulse-glow">
                            <i class="fas fa-sign-in-alt text-white text-2xl"></i>
                        </div>
                        <h2 class="text-3xl font-black text-white mb-2">Welcome Back! 🏆</h2>
                        <p class="text-gray-400">Login untuk akses lapangan premium</p>
                    </div>

                    <!-- Error Messages -->
                    <?php if($errors->any()): ?>
                        <div class="bg-red-500/10 border border-red-500/30 rounded-2xl p-4 mb-6 backdrop-blur-sm bounce-in">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-red-500/20 rounded-xl flex items-center justify-center border border-red-500/30">
                                    <i class="fas fa-exclamation-triangle text-red-400 text-sm"></i>
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-red-200 font-bold text-sm mb-1">Login Gagal</h4>
                                    <ul class="text-red-300 text-xs">
                                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li><?php echo e($error); ?></li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Login Form -->
                    <!-- PERBAIKAN DI SINI: route('user.login') bukan route('login') -->
                    <form method="POST" action="<?php echo e(route('user.login')); ?>" class="space-y-6">
                        <?php echo csrf_field(); ?>
                        
                        <!-- Email Input -->
                        <div class="group bounce-in" style="animation-delay: 0.3s;">
                            <label class="block text-emerald-200 text-sm font-bold mb-3 flex items-center space-x-2">
                                <div class="w-5 h-5 bg-emerald-500/20 rounded-lg flex items-center justify-center border border-emerald-500/30">
                                    <i class="fas fa-envelope text-emerald-400 text-xs"></i>
                                </div>
                                <span>Masukan Email</span>
                            </label>
                            <div class="relative">
                                <input class="w-full bg-gray-800/50 border border-gray-700 text-white px-6 py-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500/30 transition placeholder-gray-500 backdrop-blur-sm" 
                                       id="email" 
                                       type="email" 
                                       name="email" 
                                       value="<?php echo e(old('email')); ?>"
                                       placeholder="athlete@champion.com" 
                                       required
                                       autocomplete="email">
                                <div class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-500 group-focus-within:text-emerald-400 transition-colors">
                                    <i class="fas fa-user-circle"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Password Input -->
                        <div class="group bounce-in" style="animation-delay: 0.5s;">
                            <label class="block text-cyan-200 text-sm font-bold mb-3 flex items-center space-x-2">
                                <div class="w-5 h-5 bg-cyan-500/20 rounded-lg flex items-center justify-center border border-cyan-500/30">
                                    <i class="fas fa-lock text-cyan-400 text-xs"></i>
                                </div>
                                <span>Password</span>
                            </label>
                            <div class="relative">
                                <input class="w-full bg-gray-800/50 border border-gray-700 text-white px-6 py-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500/30 transition placeholder-gray-500 backdrop-blur-sm" 
                                       id="password" 
                                       type="password" 
                                       name="password"
                                       placeholder="••••••••" 
                                       required
                                       autocomplete="current-password">
                                <div class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-500 group-focus-within:text-cyan-400 transition-colors">
                                    <i class="fas fa-key"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Remember Me & Forgot Password -->
                        <div class="flex items-center justify-between bounce-in" style="animation-delay: 0.7s;">
                            <label class="flex items-center space-x-3 cursor-pointer group checkbox-container">
                                <input type="checkbox" name="remember" class="sr-only">
                                <div class="w-5 h-5 bg-gray-700 border border-gray-600 rounded-lg group-hover:border-emerald-500 transition-colors checkbox-toggle"></div>
                                <div class="absolute inset-0 flex items-center justify-center opacity-0 scale-0 transition-all duration-200 checkbox-checked">
                                    <i class="fas fa-check text-emerald-400 text-xs"></i>
                                </div>
                                <span class="text-gray-400 text-sm group-hover:text-emerald-300 transition-colors">Ingat Saya</span>
                            </label>
                            <a href="#" class="text-cyan-400 hover:text-cyan-300 text-sm font-semibold transition-colors">
                                Lupa Password?
                            </a>
                        </div>

                        <!-- Submit Button -->
                        <button class="w-full bg-gradient-to-r from-emerald-600 to-cyan-600 hover:from-emerald-500 hover:to-cyan-500 text-white font-bold py-4 rounded-2xl transition-all duration-300 border border-emerald-500/30 shadow-2xl group pulse-glow bounce-in" 
                                style="animation-delay: 0.9s;"
                                type="submit">
                            <div class="flex items-center justify-center space-x-3">
                                <i class="fas fa-sign-in-alt"></i>
                                <span class="text-lg">Login to Victory</span>
                                <i class="fas fa-arrow-right"></i>
                            </div>
                        </button>
                    </form>

                    <!-- Divider -->
                    <div class="flex items-center my-8 bounce-in" style="animation-delay: 1.1s;">
                        <div class="flex-1 border-t border-gray-700"></div>
                        <span class="px-4 text-gray-500 text-sm">ATAU</span>
                        <div class="flex-1 border-t border-gray-700"></div>
                    </div>

                    <!-- Register Link -->
                    <div class="text-center bounce-in" style="animation-delay: 1.3s;">
                        <p class="text-gray-400 mb-4">Belum Punya Akun? yaa Daftarlahh</p>
                        <a href="<?php echo e(route('register')); ?>" class="inline-flex items-center space-x-3 bg-gray-800/50 hover:bg-gray-700/50 text-cyan-300 hover:text-cyan-200 font-bold py-3 px-6 rounded-2xl transition-all duration-300 border border-cyan-500/30 hover:border-cyan-400/30 group">
                            <i class="fas fa-user-plus"></i>
                            <span>Daftar Akun Baru</span>
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>

                    <!-- Admin Login -->
                    <div class="text-center mt-8 pt-6 border-t border-gray-700/50 bounce-in" style="animation-delay: 1.5s;">
                        <!-- PERBAIKAN DI SINI JUGA: url('/admin/login') sudah benar -->
                        <a href="<?php echo e(url('/admin/login')); ?>" class="inline-flex items-center space-x-2 text-gray-500 hover:text-emerald-300 text-sm transition-colors group">
                            <i class="fas fa-user-shield"></i>
                            <span>Login sebagai Admin</span>
                        </a>
                    </div>
                </div>

                <!-- Floating Elements -->
                <div class="absolute -top-4 -right-4 w-8 h-8 bg-emerald-500/20 rounded-full border border-emerald-500/30 animate-pulse float-animation"></div>
                <div class="absolute -bottom-4 -left-4 w-6 h-6 bg-cyan-500/20 rounded-full border border-cyan-500/30 animate-pulse float-animation" style="animation-delay: 1s;"></div>
            </div>
        </div>
    </div>

    <script>
        // Enhanced animations
        document.addEventListener('DOMContentLoaded', function() {
            // Checkbox interactivity - FIXED
            document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
                const container = checkbox.closest('.checkbox-container');
                
                checkbox.addEventListener('change', function() {
                    if (this.checked) {
                        container.classList.add('checked');
                    } else {
                        container.classList.remove('checked');
                    }
                });

                // Initialize checkbox state
                if (checkbox.checked) {
                    container.classList.add('checked');
                }
            });

            // Input focus effects
            document.querySelectorAll('input').forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('ring-2', 'ring-emerald-500/20');
                });
                
                input.addEventListener('blur', function() {
                    this.parentElement.classList.remove('ring-2', 'ring-emerald-500/20');
                });
            });

            // Button hover effects - FIXED (removed transform conflicts)
            const buttons = document.querySelectorAll('button, a');
            buttons.forEach(button => {
                if (!button.classList.contains('pulse-glow')) {
                    button.addEventListener('mouseenter', function() {
                        this.style.transform = 'translateY(-2px)';
                    });
                    button.addEventListener('mouseleave', function() {
                        this.style.transform = 'translateY(0)';
                    });
                }
            });

            // Add ripple effect to submit button
            const submitBtn = document.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.addEventListener('click', function(e) {
                    const ripple = document.createElement('span');
                    const rect = this.getBoundingClientRect();
                    const size = Math.max(rect.width, rect.height);
                    const x = e.clientX - rect.left - size / 2;
                    const y = e.clientY - rect.top - size / 2;
                    
                    ripple.style.cssText = `
                        position: absolute;
                        border-radius: 50%;
                        background: rgba(255, 255, 255, 0.6);
                        transform: scale(0);
                        animation: ripple 600ms linear;
                        width: ${size}px;
                        height: ${size}px;
                        left: ${x}px;
                        top: ${y}px;
                        pointer-events: none;
                    `;
                    
                    this.style.position = 'relative';
                    this.style.overflow = 'hidden';
                    this.appendChild(ripple);
                    
                    setTimeout(() => {
                        ripple.remove();
                    }, 600);
                });
            }

            // Add ripple animation
            const style = document.createElement('style');
            style.textContent = `
                @keyframes ripple {
                    to {
                        transform: scale(4);
                        opacity: 0;
                    }
                }
            `;
            document.head.appendChild(style);

            // Ball collision simulation
            const balls = document.querySelectorAll('.bg-white.rounded-full');
            balls.forEach(ball => {
                ball.addEventListener('mouseenter', function() {
                    this.style.animation = 'none';
                    setTimeout(() => {
                        this.style.animation = '';
                    }, 50);
                });
            });
        });

        // Page load animations
        window.addEventListener('load', function() {
            // Stagger animation for feature items
            const features = document.querySelectorAll('.bounce-in');
            features.forEach((feature, index) => {
                feature.style.animationDelay = `${index * 0.1}s`;
            });
        });
    </script>
</body>
</html><?php /**PATH C:\xampp\htdocs\Gor_Kita_Id\resources\views/auth/user-login.blade.php ENDPATH**/ ?>