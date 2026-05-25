<?php 
include 'config.php'; 

// Ako je već ulogovan, šalji ga tamo gdje pripada
if(isset($_SESSION['user_id'])){
    header("Location: " . ($_SESSION['role'] == 'admin' ? 'admin_dashboard.php' : 'user_dashboard.php'));
    exit;
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Pronađi korisnika po korisničkom imenu
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    // Provjera lozinke pomoću password_verify (jer smo u register koristili password_hash)
    if ($user && password_verify($password, $user['password'])) {
        // Postavljanje sesijskih varijabli
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        // Preusmjeravanje na osnovu uloge
        if ($user['role'] == 'admin') {
            header("Location: admin_dashboard.php");
        } else {
            header("Location: user_dashboard.php");
        }
        exit;
    } else {
        $error = "Pogrešno korisničko ime ili lozinka!";
    }
}
?>

<!DOCTYPE html>
<html lang="bs">
<head>
    <meta charset="UTF-8">
    <title>Prijava | AUTO-PRO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/all.min.css">
    <?php include 'theme_init.php'; ?>
    <style>
        body { background: var(--bs-body-bg); min-height: 100vh; display: flex; align-items: center; }
        .auth-card {
            background: rgba(var(--bs-body-color-rgb), 0.03);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(var(--bs-body-color-rgb), 0.1);
            border-radius: 24px;
            padding: 40px;
            width: 100%;
            max-width: 420px;
            margin: auto;
        }
        .form-control { border-radius: 12px; padding: 12px; background: rgba(var(--bs-body-color-rgb), 0.05); }
        .btn-premium { border-radius: 12px; padding: 14px; font-weight: 800; letter-spacing: 1px; transition: 0.3s; }
        .btn-premium:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(13, 110, 253, 0.3); }
    </style>
</head>
<body>

<div class="container">
    <div class="auth-card shadow-lg">
        <div class="text-center mb-4">
            <a href="index.php" class="text-decoration-none text-primary">
                <h2 class="fw-bold"><i class="fas fa-bolt-auto me-2"></i>AUTO-PRO</h2>
            </a>
            <p class="text-muted">Prijavite se na svoj profil</p>
        </div>

        <?php if($error): ?>
            <div class="alert alert-danger border-0 shadow-sm animate__animated animate__shakeX"><?php echo $error; ?></div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="mb-3">
                <label class="form-label small fw-bold">Korisničko ime</label>
                <div class="input-group">
                    <span class="input-group-text bg-transparent border-end-0"><i class="fas fa-user text-primary"></i></span>
                    <input type="text" name="username" class="form-control border-start-0" placeholder="Unesite username" required>
                </div>
            </div>
            
            <div class="mb-4">
                <label class="form-label small fw-bold">Lozinka</label>
                <div class="input-group">
                    <span class="input-group-text bg-transparent border-end-0"><i class="fas fa-lock text-primary"></i></span>
                    <input type="password" name="password" class="form-control border-start-0" placeholder="••••••••" required>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary w-100 btn-premium shadow mb-3">PRIJAVI SE</button>
            
            <div class="d-flex justify-content-between align-items-center">
                <a href="index.php" class="text-decoration-none small text-muted"><i class="fas fa-arrow-left me-1"></i> Nazad</a>
                <a href="register.php" class="text-primary fw-bold text-decoration-none small">Nemaš nalog?</a>
            </div>
        </form>
    </div>
</div>

<script>
    // Sinkronizacija teme
    const currentTheme = localStorage.getItem('theme') || 'dark';
    document.documentElement.setAttribute('data-bs-theme', currentTheme);
</script>
</body>
</html>