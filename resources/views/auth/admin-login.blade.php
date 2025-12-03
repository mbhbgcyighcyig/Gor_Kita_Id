<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        @isset($is_user_login)
            {{ $is_user_login ? 'User Login - GOR Booking' : 'Admin Login - GOR Booking' }}
        @else
            Admin Login - GOR Booking
        @endisset
    </title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">
                @isset($is_user_login)
                    @if($is_user_login)
                        <i class="fas fa-user text-blue-600"></i> User Panel
                    @else
                        <i class="fas fa-user-shield text-red-600"></i> Admin Panel
                    @endif
                @else
                    <i class="fas fa-user-shield text-red-600"></i> Admin Panel
                @endisset
            </h1>
            <p class="text-gray-600">
                @isset($is_user_login)
                    {{ $is_user_login ? 'Login sebagai Pengguna' : 'Login sebagai Administrator' }}
                @else
                    Login sebagai Administrator
                @endisset
            </p>
        </div>

        <!-- Login Form - KONDISIONAL -->
        @isset($is_user_login)
            @if($is_user_login)
                <!-- FORM USER LOGIN -->
                <form method="POST" action="/login">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="email" class="block text-gray-700 text-sm font-bold mb-2">
                            <i class="fas fa-envelope mr-2"></i>Email
                        </label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            value="{{ old('email') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="user@example.com"
                            required
                            autofocus
                        >
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
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
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
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
            @else
                <!-- FORM ADMIN LOGIN -->
                <form method="POST" action="/admin/login">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="email" class="block text-gray-700 text-sm font-bold mb-2">
                            <i class="fas fa-envelope mr-2"></i>Email Admin
                        </label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            value="{{ old('email') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                            placeholder="admin@gor.com"
                            required
                            autofocus
                        >
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
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
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
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
            @endif
        @else
            <!-- DEFAULT (Admin Login jika tidak ada parameter) -->
            <form method="POST" action="/admin/login">
                @csrf
                <!-- ... form admin login default ... -->
            </form>
        @endisset

        <!-- Error Messages -->
        @if($errors->any())
            <div class="mt-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded">
                <ul class="list-disc list-inside text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('error'))
            <div class="mt-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded text-sm">
                {{ session('error') }}
            </div>
        @endif

        @if(session('success'))
            <div class="mt-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded text-sm">
                {{ session('success') }}
            </div>
        @endif
    </div>
</body>
</html>