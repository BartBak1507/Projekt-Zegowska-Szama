<?php
// Uruchomienie sesji i podłączenie bazy danych
require_once('src/config.php'); // Upewnij się, że ścieżka do config.php jest poprawna

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Bezpiecznik: Jeśli nie ma ID sesji, wyrzucamy do logowania
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['id'];
$message = "";
$messageType = ""; // 'success' lub 'error'

// Pobieranie aktualnych danych użytkownika
$query = "SELECT nazwa_użytkownika, hasło FROM użytkownik WHERE id = ?";
$stmt = mysqli_prepare($mysqli, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$current_user = mysqli_fetch_assoc($result);

$aktualna_nazwa = $current_user['nazwa_użytkownika'];

// Obsługa formularza po kliknięciu "Zatwierdź zmiany"
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $obecne_haslo = $_POST['obecne_haslo'] ?? '';
    $nowa_nazwa = htmlspecialchars(trim($_POST['nowa_nazwa'] ?? ''), ENT_QUOTES);
    $nowe_haslo = $_POST['nowe_haslo'] ?? '';
    $powtorz_haslo = $_POST['powtorz_haslo'] ?? '';

    // 1. Sprawdzamy, czy podano obecne hasło i czy jest poprawne
    if (empty($obecne_haslo)) {
        $message = "Musisz podać obecne hasło, aby dokonać zmian!";
        $messageType = "error";
    } elseif (!password_verify($obecne_haslo, $current_user['hasło'])) {
        $message = "Obecne hasło jest niepoprawne ❌";
        $messageType = "error";
    } else {
        // Hasło się zgadza, lecimy dalej z weryfikacją zmian
        $update_queries = [];
        $update_params = [];
        $param_types = "";
        $zmiany_wykonane = false;

        // --- ZMIANA NAZWY UŻYTKOWNIKA ---
        if (!empty($nowa_nazwa) && $nowa_nazwa !== $aktualna_nazwa) {
            // Sprawdzamy czy nowa nazwa nie jest już zajęta
            $check_query = "SELECT id FROM użytkownik WHERE nazwa_użytkownika = ?";
            $check_stmt = mysqli_prepare($mysqli, $check_query);
            mysqli_stmt_bind_param($check_stmt, "s", $nowa_nazwa);
            mysqli_stmt_execute($check_stmt);
            $check_result = mysqli_stmt_get_result($check_stmt);

            if (mysqli_num_rows($check_result) > 0) {
                $message = "Ta nazwa użytkownika jest już zajęta.";
                $messageType = "error";
            } else {
                $update_queries[] = "nazwa_użytkownika = ?";
                $update_params[] = $nowa_nazwa;
                $param_types .= "s";
                $zmiany_wykonane = true;
                
                // Aktualizujemy nazwę do wyświetlania w tym widoku
                $aktualna_nazwa = $nowa_nazwa; 
            }
        }

        // --- ZMIANA HASŁA ---
        if (!empty($nowe_haslo)) {
            if ($nowe_haslo !== $powtorz_haslo) {
                $message = "Nowe hasła nie są identyczne!";
                $messageType = "error";
            } else {
                $hashed_password = password_hash($nowe_haslo, PASSWORD_DEFAULT);
                $update_queries[] = "hasło = ?";
                $update_params[] = $hashed_password;
                $param_types .= "s";
                $zmiany_wykonane = true;
            }
        }

        // --- WYKONANIE ZAPYTANIA DO BAZY ---
        if (empty($message) && $zmiany_wykonane && !empty($update_queries)) {
            $sql = "UPDATE użytkownik SET " . implode(", ", $update_queries) . " WHERE id = ?";
            $update_params[] = $user_id;
            $param_types .= "i";

            $update_stmt = mysqli_prepare($mysqli, $sql);
            mysqli_stmt_bind_param($update_stmt, $param_types, ...$update_params);

            if (mysqli_stmt_execute($update_stmt)) {
                $message = "Zmiany zostały pomyślnie zapisane";
                $messageType = "success";
                
                // Aktualizujemy sesję, jeśli nazwa została zmieniona
                if (in_array("nazwa_użytkownika = ?", $update_queries)) {
                    $_SESSION['nazwa_użytkownika'] = $aktualna_nazwa;
                }
            } else {
                $message = "Wystąpił błąd podczas zapisywania zmian ❌";
                $messageType = "error";
            }
        } elseif (empty($message) && !$zmiany_wykonane) {
            $message = "Nie wprowadzono żadnych nowych danych do zmiany";
            $messageType = "error";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style_settings.css">
    <title>Ustawienia konta - Zegowska Szama</title>
    
</head>
<body>

    <div class="settings-container">
        <div class="settings-header">
            <h1>Ustawienia konta</h1>
            <p>Zarządzaj swoimi danymi logowania</p>
        </div>

        <?php if (!empty($message)): ?>
            <div class="alert <?php echo $messageType; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="settings.php">
            
            <div class="form-group">
                <label for="nowa_nazwa">Nazwa użytkownika</label>
                <input type="text" id="nowa_nazwa" name="nowa_nazwa" value="<?php echo htmlspecialchars($aktualna_nazwa, ENT_QUOTES); ?>">
            </div>

            <div class="divider"></div>

            <p style="color: var(--text-dim); font-size: 13px; margin-bottom: 15px;">Wypełnij poniższe pola tylko, jeśli chcesz zmienić hasło.</p>

            <div class="form-group">
                <label for="nowe_haslo">Nowe hasło</label>
                <input type="password" id="nowe_haslo" name="nowe_haslo" placeholder="Pozostaw puste, by nie zmieniać">
            </div>

            <div class="form-group">
                <label for="powtorz_haslo">Powtórz nowe hasło</label>
                <input type="password" id="powtorz_haslo" name="powtorz_haslo" placeholder="Powtórz hasło">
            </div>

            <div class="divider"></div>

            <div class="form-group">
                <label for="obecne_haslo" style="color: var(--accent);">Obecne hasło (Wymagane do autoryzacji zmian)</label>
                <input type="password" id="obecne_haslo" name="obecne_haslo" placeholder="Wpisz swoje aktualne hasło" required>
            </div>

            <button type="submit" class="submit-btn">Zatwierdź zmiany</button>
        </form>

        <a href="main.php" class="back-link">← Powrót na stronę główną</a>
    </div>

</body>
</html>