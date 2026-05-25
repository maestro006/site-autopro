<?php 
include 'config.php'; 

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}

$error = "";
$success = "";

// 1. Izvuci kategorije iz baze za dropdown (onih 50km, 120km itd.)
$categories = $conn->query("SELECT * FROM vehicle_categories")->fetchAll();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $category_id = $_POST['category_id'];
    $brand = trim($_POST['brand']);
    $model = trim($_POST['model']);
    $plate = trim($_POST['plate_number']);
    $year = $_POST['year_produced'];

    // Provjera da tablice već ne postoje
    $check = $conn->prepare("SELECT id FROM vehicles WHERE plate_number = ?");
    $check->execute([$plate]);

    if($check->rowCount() > 0) {
        $error = "Vozilo sa tim tablicama je već registrovano!";
    } else {
        $stmt = $conn->prepare("INSERT INTO vehicles (user_id, category_id, brand, model, plate_number, year_produced) VALUES (?, ?, ?, ?, ?, ?)");
        if($stmt->execute([$user_id, $category_id, $brand, $model, $plate, $year])) {
            $success = "Vozilo uspješno dodano! Prebacujemo vas na panel...";
            header("refresh:2;url=user_dashboard.php");
        } else {
            $error = "Greška pri spremanju podataka.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="bs" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <title>Dodaj Vozilo | AUTO-PRO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/all.min.css">
    <style>
        body { background-color: var(--bs-body-bg); min-height: 100vh; display: flex; align-items: center; }
        .form-card {
            background: rgba(var(--bs-body-color-rgb), 0.03);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(var(--bs-body-color-rgb), 0.1);
            border-radius: 24px;
            padding: 40px;
            width: 100%;
            max-width: 600px;
            margin: auto;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="form-card shadow-lg">
        <div class="text-center mb-4">
            <h2 class="fw-bold text-primary"><i class="fas fa-car me-2"></i>Novo Vozilo</h2>
            <p class="text-muted">Unesite podatke o vašem automobilu</p>
        </div>

        <?php if($error): ?>
            <div class="alert alert-danger border-0"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if($success): ?>
            <div class="alert alert-success border-0"><?php echo $success; ?></div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label small fw-bold">Marka (npr. Opel)</label>
                    <input type="text" name="brand" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label small fw-bold">Model (npr. Insignia)</label>
                    <input type="text" name="model" class="form-control" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label small fw-bold">Kategorija</label>
                    <select name="category_id" class="form-select" required>
                        <?php foreach($categories as $cat): ?>
                            <option value="<?php echo $cat['id']; ?>"><?php echo $cat['category_name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label small fw-bold">Godina proizvodnje</label>
                    <input type="number" name="year_produced" class="form-control" min="1950" max="2026" value="2014" required>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label small fw-bold">Registarske oznake</label>
                <input type="text" name="plate_number" class="form-control" placeholder="E12-K-345" required>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100 py-3 fw-bold">DODAJ VOZILO</button>
                <a href="user_dashboard.php" class="btn btn-outline-secondary w-50 py-3 fw-bold">NAZAD</a>
            </div>
        </form>
    </div>
</div>

<script>
    document.documentElement.setAttribute('data-bs-theme', localStorage.getItem('theme') || 'dark');
</script>
</body>
</html>