<?php 

    session_start();

    $mysqli = mysqli_connect('localhost', 'root', '', 'zegowska_szama');
    $info = "";

    if (!$mysqli) {
        die("Przepraszamy wystąpił błąd: " . mysqli_connect_error());
    }

    mysqli_set_charset($mysqli, "utf8mb4");

    $query = "SELECT nazwa, cena, kategoria, mnożnik_promocji, zdjęcie FROM `produkty` WHERE dostępność = 1";

    $stmt = mysqli_prepare($mysqli, $query);

    mysqli_stmt_execute($stmt);

    $mainFetchResult = mysqli_stmt_get_result($stmt);


    if($_SERVER['REQUEST_METHOD'] == "POST"){
        if(isset($_POST['login'])){

            $nazwaAlboMail = htmlspecialchars($_POST['nazwaAlboMail'], ENT_QUOTES);
            $haslo = htmlspecialchars($_POST['hasło'], ENT_QUOTES);

            $query = "SELECT id, mail, hasło, nazwa_użytkownika, czy_admin FROM użytkownik WHERE nazwa_użytkownika = ? OR mail = ?";

            $stmt = mysqli_prepare($mysqli, $query);

            mysqli_stmt_bind_param($stmt, "ss", $nazwaAlboMail, $nazwaAlboMail);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $user = mysqli_fetch_assoc($result);

            if (mysqli_num_rows($result) == 0 || !password_verify($haslo, $user['hasło'])) {
                $info = "wrongLoginOrPass";
            }
            else{

                $_SESSION['id'] = $user['id'];
                $_SESSION['nazwa_użytkownika'] = $user['nazwa_użytkownika'];
                $_SESSION['czy_admin'] = $user['czy_admin'];

                header("Location: main.php");
                exit();
            }



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
