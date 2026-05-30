<?php

require_once('config.php');

// Jeśli ktoś nie jest zalogowany albo wszedł tu przez przypadek to go cyk wywalamy
if (!isset($_SESSION['id']) || $_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['cart_data'])) {
    header("Location: ../main.php");
    exit();
}

$cart = json_decode($_POST['cart_data'], true);
$uzytkownik_id = $_SESSION['id'];
$payment_method = isset($_POST['payment_method']) ? $_POST['payment_method'] : 'money';

if (!empty($cart)) {
    
    // unikalny numer zamówienia 
    $numer_zamowienia = "ZAM-" . time() . "-" . rand(100, 999);

    $razem_do_zaplaty = 0;

    foreach ($cart as $item) {
        if ($item['id'] === 'DISCOUNT') {
            $razem_do_zaplaty += $item['price']; 
        } else {
            $razem_do_zaplaty += $item['price'] * $item['quantity'];
        }
    }
    
    if ($razem_do_zaplaty < 0) {
        $razem_do_zaplaty = 0;
    }

    $query_insert = "INSERT INTO zamówienia_online (numer_zamowienia, użytkownik_id, produkt_id, szczegóły, ilość) VALUES (?, ?, ?, ?, ?)";
    $stmt_insert = mysqli_prepare($mysqli, $query_insert);

    foreach ($cart as $item) {
        if ($item['id'] === 'DISCOUNT') {
            continue; 
        }

        $produkt_id = (int)$item['id'];
        $szczegoly = htmlspecialchars($item['details'], ENT_QUOTES);
        $ilosc = (int)$item['quantity'];

        mysqli_stmt_bind_param($stmt_insert, "siisi", $numer_zamowienia, $uzytkownik_id, $produkt_id, $szczegoly, $ilosc);
        mysqli_stmt_execute($stmt_insert);
    }


    $komunikat_szamsy = "";

    if ($payment_method === 'szamsy') {
        $koszt_szamsy = floor($razem_do_zaplaty * 75);

        $query_check = "SELECT szamsy FROM użytkownik WHERE id = ?";
        $stmt_check = mysqli_prepare($mysqli, $query_check);
        mysqli_stmt_bind_param($stmt_check, "i", $uzytkownik_id);
        mysqli_stmt_execute($stmt_check);
        $res_check = mysqli_stmt_get_result($stmt_check);
        $user_data = mysqli_fetch_assoc($res_check);

        if (!$user_data || $user_data['szamsy'] < $koszt_szamsy) {
            die("Błąd: Masz za mało punktów Szamsy na to zamówienie!");
        }

        $query_punkty = "UPDATE użytkownik SET szamsy = szamsy - ? WHERE id = ?";
        $stmt_punkty = mysqli_prepare($mysqli, $query_punkty);
        mysqli_stmt_bind_param($stmt_punkty, "ii", $koszt_szamsy, $uzytkownik_id);
        mysqli_stmt_execute($stmt_punkty);

        $komunikat_szamsy = "Zapłacono punktami! Pobrano z konta: <b>{$koszt_szamsy}</b> Szamsów.";
    } else {
        $pelne_zlotowki = floor($razem_do_zaplaty);
        $naliczone_punkty = $pelne_zlotowki * 4;

        if ($naliczone_punkty > 0) {
            $query_punkty = "UPDATE użytkownik SET szamsy = szamsy + ? WHERE id = ?";
            $stmt_punkty = mysqli_prepare($mysqli, $query_punkty);
            mysqli_stmt_bind_param($stmt_punkty, "ii", $naliczone_punkty, $uzytkownik_id);
            mysqli_stmt_execute($stmt_punkty);
        }

        $komunikat_szamsy = "Tradycyjne zamówienie. Zyskujesz: <b>+{$naliczone_punkty}</b> punktów Szamsy!";
    }

    echo "
    <!DOCTYPE html>
    <html lang='pl'>
    <head>
        <meta charset='UTF-8'>
        <title>Dziękujemy!</title>
    </head>
    <body style='background:#04050D; color:white; font-family:sans-serif; text-align:center; padding-top:120px;'>
        <h1 style='color: #FF0000;'>Smacznego!</h1>
        <h2>Zamówienie zostało pomyślnie złożone.</h2>
        <p>{$komunikat_szamsy}</p>
        <p>Za chwilę nastąpi powrót na stronę główną...</p>
        
        <script>
            // Skrypt JS czyści localStorage
            localStorage.removeItem('cart');
            
            setTimeout(function() {
                window.location.href = '../main.php'; 
            }, 4500);
        </script>
    </body>
    </html>
    ";
    exit();
}
?>