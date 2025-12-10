

<?php $__env->startSection('title', 'About - GotKita.ID'); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-950 to-black py-12">
    <div class="max-w-7xl mx-auto px-4">
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-emerald-500/10 to-cyan-500/10 p-12 text-center mb-16 border border-emerald-500/30 backdrop-blur-lg">
            <div class="absolute top-0 left-0 w-72 h-72 bg-emerald-500/5 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 right-0 w-96 h-96 bg-cyan-500/5 rounded-full blur-3xl"></div>
            
            <div class="relative z-10">
                <div class="inline-flex items-center space-x-3 bg-white/10 backdrop-blur-xl border border-emerald-500/30 rounded-2xl px-6 py-3 mb-6">
                    <div class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></div>
                    <span class="text-sm font-bold tracking-wider text-emerald-200">PENGALAMAN OLAHRAGA PREMIUM</span>
                    <div class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></div>
                </div>
                
                <h1 class="text-5xl lg:text-6xl font-black mb-6 leading-tight">
                    <span class="bg-gradient-to-r from-emerald-300 to-cyan-400 bg-clip-text text-transparent">
                        Tentang GotKita.ID
                    </span>
                </h1>
                <p class="text-xl text-gray-300 max-w-3xl mx-auto leading-relaxed">
                   tempat impian para pejuang bertemu dengan fasilitas kelas dunia. Membangun sportyfitas dalam olahraga.
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-20">
            <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-emerald-500/10 to-cyan-500/10 p-8 text-center border border-emerald-500/20 backdrop-blur-lg transition-all duration-500 hover:scale-105 hover:border-emerald-500/40">
                <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/5 to-cyan-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="relative z-10">
                    <div class="w-20 h-20 bg-gradient-to-br from-emerald-500 to-cyan-500 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-emerald-400/30 group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-building text-white text-2xl"></i>
                    </div>
                    <p class="text-5xl font-black text-emerald-300 mb-2"><?php echo e($totalFields); ?></p>
                    <p class="text-gray-400 font-semibold uppercase tracking-wider text-sm">Lapangan Premium</p>
                </div>
            </div>

            <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-cyan-500/10 to-emerald-500/10 p-8 text-center border border-cyan-500/20 backdrop-blur-lg transition-all duration-500 hover:scale-105 hover:border-cyan-500/40">
                <div class="absolute inset-0 bg-gradient-to-br from-cyan-500/5 to-emerald-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="relative z-10">
                    <div class="w-20 h-20 bg-gradient-to-br from-cyan-500 to-emerald-500 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-cyan-400/30 group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-calendar-check text-white text-2xl"></i>
                    </div>
                    <p class="text-5xl font-black text-cyan-300 mb-2"><?php echo e($totalBookings); ?>+</p>
                    <p class="text-gray-400 font-semibold uppercase tracking-wider text-sm">Total Booking</p>
                </div>
            </div>

            <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-emerald-500/10 to-cyan-500/10 p-8 text-center border border-emerald-500/20 backdrop-blur-lg transition-all duration-500 hover:scale-105 hover:border-emerald-500/40">
                <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/5 to-cyan-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="relative z-10">
                    <div class="w-20 h-20 bg-gradient-to-br from-emerald-500 to-cyan-500 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-emerald-400/30 group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-star text-white text-2xl"></i>
                    </div>
                    <p class="text-5xl font-black text-emerald-300 mb-2"><?php echo e(number_format($averageRating, 1)); ?></p>
                    <p class="text-gray-400 font-semibold uppercase tracking-wider text-sm">Rating</p>
                </div>
            </div>

            <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-cyan-500/10 to-emerald-500/10 p-8 text-center border border-cyan-500/20 backdrop-blur-lg transition-all duration-500 hover:scale-105 hover:border-cyan-500/40">
                <div class="absolute inset-0 bg-gradient-to-br from-cyan-500/5 to-emerald-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="relative z-10">
                    <div class="w-20 h-20 bg-gradient-to-br from-cyan-500 to-emerald-500 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-cyan-400/30 group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-users text-white text-2xl"></i>
                    </div>
                    <p class="text-5xl font-black text-cyan-300 mb-2">1000+</p>
                    <p class="text-gray-400 font-semibold uppercase tracking-wider text-sm">member aktif</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mb-20">
            <div class="relative group">
                <div class="absolute -inset-1 bg-gradient-to-r from-emerald-500 to-cyan-500 rounded-3xl blur opacity-25 group-hover:opacity-75 transition duration-1000 group-hover:duration-200"></div>
                <div class="relative bg-gradient-to-br from-gray-800/60 to-gray-900/60 rounded-3xl p-8 border border-emerald-500/20 backdrop-blur-xl">
                    <div class="flex items-center space-x-4 mb-6">
                        <div class="w-14 h-14 bg-gradient-to-br from-emerald-500 to-cyan-500 rounded-2xl flex items-center justify-center border border-emerald-400/30 shadow-lg">
                            <i class="fas fa-rocket text-white text-xl"></i>
                        </div>
                        <h2 class="text-3xl font-black text-white">Tentang Kami</h2>
                    </div>
                    <div class="space-y-4 text-gray-300 leading-relaxed">
                        <p class="text-lg">
                            <span class="text-emerald-300 font-bold">GotKita.ID</span>dimulai dengan visi sederhana: menciptakan destinasi olahraga terbaik di mana atlet dari semua tingkat dapat merasakan fasilitas setara kejuaraan.
                        <p>
                          Dari awal yang sederhana, kami telah berkembang menjadi kompleks olahraga utama yang menampilkan lapangan futsal mutakhir, lapangan bulu tangkis, dan arena mini sepak bola - semuanya dirancang sesuai standar internasional.
                        </p>
                        <p>
                        Terletak secara strategis di Kedep, kami bangga melayani komunitas pecinta olahraga yang terus berkembang dan yang berbagi semangat kami untuk keunggulan dan hidup sehat.
                        </p>
                    </div>
                </div>
            </div>

            <div class="relative group">
                <div class="absolute -inset-1 bg-gradient-to-r from-cyan-500 to-emerald-500 rounded-3xl blur opacity-25 group-hover:opacity-75 transition duration-1000 group-hover:duration-200"></div>
                <div class="relative bg-gradient-to-br from-gray-800/60 to-gray-900/60 rounded-3xl p-8 border border-cyan-500/20 backdrop-blur-xl">
                    <div class="flex items-center space-x-4 mb-6">
                        <div class="w-14 h-14 bg-gradient-to-br from-cyan-500 to-emerald-500 rounded-2xl flex items-center justify-center border border-cyan-400/30 shadow-lg">
                            <i class="fas fa-bullseye text-white text-xl"></i>
                        </div>
                        <h2 class="text-3xl font-black text-white">Visi & Misi</h2>
                    </div>
                    
                    <div class="space-y-6">
                        <div class="bg-white/5 rounded-2xl p-4 border border-emerald-500/10">
                            <h3 class="text-xl font-bold text-emerald-300 mb-3 flex items-center space-x-3">
                                <i class="fas fa-eye text-emerald-400"></i>
                                <span>Visi</span>
                            </h3>
                            <p class="text-gray-300 leading-relaxed">
                              Menjadi penyedia fasilitas olahraga terkemuka yang menginspirasi gaya hidup sehat dan mendorong kinerja tingkat juara melalui fasilitas kelas dunia dan layanan yang luar biasa.
                            </p>
                        </div>

                        <div class="bg-white/5 rounded-2xl p-4 border border-cyan-500/10">
                            <h3 class="text-xl font-bold text-cyan-300 mb-3 flex items-center space-x-3">
                                <i class="fas fa-flag text-cyan-400"></i>
                                <span>Misi</span>
                            </h3>
                            <div class="grid gap-3">
                                <div class="flex items-center space-x-3">
                                    <div class="w-6 h-6 bg-emerald-500/20 rounded-full flex items-center justify-center">
                                        <i class="fas fa-check text-emerald-400 text-xs"></i>
                                    </div>
                                    <span class="text-gray-300 text-sm">Menyediakan fasilitas olahraga premium dengan standar internasional</span>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <div class="w-6 h-6 bg-cyan-500/20 rounded-full flex items-center justify-center">
                                        <i class="fas fa-check text-cyan-400 text-xs"></i>
                                    </div>
                                    <span class="text-gray-300 text-sm">Pemesanan Melalui Digital yang praktis</span>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <div class="w-6 h-6 bg-emerald-500/20 rounded-full flex items-center justify-center">
                                        <i class="fas fa-check text-emerald-400 text-xs"></i>
                                    </div>
                                    <span class="text-gray-300 text-sm">membangun komunitas olahraga yang hidup</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-20">
            <div class="text-center mb-12">
                <h2 class="text-4xl lg:text-5xl font-black text-white mb-4">
                    <span class="bg-gradient-to-r from-emerald-300 to-cyan-400 bg-clip-text text-transparent">
                      Fasilitas Kelas Dunia
                    </span>
                </h2>
                <p class="text-xl text-gray-400 max-w-2xl mx-auto">Rasakan fasilitas olahraga kelas kejuaraan yang dirancang untuk performa puncak</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="group relative overflow-hidden rounded-3xl bg-gradient-to-br from-emerald-500/10 to-cyan-500/10 p-8 border border-emerald-500/20 backdrop-blur-lg transition-all duration-500 hover:scale-105">
                    <div class="absolute top-4 right-4">
                        <span class="bg-emerald-500 text-white px-3 py-1 rounded-full text-sm font-bold shadow-lg">Popular</span>
                    </div>
                    <div class="text-center">
                        <div class="w-24 h-24 bg-gradient-to-br from-emerald-500 to-cyan-500 rounded-3xl flex items-center justify-center mx-auto mb-6 border border-emerald-400/30 group-hover:scale-110 transition-transform duration-300 shadow-2xl">
                            <i class="fas fa-futbol text-white text-3xl"></i>
                        </div>
                        <h3 class="text-2xl font-black text-white mb-4">Lapangan Futsal</h3>
                        <div class="space-y-3 text-gray-300 text-left">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-check-circle text-emerald-400"></i>
                                <span>Lapangan Dengan Karpet Finil Standar International</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-check-circle text-emerald-400"></i>
                                <span>Lapangan indoor dilengkapi dengan pencahayaan yang ajib</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-check-circle text-emerald-400"></i>
                                <span>3 lapangan futsal berkualitas</span>
                            </div>
                        </div>
                    </div>
                </div>

