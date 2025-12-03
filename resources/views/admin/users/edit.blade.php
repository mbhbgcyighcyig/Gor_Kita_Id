<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Lapangan - GorKita.ID</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    @include('partials.sidebar')
    
    <main class="ml-64 p-8">
        <div class="max-w-2xl mx-auto">
            <!-- Header -->
            <div class="flex items-center mb-8">
                <a href="{{ route('lapangan.index') }}" class="text-gray-600 hover:text-gray-800 mr-4">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 class="text-3xl font-bold text-gray-800">Edit Lapangan</h1>
            </div>

            <!-- Form -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <form action="{{ route('lapangan.update', $lapangan->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-6">
                        <label for="nama" class="block text-gray-700 font-bold mb-2">Nama Lapangan</label>
                        <input type="text" id="nama" name="nama" value="{{ old('nama', $lapangan->nama) }}" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500"
                            placeholder="Contoh: Lapangan Futsal A" required>
                        @error('nama')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="jenis_lapangan" class="block text-gray-700 font-bold mb-2">Jenis Lapangan</label>
                        <select id="jenis_lapangan" name="jenis_lapangan" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" required>
                            <option value="">Pilih Jenis</option>
                            <option value="Futsal" {{ old('jenis_lapangan', $lapangan->jenis_lapangan) == 'Futsal' ? 'selected' : '' }}>Futsal</option>
                            <option value="Badminton" {{ old('jenis_lapangan', $lapangan->jenis_lapangan) == 'Badminton' ? 'selected' : '' }}>Badminton</option>
                            <option value="Mini Soccer" {{ old('jenis_lapangan', $lapangan->jenis_lapangan) == 'Mini Soccer' ? 'selected' : '' }}>Mini Soccer</option>
                        </select>
                        @error('jenis_lapangan')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="harga_per_jam" class="block text-gray-700 font-bold mb-2">Harga per Jam</label>
                        <input type="number" id="harga_per_jam" name="harga_per_jam" value="{{ old('harga_per_jam', $lapangan->harga_per_jam) }}" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500"
                            placeholder="Contoh: 150000" min="0" required>
                        @error('harga_per_jam')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="kapasitas" class="block text-gray-700 font-bold mb-2">Kapasitas (orang)</label>
                        <input type="number" id="kapasitas" name="kapasitas" value="{{ old('kapasitas', $lapangan->kapasitas) }}" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500"
                            placeholder="Contoh: 10">
                        @error('kapasitas')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="ukuran" class="block text-gray-700 font-bold mb-2">Ukuran Lapangan</label>
                        <input type="text" id="ukuran" name="ukuran" value="{{ old('ukuran', $lapangan->ukuran) }}" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500"
                            placeholder="Contoh: 20m x 40m">
                        @error('ukuran')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="status" class="block text-gray-700 font-bold mb-2">Status</label>
                        <select id="status" name="status" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" required>
                            <option value="">Pilih Status</option>
                            <option value="tersedia" {{ old('status', $lapangan->status) == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                            <option value="tidak tersedia" {{ old('status', $lapangan->status) == 'tidak tersedia' ? 'selected' : '' }}>Tidak Tersedia</option>
                        </select>
                        @error('status')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="deskripsi" class="block text-gray-700 font-bold mb-2">Deskripsi</label>
                        <textarea id="deskripsi" name="deskripsi" rows="3"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500"
                            placeholder="Deskripsi lapangan...">{{ old('deskripsi', $lapangan->deskripsi) }}</textarea>
                        @error('deskripsi')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Input Gambar -->
                    <div class="mb-6">
                        <label for="gambar" class="block text-gray-700 font-bold mb-2">Gambar Lapangan</label>
                        
                        <!-- Tampilkan gambar saat ini -->
                        @if($lapangan->gambar)
                            <div class="mb-3">
                                <p class="text-gray-600 mb-2">Gambar Saat Ini:</p>
                                <img src="{{ $lapangan->gambar_url }}" 
                                     alt="{{ $lapangan->nama }}" 
                                     class="w-64 h-48 object-cover rounded-lg border border-gray-300">
                            </div>
                        @endif

                        <input type="file" id="gambar" name="gambar" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500"
                            accept="image/*">
                        @error('gambar')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-gray-500 text-sm mt-2">Kosongkan jika tidak ingin mengubah gambar</p>
                    </div>

                    <!-- Preview Gambar Baru -->
                    <div class="mb-6 hidden" id="image-preview">
                        <label class="block text-gray-700 font-bold mb-2">Preview Gambar Baru</label>
                        <img id="preview" class="w-64 h-48 object-cover rounded-lg border border-gray-300">
                    </div>

                    <div class="flex space-x-4">
                        <a href="{{ route('lapangan.index') }}" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 px-4 rounded-lg text-center transition">
                            Batal
                        </a>
                        <button type="submit" class="flex-1 bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-4 rounded-lg transition">
                            <i class="fas fa-save mr-2"></i>Update Lapangan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script>
        // Preview image baru sebelum upload
        document.getElementById('gambar').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('preview').src = e.target.result;
                    document.getElementById('image-preview').classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>
</html>