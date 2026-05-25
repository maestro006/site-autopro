<?php 
include 'config.php'; 

// Sigurnosna provjera: Samo admin može ovdje
if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin'){
    header("Location: login.php");
    exit;
}

// 1. Izvlačenje statistike za kartice
$total_users = $conn->query("SELECT COUNT(*) FROM users WHERE role = 'user'")->fetchColumn();
$total_vehicles = $conn->query("SELECT COUNT(*) FROM vehicles")->fetchColumn();
$total_earnings = $conn->query("SELECT SUM(total_price) FROM transactions WHERE payment_status = 'paid'")->fetchColumn() ?: 0;
$pending_apps = $conn->query("SELECT COUNT(*) FROM appointments WHERE status = 'pending'")->fetchColumn();

// 2. Izvlačenje zadnjih transakcija za tabelu
$stmt = $conn->prepare("
    SELECT t.*, v.brand, v.model, u.username 
    FROM transactions t 
    JOIN vehicles v ON t.vehicle_id = v.id 
    JOIN users u ON v.user_id = u.id 
    ORDER BY t.transaction_date DESC LIMIT 5
");
$stmt->execute();
$recent_transactions = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="bs" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel | AUTO-PRO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root { --sidebar-width: 260px; }
        body { background-color: var(--bs-body-bg); font-family: 'Inter', sans-serif; }
        
        /* Sidebar Stil */
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            background: rgba(var(--bs-body-color-rgb), 0.05);
            backdrop-filter: blur(10px);
            border-right: 1px solid rgba(var(--bs-body-color-rgb), 0.1);
            transition: 0.3s;
        }
        
        .main-content { margin-left: var(--sidebar-width); padding: 30px; }
        
        .nav-link {
            color: var(--bs-body-color);
            padding: 12px 20px;
            border-radius: 10px;
            margin: 5px 15px;
            transition: 0.3s;
        }
        
        .nav-link:hover, .nav-link.active {
            background: var(--bs-primary);
            color: white !important;
        }

        .stat-card {
            background: rgba(var(--bs-body-color-rgb), 0.03);
            border-radius: 20px;
            border: 1px solid rgba(var(--bs-body-color-rgb), 0.1);
            padding: 25px;
            transition: 0.3s;
        }
        
        .stat-card:hover { transform: translateY(-5px); }
        
        .icon-shape {
            width: 48px;
            height: 48px;
            background: rgba(13, 110, 253, 0.2);
            color: #0d6efd;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }
    </style>
</head>
<body>

    <?php include 'admin_sidebar.php'; ?>

    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-0">Dobrodošao, <?php echo $_SESSION['username']; ?>! 👋</h2>
                <p class="text-muted">Evo šta se dešava u sistemu danas.</p>
            </div>
            <div class="d-flex gap-2">
                 <button class="btn btn-outline-secondary btn-sm" onclick="location.reload()"><i class="fas fa-sync"></i></button>
                 <a href="index.php" class="btn btn-primary btn-sm">Vidi Sajt</a>
            </div>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="text-muted small fw-bold mb-1">ZARADA</p>
                            <h3 class="fw-bold mb-0"><?php echo number_format($total_earnings, 2); ?> KM</h3>
                        </div>
                        <div class="icon-shape"><i class="fas fa-money-bill-wave"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="text-muted small fw-bold mb-1">KORISNICI</p>
                            <h3 class="fw-bold mb-0"><?php echo $total_users; ?></h3>
                        </div>
                        <div class="icon-shape text-success"><i class="fas fa-user-check"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="text-muted small fw-bold mb-1">VOZILA</p>
                            <h3 class="fw-bold mb-0"><?php echo $total_vehicles; ?></h3>
                        </div>
                        <div class="icon-shape text-info"><i class="fas fa-car-side"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="text-muted small fw-bold mb-1">NA ČEKANJU</p>
                            <h3 class="fw-bold mb-0"><?php echo $pending_apps; ?></h3>
                        </div>
                        <div class="icon-shape text-warning"><i class="fas fa-clock"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="stat-card h-100">
                    <h5 class="fw-bold mb-4">Pregled Prometa (7 dana)</h5>
                    <canvas id="earningsChart" height="250"></canvas>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="stat-card h-100">
                    <h5 class="fw-bold mb-4">Zadnje uplate</h5>
                    <?php foreach($recent_transactions as $tr): ?>
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-shrink-0 me-3">
                                <div class="icon-shape small"><i class="fas fa-receipt"></i></div>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0 fw-bold"><?php echo $tr['username']; ?></h6>
                                <small class="text-muted"><?php echo $tr['brand'] . " " . $tr['model']; ?></small>
                            </div>
                            <div class="text-end">
                                <p class="mb-0 fw-bold text-success">+<?php echo $tr['total_price']; ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <a href="admin_transactions.php" class="btn btn-light w-100 btn-sm mt-3">Vidi sve</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Grafikon (Chart.js)
        const ctx = document.getElementById('earningsChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Pon', 'Uto', 'Sri', 'Čet', 'Pet', 'Sub', 'Ned'],
                datasets: [{
                    label: 'Zarada (KM)',
                    data: [120, 450, 300, 600, 800, 200, 100],
                    borderColor: '#0d6efd',
                    tension: 0.4,
                    fill: true,
                    backgroundColor: 'rgba(13, 110, 253, 0.1)'
                }]
            },
            options: {
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } }
            }
        });

        // Tema
        document.documentElement.setAttribute('data-bs-theme', localStorage.getItem('theme') || 'dark');
    </script>
</body>
</html>