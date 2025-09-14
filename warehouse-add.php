<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

include 'database/db_connection.php';

$company_id = $_SESSION['company_id'] ?? 1;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $address = $_POST['address'];

    $sql = "INSERT INTO warehouses (company_id, name, address) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $company_id, $name, $address);

    if ($stmt->execute()) {
        header("Location: warehouses.php");
        exit();
    } else {
        $error = "Chyba při ukládání: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Přidat sklad</title>
    <link rel="stylesheet" href="css/form-edit.css">
</head>
<body>
<div class="form-container">
    <h1>Přidat sklad</h1>

    <?php if (!empty($error)): ?>
        <p style="color:red;"><?= $error ?></p>
    <?php endif; ?>

    <form method="post">
        <div class="form-group">
            <label for="name">Název skladu:</label>
            <input type="text" name="name" id="name" required>
        </div>

        <div class="form-group">
            <label for="address">Adresa:</label>
            <input type="text" name="address" id="address">
        </div>

        <button type="submit" class="btn btn-save">💾 Uložit</button>
        <a href="warehouses.php" class="btn btn-back">⬅️ Zpět</a>
    </form>
</div>
</body>
</html>
