<?php
// Detektuj trenutnu stranicu za active link
$current_page = basename($_SERVER['PHP_SELF']);
?>
<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bolder fs-3" href="index.php">
            <i class="fas fa-bolt-auto me-2 text-primary"></i>AUTO-PRO
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link fw-semibold px-3 <?php echo ($current_page == 'index.php') ? 'active text-primary' : ''; ?>" href="index.php">
                        Početna
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-semibold px-3 <?php echo ($current_page == 'usluge.php') ? 'active text-primary' : ''; ?>" href="usluge.php">
                        Usluge
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-semibold px-3 <?php echo ($current_page == 'index.php' && isset($_GET['section']) && $_GET['section'] == 'o-nama') ? 'active text-primary' : ''; ?>" href="index.php#o-nama">
                        O nama
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-semibold px-3 <?php echo ($current_page == 'kontakt.php') ? 'active text-primary' : ''; ?>" href="kontakt.php">
                        Kontakt
                    </a>
                </li>
            </ul>

            <div class="d-flex align-items-center gap-3">
                <!-- Dark/Light Theme Switcher sa ikonicama -->
                <div class="theme-switch" id="themeSwitcher" title="Promijeni temu">
                    <span class="theme-icon-wrap">
                        <i class="fas fa-sun theme-icon-sun"></i>
                        <i class="fas fa-moon theme-icon-moon"></i>
                    </span>
                </div>

                <?php if(isset($_SESSION['user_id'])): ?>
                    <div class="dropdown">
                        <button class="btn btn-primary dropdown-toggle btn-premium" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-2"></i>Profil
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow">
                            <li>
                                <a class="dropdown-item" href="<?php echo ($_SESSION['role'] == 'admin') ? 'admin_dashboard.php' : 'user_dashboard.php'; ?>">
                                    <i class="fas fa-columns me-2 text-primary"></i>Dashboard
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item text-danger" href="logout.php">
                                    <i class="fas fa-sign-out-alt me-2"></i>Odjava
                                </a>
                            </li>
                        </ul>
                    </div>
                <?php else: ?>
                    <a href="login.php" class="nav-link fw-bold <?php echo ($current_page == 'login.php') ? 'text-primary' : ''; ?>">Prijava</a>
                    <a href="register.php" class="btn btn-primary btn-premium shadow-sm">Registracija</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>
