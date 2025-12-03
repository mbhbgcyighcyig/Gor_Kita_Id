/* global helper for sidebar active state and small interactivity */
document.addEventListener('DOMContentLoaded', () => {
  // Activate nav links by current url (simple)
  const navLinks = document.querySelectorAll('.sidebar nav a');
  navLinks.forEach(a => {
    a.addEventListener('click', (e) => {
      navLinks.forEach(n => n.classList.remove('active'));
      a.classList.add('active');
    });
  });

  // Simple logout button action (mock)
  document.querySelectorAll('.logout').forEach(btn => {
    btn.addEventListener('click', () => {
      alert('Anda berhasil logout (mock).');
      // In production, call logout endpoint or redirect to login page
      window.location.href = 'login.html';
    });
  });

  // Add-button generic (open simple prompt to simulate modal)
  document.querySelectorAll('.add-btn').forEach(b => {
    b.addEventListener('click', () => {
      const title = document.querySelector('header h1')?.innerText || 'Add';
      alert(`${title}: fitur tambah (mock).`);
    });
  });
});
