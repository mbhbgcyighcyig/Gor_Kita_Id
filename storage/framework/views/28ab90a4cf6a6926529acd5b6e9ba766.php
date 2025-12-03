

<?php $__env->startSection('title', 'Beri Rating - SportSpace'); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-950 to-black py-8">
    <div class="max-w-4xl mx-auto px-4">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-black text-white mb-2">
                Beri <span class="bg-gradient-to-r from-emerald-300 to-cyan-400 bg-clip-text text-transparent">Rating</span>
            </h1>
            <p class="text-xl text-gray-400">Bagikan pengalaman Anda setelah bermain</p>
        </div>

        <!-- Error/Success Messages -->
        <?php if(session('error')): ?>
        <div class="bg-red-900/20 border border-red-700 rounded-xl p-4 mb-6">
            <div class="flex items-center space-x-2">
                <i class="fas fa-exclamation-circle text-red-400"></i>
                <span class="text-red-300 font-bold"><?php echo e(session('error')); ?></span>
            </div>
        </div>
        <?php endif; ?>

        <?php if(session('success')): ?>
        <div class="bg-emerald-900/20 border border-emerald-700 rounded-xl p-4 mb-6">
            <div class="flex items-center space-x-2">
                <i class="fas fa-check-circle text-emerald-400"></i>
                <span class="text-emerald-300 font-bold"><?php echo e(session('success')); ?></span>
            </div>
        </div>
        <?php endif; ?>

        <?php if($errors->any()): ?>
        <div class="bg-red-900/20 border border-red-700 rounded-xl p-4 mb-6">
            <ul class="space-y-1">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li class="text-red-300 flex items-center space-x-2">
                    <i class="fas fa-exclamation-circle text-xs"></i>
                    <span><?php echo e($error); ?></span>
                </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
        <?php endif; ?>

        <?php
            // Logika dari controller disederhanakan untuk view
            $canRate = true;
            $errorMessage = '';
            
            // Validasi dari controller sudah dilakukan, tapi kita tampilkan ulang di view
            $userId = Auth::check() ? Auth::id() : session('user_id');
            
            if ($booking->user_id != $userId) {
                $canRate = false;
                $errorMessage = 'Anda tidak memiliki akses untuk memberikan rating pada booking ini.';
            }
            
            // Cek status booking
            $allowedStatuses = ['completed', 'confirmed'];
            if (!in_array($booking->status, $allowedStatuses)) {
                $canRate = false;
                $errorMessage = 'Hanya booking yang sudah selesai atau confirmed yang bisa diberi rating.';
            }
            
            // Cek payment status untuk confirmed bookings
            if ($booking->status === 'confirmed' && $booking->payment_status !== 'paid') {
                $canRate = false;
                $errorMessage = 'Harap selesaikan pembayaran sebelum memberi rating.';
            }
            
            // Cek waktu untuk confirmed bookings
            if ($booking->status === 'confirmed') {
                try {
                    $endTime = \Carbon\Carbon::parse($booking->tanggal_booking . ' ' . $booking->jam_selesai);
                    $now = \Carbon\Carbon::now();
                    
                    if (!$endTime->isPast()) {
                        $timeRemaining = $endTime->diffForHumans($now, ['parts' => 2]);
                        $canRate = false;
                        $errorMessage = "Anda bisa memberi rating setelah booking selesai (tersisa $timeRemaining).";
                    }
                } catch (\Exception $e) {
                    $canRate = false;
                    $errorMessage = 'Data waktu booking tidak valid.';
                }
            }
            
            // Cek status cancelled/expired
            if (in_array($booking->status, ['cancelled', 'expired'])) {
                $canRate = false;
                $errorMessage = 'Booking yang ' . $booking->status . ' tidak bisa diberi rating.';
            }
        ?>

        <?php if(!$canRate): ?>
        <!-- Cannot Rate Message -->
        <div class="bg-gradient-to-br from-gray-800/40 to-gray-900/40 rounded-3xl p-8 text-center border border-red-500/20 backdrop-blur-sm max-w-2xl mx-auto">
            <div class="w-20 h-20 mx-auto mb-6 bg-gradient-to-br from-red-500/20 to-pink-500/20 rounded-2xl flex items-center justify-center border border-red-500/30">
                <i class="fas fa-exclamation-triangle text-red-400 text-3xl"></i>
            </div>
            <h2 class="text-2xl font-black text-white mb-4">Tidak Dapat Memberi Rating</h2>
            <p class="text-gray-300 mb-6"><?php echo e($errorMessage); ?></p>
            <a href="<?php echo e(route('booking.my-bookings')); ?>" 
               class="inline-flex items-center space-x-2 bg-gradient-to-r from-emerald-600 to-cyan-600 hover:from-emerald-500 hover:to-cyan-500 text-white font-bold py-3 px-6 rounded-xl transition-all duration-300 border border-emerald-500/30">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali ke My Bookings</span>
            </a>
        </div>
        <?php else: ?>
        <!-- Rating Form -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Left - Booking Info -->
            <div class="bg-gradient-to-br from-gray-800/40 to-gray-900/40 rounded-3xl p-6 border border-emerald-500/20 backdrop-blur-sm">
                <h2 class="text-2xl font-black text-white mb-6 flex items-center">
                    <i class="fas fa-info-circle text-emerald-400 mr-3"></i>
                    Detail Booking
                </h2>
                
                <div class="space-y-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-cyan-500 rounded-2xl flex items-center justify-center border border-emerald-400/30">
                            <?php
                                // Tentukan icon berdasarkan tipe lapangan
                                $fieldType = strtolower($booking->lapangan->type ?? '');
                                $fieldIcon = 'fa-running'; // default
                                
                                if (str_contains($fieldType, 'futsal') || str_contains($fieldType, 'soccer')) {
                                    $fieldIcon = 'fa-futbol';
                                } elseif (str_contains($fieldType, 'badminton') || str_contains($fieldType, 'tennis')) {
                                    $fieldIcon = 'fa-table-tennis-paddle-ball';
                                } elseif (str_contains($fieldType, 'basket')) {
                                    $fieldIcon = 'fa-basketball';
                                } elseif (str_contains($fieldType, 'volley')) {
                                    $fieldIcon = 'fa-volleyball';
                                }
                            ?>
                            <i class="fas <?php echo e($fieldIcon); ?> text-white text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-black text-white"><?php echo e($booking->lapangan->name ?? $booking->lapangan->nama_lapangan ?? 'Lapangan #' . $booking->lapangan_id); ?></h3>
                            <p class="text-emerald-300 text-sm"><?php echo e($booking->lapangan->type ?? 'Sports Field'); ?></p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 mt-6">
                        <div class="bg-gray-800/50 rounded-xl p-4">
                            <div class="flex items-center space-x-2 text-gray-300 mb-1">
                                <i class="fas fa-calendar text-emerald-400"></i>
                                <span class="text-sm font-semibold">Tanggal</span>
                            </div>
                            <div class="text-white font-bold">
                                <?php echo e(\Carbon\Carbon::parse($booking->tanggal_booking)->translatedFormat('d F Y')); ?>

                            </div>
                        </div>
                        
                        <div class="bg-gray-800/50 rounded-xl p-4">
                            <div class="flex items-center space-x-2 text-gray-300 mb-1">
                                <i class="fas fa-clock text-cyan-400"></i>
                                <span class="text-sm font-semibold">Waktu</span>
                            </div>
                            <div class="text-white font-bold">
                                <?php echo e($booking->jam_mulai . ' - ' . $booking->jam_selesai); ?>

                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4 pt-4 border-t border-gray-700/50">
                        <p class="text-gray-400 text-sm mb-2">Kode Booking</p>
                        <div class="inline-flex items-center px-4 py-2 bg-gray-800/70 rounded-full border border-emerald-500/30">
                            <span class="text-emerald-300 font-bold">#<?php echo e($booking->id); ?></span>
                        </div>
                    </div>
                    
                    <!-- Status Info -->
                    <div class="bg-blue-900/20 border border-blue-700/30 rounded-xl p-4 mt-4">
                        <div class="flex items-center space-x-2">
                            <?php if($booking->status === 'completed'): ?>
                                <i class="fas fa-check-circle text-emerald-400"></i>
                                <div>
                                    <p class="text-emerald-300 font-semibold">Status: Selesai</p>
                                    <p class="text-gray-400 text-sm mt-1">Booking ini sudah selesai dan siap untuk diberi rating</p>
                                </div>
                            <?php elseif($booking->status === 'confirmed'): ?>
                                <i class="fas fa-clock text-amber-400"></i>
                                <div>
                                    <p class="text-amber-300 font-semibold">Status: Terkonfirmasi</p>
                                    <p class="text-gray-400 text-sm mt-1">Anda bisa memberi rating setelah waktu booking selesai</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Payment Status -->
                    <?php if($booking->status === 'confirmed'): ?>
                    <div class="bg-<?php echo e($booking->payment_status === 'paid' ? 'emerald' : 'amber'); ?>-900/20 border border-<?php echo e($booking->payment_status === 'paid' ? 'emerald' : 'amber'); ?>-700/30 rounded-xl p-4">
                        <div class="flex items-center space-x-2">
                            <?php if($booking->payment_status === 'paid'): ?>
                                <i class="fas fa-check-circle text-emerald-400"></i>
                            <?php else: ?>
                                <i class="fas fa-exclamation-circle text-amber-400"></i>
                            <?php endif; ?>
                            <div>
                                <p class="text-<?php echo e($booking->payment_status === 'paid' ? 'emerald' : 'amber'); ?>-300 font-semibold">
                                    Pembayaran: <?php echo e(ucfirst($booking->payment_status)); ?>

                                </p>
                                <?php if($booking->payment_status !== 'paid'): ?>
                                    <p class="text-gray-400 text-sm mt-1">Harap selesaikan pembayaran terlebih dahulu</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Right - Rating Form -->
            <div class="bg-gradient-to-br from-gray-800/40 to-gray-900/40 rounded-3xl p-6 border border-emerald-500/20 backdrop-blur-sm">
                <h2 class="text-2xl font-black text-white mb-6 flex items-center">
                    <i class="fas fa-star text-yellow-400 mr-3"></i>
                    Penilaian Anda
                </h2>
                
                <form method="POST" action="<?php echo e(route('rating.store', $booking->id)); ?>">
                    <?php echo csrf_field(); ?>
                    
                    <!-- Rating Stars -->
                    <div class="mb-8">
                        <label class="block text-gray-300 text-sm font-bold mb-4">
                            Berapa bintang untuk pengalaman Anda? *
                        </label>
                        <div class="flex justify-center space-x-2">
                            <?php for($i = 1; $i <= 5; $i++): ?>
                            <button type="button" 
                                    onclick="setRating(<?php echo e($i); ?>)"
                                    class="rating-star text-4xl transition-all duration-300 hover:scale-125 focus:outline-none"
                                    data-value="<?php echo e($i); ?>">
                                <i class="far fa-star text-yellow-400"></i>
                            </button>
                            <?php endfor; ?>
                        </div>
                        <input type="hidden" name="rating" id="ratingValue" value="5" required>
                        <div class="text-center mt-2">
                            <span id="ratingText" class="text-2xl font-black text-yellow-400">5.0</span>
                            <span class="text-gray-400 ml-1">/ 5.0</span>
                        </div>
                        <?php $__errorArgs = ['rating'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-400 text-sm text-center mt-2"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Review Text -->
                    <div class="mb-6">
                        <label for="review" class="block text-gray-300 text-sm font-bold mb-3">
                            <i class="fas fa-comment-dots text-cyan-400 mr-2"></i>
                            Ulasan (Opsional)
                        </label>
                        <textarea name="review" 
                                  id="review" 
                                  rows="4"
                                  maxlength="500"
                                  class="w-full bg-gray-800/50 border border-gray-700 text-white px-4 py-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500/30 transition placeholder-gray-500"
                                  placeholder="Bagikan pengalaman Anda bermain di lapangan ini..."><?php echo e(old('review')); ?></textarea>
                        <?php $__errorArgs = ['review'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-400 text-sm mt-2"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <p class="text-gray-400 text-sm mt-1">Maksimal 500 karakter</p>
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-8">
                        <button type="submit"
                                class="w-full bg-gradient-to-r from-emerald-600 to-cyan-600 hover:from-emerald-500 hover:to-cyan-500 text-white font-bold py-4 rounded-xl text-lg transition-all duration-300 transform hover:-translate-y-1 border border-emerald-500/30 flex items-center justify-center space-x-3 disabled:opacity-50 disabled:cursor-not-allowed"
                                id="submitBtn">
                            <i class="fas fa-paper-plane"></i>
                            <span>Kirim Rating</span>
                        </button>
                    </div>
                    
                    <!-- Cancel Button -->
                    <div class="mt-4 text-center">
                        <a href="<?php echo e(route('booking.my-bookings')); ?>" 
                           class="text-gray-400 hover:text-gray-300 transition inline-flex items-center space-x-2">
                            <i class="fas fa-times"></i>
                            <span>Batalkan</span>
                        </a>
                    </div>
                </form>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
let selectedRating = 5;

function setRating(rating) {
    selectedRating = rating;
    document.getElementById('ratingValue').value = rating;
    document.getElementById('ratingText').textContent = rating + '.0';
    
    // Update star display
    const stars = document.querySelectorAll('.rating-star');
    stars.forEach((star, index) => {
        const starIndex = index + 1;
        if (starIndex <= rating) {
            star.innerHTML = '<i class="fas fa-star text-yellow-400"></i>';
            star.classList.add('selected');
        } else {
            star.innerHTML = '<i class="far fa-star text-yellow-400"></i>';
            star.classList.remove('selected');
        }
    });
    
    // Enable/disable submit button
    const submitBtn = document.getElementById('submitBtn');
    if (rating > 0) {
        submitBtn.disabled = false;
    } else {
        submitBtn.disabled = true;
    }
}

// Initialize with 5 stars
document.addEventListener('DOMContentLoaded', function() {
    setRating(5);
    
    // Character counter for review
    const reviewTextarea = document.getElementById('review');
    const charCounter = document.createElement('div');
    charCounter.className = 'text-gray-500 text-xs text-right mt-1';
    reviewTextarea.parentNode.appendChild(charCounter);
    
    function updateCharCounter() {
        const length = reviewTextarea.value.length;
        charCounter.textContent = `${length}/500 karakter`;
        
        if (length > 450) {
            charCounter.className = 'text-amber-500 text-xs text-right mt-1';
        } else if (length > 400) {
            charCounter.className = 'text-gray-300 text-xs text-right mt-1';
        } else {
            charCounter.className = 'text-gray-500 text-xs text-right mt-1';
        }
    }
    
    reviewTextarea.addEventListener('input', updateCharCounter);
    updateCharCounter(); // Initial update
    
    // Form validation
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const ratingValue = document.getElementById('ratingValue').value;
        
        if (!ratingValue || ratingValue == '0') {
            e.preventDefault();
            showAlert('Silakan berikan rating bintang terlebih dahulu!', 'error');
            return false;
        }
        
        // Add loading state
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Mengirim...';
    });
    
    // Star hover effect
    const stars = document.querySelectorAll('.rating-star');
    stars.forEach((star, index) => {
        star.addEventListener('mouseenter', () => {
            const hoverRating = index + 1;
            stars.forEach((s, i) => {
                if (i < hoverRating) {
                    s.style.transform = 'scale(1.1)';
                }
            });
        });
        
        star.addEventListener('mouseleave', () => {
            stars.forEach(s => {
                s.style.transform = '';
            });
        });
    });
    
    function showAlert(message, type = 'error') {
        // Remove existing alert
        const existingAlert = document.querySelector('.custom-alert');
        if (existingAlert) existingAlert.remove();
        
        const alert = document.createElement('div');
        alert.className = `fixed top-4 right-4 z-50 ${type === 'error' ? 'bg-red-900' : 'bg-emerald-900'} text-white px-6 py-4 rounded-xl shadow-lg custom-alert`;
        alert.innerHTML = `
            <div class="flex items-center space-x-3">
                <i class="fas ${type === 'error' ? 'fa-exclamation-circle' : 'fa-check-circle'}"></i>
                <span>${message}</span>
            </div>
        `;
        
        document.body.appendChild(alert);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            alert.remove();
        }, 5000);
    }
});
</script>

<style>
.rating-star {
    cursor: pointer;
    transition: all 0.3s ease;
}

.rating-star.selected i {
    text-shadow: 0 0 10px rgba(255, 193, 7, 0.5);
}

.rating-star:hover {
    transform: scale(1.2);
}
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Gor_Kita_Id\resources\views/rating/create.blade.php ENDPATH**/ ?>