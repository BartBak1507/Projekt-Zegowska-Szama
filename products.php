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
    <link rel="stylesheet" href="style_manage.css"> 
    <title>Zarządzanie Produktami - Zegowska Szama</title>
    <style>
        /* Nadpisanie domyślnych stylów Bootstrap dla idealnego dopasowania do Twoich screenów */
        body {
            background-color: #030708 !important; /* Bardzo ciemne, niemal czarne tło */
            color: #ffffff !important;
        }
        
        .product-box {
            background-color: #212529 !important; /* Ciemnografitowe tło kafelka użytkownika */
            border: 2px solid #910000 !important; /* Charakterystyczna czerwona ramka Szamy */
            border-radius: 20px !important; /* Zaokrąglone rogi jak w panelu użytkowników */
            padding: 20px !important;
            transition: transform 0.2s;
        }

        .product-box:hover {
            transform: scale(1.01);
        }

        .new-product-box {
            background-color: #111416 !important;
            border: 2px dashed #ffc107 !important; /* Przerywana żółto-pomarańczowa linia */
            border-radius: 20px !important;
            padding: 20px !important;
        }

        /* Stylizacja inputów, aby wtapiały się w ciemne tło */
        .custom-input {
            background-color: #16191c !important;
            border: 1px solid #444 !important;
            color: #fff !important;
            border-radius: 8px !important;
        }

        .custom-input:focus {
            border-color: #910000 !important;
            box-shadow: 0 0 0 0.25rem rgba(145, 0, 0, 0.25) !important;
            color: #fff !important;
        }

        /* Przyciski w kolorystyce Zegowskiej Szamy */
        .btn-szama-red {
            background-color: #910000 !important;
            color: #ffffff !important;
            border: none !important;
            font-weight: bold !important;
            border-radius: 10px !important;
            padding: 8px 16px !important;
        }

        .btn-szama-red:hover {
            background-color: #b00000 !important;
        }

        .btn-szama-save {
            background-color: #910000 !important;
            color: white !important;
            font-weight: bold !important;
            border: 1px solid #ff4d4d !important;
            border-radius: 10px !important;
        }

        .btn-szama-save:hover {
            background-color: #b00000 !important;
            box-shadow: 0 0 8px rgba(255, 77, 77, 0.4);
        }

        .input-group-text-custom {
            background-color: #16191c !important;
            border: 1px solid #444 !important;
            color: #ff4d4d !important;
            font-weight: bold;
        }
    </style>
</head>
<body style="padding-bottom: 120px;">

    <header class="text-center mb-4">
        <a href="main.php">
            <img class="img-fluid" src="files/BANER_LEPSZY.png" alt="baner" style="max-height: 150px; object-fit: contain;">
        </a>
    </header>

    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4 px-2">
            <h1 class="fw-bold" style="letter-spacing: 1px; font-size: 2rem;">ZARZĄDZANIE PRODUKTAMI</h1>
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
                                        <span class="input-group-text input-group-text-custom">Rabat</span>
                                        <input type="number" name="przecena_procent" min="0" max="100" class="form-control custom-input text-center fw-bold" value="<?php echo $wyswietlany_procent; ?>" placeholder="0">
                                        <span class="input-group-text input-group-text-custom" style="color: #fff !important;">%</span>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" name="update_product" class="btn btn-szama-save w-100 py-2">Zapisz zmiany</button>
                        </form>
                    </div>
                </div>
            <?php endwhile; ?>

            <div class="col-12 col-md-6 col-lg-4">
                <div class="new-product-box h-100 shadow d-flex flex-column justify-content-between">
                    <form action="products.php" method="POST" class="h-100 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex align-items-center mb-3">
                                <span class="badge bg-warning text-dark me-2 fs-6 fw-bold">+</span>
                                <h5 class="m-0 text-warning fw-bold" style="letter-spacing: 0.5px;">Nowy Produkt</h5>
                            </div>
                            <hr style="border-color: #ffc107 !important; margin-top: 0; margin-bottom: 15px;">

                            <div class="mb-3">
                                <label class="form-label small fw-bold text-warning mb-1">Nazwa:</label>
                                <input type="text" name="nazwa" class="form-control custom-input" placeholder="np. Tost z Salami" style="border-color: #555 !important;" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold text-warning mb-1">Cena początkowa (zł):</label>
                                <input type="number" name="cena" step="0.01" class="form-control custom-input" placeholder="0.00" style="border-color: #555 !important;" required>
                            </div>

                            <div class="mb-4">
                                <label class="form-label small fw-bold text-warning mb-1">Kategoria menu:</label>
                                <select name="kategoria" class="form-select custom-input" style="border-color: #555 !important;" required>
                                    <option value="1">Ciepłe</option>
                                    <option value="2">Ciepłe napoje</option>
                                    <option value="3">Napoje</option>
                                    <option value="4">Słodkie</option>
                                    <option value="5">Słone</option>
                                </select>
                            </div>
                        </div>

                        <button type="submit" name="add_product" class="btn btn-warning text-dark w-100 py-2 fw-bold rounded-3 shadow">Dodaj do menu</button>
                    </form>
                </div>
            </div>

        </div>
    </div>

</body>
</html>