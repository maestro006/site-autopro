<?php
// Odredi koja stranica je aktivna
$current_page = basename($_SERVER['PHP_SELF']);
?>
<div class="sidebar">
    <div class="p-4 text-center">
        <h4 class="fw-bold text-primary"><i class="fas fa-bolt-auto me-2"></i>ADMIN</h4>
        <hr>
    </div>
    <nav class="nav flex-column">
        <a class="nav-link px-4 py-3 text-decoration-none <?php echo ($current_page == 'admin_dashboard.php') ? 'text-primary fw-bold bg-primary bg-opacity-10 rounded-3 mx-2' : 'text-white'; ?>" href="admin_dashboard.php">
            <i class="fas fa-home me-2"></i> Dashboard
        </a>
        <a class="nav-link px-4 py-3 text-decoration-none <?php echo ($current_page == 'admin_appointments.php') ? 'text-primary fw-bold bg-primary bg-opacity-10 rounded-3 mx-2' : 'text-white'; ?>" href="admin_appointments.php">
            <i class="fas fa-calendar-alt me-2"></i> Termini
        </a>
        <a class="nav-link px-4 py-3 text-decoration-none <?php echo ($current_page == 'admin_users.php') ? 'text-primary fw-bold bg-primary bg-opacity-10 rounded-3 mx-2' : 'text-white'; ?>" href="admin_users.php">
            <i class="fas fa-users me-2"></i> Korisnici
        </a>
        <a class="nav-link px-4 py-3 text-decoration-none <?php echo ($current_page == 'admin_transactions.php') ? 'text-primary fw-bold bg-primary bg-opacity-10 rounded-3 mx-2' : 'text-white'; ?>" href="admin_transactions.php">
            <i class="fas fa-wallet me-2"></i> Finansije
        </a>
        <a class="nav-link px-4 py-3 text-decoration-none <?php echo ($current_page == 'admin_logs.php') ? 'text-primary fw-bold bg-primary bg-opacity-10 rounded-3 mx-2' : 'text-white'; ?>" href="admin_logs.php">
            <i class="fas fa-history me-2"></i> Aktivnost
        </a>
        <div class="mt-5">
            <a class="nav-link px-4 py-3 text-danger text-decoration-none" href="logout.php">
                <i class="fas fa-sign-out-alt me-2"></i> Odjava
            </a>
        </div>
    </nav>
</div>
