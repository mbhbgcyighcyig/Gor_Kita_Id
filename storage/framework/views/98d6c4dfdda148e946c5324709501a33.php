<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pengguna - GorKita.ID</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <?php echo $__env->make('admin.partials.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
        
        <!-- Main Content -->
        <div class="main-content">
            <h1 class="text-3xl font-bold text-gray-800 mb-8">Kelola Pengguna</h1>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <?php
                    $totalUsers = \App\Models\User::count();
                    // HAPUS QUERY STATUS KARENA KOLOM TIDAK ADA
                    $activeUsers = $totalUsers; // Semua user dianggap aktif
                    $inactiveUsers = 0; // Tidak ada user inactive
                    $newUsers = \App\Models\User::whereDate('created_at', today())->count();
                    $adminUsers = \App\Models\User::where('role', 'admin')->count();
                ?>
                
                <div class="bg-gradient-to-r from-blue-500 to-cyan-500 p-6 text-white rounded-2xl shadow-lg">
                    <div class="flex items-center">
                        <div class="bg-white bg-opacity-20 rounded-full p-3 mr-4">
                            <i class="fas fa-users text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm opacity-90">Total Pengguna</p>
                            <p class="text-2xl font-bold"><?php echo e($totalUsers); ?></p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gradient-to-r from-green-500 to-emerald-500 p-6 text-white rounded-2xl shadow-lg">
                    <div class="flex items-center">
                        <div class="bg-white bg-opacity-20 rounded-full p-3 mr-4">
                            <i class="fas fa-user-check text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm opacity-90">User Aktif</p>
                            <p class="text-2xl font-bold"><?php echo e($activeUsers); ?></p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gradient-to-r from-purple-500 to-pink-500 p-6 text-white rounded-2xl shadow-lg">
                    <div class="flex items-center">
                        <div class="bg-white bg-opacity-20 rounded-full p-3 mr-4">
                            <i class="fas fa-user-shield text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm opacity-90">Admin</p>
                            <p class="text-2xl font-bold"><?php echo e($adminUsers); ?></p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gradient-to-r from-orange-500 to-red-500 p-6 text-white rounded-2xl shadow-lg">
                    <div class="flex items-center">
                        <div class="bg-white bg-opacity-20 rounded-full p-3 mr-4">
                            <i class="fas fa-user-plus text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm opacity-90">Baru Hari Ini</p>
                            <p class="text-2xl font-bold"><?php echo e($newUsers); ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Users Table -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">Daftar Pengguna</h2>
                </div>
                
                <?php if($users->count() > 0): ?>
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead class="bg-gradient-to-r from-gray-800 to-gray-700">
                                <tr>
                                    <th class="px-4 py-3 text-left text-white font-semibold">ID</th>
                                    <th class="px-4 py-3 text-left text-white font-semibold">Nama</th>
                                    <th class="px-4 py-3 text-left text-white font-semibold">Email</th>
                                    <th class="px-4 py-3 text-left text-white font-semibold">Role</th>
                                    <th class="px-4 py-3 text-left text-white font-semibold">Tanggal Daftar</th>
                                    <th class="px-4 py-3 text-left text-white font-semibold">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="border-b hover:bg-gray-50 transition">
                                    <td class="px-4 py-3">
                                        <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded text-sm font-mono">
                                            #<?php echo e($user->id); ?>

                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center">
                                            <div class="bg-blue-100 rounded-full p-2 mr-3">
                                                <i class="fas fa-user text-blue-600"></i>
                                            </div>
                                            <div>
                                                <p class="font-bold text-gray-800"><?php echo e($user->name); ?></p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <p class="text-gray-800"><?php echo e($user->email); ?></p>
                                    </td>
                                
                                    <td class="px-4 py-3">
                                        <?php
                                            $roleClass = $user->role == 'admin' 
                                                ? 'bg-purple-100 text-purple-800' 
                                                : 'bg-green-100 text-green-800';
                                        ?>
                                        <span class="px-3 py-1 rounded-full text-sm font-bold <?php echo e($roleClass); ?>">
                                            <?php echo e($user->role); ?>

                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <p class="text-gray-600"><?php echo e($user->created_at->format('d M Y')); ?></p>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex space-x-2">
                                            <a href="<?php echo e(route('admin.users.edit', $user->id)); ?>" 
                                               class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm font-semibold transition">
                                                <i class="fas fa-edit mr-1"></i> Edit
                                            </a>
                                            <?php if($user->role !== 'admin'): ?>
                                            <form action="<?php echo e(route('admin.users.destroy', $user->id)); ?>" method="POST" class="inline">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" 
                                                        class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm font-semibold transition"
                                                        onclick="return confirm('Yakin ingin menghapus user ini?')">
                                                    <i class="fas fa-trash mr-1"></i> Hapus
                                                </button>
                                            </form>
                                            <?php else: ?>
                                            <span class="text-gray-400 text-sm">Tidak bisa hapus admin</span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        <?php echo e($users->links()); ?>

                    </div>
                <?php else: ?>
                    <div class="text-center py-12">
                        <i class="fas fa-users text-gray-400 text-6xl mb-4"></i>
                        <p class="text-gray-500 text-lg font-semibold">Belum ada pengguna</p>
                        <p class="text-gray-400">Tidak ada data pengguna yang ditemukan</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <style>
        .admin-container {
            display: flex;
            min-height: 100vh;
        }
        
        .sidebar {
            width: 250px;
            background: #1f2937;
            color: white;
            position: fixed;
            height: 100vh;
            left: 0;
            top: 0;
            overflow-y: auto;
        }
        
        .main-content {
            flex: 1;
            margin-left: 250px;
            padding: 20px;
            background: #f8fafc;
            min-height: 100vh;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</body>
</html><?php /**PATH C:\xampp\htdocs\Gor_Kita_Id\resources\views/admin/users/index.blade.php ENDPATH**/ ?>