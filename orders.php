<?php
require_once('src/config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Aktualizacja stanu przygotowania zamówienia
    if (isset($_POST['update_status']) && isset($_POST['numer_zamowienia']) && isset($_POST['new_status'])) {
        $nr = $_POST['numer_zamowienia'];
        $status = $_POST['new_status'];
        $query_update = "UPDATE zamówienia_online SET stan_przygotowania = ? WHERE numer_zamowienia = ?";
        $stmt_update = mysqli_prepare($mysqli, $query_update);
        mysqli_stmt_bind_param($stmt_update, "ss", $status, $nr);
        mysqli_stmt_execute($stmt_update);
    } 
    
    // Usunięcie całego zamówienia z bazy
    if (isset($_POST['delete_order']) && isset($_POST['numer_zamowienia'])) {
        $nr = $_POST['numer_zamowienia'];
        $query_delete = "DELETE FROM zamówienia_online WHERE numer_zamowienia = ?";
        $stmt_delete = mysqli_prepare($mysqli, $query_delete);
        mysqli_stmt_bind_param($stmt_delete, "s", $nr);
        mysqli_stmt_execute($stmt_delete);
    }
    
    header("Location: orders.php");
    exit();
}

// Pobieranie danych z bazy
$query = "SELECT zo.numer_zamowienia, zo.data_zamowienia, zo.szczegóły, zo.ilość, zo.stan_przygotowania, p.nazwa, u.nazwa_użytkownika
          FROM zamówienia_online zo
          JOIN produkty p ON zo.produkt_id = p.id
          LEFT JOIN użytkownik u ON zo.użytkownik_id = u.id
          ORDER BY zo.data_zamowienia DESC";

$result = mysqli_query($mysqli, $query);

$orders = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $nr = $row['numer_zamowienia'];
        if (!isset($orders[$nr])) {
            $orders[$nr] = [
                'numer_zamowienia' => $nr,
                'data_zamowienia' => $row['data_zamowienia'],
                'stan_przygotowania' => $row['stan_przygotowania'] ?? 'Płatność zaakceptowana',
                'nazwa_użytkownika' => $row['nazwa_użytkownika'] ?? 'Nieznany',
                'produkty' => []
            ];
        }
        $orders[$nr]['produkty'][] = [
            'nazwa' => $row['nazwa'],
            'szczegoly' => $row['szczegóły'],
            'ilosc' => $row['ilość']
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles/style_manage.css">
    <title>Panel Admina - Zegowska Szama</title>
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
                <div class="col-12 text-center text-md-start d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <h1 class="orders-title m-0">Zarządzanie Zamówieniami</h1>
                    <a href="users.php" class="btn btn-admin-save" style="text-decoration: none;">Do Użytkowników →</a>
                </div>
            </div>

            <div class="row g-4">
                <?php if (empty($orders)): ?>
                    <div class="col-12 text-center my-5">
                        <h3 class="text-muted">Brak zamówień w systemie.</h3>
                    </div>
                <?php else: ?>
                    <?php foreach ($orders as $order): 
                        $current_status = trim($order['stan_przygotowania']);
                        
                        $date_formatted = '';
                        if (!empty($order['data_zamowienia'])) {
                            $date_obj = new DateTime($order['data_zamowienia']);
                            $date_formatted = $date_obj->format('d.m.Y, H:i');
                        }
                    ?>
                        <div class="col-12">
                            <div class="order-card p-3 p-sm-4">
                                <div class="row align-items-center g-3 border-bottom pb-3 mb-3">
                                    <div class="col-12 col-xl-6 text-center text-xl-start">
                                        <span class="order-number d-block d-sm-inline">Zamówienie <b>#<?= htmlspecialchars($order['numer_zamowienia']) ?></b></span>
                                        <span class="order-date ms-sm-3 d-block d-sm-inline"><?= $date_formatted ?></span>
                                        <span class="admin-user-badge d-block d-sm-inline-block mt-2 mt-sm-0 ms-sm-2">
                                            <?= htmlspecialchars($order['nazwa_użytkownika']) ?>
                                        </span>
                                    </div>
                                    <div class="col-12 col-xl-6">
                                        <div class="d-flex flex-column flex-sm-row justify-content-center justify-content-xl-end align-items-center gap-2">
                                            <form method="POST" class="d-flex align-items-center gap-2 m-0 spec-form-width">
                                                <input type="hidden" name="numer_zamowienia" value="<?= htmlspecialchars($order['numer_zamowienia']) ?>">
                                                <select name="new_status" class="form-select admin-select">
                                                    <option value="Płatność zaakceptowana" <?= $current_status === 'Płatność zaakceptowana' ? 'selected' : '' ?>>Płatność zaakceptowana</option>
                                                    <option value="W realizacji" <?= $current_status === 'W realizacji' ? 'selected' : '' ?>>W realizacji</option>
                                                    <option value="Gotowe do odbioru" <?= $current_status === 'Gotowe do odbioru' ? 'selected' : '' ?>>Gotowe do odbioru</option>
                                                    <option value="Anulowane" <?= $current_status === 'Anulowane' ? 'selected' : '' ?>>Anulowane</option>
                                                </select>
                                                <button type="submit" name="update_status" class="btn btn-admin-save">Zmień</button>
                                            </form>
                                            <form method="POST" class="m-0" onsubmit="return confirm('Czy na pewno chcesz permanentnie usunąć to zamówienie z bazy danych?');">
                                                <input type="hidden" name="numer_zamowienia" value="<?= htmlspecialchars($order['numer_zamowienia']) ?>">
                                                <button type="submit" name="delete_order" class="btn btn-admin-delete">Usuń</button>
                                            </form>
                                        </div>
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