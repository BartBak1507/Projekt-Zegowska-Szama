<?php 
require_once('src/config.php'); 

if (!isset($_SESSION['id'])) {
    header("Location: main.php");
    exit();
}

$uzytkownik_id = $_SESSION['id'];

$query = "SELECT zo.numer_zamowienia, zo.data_zamowienia, zo.szczegóły, zo.ilość, zo.stan_przygotowania, p.nazwa
          FROM zamówienia_online zo
          JOIN produkty p ON zo.produkt_id = p.id
          WHERE zo.użytkownik_id = ?
          ORDER BY zo.data_zamowienia DESC";

$stmt = mysqli_prepare($mysqli, $query);
mysqli_stmt_bind_param($stmt, "i", $uzytkownik_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$orders = [];
while ($row = mysqli_fetch_assoc($result)) {
    $nr = $row['numer_zamowienia'];
    if (!isset($orders[$nr])) {
        $orders[$nr] = [
            'numer_zamowienia' => $nr,
            'data_zamowienia' => $row['data_zamowienia'],
            'status' => $row['stan_przygotowania'],
            'produkty' => []
        ];
    }
    $orders[$nr]['produkty'][] = [
        'nazwa' => $row['nazwa'],
        'szczegoly' => $row['szczegóły'],
        'ilosc' => $row['ilość']
    ];
}
?>
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
                <?php if (empty($orders)): ?>
                    <div class="col-12 text-center my-5">
                        <h3 class="text-muted">Nie złożyłeś jeszcze żadnych zamówień.</h3>
                    </div>
                <?php else: ?>
                    <?php foreach ($orders as $order): 
                        $badge_class = 'status-payment';
                        $status_text = 'Płatność zaakceptowana';

                        $db_status = strtolower(trim($order['status']));

                        if ($db_status === 'w realizacji') {
                            $badge_class = 'status-pending';
                            $status_text = 'W realizacji';
                        } elseif ($db_status === 'gotowe do odbioru' || $db_status === 'dostarczone' || $db_status === 'gotowe') {
                            $badge_class = 'status-delivered';
                            $status_text = 'Gotowe do odbioru';
                        } elseif ($db_status === 'anulowane') {
                            $badge_class = 'status-cancelled';
                            $status_text = 'Anulowane';
                        }

                        $date_formatted = '';
                        if (!empty($order['data_zamowienia'])) {
                            $date_obj = new DateTime($order['data_zamowienia']);
                            $date_formatted = $date_obj->format('d.m.Y, H:i');
                        }
                    ?>
                        <div class="col-12">
                            <div class="order-card p-3 p-sm-4">
                                <div class="row align-items-center g-3 border-bottom pb-3 mb-3">
                                    <div class="col-12 col-md-6 text-center text-md-start">
                                        <span class="order-number d-block d-sm-inline">Zamówienie <b>#<?= htmlspecialchars($order['numer_zamowienia']) ?></b></span>
                                        <?php if (!empty($date_formatted)): ?>
                                            <span class="order-date ms-sm-3 d-block d-sm-inline"><?= $date_formatted ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-12 col-md-6 text-center text-md-end">
                                        <span class="badge <?= $badge_class ?> px-3 py-2 text-uppercase"><?= $status_text ?></span>
                                    </div>
                                </div>

                                <div class="row g-3 align-items-center mb-1">
                                    <?php foreach ($order['produkty'] as $product): ?>
                                        <div class="col-12 col-sm-6 col-lg-4">
                                            <div class="p-3 item-mini-card">
                                                <div>
                                                    <h6 class="mb-1 text-white fw-bold"><?= htmlspecialchars($product['nazwa']) ?></h6>
                                                    <small class="textMuted d-block">Ilość: <?= (int)$product['ilosc'] ?></small>
                                                    <?php if (!empty($product['szczegoly'])): ?>
                                                        <small class="textMuted d-block mt-1">Szczegóły: <?= htmlspecialchars($product['szczegoly']) ?></small>
                                                    <?php endif; ?>
                                                    <?php if (empty($product['szczegoly'])): ?>
                                                        <small class="textMuted d-block mt-1">Szczegóły: --Brak--</small>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </main>
    
</body>
</html>