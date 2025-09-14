<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Přihlášení - MontCrm01</title>
    <link rel="stylesheet" href="css/index.css">
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <h1>Vítejte v MontCRM01</h1>
            <p class="current-date"><?php echo date("d.m.Y"); ?></p>
            <form action="login.php" method="post">
                <div class="input-group">
                    <label for="username">Uživatelské jméno:</label>
                    <input type="text" name="username" id="username" required>
                </div>
                <div class="input-group">
                    <label for="password">Heslo:</label>
                    <input type="password" name="password" id="password" required>
                </div>
                <button type="submit" class="login-button">Přihlásit se</button>
            </form>
            <p class="admin-link"><a href="admin_login.php">Přihlášení administrátora</a></p>
        </div>
    </div>
</body>
</html>