<div class="group relative overflow-hidden rounded-3xl bg-gradient-to-br from-cyan-500/10 to-emerald-500/10 p-8 border border-cyan-500/20 backdrop-blur-lg transition-all duration-500 hover:scale-105">
    <div class="text-center">
        <div class="w-24 h-24 bg-gradient-to-br from-cyan-500 to-emerald-500 rounded-3xl flex items-center justify-center mx-auto mb-6 border border-cyan-400/30 group-hover:scale-110 transition-transform duration-300 shadow-2xl">
            <i class="fas fa-table-tennis-paddle-ball text-white text-3xl"></i>
        </div>
        <h3 class="text-2xl font-black text-white mb-4">Badminton Premium</h3>
        <div class="space-y-3 text-gray-300 text-left">
            <div class="flex items-center space-x-3">
                <i class="fas fa-check-circle text-cyan-400"></i>
                <span>Lapangan standar BWF</span>
            </div>
            <div class="flex items-center space-x-3">
                <i class="fas fa-check-circle text-cyan-400"></i>
                <span>Lapangan Idoor dilengapi dengan pencahayaan yang ajib</span>
            </div>
            <div class="flex items-center space-x-3">
                <i class="fas fa-check-circle text-cyan-400"></i>
                <span>4 lapangan profesional</span>
            </div>
        </div>
    </div>
