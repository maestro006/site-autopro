<?php 
include 'config.php'; 

// Sigurnosna provjera: Samo ulogovani korisnik koji NIJE admin (mada može i admin vidjeti svoj user panel)
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// 1. Podaci o vozilima korisnika
$stmt_vehicles = $conn->prepare("SELECT * FROM vehicles WHERE user_id = ?");
$stmt_vehicles->execute([$user_id]);
$my_vehicles = $stmt_vehicles->fetchAll();

// 2. Aktivni termini (zakazani pregledi)
$stmt_apps = $conn->prepare("
    SELECT a.*, v.brand, v.model 
    FROM appointments a 
    JOIN vehicles v ON a.vehicle_id = v.id 
    WHERE a.user_id = ? AND a.status != 'completed'
    ORDER BY a.appointment_date ASC
");
$stmt_apps->execute([$user_id]);
$my_appointments = $stmt_apps->fetchAll();

// 3. Ukupni troškovi korisnika (koliko je ostavio para u firmi)
$stmt_spent = $conn->prepare("SELECT SUM(total_price) FROM transactions WHERE vehicle_id IN (SELECT id FROM vehicles WHERE user_id = ?)");
$stmt_spent->execute([$user_id]);
$total_spent = $stmt_spent->fetchColumn() ?: 0;
?>

<!DOCTYPE html>
<html lang="bs" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <title>Moj Panel | AUTO-PRO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/all.min.css">
    <style>
        body { background-color: var(--bs-body-bg); font-family: 'Inter', sans-serif; }
        .user-card {
            background: rgba(var(--bs-body-color-rgb), 0.03);
            border-radius: 20px;
            border: 1px solid rgba(var(--bs-body-color-rgb), 0.1);
            padding: 25px;
            margin-bottom: 20px;
        }
        .vehicle-badge {
            background: var(--bs-primary);
            color: white;
            padding: 5px 15px;
            border-radius: 50px;
            font-size: 0.8rem;
        }
        .status-pending { color: #ffc107; }
        .status-approved { color: #0dcaf0; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg bg-dark mb-4 border-bottom border-secondary">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="index.php"><i class="fas fa-bolt-auto me-2"></i>AUTO-PRO</a>
            <div class="d-flex align-items-center gap-3">
                <span class="text-muted d-none d-md-block">Korisnik: <strong><?php echo $_SESSION['username']; ?></strong></span>
                <a href="logout.php" class="btn btn-outline-danger btn-sm">Odjava</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="fw-bold mb-0">Moja Vozila</h2>
                    <a href="dodaj_vozilo.php" class="btn btn-primary btn-sm rounded-pill px-3">+ Dodaj Vozilo</a>
                </div>

                <?php if(empty($my_vehicles)): ?>
                    <div class="user-card text-center py-5">
                        <i class="fas fa-car-crash fa-3x mb-3 opacity-25"></i>
                        <p class="text-muted">Nemate registrovanih vozila u našem sistemu.</p>
                    </div>
                <?php else: ?>
                    <div class="row g-3">
                        <?php foreach($my_vehicles as $v): ?>
                            <div class="col-md-6">
                                <div class="user-card">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h5 class="fw-bold mb-1"><?php echo $v['brand'] . " " . $v['model']; ?></h5>
                                            <p class="text-muted small mb-3">Tablice: <?php echo $v['plate_number']; ?></p>
                                        </div>
                                        <span class="vehicle-badge"><?php echo $v['year_produced']; ?>. god</span>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <a href="zakazi.php?vehicle_id=<?php echo $v['id']; ?>" class="btn btn-outline-primary btn-sm w-100">Zakaži Pregled</a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <h2 class="fw-bold mt-5 mb-4">Aktivni Termini</h2>
                <div class="user-card">
                    <?php if(empty($my_appointments)): ?>
                        <p class="text-muted mb-0">Trenutno nemate zakazanih termina.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>Vozilo</th>
                                        <th>Datum i Vrijeme</th>
                                        <th>Status</th>
                                        <th>Akcija</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($my_appointments as $app): ?>
                                        <tr>
                                            <td><strong><?php echo $app['brand'] . " " . $app['model']; ?></strong></td>
                                            <td><?php echo date('d.m.Y H:i', strtotime($app['appointment_date'])); ?></td>
                                            <td>
                                                <span class="fw-bold status-<?php echo $app['status']; ?>">
                                                    <?php echo strtoupper($app['status']); ?>
                                                </span>
                                            </td>
                                            <td><button class="btn btn-sm btn-link text-danger">Otkaži</button></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="user-card bg-primary text-white">
                    <p class="mb-1 opacity-75">Ukupno potrošeno</p>
                    <h1 class="fw-bold mb-0"><?php echo number_format($total_spent, 2); ?> KM</h1>
                    <hr class="opacity-25">
                    <small>Sve vaše uplate za tehnički i osiguranje na jednom mjestu.</small>
                </div>

                <div class="user-card">
                    <h5 class="fw-bold mb-3">Brze informacije</h5>
                    <ul class="list-unstyled small mb-0">
                        <li class="mb-2"><i class="fas fa-info-circle text-primary me-2"></i> Tehnički pregled važi 12 mjeseci.</li>
                        <li class="mb-2"><i class="fas fa-exclamation-triangle text-warning me-2"></i> Provjerite datum isteka osiguranja.</li>
                        <li><i class="fas fa-phone text-success me-2"></i> Hitna pomoć: 1234-567</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.documentElement.setAttribute('data-bs-theme', localStorage.getItem('theme') || 'dark');
    </script>
</body>
</html>