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
<body>
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
                <input type="text" placeholder="KOD RABATOWY">
            </div>
            <div class="totalSummary">
                <div class="priceBox">
                    <p>Razem: <span class="priceAmount">4.50</span> <span class="currency">zł</span></p>
                </div>
                <button class="payBtn">ZAPŁAĆ</button>
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
                <a href="profil.html">Moje Punkty</a>
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