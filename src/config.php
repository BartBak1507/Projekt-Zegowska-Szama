<?php 


    $mysqli = mysqli_connect('localhost', 'root', '', 'zegowska_szama');

    if (!$mysqli) {
        die("Błąd połączenia: " . mysqli_connect_error());
    }

    mysqli_set_charset($mysqli, "utf8mb4");

    $query = "SELECT nazwa, cena, kategoria, mnożnik_promocji FROM `produkty` WHERE dostępność = 1";

    $stmt = mysqli_prepare($mysqli, $query);

    mysqli_stmt_execute($stmt);

    $mainFetchResult = mysqli_stmt_get_result($stmt);
