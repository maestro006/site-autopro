<?php 
include 'config.php'; 

$success_db = false;
$error_db = "";

// Logika za spremanje poruke u bazu
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $message = trim($_POST['message']);

    if(!empty($name) && !empty($email) && !empty($message)) {
        try {
            $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)");
            if($stmt->execute([$name, $email, $message])) {
                $success_db = true;
            }
        } catch (PDOException $e) {
            $error_db = "Greška pri slanju: " . $e->getMessage();
        }
    } else {
        $error_db = "Sva polja su obavezna!";
    }
}
?>

<!DOCTYPE html>
<html lang="bs">
<head>
    <meta charset="UTF-8">
    <title>Kontakt | AUTO-PRO Tuzla</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/all.min.css">
    <?php include 'navbar_styles.php'; ?>
    <?php include 'theme_init.php'; ?>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap');
        
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: var(--bs-body-bg); }
        
        .contact-info-card {
            background: rgba(var(--bs-body-color-rgb), 0.03);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(var(--bs-body-color-rgb), 0.1);
            border-radius: 24px;
            padding: 30px;
            height: 100%;
            transition: all 0.3s ease;
        }

        .contact-info-card:hover {
            transform: translateY(-5px);
            border-color: var(--bs-primary);
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .icon-box {
            width: 55px;
            height: 55px;
            background: rgba(13, 110, 253, 0.1);
            color: var(--bs-primary);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            margin-bottom: 20px;
        }

        .map-container {
            border-radius: 24px;
            overflow: hidden;
            border: 1px solid rgba(var(--bs-body-color-rgb), 0.1);
            height: 500px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
        }

        .form-control {
            border-radius: 14px;
            padding: 14px;
            background: rgba(var(--bs-body-color-rgb), 0.05);
            border: 1px solid rgba(var(--bs-body-color-rgb), 0.1);
            transition: 0.3s;
        }

        .form-control:focus {
            background: rgba(var(--bs-body-color-rgb), 0.08);
            border-color: var(--bs-primary);
            box-shadow: none;
        }

        .btn-send {
            border-radius: 14px;
            padding: 16px;
            font-weight: 700;
            letter-spacing: 0.5px;
            transition: 0.3s;
        }

        .btn-send:hover {
            transform: scale(1.02);
            box-shadow: 0 10px 20px rgba(13, 110, 253, 0.3);
        }
    </style>
</head>
<body>

    <?php include 'navbar.php'; ?>

    <div class="container py-5">
        <div class="text-center mb-5">
            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill mb-3 fw-bold">KONTAKTIRAJTE NAS</span>
            <h1 class="display-4 fw-bold">Pronađite nas u Tuzli</h1>
            <p class="text-muted lead">Vaša sigurnost na putu počinje ovdje.</p>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="contact-info-card text-center">
                    <div class="icon-box mx-auto"><i class="fas fa-map-marked-alt"></i></div>
                    <h5 class="fw-bold">Adresa Poslovnice</h5>
                    <p class="text-muted mb-0">Mitar Trifunović Učo bb,<br>75000 Tuzla, BiH</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="contact-info-card text-center">
                    <div class="icon-box mx-auto"><i class="fas fa-headset"></i></div>
                    <h5 class="fw-bold">Tehnička Podrška</h5>
                    <p class="text-muted mb-0">Tel: +387 35 222 333<br>Viber: +387 62 111 222</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="contact-info-card text-center">
                    <div class="icon-box mx-auto"><i class="fas fa-clock"></i></div>
                    <h5 class="fw-bold">Radno Vrijeme</h5>
                    <p class="text-muted mb-0">Pon - Pet: 08:00 - 17:00<br>Subota: 08:00 - 14:00</p>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-5">
                <div class="contact-info-card shadow-sm">
                    <h3 class="fw-bold mb-4">Pošaljite poruku</h3>
                    
                    <?php if($success_db): ?>
                        <div class="alert alert-success border-0 rounded-4 py-3 mb-4 animate__animated animate__fadeIn">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-check-circle fa-2x me-3"></i>
                                <div>
                                    <h6 class="mb-0 fw-bold">Uspješno poslano!</h6>
                                    <small>Vaša poruka je u bazi, admin će je uskoro vidjeti.</small>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if($error_db): ?>
                        <div class="alert alert-danger border-0 rounded-4 py-3 mb-4">
                            <?php echo $error_db; ?>
                        </div>
                    <?php endif; ?>

                    <form action="" method="POST" <?php if($success_db) echo 'style="display:none;"'; ?>>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Ime i prezime</label>
                            <input type="text" name="name" class="form-control" placeholder="npr. Bilal Bilalić" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Email adresa</label>
                            <input type="email" name="email" class="form-control" placeholder="ime@primjer.com" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label small fw-bold">Vaša poruka</label>
                            <textarea name="message" class="form-control" rows="5" placeholder="Pišite nam..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 btn-send shadow">
                            POŠALJI PORUKU <i class="fas fa-paper-plane ms-2"></i>
                        </button>
                    </form>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="map-container">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2864.123!2d18.673!3d44.538!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47596b6d5167b579%3A0xc3f8e5f187a5f6e8!2sTuzla%2C%20Bosnia%20and%20Herzegovina!5e0!3m2!1sen!2sba!4v1650000000000!5m2!1sen!2sba" 
                        width="100%" 
                        height="100%" 
                        style="border:0;" 
                        allowfullscreen="" 
                        loading="lazy">
                    </iframe>
                </div>
            </div>
        </div>
    </div>

    <footer class="py-5 bg-dark border-top border-secondary mt-5">
        <div class="container text-center">
            <p class="text-muted mb-0 small">© 2026 AUTO-PRO TUZLA. Matururski rad - Informacioni sistem za tehnički pregled.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <?php include 'theme_script.php'; ?>
</body>
</html>