import './bootstrap';

// Theme toggling: persist in localStorage and apply on load
function getStoredTheme() {
  try {
    return localStorage.getItem('theme');
  } catch (_) {
    return null;
  }
}

function getPreferredTheme() {
  const stored = getStoredTheme();
  if (stored === 'dark' || stored === 'light') return stored;
  return window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches
    ? 'dark'
    : 'light';
}

function updateIcon(theme) {
  const icons = document.querySelectorAll('#theme-toggle-icon, [data-theme-icon]');
  icons.forEach((icon) => {
    icon.classList.remove('fa-sun', 'fa-moon');
    icon.classList.add(theme === 'dark' ? 'fa-moon' : 'fa-sun');
  });
}

function applyTheme(theme) {
  const root = document.documentElement;
  if (theme === 'dark') root.classList.add('dark');
  else root.classList.remove('dark');
  updateIcon(theme);
}

document.addEventListener('DOMContentLoaded', () => {
  // Initialize theme early
  applyTheme(getPreferredTheme());

  // Bind toggle (dukung banyak tombol)
  const toggles = document.querySelectorAll('#theme-toggle, .theme-toggle');
  console.log('Found toggles:', toggles.length); // Debug
  toggles.forEach((toggle) => {
    toggle.addEventListener('click', () => {
      console.log('Toggle clicked!'); // Debug
      const next = document.documentElement.classList.contains('dark') ? 'light' : 'dark';
      try { localStorage.setItem('theme', next); } catch (_) {}
      applyTheme(next);
    });
  });

  // React to OS preference changes if user hasn't set a choice
  const media = window.matchMedia('(prefers-color-scheme: dark)');
  media.addEventListener?.('change', () => {
    if (!getStoredTheme()) applyTheme(getPreferredTheme());
  });
});