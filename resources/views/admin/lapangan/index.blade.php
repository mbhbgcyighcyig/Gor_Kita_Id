<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Management Lapangan - GorKita.ID</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <!-- Admin Layout -->
    @include('admin.partials.sidebar')
    
    <main class="ml-64 p-8">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold text-gray-800">Management Lapangan</h1>
                <a href="{{ route('admin.lapangan.create') }}" class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-lg transition">
                    <i class="fas fa-plus mr-2"></i>Tambah Lapangan
                </a>
            </div>

            <!-- Lapangan List -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                        {{ session('success') }}
                    </div>
                @endif

                @if($fields->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead class="bg-gray-200">
                                <tr>
                                    <th class="px-4 py-3 text-left">Nama</th>
                                    <th class="px-4 py-3 text-left">Tipe</th>
                                    <th class="px-4 py-3 text-left">Harga/Jam</th>
                                    <th class="px-4 py-3 text-left">Deskripsi</th>
                                    <th class="px-4 py-3 text-left">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($fields as $field)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-4 py-3 font-semibold">{{ $field->name }}</td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-1 rounded text-xs font-bold
                                            {{ $field->type === 'futsal' ? 'bg-blue-200 text-blue-800' : '' }}
                                            {{ $field->type === 'badminton' ? 'bg-green-200 text-green-800' : '' }}
                                            {{ $field->type === 'minisoccer' ? 'bg-red-200 text-red-800' : '' }}">
                                            {{ ucfirst($field->type) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">Rp {{ number_format($field->price_per_hour, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3">{{ $field->description ?? '-' }}</td>
                                    <td class="px-4 py-3">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('admin.lapangan.edit', $field->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm">
                                                <i class="fas fa-edit mr-1"></i>Edit
                                            </a>
                                            <form action="{{ route('admin.lapangan.destroy', $field->id) }}" method="POST" onsubmit="return confirm('Yakin hapus lapangan?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                                                    <i class="fas fa-trash mr-1"></i>Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-12">
                        <i class="fas fa-futbol text-6xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500 text-lg">Belum ada lapangan terdaftar</p>
                        <a href="{{ route('admin.lapangan.create') }}" class="inline-block mt-4 bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                            Tambah Lapangan Pertama
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </main>
</body>
</html>