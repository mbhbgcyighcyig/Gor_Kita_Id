<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php if(isset($is_user_login)): ?>
            <?php echo e($is_user_login ? 'User Login - GOR Booking' : 'Admin Login - GOR Booking'); ?>

        <?php else: ?>
            Admin Login - GOR Booking
        <?php endif; ?>
    </title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">
                <?php if(isset($is_user_login)): ?>
                    <?php if($is_user_login): ?>
                        <i class="fas fa-user text-blue-600"></i> User Panel
                    <?php else: ?>
                        <i class="fas fa-user-shield text-red-600"></i> Admin Panel
                    <?php endif; ?>
                <?php else: ?>
                    <i class="fas fa-user-shield text-red-600"></i> Admin Panel
                <?php endif; ?>
            </h1>
            <p class="text-gray-600">
                <?php if(isset($is_user_login)): ?>
                    <?php echo e($is_user_login ? 'Login sebagai Pengguna' : 'Login sebagai Administrator'); ?>

                <?php else: ?>
                    Login sebagai Administrator
                <?php endif; ?>
            </p>
        </div>

        <!-- Login Form - KONDISIONAL -->
        <?php if(isset($is_user_login)): ?>
            <?php if($is_user_login): ?>
                <!-- FORM USER LOGIN -->
                <form method="POST" action="/login">
                    <?php echo csrf_field(); ?>
                    
                    <div class="mb-4">
                        <label for="email" class="block text-gray-700 text-sm font-bold mb-2">
                            <i class="fas fa-envelope mr-2"></i>Email
                        </label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            value="<?php echo e(old('email')); ?>"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="user@example.com"
                            required
                            autofocus
                        >
                        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="mb-6">
                        <label for="password" class="block text-gray-700 text-sm font-bold mb-2">
                            <i class="fas fa-lock mr-2"></i>Password
                        </label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Masukkan password"
                            required
                        >
                        <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="mb-6">
                        <label class="flex items-center">
                            <input 
                                type="checkbox" 
                                name="remember" 
                                class="form-checkbox h-4 w-4 text-blue-600"
                            >
                            <span class="ml-2 text-gray-700 text-sm">Ingat Saya</span>
                        </label>
                    </div>

                    <button 
                        type="submit" 
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition duration-200"
                    >
                        <i class="fas fa-sign-in-alt mr-2"></i>Login sebagai User
                    </button>
                </form>

                <!-- Link ke Admin Login -->
                <div class="mt-6 text-center">
                    <a 
                        href="/admin/login" 
                        class="text-blue-600 hover:text-blue-700 text-sm font-medium"
                    >
                        <i class="fas fa-user-shield mr-1"></i>Login sebagai Admin
                    </a>
                </div>
            <?php else: ?>
                <!-- FORM ADMIN LOGIN -->
                <form method="POST" action="/admin/login">
                    <?php echo csrf_field(); ?>
                    
                    <div class="mb-4">
                        <label for="email" class="block text-gray-700 text-sm font-bold mb-2">
                            <i class="fas fa-envelope mr-2"></i>Email Admin
                        </label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            value="<?php echo e(old('email')); ?>"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                            placeholder="admin@gor.com"
                            required
                            autofocus
                        >
                        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="mb-6">
                        <label for="password" class="block text-gray-700 text-sm font-bold mb-2">
                            <i class="fas fa-lock mr-2"></i>Password Admin
                        </label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                            placeholder="Masukkan password"
                            required
                        >
                        <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="mb-6">
                        <label class="flex items-center">
                            <input 
                                type="checkbox" 
                                name="remember" 
                                class="form-checkbox h-4 w-4 text-red-600"
                            >
                            <span class="ml-2 text-gray-700 text-sm">Ingat Saya</span>
                        </label>
                    </div>

                    <button 
                        type="submit" 
                        class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-4 rounded-lg transition duration-200"
                    >
                        <i class="fas fa-sign-in-alt mr-2"></i>Login sebagai Admin
                    </button>
                </form>

                <!-- Demo Credentials -->
                <div class="mt-6 p-4 bg-gray-100 rounded-lg">
                    <p class="text-sm text-gray-600 font-bold mb-2">Default Admin:</p>
                    <p class="text-sm text-gray-600">Email: admin@gor.com</p>
                    <p class="text-sm text-gray-600">Password: admin123</p>
                </div>

                <!-- Back to User Login -->
                <div class="mt-6 text-center">
                    <a 
                        href="/login" 
                        class="text-red-600 hover:text-red-700 text-sm font-medium"
                    >
                        <i class="fas fa-arrow-left mr-1"></i>Kembali ke Login User
                    </a>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <!-- DEFAULT (Admin Login jika tidak ada parameter) -->
            <form method="POST" action="/admin/login">
                <?php echo csrf_field(); ?>
                <!-- ... form admin login default ... -->
            </form>
        <?php endif; ?>

        <!-- Error Messages -->
        <?php if($errors->any()): ?>
            <div class="mt-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded">
                <ul class="list-disc list-inside text-sm">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div class="mt-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded text-sm">
                <?php echo e(session('error')); ?>

            </div>
        <?php endif; ?>

        <?php if(session('success')): ?>
            <div class="mt-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded text-sm">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>
    </div>
</body>
</html><?php /**PATH C:\xampp\htdocs\Gor_Kita_Id\resources\views/auth/admin-login.blade.php ENDPATH**/ ?>