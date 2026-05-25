<?php 
include 'config.php'; 

// Izvlačimo kategorije vozila i cijene iz baze
$stmt = $conn->query("SELECT * FROM vehicle_categories ORDER BY price ASC");
$categories = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="bs">
<head>
    <meta charset="UTF-8">
    <title>Usluge i Cjenovnik | AUTO-PRO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/all.min.css">
    <?php include 'navbar_styles.php'; ?>
    <?php include 'theme_init.php'; ?>
    <style>
        body { font-family: 'Inter', sans-serif; }
        
        .page-header {
            background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), 
                        url('https://images.unsplash.com/photo-1486006920555-c77dcf18193c?auto=format&fit=crop&w=1920&q=80');
            background-size: cover;
            background-position: center;
            padding: 100px 0;
            margin-bottom: 50px;
            clip-path: polygon(0 0, 100% 0, 100% 85%, 0% 100%);
        }

        .price-card {
            background: rgba(var(--bs-body-color-rgb), 0.03);
            border: 1px solid rgba(var(--bs-body-color-rgb), 0.1);
            border-radius: 25px;
            padding: 40px;
            transition: 0.4s;
            position: relative;
            overflow: hidden;
        }

        .price-card:hover {
            transform: translateY(-15px);
            border-color: var(--bs-primary);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }

        .price-tag {
            font-size: 3rem;
            font-weight: 800;
            color: var(--bs-primary);
        }

        .check-icon {
            color: #198754;
            margin-right: 10px;
        }

    </style>
</head>
<body>

    <?php include 'navbar.php'; ?>

    <header class="page-header text-center text-white">
        <div class="container">
            <h1 class="display-3 fw-bold">Naše Usluge</h1>
            <p class="lead opacity-75">Transparentne cijene bez skrivenih troškova.</p>
        </div>
    </header>

    <div class="container mb-5">
        <div class="row g-4 justify-content-center">
            <?php foreach($categories as $cat): ?>
                <div class="col-lg-4 col-md-6">
                    <div class="price-card text-center">
                        <div class="mb-3">
                            <?php 
                                // Mala logika za ikone na osnovu naziva
                                $icon = "fa-car";
                                if(stripos($cat['category_name'], 'teretno') !== false) $icon = "fa-truck";
                                if(stripos($cat['category_name'], 'moto') !== false) $icon = "fa-motorcycle";
                            ?>
                            <i class="fas <?php echo $icon; ?> fa-3x text-primary opacity-50"></i>
                        </div>
                        <h3 class="fw-bold"><?php echo $cat['category_name']; ?></h3>
                        <div class="price-tag my-3"><?php echo number_format($cat['price'], 0); ?><span class="fs-6 text-muted"> KM</span></div>
                        
                        <ul class="list-unstyled text-start my-4">
                            <li class="mb-2"><i class="fas fa-check-circle check-icon"></i> Tehnički pregled</li>
                            <li class="mb-2"><i class="fas fa-check-circle check-icon"></i> Eko test uključen</li>
                            <li class="mb-2"><i class="fas fa-check-circle check-icon"></i> Administrativne takse</li>
                            <li class="opacity-50"><i class="fas fa-check-circle check-icon"></i> Osiguranje (opcionalno)</li>
                        </ul>

                        <a href="register.php" class="btn btn-primary w-100 py-3 fw-bold rounded-pill">ZAKAŽI PREGLED</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="row mt-5 g-4 text-center">
            <div class="col-md-4">
                <div class="p-4 border border-secondary rounded-4">
                    <i class="fas fa-clock fa-2x mb-3 text-primary"></i>
                    <h5>Brzi proces</h5>
                    <p class="small text-muted">Završavamo sve u roku od 30 do 45 minuta.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-4 border border-secondary rounded-4">
                    <i class="fas fa-file-invoice-dollar fa-2x mb-3 text-primary"></i>
                    <h5>Platite na rate</h5>
                    <p class="small text-muted">Mogućnost plaćanja osiguranja na do 12 rata.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-4 border border-secondary rounded-4">
                    <i class="fas fa-certificate fa-2x mb-3 text-primary"></i>
                    <h5>Licencirani servis</h5>
                    <p class="small text-muted">Svi naši inženjeri posjeduju državne licence.</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <?php include 'theme_script.php'; ?>
</body>
</html>