</div>

<div class="group relative overflow-hidden rounded-3xl bg-gradient-to-br from-emerald-500/10 to-cyan-500/10 p-8 border border-emerald-500/20 backdrop-blur-lg transition-all duration-500 hover:scale-105">
    <div class="text-center">
        <div class="w-24 h-24 bg-gradient-to-br from-emerald-500 to-cyan-500 rounded-3xl flex items-center justify-center mx-auto mb-6 border border-emerald-400/30 group-hover:scale-110 transition-transform duration-300 shadow-2xl">
            <i class="fas fa-futbol text-white text-3xl"></i>
        </div>
        <h3 class="text-2xl font-black text-white mb-4">Arena Mini Soccer</h3>
        <div class="space-y-3 text-gray-300 text-left">
            <div class="flex items-center space-x-3">
                <i class="fas fa-check-circle text-emerald-400"></i>
                <span>Rumput sintetis berkualitas tinggi</span>
            </div>
            <div class="flex items-center space-x-3">
                <i class="fas fa-check-circle text-emerald-400"></i>
                <span>Gawang standar profesional</span>
            </div>
            <div class="flex items-center space-x-3">
                <i class="fas fa-check-circle text-emerald-400"></i>
                <span>1 lapangan outdoor premium</span>
            </div>
        </div>
    </div>
