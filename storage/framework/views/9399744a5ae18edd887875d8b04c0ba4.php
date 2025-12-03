

<?php $__env->startSection('title', 'Booking - Pilih Lapangan'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="text-center mb-8">
        <h1 class="text-4xl font-bold text-gray-800 mb-4">Pilih Jenis Lapangan</h1>
        <p class="text-gray-600 text-lg">Tentukan jenis lapangan yang ingin kamu booking</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        
        <!-- Futsal -->
  <!-- Futsal Card -->
<a href="<?php echo e(route('booking.select-field', 'futsal')); ?>" class="group">
    <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-2xl transition">
        <div class="bg-gradient-to-br from-green-400 to-blue-500 h-64 flex items-center justify-center">
            <i class="fas fa-futbol text-white text-8xl group-hover:scale-110 transition"></i>
        </div>
        <div class="p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-2">Futsal</h2>
            <p class="text-gray-600 mb-4"></p>
            <div class="flex items-center justify-between">
                <span class="text-blue-600 font-bold text-lg">Mulai Rp 150.000/jam</span>
                <i class="fas fa-arrow-right text-blue-600 group-hover:translate-x-2 transition"></i>
            </div>
        </div>
    </div>
</a>

<!-- Badminton Card -->
<a href="<?php echo e(route('booking.select-field', 'badminton')); ?>" class="group">
    <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-2xl transition">
        <div class="bg-gradient-to-br from-red-400 to-pink-500 h-64 flex items-center justify-center">
            <i class="fas fa-table-tennis text-white text-8xl group-hover:scale-110 transition"></i>
        </div>
        <div class="p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-2">Badminton</h2>
            <p class="text-gray-600 mb-4"></p>
            <div class="flex items-center justify-between">
                <span class="text-red-600 font-bold text-lg">Mulai Rp 40.000/jam</span>
                <i class="fas fa-arrow-right text-red-600 group-hover:translate-x-2 transition"></i>
            </div>
        </div>
    </div>
</a>

<!-- Mini Soccer Card -->
<a href="<?php echo e(route('booking.select-field', 'mini_soccer')); ?>" class="group">
    <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-2xl transition">
        <div class="bg-gradient-to-br from-green-500 to-emerald-600 h-64 flex items-center justify-center">
            <i class="fas fa-football-ball text-white text-8xl group-hover:scale-110 transition"></i>
        </div>
        <div class="p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-2">Mini Soccer</h2>
            <p class="text-gray-600 mb-4"></p>
            <div class="flex items-center justify-between">
                <span class="text-green-600 font-bold text-lg">Rp 800.000/2jam</span>
                <i class="fas fa-arrow-right text-green-600 group-hover:translate-x-2 transition"></i>
            </div>
        </div>
    </div>
</a>

    <!-- Info Box -->
    <div class="mt-12 bg-blue-50 border-l-4 border-blue-500 p-6 rounded">
        <h3 class="text-lg font-bold text-blue-800 mb-2">
            <i class="fas fa-info-circle mr-2"></i> Tips Booking
        </h3>
        <ul class="text-blue-700 space-y-2">
            <li><i class="fas fa-check mr-2"></i> Pilih jenis lapangan yang sesuai dengan kebutuhan</li>
            <li><i class="fas fa-check mr-2"></i> Booking jauh-jauh hari untuk mendapatkan slot</li>
            <li><i class="fas fa-check mr-2"></i> Siapkan bukti pembayaran untuk konfirmasi booking</li>
            <li><i class="fas fa-check mr-2"></i> Datang 10 menit sebelum waktu booking untuk persiapan</li>
        </ul>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Gor_Kita_Id\resources\views/booking/index.blade.php ENDPATH**/ ?>