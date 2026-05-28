<?php
    // Wczytujemy config na samej górze, żeby sprawdzić sesję ZANIM HTML się załaduje
    require_once('src/config.php');
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    // Pobieramy Szamsy zalogowanego użytkownika
    $userSzamsy = 0;
    if (isset($_SESSION['id'])) {
        $resultSzamsy = fetchSzamsy($mysqli);
        if ($rowSzamsy = mysqli_fetch_assoc($resultSzamsy)) {
            $userSzamsy = (int)$rowSzamsy['szamsy'];
        }
    }
    // Ustawiamy prostą zmienną dla wygody
    $isLoggedIn = isset($_SESSION['id']) ? 'true' : 'false';
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style_basket.css">
    <link rel="stylesheet" href="style_dock.css">
    <title>Koszyk - Zegowska Szama</title>
</head>
<body data-logged-in="<?php echo $isLoggedIn; ?>" data-user-szamsy="<?php echo $userSzamsy; ?>">
    <header>
        <img class="img-fluid" src="files/BANER_LEPSZY.png" alt="baner">
    </header>

    <main>
        <div class="basketHeader">
            <h1>Twój Koszyk</h1>
        </div>

        <div class="basketScroll">
            <div class="scrollItem">
                <img src="produkty/bułka_z_serem.png" alt="artykuł">
                <div class="scrollItemsMainDiv">
                    <div class="itemFlexContainer">
                        <div class="itemInfo">
                            <p class="itemName">Nazwa</p>
                            <p class="itemDesc">Opis Produktu jak i skład produktu</p>
                        </div>
                        <div class="adjustAmount">
                            <div class="topBtn">+</div>
                            <p>1</p>
                            <div class="bottomBtn">-</div>
                        </div>
                    </div>
                    <div class="detailsInputDiv">
                        <input type="text" placeholder="Wpisz szczegóły..." class="itemDetails">
                    </div>
                </div>
            </div>

                        <div class="scrollItem">
                <img src="produkty/bułka_z_serem.png" alt="artykuł">
                <div class="scrollItemsMainDiv">
                    <div class="itemFlexContainer">
                        <div class="itemInfo">
                            <p class="itemName">Nazwa</p>
                            <p class="itemDesc">Opis Produktu jak i skład produktu</p>
                        </div>
                        <div class="adjustAmount">
                            <div class="topBtn">+</div>
                            <p>1</p>
                            <div class="bottomBtn">-</div>
                        </div>
                    </div>
                    <div class="detailsInputDiv">
                        <input type="text" placeholder="Wpisz szczegóły..." class="itemDetails">
                    </div>
                </div>
            </div>
        </div>

        <div class="checkoutSection">
            <div class="promoCode">
                <input type="text" id="promoInput" placeholder="KOD RABATOWY">
            </div>
            
            <form id="orderForm" action="src/process_order.php" method="POST">
                <input type="hidden" name="cart_data" id="cartDataInput">
                <input type="hidden" name="payment_method" id="paymentMethodInput" value="money">
                
                <div class="totalSummary">
                    <div class="priceBox">
                        <p>Razem: <span id="totalPrice" class="priceAmount">0.00</span> <span class="currency">zł</span></p>
                        <p style="font-size: 0.9rem; color: #aaa; margin: 0; margin-top: -15px;">lub <span id="totalSzamsyPrice">0</span> Szamsów</p>
                    </div>
                    <div>
                        <button type="submit" id="payMoneyBtn" class="payBtn">ZAPŁAĆ</button>
                        <button type="submit" id="paySzamsyBtn" class="payBtn" style="background: #e67e22; margin-left: 10px;" disabled>SZAMSY</button>
                    </div>
                </div>
            </form>
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

    <?php require_once("dockingPanel.php"); ?>

</body>
</html>

<script src="src/app.js"></script>