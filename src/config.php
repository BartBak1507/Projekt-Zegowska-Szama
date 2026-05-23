<?php 


    $mysqli = mysqli_connect('localhost', 'root', '', 'zegowska_szama');
    $info = "";

    if (!$mysqli) {
        die("Błąd połączenia: " . mysqli_connect_error());
    }

    mysqli_set_charset($mysqli, "utf8mb4");

    $query = "SELECT nazwa, cena, kategoria, mnożnik_promocji, zdjęcie FROM `produkty` WHERE dostępność = 1";

    $stmt = mysqli_prepare($mysqli, $query);

    mysqli_stmt_execute($stmt);

    $mainFetchResult = mysqli_stmt_get_result($stmt);


    if($_SERVER['REQUEST_METHOD'] == "POST"){
        if(isset($_POST['login'])){

        }

        if(isset($_POST['register'])){
            $nazwa = htmlspecialchars($_POST['nazwa'], ENT_QUOTES);
            $email = htmlspecialchars($_POST['email'], ENT_QUOTES);
            $haslo = htmlspecialchars($_POST['hasło'], ENT_QUOTES);
            $hasloPowt = htmlspecialchars($_POST['hasłoPowt'], ENT_QUOTES);

            $query = "SELECT id FROM użytkownik WHERE nazwa_użytkownika = ? OR mail = ?";

            $stmt = mysqli_prepare($mysqli, $query);

            mysqli_stmt_bind_param($stmt, "ss", $nazwa, $email);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($result) > 0) {
                $info = "loginTaken";
            }
            else{ // tutaj kontnuacja jak jest login dostępny

                if($haslo != $hasloPowt){
                    $info = "differentPass";
                }
                else{ // tutaj jak hasło sie zgadza
                    $haslo_hash = password_hash($haslo, PASSWORD_DEFAULT);

                    $query = "INSERT INTO użytkownik (mail, hasło, nazwa_użytkownika) VALUES (?, ?, ?)";
                    $stmt = mysqli_prepare($mysqli, $query);

                    mysqli_stmt_bind_param($stmt, "sss", $email, $haslo_hash, $nazwa);

                    if (mysqli_stmt_execute($stmt)) {
                        $info = "succs";
                    }
                }

            }
        }
    }
