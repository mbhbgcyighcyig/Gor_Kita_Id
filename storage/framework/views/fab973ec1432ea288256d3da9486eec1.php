<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan - GorKita.ID</title>
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
        
        .chart-container {
            background: white;
            border-radius: 16px;
            padding: 25px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="sidebar">
            <?php echo $__env->make('admin.partials.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
        
        <div class="main-content">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Pengaturan</h1>
                    <p class="text-gray-600">Kelola pengaturan aplikasi</p>
                </div>
                <div class="text-right">
                    <p class="text-2xl font-bold text-blue-600">Hi, Admin GOR! 👋</p>
                    <p class="text-gray-500"><?php echo e(\Carbon\Carbon::now()->translatedFormat('l, d F Y')); ?></p>
                </div>
            </div>

            <?php if(session('success')): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
                <i class="fas fa-check-circle mr-2"></i>
                <?php echo e(session('success')); ?>

            </div>
            <?php endif; ?>

            <?php if(session('error')): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <?php echo e(session('error')); ?>

            </div>
            <?php endif; ?>

            <!-- Form Pengaturan -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <!-- ✅ PERBAIKAN: Gunakan route yang benar -->
                <form action="<?php echo e(route('admin.pengaturan.update')); ?>" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    
                    <!-- Section Umum -->
                    <div class="mb-8">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4">Umum</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Aplikasi</label>
                                <input type="text" name="nama_aplikasi" value="<?php echo e($pengaturan->nama_aplikasi ?? 'GorKita.ID'); ?>" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Email Admin</label>
                                <input type="email" name="email_admin" value="<?php echo e($pengaturan->email_admin ?? 'admin@gorkita.id'); ?>" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                            </div>
                        </div>
                    </div>

                    <!-- Section Pembayaran -->
                    <div class="mb-8">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4">Pembayaran</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Batas Waktu Bayar (menit)</label>
                                <input type="number" name="batas_waktu_bayar" value="<?php echo e($pengaturan->batas_waktu_bayar ?? 15); ?>" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">PIN Demo</label>
                                <input type="text" name="pin_demo" value="<?php echo e($pengaturan->pin_demo ?? '123456'); ?>" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Max Attempt PIN</label>
                                <input type="number" name="max_attempt_pin" value="<?php echo e($pengaturan->max_attempt_pin ?? 3); ?>" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                            </div>
                        </div>
                    </div>

                    <!-- Section Harga Default -->
                    <div class="mb-8">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4">Harga Default</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Harga Badminton /jam</label>
                                <div class="flex">
                                    <span class="inline-flex items-center px-3 rounded-l-lg border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                                        Rp
                                    </span>
                                    <input type="number" name="harga_badminton" value="<?php echo e($pengaturan->harga_badminton ?? 40000); ?>" 
                                           class="w-full px-4 py-2 border border-gray-300 rounded-r-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Harga Futsal /jam</label>
                                <div class="flex">
                                    <span class="inline-flex items-center px-3 rounded-l-lg border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                                        Rp
                                    </span>
                                    <input type="number" name="harga_futsal" value="<?php echo e($pengaturan->harga_futsal ?? 80000); ?>" 
                                           class="w-full px-4 py-2 border border-gray-300 rounded-r-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section Notifikasi -->
                    <div class="mb-8">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4">Notifikasi</h3>
                        <div class="space-y-4">
                            <div class="flex items-center">
                                <input type="checkbox" name="notif_email" id="notif_email" 
                                       class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded" 
                                       <?php echo e(($pengaturan->notif_email ?? true) ? 'checked' : ''); ?>>
                                <label for="notif_email" class="ml-2 block text-sm text-gray-700">
                                    Aktifkan notifikasi email
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" name="notif_booking_baru" id="notif_booking_baru" 
                                       class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded" 
                                       <?php echo e(($pengaturan->notif_booking_baru ?? true) ? 'checked' : ''); ?>>
                                <label for="notif_booking_baru" class="ml-2 block text-sm text-gray-700">
                                    Notifikasi booking baru
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" name="notif_pembayaran" id="notif_pembayaran" 
                                       class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded" 
                                       <?php echo e(($pengaturan->notif_pembayaran ?? true) ? 'checked' : ''); ?>>
                                <label for="notif_pembayaran" class="ml-2 block text-sm text-gray-700">
                                    Notifikasi pembayaran
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Button Simpan -->
                    <div class="flex justify-end space-x-4">
                        <button type="reset" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                            Reset
                        </button>
                        <button type="submit" class="px-6 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition flex items-center">
                            <i class="fas fa-save mr-2"></i>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html><?php /**PATH C:\xampp\htdocs\Gor_Kita_Id\resources\views/admin/pengaturan.blade.php ENDPATH**/ ?>