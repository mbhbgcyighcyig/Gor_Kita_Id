<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan - GorKita.ID</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <!-- Admin Layout -->
    @include('admin.partials.sidebar')
    
    <main class="ml-64 p-8">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-800">Pengaturan Sistem</h1>
                <p class="text-gray-600">Kelola pengaturan aplikasi SportSpace</p>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Form Pengaturan -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <form action="{{ route('admin.pengaturan.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Section Umum -->
                    <div class="mb-8">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4">Umum</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Aplikasi</label>
                                <input type="text" name="nama_aplikasi" value="{{ $pengaturan->nama_aplikasi }}" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Email Admin</label>
                                <input type="email" name="email_admin" value="{{ $pengaturan->email_admin }}" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                            </div>
                        </div>
                    </div>

                    <!-- Section Jam Operasional -->
                    <div class="mb-8">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4">Jam Operasional</h3>
                        <div class="flex items-center space-x-4">
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Jam Buka</label>
                                <input type="time" name="jam_buka" value="{{ $pengaturan->jam_buka }}" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                            </div>
                            <span class="mt-6 text-gray-500">s/d</span>
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Jam Tutup</label>
                                <input type="time" name="jam_tutup" value="{{ $pengaturan->jam_tutup }}" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                            </div>
                        </div>
                    </div>

                    <!-- Section Pembayaran -->
                    <div class="mb-8">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4">Pembayaran</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Payment Gateway</label>
                                <select name="payment_gateway" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                    <option value="midtrans" {{ $pengaturan->payment_gateway == 'midtrans' ? 'selected' : '' }}>Midtrans</option>
                                    <option value="xendit" {{ $pengaturan->payment_gateway == 'xendit' ? 'selected' : '' }}>Xendit</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">API Key</label>
                                <input type="text" name="api_key" value="{{ $pengaturan->api_key }}" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                            </div>
                        </div>
                    </div>

                    <!-- Section Logo -->
                    <div class="mb-8">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4">Logo Aplikasi</h3>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Upload Logo</label>
                            <input type="file" name="logo" accept="image/*" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                            @if($pengaturan->logo)
                                <p class="text-sm text-gray-500 mt-2">Logo saat ini: {{ $pengaturan->logo }}</p>
                                <img src="{{ Storage::url($pengaturan->logo) }}" alt="Logo" class="mt-2 h-20">
                            @else
                                <p class="text-sm text-gray-500 mt-2">Belum ada logo yang diupload</p>
                            @endif
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end">
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-lg transition">
                            <i class="fas fa-save mr-2"></i>Simpan Pengaturan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</body>
</html>