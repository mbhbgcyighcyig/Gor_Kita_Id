

<?php $__env->startSection('title', 'Konfirmasi Booking - GotKita.ID'); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-950 to-black py-8">
    <div class="max-w-4xl mx-auto px-4">

        <!-- Header dengan Tombol Kembali -->
        <div class="text-center mb-8 relative">

            <!-- Back Button -->
            <div class="absolute left-0 top-1/2 transform -translate-y-1/2">
                <a href="<?php echo e(url()->previous()); ?>" 
                   class="inline-flex items-center space-x-2 bg-gray-800/50 hover:bg-gray-700/50 text-gray-300 hover:text-white rounded-full px-4 py-2 border border-gray-600/30 transition-all duration-300 group">
                    <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
                    <span class="text-sm font-semibold">Kembali</span>
                </a>
            </div>

            <div class="inline-flex items-center space-x-2 bg-gray-800/50 rounded-full px-6 py-3 border border-emerald-500/30 mb-6">
                <div class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></div>
                <span class="text-emerald-300 text-sm font-semibold">SELESAIKAN BOOKING ANDA</span>
            </div>

            <h1 class="text-4xl lg:text-5xl font-black text-white mb-4">
                Konfirmasi <span class="bg-gradient-to-r from-emerald-300 to-cyan-400 bg-clip-text text-transparent">Booking</span>
            </h1>

            <p class="text-xl text-gray-400">Periksa kembali detail booking Anda sebelum konfirmasi</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- Left Column - Booking Details -->
            <div class="lg:col-span-2">
                <div class="glass-card rounded-3xl p-8 border border-emerald-500/20">

                    <!-- Field Info -->
                    <div class="bg-gradient-to-br from-emerald-500/10 to-cyan-500/10 rounded-2xl p-6 border border-emerald-500/30 mb-8">
                        <div class="flex items-center space-x-4">
                            <div class="w-16 h-16 bg-gradient-to-br from-emerald-500 to-cyan-500 rounded-2xl flex items-center justify-center border border-emerald-400/30">
                                <i class="fas 
                                    <?php if($field->type == 'futsal'): ?> fa-futbol
                                    <?php elseif($field->type == 'badminton'): ?> fa-table-tennis-paddle-ball
                                    <?php else: ?> fa-futbol <?php endif; ?>
                                    text-white text-xl">
                                </i>
                            </div>

                            <div class="flex-1">
                                <h2 class="text-2xl font-black text-white"><?php echo e($field->name); ?></h2>
                                <p class="text-gray-300"><?php echo e($field->description ?? 'Lapangan premium berkualitas'); ?></p>
                                <div class="flex items-center space-x-4 mt-2">
                                    <span class="text-emerald-300 font-bold text-lg">
                                        Rp <?php echo e(number_format($field->price_per_hour, 0, ',', '.')); ?>

                                    </span>
                                    <span class="text-gray-400 text-sm">per jam</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Booking Details -->
                    <div class="space-y-6">
                        <h3 class="text-xl font-black text-white mb-4">Detail Booking</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                            <!-- Date -->
                            <div class="glass-card rounded-2xl p-4 border border-gray-700/50">
                                <div class="flex items-center space-x-3 mb-2">
                                    <div class="w-10 h-10 bg-emerald-500/20 rounded-2xl flex items-center justify-center">
                                        <i class="fas fa-calendar text-emerald-400"></i>
                                    </div>
                                    <div>
                                        <div class="text-gray-400 text-sm">Tanggal</div>
                                        <div class="text-white font-bold">
                                            <?php echo e(\Carbon\Carbon::parse($selectedDate)->translatedFormat('d F Y')); ?>

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Time -->
                            <div class="glass-card rounded-2xl p-4 border border-gray-700/50">
                                <div class="flex items-center space-x-3 mb-2">
                                    <div class="w-10 h-10 bg-cyan-500/20 rounded-2xl flex items-center justify-center">
                                        <i class="fas fa-clock text-cyan-400"></i>
                                    </div>
                                    <div>
                                        <div class="text-gray-400 text-sm">Jam Booking</div>
                                        <?php
                                            $timeParts = explode('-', $timeSlot);
                                            $startTime = trim($timeParts[0] ?? $timeSlot);
                                            $endTime = trim($timeParts[1] ?? '');
                                        ?>
                                        <div class="text-white font-bold"><?php echo e($startTime); ?></div>
                                        <?php if($endTime): ?>
                                        <div class="text-gray-300 text-sm">sampai <?php echo e($endTime); ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <!-- Duration -->
                            <div class="glass-card rounded-2xl p-4 border border-gray-700/50">
                                <div class="flex items-center space-x-3 mb-2">
                                    <div class="w-10 h-10 bg-emerald-500/20 rounded-2xl flex items-center justify-center">
                                        <i class="fas fa-hourglass text-emerald-400"></i>
                                    </div>
                                    <div>
                                        <div class="text-gray-400 text-sm">Durasi</div>
                                        <div class="text-white font-bold"><?php echo e($duration); ?> Jam</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Field Type -->
                            <div class="glass-card rounded-2xl p-4 border border-gray-700/50">
                                <div class="flex items-center space-x-3 mb-2">
                                    <div class="w-10 h-10 bg-cyan-500/20 rounded-2xl flex items-center justify-center">
                                        <i class="fas fa-map-marker-alt text-cyan-400"></i>
                                    </div>
                                    <div>
                                        <div class="text-gray-400 text-sm">Jenis Lapangan</div>
                                        <div class="text-white font-bold capitalize"><?php echo e($field->type); ?></div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- Payment Notice -->
                        <div class="bg-gradient-to-r from-yellow-500/10 to-orange-500/10 rounded-2xl p-4 border border-yellow-500/30 mt-6">
                            <div class="flex items-start space-x-3">
                                <i class="fas fa-exclamation-circle text-yellow-400 text-xl mt-1"></i>
                                <div>
                                    <h4 class="text-yellow-300 font-bold mb-1">PERHATIAN!</h4>
                                    <p class="text-gray-300 text-sm">
                                        Booking akan <strong class="text-yellow-300">berstatus PENDING</strong> setelah konfirmasi. 
                                        Anda harus <strong class="text-emerald-300">melakukan pembayaran terlebih dahulu</strong> 
                                        sebelum booking dapat diverifikasi oleh admin.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="lg:col-span-1">
                <div class="sticky top-8 space-y-6">

                    <!-- Order Summary -->
                    <div class="glass-card rounded-3xl p-6 border border-emerald-500/20">
                        <h3 class="text-xl font-black text-white mb-4">Ringkasan Pesanan</h3>

                        <div class="space-y-3 mb-4">
                            <div class="flex justify-between text-gray-300">
                                <span>Harga per jam</span>
                                <span>Rp <?php echo e(number_format($field->price_per_hour, 0, ',', '.')); ?></span>
                            </div>

                            <div class="flex justify-between text-gray-300">
                                <span>Durasi</span>
                                <span><?php echo e($duration); ?> jam</span>
                            </div>

                            <?php if($field->price_per_hour && $duration): ?>
                            <div class="flex justify-between text-gray-300">
                                <span>Subtotal</span>
                                <span>Rp <?php echo e(number_format($field->price_per_hour * $duration, 0, ',', '.')); ?></span>
                            </div>
                            <?php endif; ?>
                            
                            <div class="flex justify-between text-gray-300">
                                <span>Pajak & Layanan</span>
                                <span>Rp 0</span>
                            </div>
                        </div>

                        <div class="border-t border-gray-600 pt-4">
                            <div class="flex justify-between items-center">
                                <span class="text-lg font-bold text-white">Total Pembayaran</span>
                                <span class="text-2xl font-black text-emerald-300">
                                    Rp <?php echo e(number_format($totalPrice, 0, ',', '.')); ?>

                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Action -->
                    <div class="glass-card rounded-3xl p-6 border border-emerald-500/20">

                        <form action="<?php echo e(route('booking.process')); ?>" method="POST" id="bookingForm">
                            <?php echo csrf_field(); ?>

                            <input type="hidden" name="field_id" value="<?php echo e($field->id); ?>">
                            <input type="hidden" name="booking_date" value="<?php echo e($selectedDate); ?>">
                            <input type="hidden" name="start_time" value="<?php echo e($startTime); ?>">
                            <input type="hidden" name="end_time" value="<?php echo e($endTime ?? $startTime); ?>">
                            <input type="hidden" name="duration" value="<?php echo e($duration); ?>">
                            <input type="hidden" name="total_price" value="<?php echo e($totalPrice); ?>">

                            <!-- Optional Notes -->
                            <div class="mb-4">
                                <label class="block text-gray-400 text-sm font-semibold mb-2">
                                    Catatan (Opsional)
                                </label>
                                <textarea name="notes" 
                                          rows="3" 
                                          class="w-full bg-gray-800/50 border border-gray-600 rounded-2xl px-4 py-3 text-white placeholder-gray-500 focus:outline-none focus:border-emerald-400 transition"
                                          placeholder="Contoh: Booking untuk latihan tim, butuh bola futsal..."></textarea>
                            </div>

                            <button type="submit"
                                id="submitBtn"
                                class="w-full bg-gradient-to-r from-emerald-500 to-cyan-500 hover:from-emerald-600 hover:to-cyan-600 text-white font-bold py-4 px-8 rounded-2xl transition-all duration-300 transform hover:-translate-y-1 hover:shadow-2xl border border-emerald-500/30 flex items-center justify-center space-x-3 group mb-4">
                                <i class="fas fa-calendar-check group-hover:scale-110 transition-transform"></i>
                                <span id="btnText">KONFIRMASI BOOKING</span>
                                <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                            </button>

                            <a href="<?php echo e(route('booking.select-time', $field)); ?>?date=<?php echo e($selectedDate); ?>" 
                               class="w-full bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-500 hover:to-gray-600 text-white font-bold py-4 px-8 rounded-2xl transition-all duration-300 transform hover:-translate-y-1 border border-gray-500/30 flex items-center justify-center space-x-3 group">
                                <i class="fas fa-times group-hover:scale-110 transition-transform"></i>
                                <span>Batalkan</span>
                            </a>

                            <div class="text-center text-gray-400 text-sm mt-4">
                                <i class="fas fa-info-circle text-emerald-400 mr-1"></i>
                                Setelah konfirmasi, Anda akan diarahkan ke halaman pembayaran
                            </div>
                        </form>

                    </div>

                    <!-- Payment Steps -->
                    <div class="glass-card rounded-3xl p-6 border border-cyan-500/20">
                        <h4 class="text-lg font-black text-white mb-4 flex items-center">
                            <i class="fas fa-credit-card text-cyan-400 mr-2"></i>
                            Langkah Pembayaran
                        </h4>

                        <div class="space-y-4">
                            <div class="flex items-start space-x-3">
                                <div class="w-8 h-8 bg-emerald-500/20 rounded-xl flex items-center justify-center border border-emerald-400/30 flex-shrink-0">
                                    <span class="text-emerald-400 font-bold">1</span>
                                </div>
                                <div>
                                    <div class="text-white font-semibold">Konfirmasi Booking</div>
                                    <div class="text-gray-400 text-sm">Isi detail booking ini</div>
                                </div>
                            </div>
                            
                            <div class="flex items-start space-x-3">
                                <div class="w-8 h-8 bg-yellow-500/20 rounded-xl flex items-center justify-center border border-yellow-400/30 flex-shrink-0">
                                    <span class="text-yellow-400 font-bold">2</span>
                                </div>
                                <div>
                                    <div class="text-white font-semibold">Pembayaran Virtual Account</div>
                                    <div class="text-gray-400 text-sm">Transfer ke VA bank pilihan</div>
                                </div>
                            </div>
                            
                            <div class="flex items-start space-x-3">
                                <div class="w-8 h-8 bg-blue-500/20 rounded-xl flex items-center justify-center border border-blue-400/30 flex-shrink-0">
                                    <span class="text-blue-400 font-bold">3</span>
                                </div>
                                <div>
                                    <div class="text-white font-semibold">Verifikasi Admin</div>
                                    <div class="text-gray-400 text-sm">Tunggu konfirmasi admin (1x24 jam)</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Help -->
                    <div class="bg-gradient-to-br from-cyan-500/10 to-emerald-500/10 rounded-3xl p-6 border border-cyan-500/20">
                        <h4 class="text-lg font-black text-white mb-4 flex items-center">
                            <i class="fas fa-headset text-cyan-400 mr-2"></i>
                            Butuh Bantuan?
                        </h4>

                        <div class="space-y-3 text-sm">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-phone text-emerald-400"></i>
                                <div>
                                    <div class="text-white font-semibold">Hubungi Kami</div>
                                    <div class="text-gray-400">0882-1001-7726</div>
                                </div>
                            </div>

                            <div class="flex items-center space-x-3">
                                <i class="fas fa-envelope text-cyan-400"></i>
                                <div>
                                    <div class="text-white font-semibold">Email</div>
                                    <div class="text-gray-400">alfreandra@gmail.com</div>
                                </div>
                            </div>

                            <div class="flex items-center space-x-3">
                                <i class="fas fa-comment text-emerald-400"></i>
                                <div>
                                    <div class="text-white font-semibold">Live Chat</div>
                                    <div class="text-gray-400">Tersedia 24/7</div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const bookingForm = document.getElementById('bookingForm');
    const submitBtn = document.getElementById('submitBtn');
    const btnText = document.getElementById('btnText');
    
    bookingForm.addEventListener('submit', function(e) {
        // Show loading state
        submitBtn.disabled = true;
        submitBtn.classList.add('opacity-70');
        btnText.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>MEMPROSES...';
        
        // The form will submit normally
    });
});
</script>

<style>
.glass-card {
    background: linear-gradient(135deg, rgba(31, 41, 55, 0.4) 0%, rgba(17, 24, 39, 0.4) 100%);
    backdrop-filter: blur(10px);
}

input[type="text"], input[type="date"], input[type="time"], textarea, select {
    background: rgba(31, 41, 55, 0.5);
    border: 1px solid rgba(75, 85, 99, 0.5);
}

input[type="text"]:focus, input[type="date"]:focus, input[type="time"]:focus, textarea:focus, select:focus {
    border-color: #10b981;
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
}
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Gor_Kita_Id\resources\views/booking/confirm.blade.php ENDPATH**/ ?>