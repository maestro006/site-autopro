<?php 
include 'config.php'; 

if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin'){
    header("Location: login.php");
    exit;
}

// Izvlačimo zadnje aktivnosti (npr. zadnje poruke, nove termine i nove korisnike)
$logs = $conn->query("
    (SELECT 'Novi Termin' as tip, appointment_date as datum, 'Zakazan pregled' as opis FROM appointments)
    UNION
    (SELECT 'Nova Poruka' as tip, created_at as datum, name as opis FROM contact_messages)
    ORDER BY datum DESC LIMIT 15
")->fetchAll();
?>

<!DOCTYPE html>
<html lang="bs" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <title>Aktivnosti | ADMIN</title>
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
        .log-card {
            background: rgba(var(--bs-body-color-rgb), 0.03);
            border-left: 4px solid var(--bs-primary);
            border-radius: 10px;
            padding: 15px 20px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

    <?php include 'admin_sidebar.php'; ?>

    <div class="main-content">
        <h2 class="fw-bold mb-4">Dnevnik Aktivnosti</h2>
        
        <?php foreach($logs as $log): ?>
            <div class="log-card d-flex justify-content-between align-items-center">
                <div>
                    <span class="badge bg-primary mb-1"><?php echo $log['tip']; ?></span>
                    <p class="mb-0 fw-bold"><?php echo $log['opis']; ?></p>
                </div>
                <div class="text-end">
                    <small class="text-muted"><i class="fas fa-clock me-1"></i> <?php echo date('d.m.Y H:i', strtotime($log['datum'])); ?></small>
                </div>
            </div>
        <?php endforeach; ?>
        
        <?php if(empty($logs)): ?>
            <p class="text-muted">Trenutno nema zabilježenih aktivnosti.</p>
        <?php endif; ?>
    </div>

    <script>
        document.documentElement.setAttribute('data-bs-theme', localStorage.getItem('theme') || 'dark');
    </script>
</body>
</html>