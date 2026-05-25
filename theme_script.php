<script>
    // ===== THEME SWITCHER - interaktivnost =====
    var themeSwitcher = document.getElementById('themeSwitcher');
    if (themeSwitcher) {
        themeSwitcher.addEventListener('click', function() {
            var current = document.documentElement.getAttribute('data-bs-theme');
            var next = (current === 'dark') ? 'light' : 'dark';
            document.documentElement.setAttribute('data-bs-theme', next);
            localStorage.setItem('theme', next);
        });
    }
</script>
