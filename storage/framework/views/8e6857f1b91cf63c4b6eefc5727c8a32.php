<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - SportSpace Arena</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');
        body {
            font-family: 'Inter', sans-serif;
        }
        .sports-bg {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        .float-animation {
            animation: float 4s ease-in-out infinite;
        }
        .spin-animation {
            animation: spin 10s linear infinite;
        }
    </style>
</head>
<body class="sports-bg min-h-screen flex items-center justify-center p-4 overflow-hidden">
    <!-- Animated Sports Elements -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <!-- Floating Balls -->
        <div class="absolute top-20 left-10 w-12 h-12 bg-white rounded-full shadow-lg float-animation">
            <div class="w-full h-full bg-gradient-to-br from-red-500 to-orange-500 rounded-full flex items-center justify-center">
                <div class="w-6 h-6 bg-white rounded-full opacity-20"></div>
            </div>
        </div>
        <div class="absolute top-40 right-20 w-10 h-10 bg-white rounded-full shadow-lg float-animation" style="animation-delay: 1s;">
            <div class="w-full h-full bg-gradient-to-br from-blue-500 to-cyan-500 rounded-full flex items-center justify-center">
                <div class="w-4 h-4 bg-white rounded-full opacity-20"></div>
            </div>
        </div>
        <div class="absolute bottom-32 left-20 w-14 h-14 bg-white rounded-full shadow-lg float-animation" style="animation-delay: 2s;">
            <div class="w-full h-full bg-gradient-to-br from-green-500 to-emerald-500 rounded-full flex items-center justify-center">
                <div class="w-6 h-6 bg-white rounded-full opacity-20"></div>
            </div>
        </div>

        <!-- Sports Icons -->
        <div class="absolute top-1/4 right-16 text-red-400/20 text-5xl spin-animation">
            <i class="fas fa-table-tennis-paddle-ball"></i>
        </div>
        <div class="absolute bottom-1/3 left-16 text-blue-400/20 text-4xl spin-animation" style="animation-duration: 15s;">
            <i class="fas fa-football"></i>
        </div>
        <div class="absolute top-1/3 left-1/4 text-green-400/20 text-6xl spin-animation" style="animation-duration: 20s;">
            <i class="fas fa-basketball"></i>
        </div>
    </div>

    <!-- Main Container -->
    <div class="relative w-full max-w-6xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            
            <!-- Left Side - Sports Showcase -->
            <div class="text-white space-y-8">
                <!-- Logo & Brand -->
                <div class="flex items-center space-x-4 mb-8">
                    <div class="w-20 h-20 bg-gradient-to-br from-red-500 to-orange-500 rounded-3xl flex items-center justify-center border-2 border-white shadow-2xl">
                        <i class="fas fa-futbol text-white text-3xl"></i>
                    </div>
                    <div>
                        <h1 class="text-5xl font-black text-white">GorKita.ID</h1>
                        <p class="text-orange-300 font-semibold text-lg">Arena Para Pejuang</p>
                    </div>
                </div>

                <!-- Main Heading -->
                <div class="space-y-6">
                    <h2 class="text-6xl lg:text-7xl font-black leading-tight">
                        Come on<br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-red-400 to-orange-400">Tunjukan </span><br>
                        Team Kamu sekarang juga
                    </h2>
                    <p class="text-xl text-gray-300 leading-relaxed font-medium">
                        Daftar sekarang dan mulai petualangan olahragamu di arena premium. 
                        Rasakan sensasi bertanding seperti sang juara.
                    </p>
                </div>

                <!-- Sports Features -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-8">
                    <div class="flex items-center space-x-4 p-4 bg-white/5 rounded-2xl border border-white/10 hover:border-orange-400/30 transition-all duration-300">
                        <div class="w-12 h-12 bg-red-500/20 rounded-2xl flex items-center justify-center border border-red-400/30">
                            <i class="fas fa-running text-red-400 text-xl"></i>
                        </div>
                        <div>
                            <h4 class="text-white font-bold text-lg">Multi Sports</h4>
                            <p class="text-gray-400 text-sm">Futsal, Badminton, Minisoccer</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4 p-4 bg-white/5 rounded-2xl border border-white/10 hover:border-blue-400/30 transition-all duration-300">
                        <div class="w-12 h-12 bg-blue-500/20 rounded-2xl flex items-center justify-center border border-blue-400/30">
                            <i class="fas fa-trophy text-blue-400 text-xl"></i>
                        </div>
                        <div>
                            <h4 class="text-white font-bold text-lg">Fasilitas Lapanagan</h4>
                            <p class="text-gray-400 text-sm">Lapangan standar nasional</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4 p-4 bg-white/5 rounded-2xl border border-white/10 hover:border-green-400/30 transition-all duration-300">
                        <div class="w-12 h-12 bg-green-500/20 rounded-2xl flex items-center justify-center border border-green-400/30">
                            <i class="fas fa-users text-green-400 text-xl"></i>
                        </div>
                        <div>
                            <h4 class="text-white font-bold text-lg">Sportifitas</h4>
                            <p class="text-gray-400 text-sm">Olahraga Menyatuka kita</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4 p-4 bg-white/5 rounded-2xl border border-white/10 hover:border-purple-400/30 transition-all duration-300">
                        <div class="w-12 h-12 bg-purple-500/20 rounded-2xl flex items-center justify-center border border-purple-400/30">
                            <i class="fas fa-chart-line text-purple-400 text-xl"></i>
                        </div>
                        <div>
                            <h4 class="text-white font-bold text-lg">Brmain Bagus</h4>
                            <p class="text-gray-400 text-sm">Tingkatkan skil mu</p>
                        </div>
                    </div>
                </div>

                <!-- Sports Stats -->
                <div class="flex items-center justify-around pt-8 border-t border-white/20">
                    <div class="text-center">
                        <div class="text-3xl font-black text-red-400">100+</div>
                        <div class="text-gray-400 text-sm font-medium">Matches/Day</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-black text-blue-400">50+</div>
                        <div class="text-gray-400 text-sm font-medium">Fields</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-black text-green-400">1K+</div>
                        <div class="text-gray-400 text-sm font-medium">Players</div>
                    </div>
                </div>
            </div>

            <!-- Right Side - Register Form -->
            <div class="relative">
                <!-- Form Container -->
                <div class="bg-white rounded-3xl border-2 border-orange-400 shadow-2xl p-8 relative overflow-hidden">
                    <!-- Decorative Elements -->
                    <div class="absolute -top-6 -right-6 w-12 h-12 bg-red-500 rounded-full"></div>
                    <div class="absolute -bottom-6 -left-6 w-12 h-12 bg-blue-500 rounded-full"></div>
                    
                    <!-- Header -->
                    <div class="text-center mb-8 relative z-10">
                        <div class="w-20 h-20 bg-gradient-to-br from-red-500 to-orange-500 rounded-3xl flex items-center justify-center mx-auto mb-4 border-2 border-white shadow-2xl">
                            <i class="fas fa-user-plus text-white text-2xl"></i>
                        </div>
                        <h2 class="text-3xl font-black text-gray-800 mb-2">Daftar Arena</h2>
                        <p class="text-gray-600 font-medium">Bergabung dengan komunitas atlet kami</p>
                    </div>

                    <!-- Error Messages -->
                    <?php if($errors->any()): ?>
                        <div class="bg-red-50 border border-red-200 rounded-2xl p-4 mb-6 relative z-10">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center border border-red-200">
                                    <i class="fas fa-exclamation-triangle text-red-500"></i>
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-red-800 font-bold mb-1 text-sm">Perbaiki data berikut:</h4>
                                    <ul class="text-red-600 text-sm">
                                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li class="flex items-center space-x-1">
                                                <i class="fas fa-circle text-xs"></i>
                                                <span><?php echo e($error); ?></span>
                                            </li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Register Form -->
                    <form method="POST" action="<?php echo e(route('auth.registrasi')); ?>" class="space-y-6 relative z-10">
                        <?php echo csrf_field(); ?>
                        
                        <!-- Name Input -->
                        <div class="group">
                            <label class="block text-gray-800 text-sm font-bold mb-3 flex items-center space-x-2">
                                <div class="w-6 h-6 bg-red-100 rounded-lg flex items-center justify-center border border-red-200">
                                    <i class="fas fa-user text-red-500 text-xs"></i>
                                </div>
                                <span class="text-gray-800">Nama Kamu</span>
                            </label>
                            <input class="w-full bg-gray-50 border-2 border-gray-200 text-gray-800 px-6 py-4 rounded-2xl focus:outline-none focus:border-red-400 focus:bg-white transition placeholder-gray-500 font-medium" 
                                   id="name" 
                                   type="text" 
                                   name="name" 
                                   value="<?php echo e(old('name')); ?>"
                                   placeholder="Masukkan nama lengkap" 
                                   required>
                        </div>

                        <!-- Email Input -->
                        <div class="group">
                            <label class="block text-gray-800 text-sm font-bold mb-3 flex items-center space-x-2">
                                <div class="w-6 h-6 bg-blue-100 rounded-lg flex items-center justify-center border border-blue-200">
                                    <i class="fas fa-envelope text-blue-500 text-xs"></i>
                                </div>
                                <span class="text-gray-800">Email Yuuu</span>
                            </label>
                            <input class="w-full bg-gray-50 border-2 border-gray-200 text-gray-800 px-6 py-4 rounded-2xl focus:outline-none focus:border-blue-400 focus:bg-white transition placeholder-gray-500 font-medium" 
                                   id="email" 
                                   type="email" 
                                   name="email" 
                                   value="<?php echo e(old('email')); ?>"
                                   placeholder="athlete@example.com" 
                                   required>
                        </div>

                        <!-- Password Input -->
                        <div class="group">
                            <label class="block text-gray-800 text-sm font-bold mb-3 flex items-center space-x-2">
                                <div class="w-6 h-6 bg-green-100 rounded-lg flex items-center justify-center border border-green-200">
                                    <i class="fas fa-lock text-green-500 text-xs"></i>
                                </div>
                                <span class="text-gray-800">Password</span>
                            </label>
                            <input class="w-full bg-gray-50 border-2 border-gray-200 text-gray-800 px-6 py-4 rounded-2xl focus:outline-none focus:border-green-400 focus:bg-white transition placeholder-gray-500 font-medium" 
                                   id="password" 
                                   type="password" 
                                   name="password"
                                   placeholder="Buat password kuat" 
                                   required>
                        </div>

                        <!-- Confirm Password Input -->
                        <div class="group">
                            <label class="block text-gray-800 text-sm font-bold mb-3 flex items-center space-x-2">
                                <div class="w-6 h-6 bg-purple-100 rounded-lg flex items-center justify-center border border-purple-200">
                                    <i class="fas fa-shield-alt text-purple-500 text-xs"></i>
                                </div>
                                <span class="text-gray-800">Konfirmasi Password</span>
                            </label>
                            <input class="w-full bg-gray-50 border-2 border-gray-200 text-gray-800 px-6 py-4 rounded-2xl focus:outline-none focus:border-purple-400 focus:bg-white transition placeholder-gray-500 font-medium" 
                                   id="password_confirmation" 
                                   type="password" 
                                   name="password_confirmation"
                                   placeholder="Ulangi password" 
                                   required>
                        </div>

                        <!-- Submit Button -->
                        <button class="w-full bg-gradient-to-r from-red-500 to-orange-500 hover:from-red-600 hover:to-orange-600 text-white font-bold py-4 rounded-2xl transition-all duration-300 transform hover:scale-105 border-2 border-white shadow-2xl group" 
                                type="submit">
                            <div class="flex items-center justify-center space-x-3">
                                <i class="fas fa-user-plus group-hover:scale-110 transition-transform"></i>
                                <span class="text-lg font-bold">Daftar Sekarang</span>
                                <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                            </div>
                        </button>
                    </form>

                    <!-- Login Link -->
                    <div class="text-center mt-6 pt-6 border-t border-gray-200 relative z-10">
                        <p class="text-gray-600 mb-3 font-medium">Sudah punya akun?</p>
                        <a href="/login" class="inline-flex items-center space-x-2 text-blue-500 hover:text-blue-700 font-bold transition-colors group">
                            <i class="fas fa-sign-in-alt group-hover:scale-110 transition-transform"></i>
                            <span>Login ke Akun</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="fixed bottom-4 left-1/2 transform -translate-x-1/2 text-center">
        <p class="text-white/80 text-sm flex items-center space-x-2 font-medium">
            <i class="fas fa-futbol text-red-400"></i>
            <span>GorKita.ID - Tunjukan team terbaikmu</span>
            <i class="fas fa-trophy text-orange-400"></i>
        </p>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add hover effects to form inputs
            const inputs = document.querySelectorAll('input');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.style.transform = 'scale(1.02)';
                    this.style.boxShadow = '0 0 0 3px rgba(249, 115, 22, 0.1)';
                });
                
                input.addEventListener('blur', function() {
                    this.style.transform = 'scale(1)';
                    this.style.boxShadow = 'none';
                });
            });

            // Button hover effect
            const button = document.querySelector('button');
            button.addEventListener('mouseenter', function() {
                this.style.transform = 'scale(1.05) translateY(-2px)';
                this.style.boxShadow = '0 20px 40px rgba(249, 115, 22, 0.3)';
            });
            button.addEventListener('mouseleave', function() {
                this.style.transform = 'scale(1) translateY(0)';
                this.style.boxShadow = '0 10px 25px rgba(0, 0, 0, 0.1)';
            });

            // Form submission effect
            const form = document.querySelector('form');
            form.addEventListener('submit', function() {
                const button = this.querySelector('button');
                button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Membuat Akun...';
                button.disabled = true;
            });

            // Add entrance animation to form
            const formContainer = document.querySelector('.bg-white');
            formContainer.style.opacity = '0';
            formContainer.style.transform = 'translateY(30px)';
            
            setTimeout(() => {
                formContainer.style.transition = 'all 0.6s ease';
                formContainer.style.opacity = '1';
                formContainer.style.transform = 'translateY(0)';
            }, 300);
        });
    </script>
</body>
</html><?php /**PATH C:\xampp\htdocs\Gor_Kita_Id\resources\views/auth/register.blade.php ENDPATH**/ ?>