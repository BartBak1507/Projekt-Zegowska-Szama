<?php require_once('src/config.php'); ?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="style_login.css">
    <title>Zegowska Szama - Rejestracja</title>
</head>
<body>
    <header>
        <a href="main.php">
            <img class="img-fluid" src="files/BANER_LEPSZY.png" alt="baner">
        </a>
    </header>
    <main>
        <h1>Dołącz do nas</h1>
        <h6>Załóż konto, aby zbierać Szamsy®</h6>
        <form action="" method="post">
            <input type="text" placeholder="Nazwa użytkownika" name="nazwa" value="<?php if(isset($nazwa)){ echo $nazwa;} ?>" required><br>
            <input type="email" placeholder="Adres e-mail" name="email" value="<?php if(isset($email)){ echo $email;} ?>" required><br>
            <input type="password" minlength="8" placeholder="Hasło" name="hasło" required><br>
            <input type="password" minlength="8" placeholder="Powtórz hasło" name="hasłoPowt" required><br>

           <div><input id="check" type="checkbox" required> <label for="check" class="inlineP">Akceptuje <a href="regulamin.html"><span>Regulamin</span></a> oraz <a href="polityka_prywatnosci.html"><span>Polityka Prywatności</span></a> </label></div> 

            <input type="submit" value="Utwórz konto" name="register"><br>
            <a href="login.php" class="linkCenter">Masz już konto? <span>Zaloguj się</span></a>
        </form>

        <?php if(isset($info) && $info == "loginTaken"): ?>

            <p class="badInf">Podany login lub mail jest zajęty ❌</p>

        <?php elseif(isset($info) && $info == "differentPass"): ?>

            <p class="badInf">Podane hasła się różnią ❌</p>

        <?php elseif(isset($info) && $info == "succs"): ?>

            <p class="goodInf">Twoje konto zostało utworzone</p>
        
        <?php endif; ?>



    </main>
</body>
</html>