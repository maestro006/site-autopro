<?php 
include 'config.php'; 

// 1. Sigurnost: Samo admin može pristupiti
if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin'){
    header("Location: login.php");
    exit;
}

// 2. Logika za promjenu statusa i AUTOMATSKU NAPLATU
if(isset($_GET['action']) && isset($_GET['id'])) {
    $id = $_GET['id'];
    $action = $_GET['action'];
    
    if($action == 'approve') {
        $stmt = $conn->prepare("UPDATE appointments SET status = 'approved' WHERE id = ?");
        $stmt->execute([$id]);
    } 
    
    if($action == 'complete') {
        // POVLAČENJE CIJENE: Gledamo koje je vozilo i koliko košta njegova kategorija
        $stmt_info = $conn->prepare("
            SELECT a.vehicle_id, c.price 
            FROM appointments a 
            JOIN vehicles v ON a.vehicle_id = v.id 
            JOIN vehicle_categories c ON v.category_id = c.id 
            WHERE a.id = ?
        ");
        $stmt_info->execute([$id]);
        $data = $stmt_info->fetch();

        if($data) {
            // A) Označi termin kao završen
            $conn->prepare("UPDATE appointments SET status = 'completed' WHERE id = ?")->execute([$id]);

            // B) KREIRAJ TRANSAKCIJU: Ovdje nula postaje stvarni iznos u bazi
            $stmt_pay = $conn->prepare("INSERT INTO transactions (vehicle_id, total_price, payment_status, transaction_date) VALUES (?, ?, 'paid', NOW())");
            $stmt_pay->execute([$data['vehicle_id'], $data['price']]);
        }
    }
    
    header("Location: admin_appointments.php");
    exit;
}

// 3. Izvlačenje svih termina za prikaz u tabeli
$stmt = $conn->query("
    SELECT a.*, u.username, v.brand, v.model, v.plate_number 
    FROM appointments a
    JOIN users u ON a.user_id = u.id
    JOIN vehicles v ON a.vehicle_id = v.id
    ORDER BY a.appointment_date DESC
");
$appointments = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="bs" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <title>Upravljanje Terminima | ADMIN</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/all.min.css">
    <style>
        :root { --sidebar-width: 260px; }
        body { background-color: var(--bs-body-bg); font-family: 'Inter', sans-serif; }
        .main-content { margin-left: var(--sidebar-width); padding: 40px; }
        
        .sidebar {
            width: var(--sidebar-width); height: 100vh; position: fixed;
            background: rgba(var(--bs-body-color-rgb), 0.05);
            backdrop-filter: blur(10px); border-right: 1px solid rgba(var(--bs-body-color-rgb), 0.1);
        }

        .appointment-card {
            background: rgba(var(--bs-body-color-rgb), 0.03);
            border: 1px solid rgba(var(--bs-body-color-rgb), 0.1);
            border-radius: 20px; padding: 25px; margin-bottom: 15px;
            transition: 0.3s ease;
        }

        .appointment-card:hover { border-color: var(--bs-primary); background: rgba(var(--bs-primary-rgb), 0.02); }
        
        .status-badge { padding: 6px 12px; border-radius: 50px; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; }
        .status-pending { background: #ffc10722; color: #ffc107; border: 1px solid #ffc10744; }
        .status-approved { background: #0dcaf022; color: #0dcaf0; border: 1px solid #0dcaf044; }
        .status-completed { background: #19875422; color: #198754; border: 1px solid #19875444; }
    </style>
</head>
<body>

    <?php include 'admin_sidebar.php'; ?>

    <div class="main-content">
        <div class="mb-5">
            <h2 class="fw-bold mb-0">Upravljanje Terminima</h2>
            <p class="text-muted">Odobravanje zahtjeva i finalizacija pregleda.</p>
        </div>

        <div class="row">
            <div class="col-12">
                <?php if(empty($appointments)): ?>
                    <div class="alert alert-info border-0 rounded-4">Trenutno nema zakazanih termina.</div>
                <?php endif; ?>

                <?php foreach($appointments as $app): ?>
                    <div class="appointment-card d-flex align-items-center justify-content-between flex-wrap shadow-sm">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary bg-opacity-10 p-3 rounded-4 text-primary me-4">
                                <i class="fas fa-car-side fa-2x"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-1"><?php echo htmlspecialchars($app['brand'] . " " . $app['model']); ?></h5>
                                <p class="mb-1 small">
                                    <span class="text-muted">Vlasnik:</span> <strong><?php echo htmlspecialchars($app['username']); ?></strong> | 
                                    <span class="text-muted">Tablice:</span> <code class="text-primary"><?php echo htmlspecialchars($app['plate_number']); ?></code>
                                </p>
                                <p class="mb-0 small text-primary">
                                    <i class="fas fa-calendar-day me-1"></i> <?php echo date('d.m.Y \u H:i', strtotime($app['appointment_date'])); ?>
                                </p>
                                <?php if(!empty($app['notes'])): ?>
                                    <p class="mt-2 mb-0 small text-warning italic"><i class="fas fa-comment-dots me-1"></i> <?php echo htmlspecialchars($app['notes']); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="text-center px-3">
                            <span class="status-badge status-<?php echo $app['status']; ?>">
                                <?php echo $app['status']; ?>
                            </span>
                        </div>

                        <div class="d-flex gap-2">
                            <?php if($app['status'] == 'pending'): ?>
                                <a href="?action=approve&id=<?php echo $app['id']; ?>" class="btn btn-outline-info rounded-pill px-4 fw-bold">Odobri</a>
                            <?php endif; ?>
                            
                            <?php if($app['status'] == 'approved'): ?>
                                <a href="?action=complete&id=<?php echo $app['id']; ?>" class="btn btn-success rounded-pill px-4 fw-bold shadow-sm">Završi i Naplati</a>
                            <?php endif; ?>
                            
                            <?php if($app['status'] == 'completed'): ?>
                                <button class="btn btn-outline-secondary rounded-pill px-4" disabled><i class="fas fa-check me-1"></i> Arhivirano</button>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <script>
        document.documentElement.setAttribute('data-bs-theme', localStorage.getItem('theme') || 'dark');
    </script>
</body>
</html>