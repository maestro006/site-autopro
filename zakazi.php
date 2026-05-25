<?php 
include 'config.php'; 

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$error = "";
$success = "";

// 1. Izvuci vozila korisnika da ih može izabrati u dropdownu
$stmt_v = $conn->prepare("SELECT id, brand, model, plate_number FROM vehicles WHERE user_id = ?");
$stmt_v->execute([$user_id]);
$my_vehicles = $stmt_v->fetchAll();

// 2. Logika za spremanje termina
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $vehicle_id = $_POST['vehicle_id'];
    $date = $_POST['appointment_date'];
    $notes = trim($_POST['notes']);
    
    // Provjera da li je datum u budućnosti
    if(strtotime($date) < time()){
        $error = "Ne možete zakazati termin u prošlosti!";
    } else {
        $stmt = $conn->prepare("INSERT INTO appointments (user_id, vehicle_id, appointment_date, status, notes) VALUES (?, ?, ?, 'pending', ?)");
        if($stmt->execute([$user_id, $vehicle_id, $date, $notes])) {
            $success = "Termin uspješno zakazan! Naš tim će vas kontaktirati za potvrdu.";
            header("refresh:3;url=user_dashboard.php");
        } else {
            $error = "Greška pri zakazivanju. Pokušajte ponovo.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="bs" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <title>Zakaži Termin | AUTO-PRO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/all.min.css">
    <style>
        body { background-color: var(--bs-body-bg); min-height: 100vh; display: flex; align-items: center; }
        .booking-card {
            background: rgba(var(--bs-body-color-rgb), 0.03);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(var(--bs-body-color-rgb), 0.1);
            border-radius: 30px;
            padding: 45px;
            max-width: 700px;
            margin: 20px auto;
        }
        .form-control, .form-select {
            border-radius: 15px;
            padding: 12px 20px;
            background: rgba(var(--bs-body-color-rgb), 0.05);
            border: 1px solid rgba(var(--bs-body-color-rgb), 0.1);
        }
        .icon-header {
            width: 80px;
            height: 80px;
            background: rgba(13, 110, 253, 0.1);
            color: #0d6efd;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin: 0 auto 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="booking-card shadow-lg">
        <div class="text-center mb-5">
            <div class="icon-header"><i class="fas fa-calendar-check"></i></div>
            <h2 class="fw-bold">Rezerviši svoj termin</h2>
            <p class="text-muted">Izaberi vozilo i željeni datum pregleda</p>
        </div>

        <?php if($error): ?>
            <div class="alert alert-danger border-0 rounded-4"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if($success): ?>
            <div class="alert alert-success border-0 rounded-4"><?php echo $success; ?></div>
        <?php endif; ?>

        <?php if(empty($my_vehicles)): ?>
            <div class="text-center py-4">
                <p class="mb-4">Prvo morate dodati vozilo da biste zakazali termin.</p>
                <a href="dodaj_vozilo.php" class="btn btn-primary px-5 py-3 fw-bold rounded-pill">DODAJ VOZILO</a>
            </div>
        <?php else: ?>
            <form action="" method="POST">
                <div class="mb-4">
                    <label class="form-label small fw-bold text-uppercase opacity-75">Izaberi vozilo</label>
                    <select name="vehicle_id" class="form-select form-select-lg" required>
                        <?php foreach($my_vehicles as $v): ?>
                            <option value="<?php echo $v['id']; ?>">
                                <?php echo $v['brand'] . " " . $v['model'] . " (" . $v['plate_number'] . ")"; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-bold text-uppercase opacity-75">Datum i vrijeme</label>
                    <input type="datetime-local" name="appointment_date" class="form-control form-control-lg" required>
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-bold text-uppercase opacity-75">Napomena (opcionalno)</label>
                    <textarea name="notes" class="form-control" rows="3" placeholder="Npr. treba mi i polisa osiguranja ili provjera kočnica..."></textarea>
                </div>

                <div class="row g-3">
                    <div class="col-md-8">
                        <button type="submit" class="btn btn-primary w-100 py-3 fw-bold rounded-pill shadow">POTVRDI REZERVACIJU</button>
                    </div>
                    <div class="col-md-4">
                        <a href="user_dashboard.php" class="btn btn-outline-secondary w-100 py-3 fw-bold rounded-pill">ODUSTANI</a>
                    </div>
                </div>
            </form>
        <?php endif; ?>
    </div>
</div>

<script>
    document.documentElement.setAttribute('data-bs-theme', localStorage.getItem('theme') || 'dark');
</script>
</body>
</html>