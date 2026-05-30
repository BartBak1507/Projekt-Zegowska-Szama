<?php require_once('src/config.php'); ?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style_manage.css">
    <title>Profil - Zegowska Szama</title>
</head>
<body>

    <header>
        <a href="main.php">
            <img class="img-fluid" src="files/BANER_LEPSZY.png" alt="baner">
        </a>
    </header>

    <main>
        <div class="container my-5">
            <div class="row mb-4">
                <div class="col-12 text-center text-md-start">
                    <h1 class="orders-title">Twoje Zamówienia</h1>
                </div>
            </div>

            <div class="row g-4">

                <div class="col-12">
                    <div class="order-card p-3 p-sm-4">
                        <div class="row align-items-center g-3 border-bottom pb-3 mb-3">
                            <div class="col-12 col-md-6 text-center text-md-start">
                                <span class="order-number d-block d-sm-inline">Zamówienie <b>#ZS-2026-8941</b></span>
                                <span class="order-date ms-sm-3 d-block d-sm-inline">30.05.2026, 14:20</span>
                            </div>
                            <div class="col-12 col-md-6 text-center text-md-end">
                                <span class="badge status-pending px-3 py-2 text-uppercase">W realizacji</span>
                            </div>
                        </div>

                        <div class="row g-3 align-items-center mb-1">
                            <div class="col-12 col-sm-6 col-lg-4">
                                <div class="d-flex align-items-center p-2 item-mini-card">
                                    <img src="produkty/pizza.png" alt="Zegowska Szama" class="img-fluid rounded me-3" style="width: 60px; height: 60px; object-fit: cover;">
                                    <div>
                                        <h6 class="mb-0 text-white fw-bold">Pizza Zegowska (Duża)</h6>
                                        <small class="textMuted">Ilość: 1 • Szczegóły: bez cebuli</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-lg-4">
                                <div class="d-flex align-items-center p-2 item-mini-card">
                                    <img src="produkty/cola.png" alt="Zegowska Szama" class="img-fluid rounded me-3" style="width: 60px; height: 60px; object-fit: cover;">
                                    <div>
                                        <h6 class="mb-0 text-white fw-bold">Coca-Cola 0.5L</h6>
                                        <small class="textMuted">Ilość: 2 • Szczegóły: zimna</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="order-card p-3 p-sm-4">
                        <div class="row align-items-center g-3 border-bottom pb-3 mb-3">
                            <div class="col-12 col-md-6 text-center text-md-start">
                                <span class="order-number d-block d-sm-inline">Zamówienie <b>#ZS-2026-7712</b></span>
                                <span class="order-date ms-sm-3 d-block d-sm-inline">28.05.2026, 19:05</span>
                            </div>
                            <div class="col-12 col-md-6 text-center text-md-end">
                                <span class="badge status-delivered px-3 py-2 text-uppercase">Dostarczone</span>
                            </div>
                        </div>

                        <div class="row g-3 align-items-center mb-1">
                            <div class="col-12 col-sm-6 col-lg-4">
                                <div class="d-flex align-items-center p-2 item-mini-card">
                                    <img src="produkty/burger.png" alt="Zegowska Szama" class="img-fluid rounded me-3" style="width: 60px; height: 60px; object-fit: cover;">
                                    <div>
                                        <h6 class="mb-0 text-white fw-bold">Giga Burger Szama</h6>
                                        <small class="textMuted">Ilość: 1 • Szczegóły: dobrze wysmażony</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="order-card p-3 p-sm-4">
                        <div class="row align-items-center g-3 border-bottom pb-3 mb-3">
                            <div class="col-12 col-md-6 text-center text-md-start">
                                <span class="order-number d-block d-sm-inline">Zamówienie <b>#ZS-2026-6110</b></span>
                                <span class="order-date ms-sm-3 d-block d-sm-inline">15.05.2026, 12:00</span>
                            </div>
                            <div class="col-12 col-md-6 text-center text-md-end">
                                <span class="badge status-cancelled px-3 py-2 text-uppercase">Anulowane</span>
                            </div>
                        </div>

                        <div class="row g-3 align-items-center mb-1">
                            <div class="col-12 col-sm-6 col-lg-4">
                                <div class="d-flex align-items-center p-2 item-mini-card">
                                    <img src="produkty/cappuccino.png" alt="Zegowska Szama" class="img-fluid rounded me-3" style="width: 60px; height: 60px; object-fit: cover;">
                                    <div>
                                        <h6 class="mb-0 text-white fw-bold">Kebab w Cienkim</h6>
                                        <small class="textMuted">Ilość: 1 • Szczegóły: sos ostry</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>
    
</body>
</html>