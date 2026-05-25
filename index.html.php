<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="bs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AUTO-PRO | Premium Sistem</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/all.min.css">
    <?php include 'navbar_styles.php'; ?>
    <?php include 'theme_init.php'; ?>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-accent: #0d6efd;
            --transition-speed: 0.4s;
        }

        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            transition: background-color var(--transition-speed), color var(--transition-speed);
        }

        /* Navbar Glassmorphism */
        .navbar {
            backdrop-filter: blur(15px);
            background: rgba(var(--bs-body-bg-rgb), 0.8);
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        /* Hero Section */
        .hero {
            min-height: 90vh;
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), 
                        url('https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?auto=format&fit=crop&w=1920&q=80');
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            clip-path: ellipse(150% 100% at 50% 0%);
        }
        /* Kartice */
        .card-feature {
            border: none;
            border-radius: 24px;
            padding: 2rem;
            background: rgba(var(--bs-body-color-rgb), 0.03);
            border: 1px solid rgba(var(--bs-body-color-rgb), 0.1);
            transition: var(--transition-speed);
        }

        .card-feature:hover {
            transform: translateY(-10px);
            background: rgba(var(--bs-body-color-rgb), 0.05);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }

        .icon-circle {
            width: 60px;
            height: 60px;
            border-radius: 18px;
            background: var(--primary-accent);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .btn-premium {
            padding: 12px 32px;
            border-radius: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
    </style>
</head>
<body>

    <?php include 'navbar.php'; ?>

    <header class="hero text-white">
        <div class="container text-center text-lg-start">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <span class="badge bg-primary px-3 py-2 mb-3 rounded-pill text-uppercase fw-bold">24/7 Dostupni</span>
                    <h1 class="display-1 fw-extrabold mb-4" style="letter-spacing: -2px;">Sigurnost koja nema <span class="text-primary">kompromis.</span></h1>
                    <p class="lead fs-4 opacity-75 mb-5">Digitalna platforma za najbrži tehnički pregled i najbolje polise osiguranja u regiji.</p>
                    <div class="d-flex flex-wrap gap-3 justify-content-center justify-content-lg-start">
                        <a href="zakazi.php" class="btn btn-primary btn-lg btn-premium">Zakaži odmah <i class="fas fa-arrow-right ms-2"></i></a>
                        <a href="usluge.php" class="btn btn-outline-light btn-lg btn-premium">Pregled cijena</a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <section id="o-nama" class="py-5 mt-5">
        <div class="container">
            <div class="text-center mb-5">
                <h6 class="text-primary fw-bold">ZAŠTO MI?</h6>
                <h2 class="display-5 fw-bold">Standard kvaliteta</h2>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card-feature h-100">
                        <div class="icon-circle"><i class="fas fa-id-card"></i></div>
                        <h4>Online Registracija</h4>
                        <p class="opacity-75">Sve podatke unesite od kuće, mi pripremamo papire unaprijed.</p>
                        <a href="register.php" class="btn btn-link p-0 text-decoration-none fw-bold">Saznaj više <i class="fas fa-chevron-right ms-1 small"></i></a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card-feature h-100">
                        <div class="icon-circle"><i class="fas fa-coins"></i></div>
                        <h4>Prati Troškove</h4>
                        <p class="opacity-75">Naš admin panel vam omogućava uvid u svaku marku potrošenu na vozilo.</p>
                        <a href="usluge.php" class="btn btn-link p-0 text-decoration-none fw-bold">Cjenovnik <i class="fas fa-chevron-right ms-1 small"></i></a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card-feature h-100">
                        <div class="icon-circle"><i class="fas fa-shield-halved"></i></div>
                        <h4>Garancija Sigurnosti</h4>
                        <p class="opacity-75">Surađujemo samo sa provjerenim partnerima i licenciranim inženjerima.</p>
                        <a href="kontakt.php" class="btn btn-link p-0 text-decoration-none fw-bold">Naše lokacije <i class="fas fa-chevron-right ms-1 small"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="py-5 bg-dark text-white mt-5">
        <div class="container text-center">
            <p class="mb-0 opacity-50">&copy; 2026 AUTO-PRO Digital Systems. Kolegijalni rad by Bilal.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <?php include 'theme_script.php'; ?>
</body>
</html>