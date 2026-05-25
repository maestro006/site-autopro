<?php 
include 'config.php'; 

if(isset($_SESSION['user_id'])){
    header("Location: index.php");
    exit;
}

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = $_POST['role']; // Hvatanje uloge (admin ili user)

    if ($password !== $confirm_password) {
        $error = "Lozinke se ne podudaraju!";
    } else {
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        
        if ($stmt->rowCount() > 0) {
            $error = "Korisničko ime ili email su već zauzeti!";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            // Sada ubacujemo i $role koji je korisnik izabrao
            $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
            
            if ($stmt->execute([$username, $email, $hashed_password, $role])) {
                $success = "Uspješna registracija kao " . strtoupper($role) . "! Sada se možete prijaviti.";
            } else {
                $error = "Došlo je do greške. Pokušajte ponovo.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="bs">
<head>
    <meta charset="UTF-8">
    <title>Registracija | AUTO-PRO</title>
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
            max-width: 480px;
            margin: 20px auto;
        }
        .form-control, .form-select { border-radius: 12px; padding: 12px; background: rgba(var(--bs-body-color-rgb), 0.05); }
        .btn-premium { border-radius: 12px; padding: 14px; font-weight: 800; letter-spacing: 1px; }
    </style>
</head>
<body>

<div class="container">
    <div class="auth-card shadow-lg">
        <div class="text-center mb-4">
            <a href="index.php" class="text-decoration-none text-primary">
                <h2 class="fw-bold"><i class="fas fa-bolt-auto me-2"></i>AUTO-PRO</h2>
            </a>
            <p class="text-muted">Kreirajte novi korisnički račun</p>
        </div>

        <?php if($error): ?>
            <div class="alert alert-danger border-0 shadow-sm"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if($success): ?>
            <div class="alert alert-success border-0 shadow-sm"><?php echo $success; ?> <a href="login.php" class="alert-link">Prijavi se</a></div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label small fw-bold">Korisničko ime</label>
                    <input type="text" name="username" class="form-control" placeholder="bilal123" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label small fw-bold">Vrsta naloga</label>
                    <select name="role" class="form-select" required>
                        <option value="user">Korisnik (User)</option>
                        <option value="admin">Administrator</option>
                    </select>
                </div>
            </div>
            
            <div class="mb-3">
                <label class="form-label small fw-bold">Email adresa</label>
                <input type="email" name="email" class="form-control" placeholder="ime@domena.com" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label small fw-bold">Lozinka</label>
                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>
            
            <div class="mb-4">
                <label class="form-label small fw-bold">Potvrdi lozinku</label>
                <input type="password" name="confirm_password" class="form-control" placeholder="••••••••" required>
            </div>
            
            <button type="submit" class="btn btn-primary w-100 btn-premium shadow">KREIRAJ NALOG</button>
        </form>

        <div class="text-center mt-4">
            <p class="mb-0 text-muted">Već ste registrovani? <a href="login.php" class="text-primary fw-bold text-decoration-none">Prijavi se ovdje</a></p>
        </div>
    </div>
</div>

<script>
    const currentTheme = localStorage.getItem('theme') || 'dark';
    document.documentElement.setAttribute('data-bs-theme', currentTheme);
</script>
</body>
</html>