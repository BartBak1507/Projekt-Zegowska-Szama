<?php require_once('src/config.php'); ?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style_profile.css">
    <link rel="stylesheet" href="style_dock.css">
    <title>Profil - Zegowska Szama</title>
</head>
<body>

    <header>
        <img class="img-fluid" src="files/BANER_LEPSZY.png" alt="baner">
    </header>

    <main>
        <div class="profileCard">
            <div class="avatarSection">
                <img src="files/ikona.png" alt="Avatar" class="userAvatar">
                <?php if(isset($_SESSION['id'])): ?>
                    <p class="profileName"><?php echo $_SESSION['nazwa_użytkownika']; ?></p>
                <?php else: ?>
                    <p class="profileName">Nie zalogowano</p>
                <?php endif; ?>
            </div>

            <div class="qrSection">
                <img src="files/QR.png" alt="Kod QR" class="qrImage">
            </div>

            <div class="profileMenu">
                <a href="dane_konta.html" class="profileBtn">
                    <span>Ustawienia</span>
                    <span>&gt;</span>
                </a>
                <a href="historia_transakcji.html" class="profileBtn">
                    <span>Historia transakcji</span>
                    <span>&gt;</span>
                </a>
                <?php if(isset($_SESSION['id'])): ?>
                <a href="profile.php?logout=1" class="profileBtn">
                    <span>Wyloguj</span>
                    <span>&gt;</span>
                </a>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <footer>
        <div class="supportMain">
            <div class="help">
                <span>POMOC</span>
                <a href="regulamin.html">Regulamin</a>
                <a href="kontakt.html">Kontakt</a>
            </div>
            <div class="support">
                <span>WSPARCIE</span>
                <a href="o_szamsach.html">O Szamsach</a>
                <a href="profile.php">Moje Punkty</a>
            </div>
        </div>
        <div class="payment">
            <img class="paymentImg" src="files/visa.png" alt="visa">
            <img class="paymentImg" src="files/blik.png" alt="blik">
            <img class="paymentImg" src="files/appleP.png" alt="apple pay">
            <img class="paymentImg" src="files/googleP.png" alt="google pay">
            <img class="paymentImg" src="files/przelewy.png" alt="przelewy24">
        </div>
        <div class="rights">
            <hr>
            <p>&copy 2026 Zegowska Szama</p>
            <p class="dimP">Wszelkie prawa zastrzeżone</p>
        </div>
    </footer>

    <?php require_once('dockingPanel.php'); ?>
    
</body>
</html>
<script src="src/app.js"></script>