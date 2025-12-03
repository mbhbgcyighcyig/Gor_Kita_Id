// Chart initializer used by index.html and laporan.html
document.addEventListener('DOMContentLoaded', () => {
  const ctx1 = document.getElementById('bookingChart');
  if (ctx1) {
    new Chart(ctx1, {
      type: 'line',
      data: {
        labels: ['Sen','Sel','Rab','Kam','Jum','Sab','Min'],
        datasets: [{
          label: 'Booking',
          data: [12,19,8,17,23,14,10],
          borderColor: '#ff3b3b',
          backgroundColor: 'rgba(255,59,59,0.12)',
          tension: 0.35,
          pointRadius: 4,
          fill: true
        }]
      },
      options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero:true } } }
    });
  }

  // Small bar chart example for laporan page
  const ctx2 = document.getElementById('revenueChart');
  if (ctx2) {
    new Chart(ctx2, {
      type: 'bar',
      data: {
        labels: ['Jan','Feb','Mar','Apr','May','Jun'],
        datasets: [{ label: 'Pendapatan', data: [1200,1500,1100,1700,1900,2200], backgroundColor: '#ff3b3b' }]
      },
      options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero:true } } }
    });
  }
});