</div>
            </div>
        </div>
       
<div class="text-center mb-20">
    <div class="bg-gradient-to-br from-emerald-500/10 to-cyan-500/10 rounded-3xl p-12 border border-emerald-500/30 backdrop-blur-lg">
        <h2 class="text-4xl lg:text-5xl font-black text-white mb-6">
            Siap Menikmati Fasilitas Terbaik?
        </h2>
        <p class="text-xl text-gray-300 mb-8 max-w-2xl mx-auto">
            Bergabunglah dengan ribuan atlet yang mempercayai GotKita.ID untuk latihan kejuaraan dan olahraga rekreasi mereka.
        </p>
       <a href="<?php echo e(route('booking.select-field', ['type' => 'badminton'])); ?>" 
   class="bg-gradient-to-r from-emerald-600 to-cyan-600 hover:from-emerald-500 hover:to-cyan-500 text-white font-bold py-4 px-8 rounded-2xl transition-all duration-300 transform hover:-translate-y-1 hover:shadow-2xl flex items-center justify-center space-x-3 border border-emerald-500/30">
    <i class="fas fa-calendar-plus"></i>
    <span>Booking Sekarang</span>
</a>
            <a href="<?php echo e(route('home')); ?>" 
               class="border-2 border-emerald-500/30 hover:border-emerald-400 text-white font-bold py-4 px-8 rounded-2xl transition-all duration-300 transform hover:-translate-y-1 flex items-center justify-center space-x-3 bg-white/5 backdrop-blur-sm">
                <i class="fas fa-home"></i>
                <span>Kembali ke Beranda</span>
            </a>
        </div>
    </div>
</div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry, index) => {
                if (entry.isIntersecting) {
                    setTimeout(() => {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }, index * 100);
                }
            });
        }, observerOptions);

        document.querySelectorAll('.group').forEach((el, index) => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(30px)';
            el.style.transition = `opacity 0.6s ease ${index * 0.1}s, transform 0.6s ease ${index * 0.1}s`;
            observer.observe(el);
        });

        const statsCards = document.querySelectorAll('.grid > .group');
        statsCards.forEach((card, index) => {
            card.style.animationDelay = `${index * 0.2}s`;
        });
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Gor_Kita_Id\resources\views/about.blade.php ENDPATH**/ ?>