<?php require_once('src/config.php'); ?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="styles/style_main.css">
    <link rel="stylesheet" href="styles/style_dock.css">
    <title>Zegowska Szama</title>
</head>
<body data-logged-in="<?php echo isset($_SESSION['id']) ? 'true' : 'false'; ?>" data-user-szamsy="<?php if(isset($_SESSION['id'])) { $res = fetchSzamsy($mysqli); $r = mysqli_fetch_assoc($res); echo $r['szamsy']; } else { echo 0; } ?>">
    
    <header>
        <a href="main.php">
            <img class="img-fluid" src="files/BANER_LEPSZY.png" alt="baner">
        </a>
    </header>
    <main>
        <?php if(isset($_SESSION['id'])): ?>

            <div class="loginOrRegister user-logged-in"><img class="ikon" src="files/ikona.png" alt="ikona"> <p>Cześć <br> <?php echo $_SESSION['nazwa_użytkownika']; ?></p> <div> <img class="szams" src="files/szams.png" alt="szams"> Masz: <?php $result = fetchSzamsy($mysqli); $row = mysqli_fetch_assoc($result);  echo $row['szamsy'];?> <br>Szamsów</div></div>

        <?php else: ?>

            <div class="loginOrRegister"><a class="loginOrRegisterBtn" href="login.php">Zaloguj się</a> lub <a class="loginOrRegisterBtn" href="register.php">Zarejestruj się</a></div>
            
        <?php endif; ?>

        <div class="searchFor"><img src="files/search_btn.png" alt="lupa"><input type="text" id="searchInput" placeholder="Na co masz dziś ochotę?"> </div>
        
        <div class="scrollable">
            
            <section id="searchResultsSection" style="display: none;">
                <p class="sectionName" style="color: #910000 !important; border-left: 5px #910000 solid; padding-left: 10px; border-radius: 10px;">
                    <b>Znalezione produkty:</b>
                </p>
                <div class="resultsContainer horizontalScroll">
                    </div>
            </section>

            <div id="originalSections">
                <?php if(isset($_SESSION['id'])): ?>
                <section>
                    <p class="sectionName" style="color: #910000 !important; border-left: 5px #910000 solid; padding-left: 10px; border-radius: 10px;"><b>Dla zalogowanych!!</b><img src="files/hipekCzips.png" alt="hipek z piciem"></p>
                    <div class="horizontalScroll" >
                        
                        <?php mysqli_data_seek($mainFetchResult, 0); while($row = mysqli_fetch_assoc($mainFetchResult)): ?>

                            <?php if($row['mnożnik_promocji'] < 1): ?>
                            
                                <div class="scrollItem">
                                    <input type="hidden" class="productId" value="<?php echo $row['id']; ?>" data-image="produkty/<?php echo $row['zdjęcie']; ?>">
                                    <img src="produkty/<?php echo $row['zdjęcie']; ?>" alt="artykuł">
                                    <p class="topP text-break"><?php echo $row['nazwa']; ?></p>
                                    <p class="bottomP">
                                        <span class="priceValue">
                                            <?php echo number_format(floor($row['cena'] * $row['mnożnik_promocji'] * 100) / 100, 2, '.', ''); ?>zł
                                        </span>
                                        <s style="color: #aaa; font-size: 0.8em; margin-right: 8px;">
                                            <?php echo number_format($row['cena'], 2, '.', ''); ?>zł
                                        </s>
                                    </p>
                                    <button>+</button>
                                </div>

                            <?php endif; ?>

                        <?php endwhile; ?>
                        
                    </div>
                </section>
                <?php endif; ?>
                
                <section>
                    <p class="sectionName">Co Nie Co Na Ciepło <img src="files/hipekBurger.png" alt="hipek z burgerem"></p>
                    <div class="horizontalScroll">

                        <?php  mysqli_data_seek($mainFetchResult, 0); while($row = mysqli_fetch_assoc($mainFetchResult)): ?>

                            <?php if($row['kategoria'] == 1): ?>
                            
                            <div class="scrollItem">
                                <input type="hidden" class="productId" value="<?php echo $row['id']; ?>" data-image="produkty/<?php echo $row['zdjęcie']; ?>">
                                <img src="produkty/<?php echo $row['zdjęcie']; ?>" alt="artykuł">
                                <p class="topP text-break"><?php echo $row['nazwa']; ?></p>
                                <p class="bottomP"><?php echo number_format($row['cena'], 2, '.', ''); ?>zł</p>
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
                                <input type="hidden" class="productId" value="<?php echo $row['id']; ?>" data-image="produkty/<?php echo $row['zdjęcie']; ?>">
                                <img src="produkty/<?php echo $row['zdjęcie']; ?>" alt="artykuł">
                                <p class="topP text-break"><?php echo $row['nazwa']; ?></p>
                                <p class="bottomP"><?php echo number_format($row['cena'], 2, '.', ''); ?>zł</p>
                                <button>+</button>
                            </div>

                            <?php endif; ?>

                        <?php endwhile; ?>

                    </div>
                </section>

                <img class="img-fluid resizeImg mx-auto" src="files/buła.png" alt="reklama">

                <section>
                    <p class="sectionName">Napoje <img src="files/hipekPicie.png" alt="hipek z piciem"></p>
                    <div class="horizontalScroll">
                        
                        <?php mysqli_data_seek($mainFetchResult, 0); while($row = mysqli_fetch_assoc($mainFetchResult)): ?>

                            <?php if($row['kategoria'] == 3): ?>
                            
                                <div class="scrollItem">
                                    <input type="hidden" class="productId" value="<?php echo $row['id']; ?>" data-image="produkty/<?php echo $row['zdjęcie']; ?>">
                                    <img src="produkty/<?php echo $row['zdjęcie']; ?>" alt="artykuł">
                                    <p class="topP text-break"><?php echo $row['nazwa']; ?></p>
                                    <p class="bottomP"><?php echo number_format($row['cena'], 2, '.', ''); ?>zł</p>
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
                                <input type="hidden" class="productId" value="<?php echo $row['id']; ?>" data-image="produkty/<?php echo $row['zdjęcie']; ?>">
                                <img src="produkty/<?php echo $row['zdjęcie']; ?>" alt="artykuł">
                                <p class="topP text-break"><?php echo $row['nazwa']; ?></p>
                                <p class="bottomP"><?php echo number_format($row['cena'], 2, '.', ''); ?>zł</p>
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
                                <input type="hidden" class="productId" value="<?php echo $row['id']; ?>" data-image="produkty/<?php echo $row['zdjęcie']; ?>">
                                <img src="produkty/<?php echo $row['zdjęcie']; ?>" alt="artykuł">
                                <p class="topP text-break"><?php echo $row['nazwa']; ?></p>
                                <p class="bottomP"><?php echo number_format($row['cena'], 2, '.', ''); ?>zł</p>
                                <button>+</button>
                            </div>

                            <?php endif; ?>

                        <?php endwhile; ?>

                    </div>
                </section>
            </div> </div>
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
                <a href="main.php">Moje Punkty</a>
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

    <script src="src/app.js"></script>
    <script src="src/search.js"></script>
</body>
</html>