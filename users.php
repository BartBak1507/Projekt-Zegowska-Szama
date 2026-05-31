<?php
require_once('src/config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Aktualizacja liczby szamsów
    if (isset($_POST['update_szamsy']) && isset($_POST['user_id']) && isset($_POST['szamsy'])) {
        $user_id = (int)$_POST['user_id'];
        $szamsy = (int)$_POST['szamsy'];
        
        $query = "UPDATE użytkownik SET szamsy = ? WHERE id = ?";
        $stmt = mysqli_prepare($mysqli, $query);
        mysqli_stmt_bind_param($stmt, "ii", $szamsy, $user_id);
        mysqli_stmt_execute($stmt);
    }

    // 2. Aktualizacja statusu Admina
    if (isset($_POST['update_admin']) && isset($_POST['user_id'])) {
        $user_id = (int)$_POST['user_id'];
        $czy_admin = isset($_POST['czy_admin']) ? 1 : 0;
        
        $query = "UPDATE użytkownik SET czy_admin = ? WHERE id = ?";
        $stmt = mysqli_prepare($mysqli, $query);
        mysqli_stmt_bind_param($stmt, "ii", $czy_admin, $user_id);
        mysqli_stmt_execute($stmt);
    }

    // 3. Usuwanie użytkownika z bazy danych (Nowość!)
    if (isset($_POST['delete_user']) && isset($_POST['user_id'])) {
        $user_id = (int)$_POST['user_id'];
        
        // Opcjonalne zabezpieczenie: Tutaj możesz sprawdzić, czy $user_id nie jest ID aktualnie zalogowanego admina, np. $_SESSION['user_id']
        $query = "DELETE FROM użytkownik WHERE id = ?";
        $stmt = mysqli_prepare($mysqli, $query);
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
    }

    header("Location: users.php");
    exit();
}

// Pobranie wszystkich użytkowników z bazy
$query = "SELECT id, nazwa_użytkownika, mail, czy_admin, szamsy FROM użytkownik ORDER BY id ASC";
$result = mysqli_query($mysqli, $query);
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles/style_manage.css">
    <title>Panel Admina - Użytkownicy</title>
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
                    <h1 class="orders-title m-0">Zarządzanie Użytkownikami</h1>
                    <a href="orders.php" class="btn btn-admin-save" style="text-decoration: none;">← Do Zamówień</a>
                </div>
            </div>

            <div class="row g-4">
                <?php if (mysqli_num_rows($result) == 0): ?>
                    <div class="col-12 text-center my-5">
                        <h3 class="text-muted">Brak użytkowników w bazie danych.</h3>
                    </div>
                <?php else: ?>
                    <?php while ($user = mysqli_fetch_assoc($result)): ?>
                        <div class="col-12">
                            <div class="order-card p-3 p-sm-4">
                                <div class="row align-items-center g-3">
                                    
                                    <div class="col-12 col-md-3 text-center text-md-start">
                                        <span class="order-number d-block mb-1">
                                            <b><?= htmlspecialchars($user['nazwa_użytkownika']) ?></b>
                                        </span>
                                        <span class="order-date d-block text-muted" style="font-size: 85%;">
                                            <?= htmlspecialchars($user['mail']) ?>
                                        </span>
                                    </div>

                                    <div class="col-12 col-sm-6 col-md-4">
                                        <form method="POST" class="d-flex align-items-center gap-2 justify-content-center justify-content-md-start m-0">
                                            <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                            <div class="input-group spec-form-width" style="max-width: 230px;">
                                                <span class="input-group-text admin-input-label">Szamsy:</span>
                                                <input type="number" name="szamsy" class="form-control admin-input-field" value="<?= (int)$user['szamsy'] ?>" min="0">
                                            </div>
                                            <button type="submit" name="update_szamsy" class="btn btn-admin-save">Zapisz</button>
                                        </form>
                                    </div>

                                    <div class="col-12 col-sm-3 col-md-2 text-center">
                                        <form id="adminForm_<?= $user['id'] ?>" method="POST" class="m-0 d-flex align-items-center justify-content-center">
                                            <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                            <input type="hidden" name="update_admin" value="1">
                                            
                                            <div class="d-flex align-items-center gap-2">
                                                <label class="text-white fw-bold m-0" for="adminSwitch<?= $user['id'] ?>" style="white-space: nowrap;">
                                                    Admin:
                                                </label>
                                                <input class="form-check-input admin-switch m-0" type="checkbox" name="czy_admin" id="adminSwitch<?= $user['id'] ?>" <?= $user['czy_admin'] == 1 ? 'checked' : '' ?> onchange="confirmAdminChange(this, '<?= htmlspecialchars($user['nazwa_użytkownika']) ?>')">
                                            </div>
                                        </form>
                                    </div>

                                    <div class="col-12 col-sm-3 col-md-3 text-center text-sm-end">
                                        <form method="POST" class="m-0" onsubmit="return confirm('⚠️ UWAGA! Czy na pewno chcesz PERMANENTNIE USUNĄĆ konto użytkownika <?= htmlspecialchars($user['nazwa_użytkownika']) ?>?\nTej operacji nie da się cofnąć!');">
                                            <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                            <button type="submit" name="delete_user" class="btn btn-admin-delete-user">
                                                <span class="warning-icon">⚠</span> Usuń Konto
                                            </button>
                                        </form>
                                    </div>

                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <script>
    function confirmAdminChange(checkbox, username) {
        let message = checkbox.checked 
            ? "Czy na pewno chcesz nadać uprawnienia ADMINISTRATORA użytkownikowi " + username + "?" 
            : "Czy na pewno chcesz ODEBRAĆ uprawnienia administratora użytkownikowi " + username + "?";
        
        if (confirm(message)) {
            checkbox.form.submit();
        } else {
            checkbox.checked = !checkbox.checked;
        }
    }
    </script>
    
</body>
</html>