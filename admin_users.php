<?php 
include 'config.php'; 

// Sigurnost: Samo admin
if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin'){
    header("Location: login.php");
    exit;
}

// Logika za brisanje korisnika
if(isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    
    // Spriječi admina da obriše samog sebe
    if($delete_id == $_SESSION['user_id']) {
        $error = "Ne možete obrisati sopstveni nalog!";
    } else {
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$delete_id]);
        header("Location: admin_users.php");
        exit;
    }
}

// Izvlačenje svih korisnika i broja njihovih vozila
$stmt = $conn->query("
    SELECT u.*, COUNT(v.id) as vehicle_count 
    FROM users u 
    LEFT JOIN vehicles v ON u.id = v.user_id 
    GROUP BY u.id 
    ORDER BY u.role ASC, u.username ASC
");
$users = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="bs" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <title>Upravljanje Korisnicima | ADMIN</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/all.min.css">
    <style>
        :root { --sidebar-width: 260px; }
        body { background-color: var(--bs-body-bg); }
        .main-content { margin-left: var(--sidebar-width); padding: 40px; }
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            background: rgba(var(--bs-body-color-rgb), 0.05);
            backdrop-filter: blur(10px);
            border-right: 1px solid rgba(var(--bs-body-color-rgb), 0.1);
        }
        .user-table-card {
            background: rgba(var(--bs-body-color-rgb), 0.03);
            border: 1px solid rgba(var(--bs-body-color-rgb), 0.1);
            border-radius: 20px;
            overflow: hidden;
        }
        .table { margin-bottom: 0; }
        .avatar-circle {
            width: 40px;
            height: 40px;
            background: var(--bs-primary);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <?php include 'admin_sidebar.php'; ?>

    <div class="main-content">
        <div class="mb-5">
            <h2 class="fw-bold mb-0">Baza Korisnika</h2>
            <p class="text-muted">Pregled svih registrovanih klijenata i administratora.</p>
        </div>

        <?php if(isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <div class="user-table-card shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="bg-primary bg-opacity-10">
                        <tr>
                            <th class="ps-4">Korisnik</th>
                            <th>Email</th>
                            <th>Uloga</th>
                            <th>Vozila</th>
                            <th class="text-end pe-4">Akcija</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($users as $user): ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle me-3">
                                            <?php echo strtoupper(substr($user['username'], 0, 1)); ?>
                                        </div>
                                        <div>
                                            <div class="fw-bold"><?php echo $user['username']; ?></div>
                                            <small class="text-muted">ID: #<?php echo $user['id']; ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td><?php echo $user['email']; ?></td>
                                <td>
                                    <span class="badge rounded-pill <?php echo ($user['role'] == 'admin') ? 'bg-danger' : 'bg-primary'; ?>">
                                        <?php echo strtoupper($user['role']); ?>
                                    </span>
                                </td>
                                <td>
                                    <i class="fas fa-car me-1 text-muted"></i> <?php echo $user['vehicle_count']; ?>
                                </td>
                                <td class="text-end pe-4">
                                    <?php if($user['id'] != $_SESSION['user_id']): ?>
                                        <a href="?delete_id=<?php echo $user['id']; ?>" 
                                           class="btn btn-sm btn-outline-danger rounded-pill" 
                                           onclick="return confirm('Jeste li sigurni da želite obrisati ovog korisnika?')">
                                            <i class="fas fa-trash-alt"></i>
                                        </div>
                                    <?php endif; ?>
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