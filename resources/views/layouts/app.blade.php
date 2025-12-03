<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'GorKita')</title>
    <link href="{{ asset('css/cssnya.css') }}" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <header class="bg-white shadow-sm">
        <nav class="container mx-auto px-4 py-4 flex justify-between items-center">
            <div class="flex items-center space-x-8">
                <h1 class="text-xl font-bold text-blue-600">GorKita</h1>
                <div class="hidden md:flex space-x-6">
                    <a href="/admin/dashboard" class="text-gray-700 hover:text-blue-600">Dashboard</a>
                    <a href="/admin/lapangan" class="text-gray-700 hover:text-blue-600">Lapangan</a>
                    <a href="/admin/book" class="text-gray-700 hover:text-blue-600">Booking</a>
                    <a href="/admin/pengguna" class="text-gray-700 hover:text-blue-600">Pengguna</a>
                    <a href="/admin/pembayaran" class="text-gray-700 hover:text-blue-600">Pembayaran</a>
                    <a href="/admin/laporan" class="text-gray-700 hover:text-blue-600">Laporan</a>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                <span class="text-gray-700">Admin</span>
                <form method="POST" action="/admin/logout">
                    @csrf
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">
                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                    </button>
                </form>
            </div>
        </nav>
    </header>

    <main class="container mx-auto px-4 py-8">
        @yield('content')
    </main>

    <footer class="bg-white border-t mt-12">
        <div class="container mx-auto px-4 py-6 text-center text-gray-600">
            © 2025 GorKita | All Rights Reserved.
        </div>
    </footer>
</body>
</html>