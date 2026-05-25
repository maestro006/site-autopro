<!-- Shared Navbar CSS + Theme Switcher styles -->
<style>
    /* ===== NAVBAR - ZAJEDNIČKI STIL ===== */
    .navbar {
        backdrop-filter: blur(15px);
        -webkit-backdrop-filter: blur(15px);
        background: rgba(var(--bs-body-bg-rgb), 0.85) !important;
        border-bottom: 1px solid rgba(var(--bs-body-color-rgb), 0.08);
        transition: background 0.3s ease;
    }

    .navbar-brand {
        font-family: 'Plus Jakarta Sans', sans-serif;
        letter-spacing: -0.5px;
    }

    .nav-link {
        transition: color 0.2s ease;
        position: relative;
    }

    .nav-link::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 0;
        height: 2px;
        background: var(--bs-primary);
        transition: width 0.25s ease;
        border-radius: 2px;
    }

    .nav-link:hover::after,
    .nav-link.active::after {
        width: 60%;
    }

    /* ===== THEME SWITCHER ===== */
    .theme-switch {
        cursor: pointer;
        width: 46px;
        height: 46px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(var(--bs-body-color-rgb), 0.1);
        border: 1.5px solid rgba(var(--bs-body-color-rgb), 0.15);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .theme-switch:hover {
        background: var(--bs-primary);
        border-color: var(--bs-primary);
        transform: rotate(20deg) scale(1.1);
    }

    .theme-switch:hover .theme-icon-sun,
    .theme-switch:hover .theme-icon-moon {
        color: white;
    }

    .theme-icon-wrap {
        position: relative;
        width: 18px;
        height: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .theme-icon-sun,
    .theme-icon-moon {
        position: absolute;
        font-size: 1rem;
        transition: all 0.4s ease;
    }

    /* Dark mode: show sun (da mozes preci na light) */
    [data-bs-theme="dark"] .theme-icon-sun  { opacity: 1; transform: scale(1) rotate(0deg); color: #ffc107; }
    [data-bs-theme="dark"] .theme-icon-moon { opacity: 0; transform: scale(0) rotate(90deg); }

    /* Light mode: show moon */
    [data-bs-theme="light"] .theme-icon-sun  { opacity: 0; transform: scale(0) rotate(-90deg); }
    [data-bs-theme="light"] .theme-icon-moon { opacity: 1; transform: scale(1) rotate(0deg); color: #6c757d; }

    /* ===== BTN PREMIUM (zajednički) ===== */
    .btn-premium {
        padding: 10px 26px;
        border-radius: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-size: 0.85rem;
    }
</style>
