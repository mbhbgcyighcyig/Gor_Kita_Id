<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Pengguna | SportSpace</title>
  <link rel="stylesheet" href="{{ asset('css/kelompok.css') }}">
</head>

<body>

  <aside class="sidebar">
    <div class="logo"><span>🏟️</span> GorKita.id</div>

    <nav>
    <a href="dashboard" class="active">🏠 Dashboard</a>
      <a href="lapangan">⚽ Lapangan</a>
      <a href="book">📅 Booking</a>
      <a href="user">👥 Pengguna</a>
      <a href="pembayaran">💳 Pembayaran</a>
      <a href="laporan">📊 Laporan</a>
      <a href="pengaturan">⚙️ Pengaturan</a>
    </nav>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="logout-btn">
            Logout
        </button>
    </form>
  </aside>


  <main class="main-content">
    <header class="topbar">
      <h1>Kelola Pengguna</h1>
    </header>

    <table class="min-w-full table-auto">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Email / HP</th>
                <th>Jumlah Booking</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
            @foreach($users as $user)
            <tr>
                <td>#U00{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }} / {{ $user->phone ?? '-' }}</td>
                <td>{{ $user->bookings->count() }}</td>

                <td>
                    <span class="status aktif">Aktif</span>
                </td>

                <td>
                    <form action="{{ route('users.destroy', $user->id) }}" method="post">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger">
                            Hapus
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <footer class="footer">
        © GorKita.ID | All Rights Reserved.
    </footer>
  </main>

</body>
</html>
