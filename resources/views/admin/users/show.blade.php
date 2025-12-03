<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail User</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <div class="flex space-x-4">
                    <a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:text-gray-800 px-3 py-2">Dashboard</a>
                    <a href="{{ route('users.index') }}" class="text-gray-600 hover:text-gray-800 px-3 py-2">Users</a>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-gray-700">{{ Auth::user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-3xl mx-auto py-8 px-4">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h1 class="text-3xl font-bold text-gray-800 mb-6">Detail User</h1>
            
            <div class="space-y-4">
                <div class="border-b pb-3">
                    <label class="block text-gray-600 text-sm font-bold mb-1">ID</label>
                    <p class="text-gray-800 text-lg">{{ $user->id }}</p>
                </div>

                <div class="border-b pb-3">
                    <label class="block text-gray-600 text-sm font-bold mb-1">Nama</label>
                    <p class="text-gray-800 text-lg">{{ $user->name }}</p>
                </div>

                <div class="border-b pb-3">
                    <label class="block text-gray-600 text-sm font-bold mb-1">Email</label>
                    <p class="text-gray-800 text-lg">{{ $user->email }}</p>
                </div>

                <div class="border-b pb-3">
                    <label class="block text-gray-600 text-sm font-bold mb-1">Akun Dibuat</label>
                    <p class="text-gray-800 text-lg">{{ $user->created_at->format('d M Y, H:i') }}</p>
                </div>

                <div class="pb-3">
                    <label class="block text-gray-600 text-sm font-bold mb-1">Terakhir Diupdate</label>
                    <p class="text-gray-800 text-lg">{{ $user->updated_at->format('d M Y, H:i') }}</p>
                </div>
            </div>

            <div class="flex space-x-4 mt-6">
                <a href="{{ route('users.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Kembali
                </a>
                <a href="{{ route('users.edit', $user) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                    Edit
                </a>
                <form method="POST" action="{{ route('users.destroy', $user) }}" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="return confirm('Yakin hapus user ini?')">
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>