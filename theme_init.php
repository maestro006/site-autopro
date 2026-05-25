<script>
    // ===== THEME SWITCHER LOGIKA (zajednički) =====
    (function() {
        // Primijeni temu odmah (prije rendera) da nema bljeskanja
        var saved = localStorage.getItem('theme') || 'dark';
        document.documentElement.setAttribute('data-bs-theme', saved);
    })();
</script>
