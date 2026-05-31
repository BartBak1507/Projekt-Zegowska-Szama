<?php
require_once('src/config.php');

// 1. OBSŁUGA FORMULARZY (ZAPIS DO BAZY)

// A. Aktualizacja istniejącego produktu
if (isset($_POST['update_product'])) {
    $product_id = intval($_POST['product_id']);
    $nazwa = mysqli_real_escape_string($mysqli, $_POST['nazwa']);
    $cena = floatval($_POST['cena']);
    $dostepnosc = intval($_POST['dostepnosc']); // 1 = dostępny, 0 = niedostępny
    
    // Przeliczenie procentu przeceny na mnożnik promocji
    $przecena_procent = floatval($_POST['przecena_procent']);
    if ($przecena_procent > 0 && $przecena_procent <= 100) {
        $mnoznik = (100 - $przecena_procent) / 100;
    } else {
        $mnoznik = 1.0; // brak promocji, mnożnik równy 1
    }

    $update_query = "UPDATE produkty SET 
                        nazwa = '$nazwa', 
                        cena = '$cena', 
                        dostępność = '$dostepnosc', 
                        mnożnik_promocji = '$mnoznik' 
                     WHERE id = $product_id";
    
    mysqli_query($mysqli, $update_query);
    header("Location: products.php?success=1");
    exit;
}

// B. Dodawanie nowego produktu
if (isset($_POST['add_product'])) {
    $nazwa = mysqli_real_escape_string($mysqli, $_POST['nazwa']);
    $cena = floatval($_POST['cena']);
    $kategoria_id = intval($_POST['kategoria']);
    
    // Domyślne wartości z bazy danych
    $zdjecie_default = "default.png";
    $dostepnosc_default = 1;
    $mnoznik_default = 1.0;

    $insert_query = "INSERT INTO produkty (nazwa, cena, zdjęcie, kategoria, dostępność, mnożnik_promocji) 
                     VALUES ('$nazwa', '$cena', '$zdjecie_default', '$kategoria_id', '$dostepnosc_default', '$mnoznik_default')";
    
    mysqli_query($mysqli, $insert_query);
    header("Location: products.php?success=2");
    exit;
}

// 2. POBRANIE PRODUKTÓW Z BAZY
$products_query = "SELECT * FROM produkty ORDER BY kategoria ASC, nazwa ASC";
$products_result = mysqli_query($mysqli, $products_query);
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="styles/style_manage.css"> 
    <title>Zarządzanie Produktami - Zegowska Szama</title>
</head>
<body style="padding-bottom: 120px;">

    <header class="text-center mb-4">
        <a href="main.php">
            <img class="img-fluid" src="files/BANER_LEPSZY.png" alt="baner" style="max-height: 150px; object-fit: contain;">
        </a>
    </header>

    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4 px-2">
            <h1 class="fw-bold orders-title" style="letter-spacing: 1px; font-size: 2rem;">ZARZĄDZANIE PRODUKTAMI</h1>
        </div>

        <?php if(isset($_GET['success'])): ?>
            <div class="alert alert-danger text-center fw-bold bg-dark text-white border-danger mx-2" style="border-radius: 12px;">
                <?php 
                    if($_GET['success'] == 1) echo "Pomyślnie zaktualizowano produkt w menu!";
                    if($_GET['success'] == 2) echo "Pomyślnie dodano nowy produkt do bazy!";
                ?>
            </div>
        <?php endif; ?>

        <div class="row g-4 px-2">
            <?php while($product = mysqli_fetch_assoc($products_result)): 
                // Dekodowanie mnożnika z bazy na procenty (np. 0.75 -> 25%)
                $wyswietlany_procent = 0;
                if ($product['mnożnik_promocji'] < 1 && $product['mnożnik_promocji'] > 0) {
                    $wyswietlany_procent = round((1 - $product['mnożnik_promocji']) * 100);
                }
            ?>
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="product-box h-100 d-flex flex-column justify-content-between shadow">
                        <form action="products.php" method="POST" class="h-100 d-flex flex-column justify-content-between">
                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                            
                            <div>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-white-50 mb-1">Nazwa produktu:</label>
                                    <input type="text" name="nazwa" class="form-control custom-input fw-bold" value="<?php echo htmlspecialchars($product['nazwa']); ?>" required>
                                </div>

                                <div class="row g-2 mb-3">
                                    <div class="col-6">
                                        <label class="form-label small fw-bold text-white-50 mb-1">Cena (zł):</label>
                                        <input type="number" name="cena" step="0.01" class="form-control custom-input text-center fw-bold" value="<?php echo $product['cena']; ?>" required>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label small fw-bold text-white-50 mb-1">Status:</label>
                                        <select name="dostepnosc" class="form-select custom-input text-center fw-bold">
                                            <option value="1" <?php echo ($product['dostępność'] == 1) ? 'selected' : ''; ?>>Dostępny</option>
                                            <option value="0" <?php echo ($product['dostępność'] == 0) ? 'selected' : ''; ?>>Wysprzedane</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label small fw-bold text-white-50 mb-1">Przecena (%):</label>
                                    <div class="input-group">
                                        <span class="input-group-text admin-input-label py-1 px-3">Rabat</span>
                                        <input type="number" name="przecena_procent" min="0" max="100" class="form-control admin-input-field text-center" value="<?php echo $wyswietlany_procent; ?>" placeholder="0">
                                        <span class="input-group-text admin-input-field py-1 px-3" style="border-left: none !important; border-top-left-radius: 0 !important; border-bottom-left-radius: 0 !important;">%</span>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" name="update_product" class="btn-szama-save w-100 py-2">Zapisz zmiany</button>
                        </form>
                    </div>
                </div>
            <?php endwhile; ?>

            <div class="col-12 col-md-6 col-lg-4">
                <div class="new-product-box h-100 shadow d-flex flex-column justify-content-between">
                    <form action="products.php" method="POST" class="h-100 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex align-items-center mb-3">
                                <span class="badge status-pending me-2 px-2 py-1" style="background-color: rgba(145,0,0,0.2) !important; color: #ffffff; border-color: #910000;">+</span>
                                <h5 class="m-0 new-product-title">Nowy Produkt</h5>
                            </div>
                            <hr class="border-bottom" style="margin-top: 10px; margin-bottom: 15px; opacity: 1;">

                            <div class="mb-3">
                                <label class="form-label small fw-bold text-white-50 mb-1">Nazwa:</label>
                                <input type="text" name="nazwa" class="form-control custom-input" placeholder="np. Tost z Salami" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold text-white-50 mb-1">Cena początkowa (zł):</label>
                                <input type="number" name="cena" step="0.01" class="form-control custom-input" placeholder="0.00" required>
                            </div>

                            <div class="mb-4">
                                <label class="form-label small fw-bold text-white-50 mb-1">Kategoria menu:</label>
                                <select name="kategoria" class="form-select custom-input" required>
                                    <option value="1">Ciepłe</option>
                                    <option value="2">Ciepłe napoje</option>
                                    <option value="3">Napoje</option>
                                    <option value="4">Słodkie</option>
                                    <option value="5">Słone</option>
                                </select>
                            </div>
                        </div>

                        <button type="submit" name="add_product" class="btn-szama-save w-100 py-2">Dodaj do menu</button>
                    </form>
                </div>
            </div>

        </div>
    </div>

</body>
</html>