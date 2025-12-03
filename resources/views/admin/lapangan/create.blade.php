<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Lapangan - GorKita.ID</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    @include('admin.partials.sidebar')
    
    <main class="ml-64 p-8">
        <div class="max-w-2xl mx-auto">
            <!-- Header -->
            <div class="flex items-center mb-8">
                <a href="{{ route('admin.lapangan.index') }}" class="text-gray-600 hover:text-gray-800 mr-4">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 class="text-3xl font-bold text-gray-800">Tambah Lapangan Baru</h1>
            </div>

            <!-- Form -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <form action="{{ route('admin.lapangan.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-6">
                        <label for="name" class="block text-gray-700 font-bold mb-2">Nama Lapangan</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500"
                            placeholder="Contoh: Lapangan Futsal A" required>
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="type" class="block text-gray-700 font-bold mb-2">Tipe Lapangan</label>
                        <select id="type" name="type" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" required>
                            <option value="">Pilih Tipe</option>
                            <option value="futsal" {{ old('type') == 'futsal' ? 'selected' : '' }}>Futsal</option>
                            <option value="badminton" {{ old('type') == 'badminton' ? 'selected' : '' }}>Badminton</option>
                            <option value="minisoccer" {{ old('type') == 'minisoccer' ? 'selected' : '' }}>Mini Soccer</option>
                        </select>
                        @error('type')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="price_per_hour" class="block text-gray-700 font-bold mb-2">Harga per Jam</label>
                        <input type="number" id="price_per_hour" name="price_per_hour" value="{{ old('price_per_hour') }}" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500"
                            placeholder="Contoh: 150000" min="0" required>
                        @error('price_per_hour')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="description" class="block text-gray-700 font-bold mb-2">Deskripsi (Opsional)</label>
                        <textarea id="description" name="description" rows="3"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500"
                            placeholder="Deskripsi lapangan...">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex space-x-4">
                        <a href="{{ route('admin.lapangan.index') }}" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 px-4 rounded-lg text-center transition">
                            Batal
                        </a>
                        <button type="submit" class="flex-1 bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-4 rounded-lg transition">
                            <i class="fas fa-save mr-2"></i>Simpan Lapangan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</body>
</html>