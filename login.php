<?php require_once('src/config.php'); ?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="style_login.css">
    <title>Zegowska Szama - Logowanie</title>
</head>
<body>
    <header>
        <img  class="img-fluid" src="files/BANER_LEPSZY.png" alt="baner">
    </header>
    <main>
        <img src="files/ikona.png" class="profileIcon" alt="ikona">
        
        <h1>Witaj ponownie!</h1>
        <form action="" method="post">
            <input type="text" placeholder="E-mail lub nazwa użytkownika" name="nazwaAlboMail" value="<?php if(isset($nazwaAlboMail)){ echo $nazwaAlboMail; } ?>" required><br>
            <input type="password" minlength="8" placeholder="Hasło" name="hasło" required><br>
            <a class="linkForgot" href="...">Zapomniałeś hasła?</a><br>
            <input type="submit" value="Zaloguj się" name="login"><br>
            <a href="register.php" class="linkCenter">Nie masz jeszcze konta? <span>Zarejestruj się</span></a>
        </form>
        
        <?php if(isset($info) && $info == "wrongLoginOrPass"): ?>

            <p class="badInf">Błędny login lub hasło ❌</p>
        
        <?php endif; ?>

    </main>
</body>
</html>