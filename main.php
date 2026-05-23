<?php require_once('src/config.php'); ?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="style_main.css">
    <link rel="stylesheet" href="style_dock.css">
    <title>Zegowska Szama</title>
</head>
<body>
    
    <header>
        <img  class="img-fluid" src="files/BANER_LEPSZY.png" alt="baner">
    </header>
    <main>
        <div class="loginOrRegister"><a class="loginOrRegisterBtn" href="login.php">Zaloguj się</a> lub <a class="loginOrRegisterBtn" href="register.php">Zarejestruj się</a></div>
        <div class="searchFor"><img src="files/search_btn.png" alt="lupa"><input type="text" placeholder="Na co masz dziś ochotę?"> </div>
        <div class="scrollable">
            <section>
                <p class="sectionName">Co Nie Co Na Ciepło <img src="files/hipekBurger.png" alt="hipek z burgerem"></p>
                <div class="horizontalScroll">

                    <?php while($row = mysqli_fetch_assoc($mainFetchResult)): ?>

                        <?php if($row['kategoria'] == 1): ?>
                        
                            <div class="scrollItem">
                                <img src="produkty/<?php echo $row['zdjęcie']; ?>" alt="artykuł">
                                <p class="topP text-break"><?php echo $row['nazwa']; ?></p>
                                <p class="bottomP"><?php echo $row['cena']. "zł"; ?></p>
                                <button>+</button>
                            </div>

                        <?php endif; ?>

                    <?php endwhile; ?>

                </div>
            </section>
            <section>
                <p class="sectionName">Ogrzej się <img src="files/hipekHerbata.png" alt="hipek z herbatą"></p>
                <div class="horizontalScroll">

                    <?php mysqli_data_seek($mainFetchResult, 0); while($row = mysqli_fetch_assoc($mainFetchResult)): ?>

                        <?php if($row['kategoria'] == 2): ?>
                        
                            <div class="scrollItem">
                                <img src="produkty/<?php echo $row['zdjęcie']; ?>" alt="artykuł">
                                <p class="topP text-break"><?php echo $row['nazwa']; ?></p>
                                <p class="bottomP"><?php echo $row['cena']. "zł"; ?></p>
                                <button>+</button>
                            </div>

                        <?php endif; ?>

                    <?php endwhile; ?>

                </div>
            </section>

            <img class="img-fluid resizeImg mx-auto" src="files/gołoszynka.png" alt="reklama">

            <section>
                <p class="sectionName">Napoje <img src="files/hipekPicie.png" alt="hipek z piciem"></p>
                <div class="horizontalScroll">
                    
                    <?php mysqli_data_seek($mainFetchResult, 0); while($row = mysqli_fetch_assoc($mainFetchResult)): ?>

                        <?php if($row['kategoria'] == 3): ?>
                        
                            <div class="scrollItem">
                                <img src="produkty/<?php echo $row['zdjęcie']; ?>" alt="artykuł">
                                <p class="topP text-break"><?php echo $row['nazwa']; ?></p>
                                <p class="bottomP"><?php echo $row['cena']. "zł"; ?></p>
                                <button>+</button>
                            </div>

                        <?php endif; ?>

                    <?php endwhile; ?>
                    
                </div>
            </section>
            <section>
                <p class="sectionName">Przekąski Słodkie <img src="files/hipekŻelek.png" alt="hipek z żelkiem"></p>
                <div class="horizontalScroll">
                    
                    <?php mysqli_data_seek($mainFetchResult, 0); while($row = mysqli_fetch_assoc($mainFetchResult)): ?>

                        <?php if($row['kategoria'] == 4): ?>
                        
                            <div class="scrollItem">
                                <img src="produkty/<?php echo $row['zdjęcie']; ?>" alt="artykuł">
                                <p class="topP text-break"><?php echo $row['nazwa']; ?></p>
                                <p class="bottomP"><?php echo $row['cena']. "zł"; ?></p>
                                <button>+</button>
                            </div>

                        <?php endif; ?>

                    <?php endwhile; ?>

                </div>
            </section>
            <section>
                <p class="sectionName">Przekąski Słone <img src="files/hipekCzips.png" alt="hipek z czipsem"></p>
                <div class="horizontalScroll">
                
                    <?php mysqli_data_seek($mainFetchResult, 0); while($row = mysqli_fetch_assoc($mainFetchResult)): ?>

                        <?php if($row['kategoria'] == 5): ?>
                        
                            <div class="scrollItem">
                                <img src="produkty/<?php echo $row['zdjęcie']; ?>" alt="artykuł">
                                <p class="topP text-break"><?php echo $row['nazwa']; ?></p>
                                <p class="bottomP"><?php echo $row['cena']. "zł"; ?></p>
                                <button>+</button>
                            </div>

                        <?php endif; ?>

                    <?php endwhile; ?>

                </div>
            </section>
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
                <img class="paymentImg" src="files/blik.png" alt="visa">
                <img class="paymentImg" src="files/appleP.png" alt="visa">
                <img class="paymentImg" src="files/googleP.png" alt="visa">
                <img class="paymentImg" src="files/przelewy.png" alt="visa">
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