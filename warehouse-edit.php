<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

include 'database/db_connection.php';

$company_id = $_SESSION['company_id'] ?? 1;
$id = intval($_GET['id']);

// Načteme sklad
$sql = "SELECT * FROM warehouses WHERE id=? AND company_id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id, $company_id);
$stmt->execute();
$result = $stmt->get_result();
$warehouse = $result->fetch_assoc();

if (!$warehouse) {
    die("Sklad nenalezen.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $address = $_POST['address'];

    $sql = "UPDATE warehouses SET name=?, address=? WHERE id=? AND company_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssii", $name, $address, $id, $company_id);

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
    <title>Upravit sklad</title>
    <link rel="stylesheet" href="css/form-edit.css">
</head>
<body>
<div class="form-container">
    <h1>Upravit sklad</h1>

    <?php if (!empty($error)): ?>
        <p style="color:red;"><?= $error ?></p>
    <?php endif; ?>

    <form method="post">
        <div class="form-group">
            <label for="name">Název skladu:</label>
            <input type="text" name="name" id="name" value="<?= htmlspecialchars($warehouse['name']) ?>" required>
        </div>

        <div class="form-group">
            <label for="address">Adresa:</label>
            <input type="text" name="address" id="address" value="<?= htmlspecialchars($warehouse['address']) ?>">
        </div>

        <button type="submit" class="btn btn-save">💾 Uložit změny</button>
        <a href="warehouses.php" class="btn btn-back">⬅️ Zpět</a>
    </form>
</div>
</body>
</html>
