<?php 
include 'config.php'; 

// Sigurnost: Samo admin može pristupiti finansijama
if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin'){
    header("Location: login.php");
    exit;
}

// 1. Izvlačenje svih transakcija sa podacima o korisniku i vozilu
$stmt = $conn->query("
    SELECT t.*, u.username, v.brand, v.model, v.plate_number 
    FROM transactions t 
    JOIN vehicles v ON t.vehicle_id = v.id 
    JOIN users u ON v.user_id = u.id 
    ORDER BY t.transaction_date DESC
");
$transactions = $stmt->fetchAll();

// 2. Izračunavanje ukupne zarade
$total_revenue = 0;
foreach($transactions as $tr) {
    if($tr['payment_status'] == 'paid') {
        $total_revenue += $tr['total_price'];
    }
}
?>

<!DOCTYPE html>
<html lang="bs" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <title>Finansije i Transakcije | ADMIN</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/all.min.css">
    <style>
        :root { --sidebar-width: 260px; }
        body { background-color: var(--bs-body-bg); }
        .main-content { margin-left: var(--sidebar-width); padding: 40px; }
        .sidebar {
            width: var(--sidebar-width); height: 100vh; position: fixed;
            background: rgba(var(--bs-body-color-rgb), 0.05);
            backdrop-filter: blur(10px); border-right: 1px solid rgba(var(--bs-body-color-rgb), 0.1);
        }
        .revenue-card {
            background: linear-gradient(45deg, #0d6efd, #00d4ff);
            color: white; border-radius: 20px; padding: 30px; margin-bottom: 30px;
        }
        .table-card {
            background: rgba(var(--bs-body-color-rgb), 0.03);
            border-radius: 20px; border: 1px solid rgba(var(--bs-body-color-rgb), 0.1);
            overflow: hidden;
        }
    </style>
</head>
<body>

    <?php include 'admin_sidebar.php'; ?>

    <div class="main-content">
        <div class="row">
            <div class="col-md-8">
                <h2 class="fw-bold mb-0">Finansijski Izvještaj</h2>
                <p class="text-muted">Pregled svih uplata i generisanih računa.</p>
            </div>
            <div class="col-md-4 text-end">
                <button class="btn btn-outline-primary" onclick="window.print()"><i class="fas fa-print me-2"></i> Štampaj Izvještaj</button>
            </div>
        </div>

        <div class="revenue-card shadow">
            <div class="row align-items-center">
                <div class="col-8">
                    <h6 class="text-uppercase opacity-75 small fw-bold">Ukupni promet sistema</h6>
                    <h1 class="display-4 fw-bold mb-0"><?php echo number_format($total_revenue, 2); ?> KM</h1>
                </div>
                <div class="col-4 text-end">
                    <i class="fas fa-money-bill-trend-up fa-4x opacity-25"></i>
                </div>
            </div>
        </div>

        <div class="table-card shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-primary bg-opacity-10">
                        <tr>
                            <th class="ps-4">ID</th>
                            <th>Klijent</th>
                            <th>Vozilo</th>
                            <th>Iznos</th>
                            <th>Datum</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Akcija</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($transactions as $tr): ?>
                            <tr>
                                <td class="ps-4 text-muted">#<?php echo $tr['id']; ?></td>
                                <td><span class="fw-bold"><?php echo $tr['username']; ?></span></td>
                                <td>
                                    <small><?php echo $tr['brand'] . " " . $tr['model']; ?></small><br>
                                    <code class="text-primary small"><?php echo $tr['plate_number']; ?></code>
                                </td>
                                <td><span class="fw-bold text-success"><?php echo number_format($tr['total_price'], 2); ?> KM</span></td>
                                <td><?php echo date('d.m.Y H:i', strtotime($tr['transaction_date'])); ?></td>
                                <td>
                                    <span class="badge rounded-pill bg-success bg-opacity-10 text-success border border-success">
                                        PLAĆENO
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <button class="btn btn-sm btn-light"><i class="fas fa-file-pdf text-danger"></i> Račun</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        document.documentElement.setAttribute('data-bs-theme', localStorage.getItem('theme') || 'dark');
    </script>
</body>
</html>