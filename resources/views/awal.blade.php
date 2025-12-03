<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="css/kelompok.css">
  <link href="https://fonts.googleapis.com/css2?f
  amily=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <script src="jschart.js"></script>
</head>
<body>
  <div class="flex items-center space-x-4">
                    <span class="text-gray-700">{{ Auth::user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                            Logout
                        </button>
                    </form>

  <!-- Sidebar -->
  <aside class="sidebar">
    <div class="logo">
      <span>🏟️</span> GorKita.id
    </div>
    <nav>
      <a href="dashboard" class="active">🏠 Dashboard</a>
      <a href="lapangan">⚽ Lapangan</a>
      <a href="booking">📅 Booking</a>
      <a href="user">👥 Pengguna</a>
      <a href="pembayaran">💳 Pembayaran</a>
      <a href="laporan">📊 Laporan</a>
      <a href="pengaturan">⚙️ Pengaturan</a>
    </nav>
    <button class="logout">🚪 Logout</button>
  </aside>

  <!-- Main -->
  <main class="main-content">
    <header class="topbar">
      <h1>Dashboard Admin</h1>
      <div class="admin-info">
      <img src="{{ asset('img/hhhh.jpg') }}" alt="Foto Lapangan Futsal A">
        
      </div>
    </header>

    <section class="cards">
      <div class="card">
        <div class="icon">🏟️</div>
        <div>
          <h2>12</h2>
          <p>Lapangan Terdaftar</p>
        </div>
      </div>

      <div class="card">
        <div class="icon">📅</div>
        <div>
          <h2>48</h2>
          <p>Booking Hari Ini</p>
        </div>
      </div>

      <div class="card">
        <div class="icon">💰</div>
        <div>
          <h2>Rp 2.450.000</h2>
          <p>Pemasukan Minggu Ini</p>
        </div>
      </div>

      <div class="card">
        <div class="icon">👥</div>
        <div>
          <h2>350</h2>
          <p>Pengguna Aktif</p>
        </div>
      </div>
    </section>
  </main>

  <script>
    const ctx = document.getElementById('bookingChart');
    new Chart(ctx, {
      type: 'line',
      data: {
        labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
        datasets: [{
          label: 'Jumlah Booking',
          data: [12, 19, 8, 17, 23, 14, 10],
          borderWidth: 3,
          borderColor: '#ff3b3b',
          backgroundColor: 'rgba(255,59,59,0.1)',
          tension: 0.4,
          fill: true
        }]
      },
      options: {
        scales: {
          y: { beginAtZero: true }
        },
        plugins: {
          legend: { display: false }
        }
      }
    });
  </script>
</body>
</html>
