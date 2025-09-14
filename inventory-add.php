<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

include 'database/db_connection.php';

$company_id = $_SESSION['company_id'] ?? 1;

// Načtení seznamu skladů
$warehouses = [];
$stmt = $conn->prepare("SELECT id, name FROM warehouses WHERE company_id=? ORDER BY name ASC");
$stmt->bind_param("i", $company_id);
$stmt->execute();
$result = $stmt->get_result();
while ($w = $result->fetch_assoc()) {
    $warehouses[] = $w;
}

// Zpracování formuláře
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $warehouse_id = $_POST['warehouse_id'];
    $item_name    = $_POST['item_name'];
    $item_code    = $_POST['item_code'];
    $quantity     = $_POST['quantity'];
    $unit         = $_POST['unit'];
    $notes        = $_POST['notes'];

    $sql = "INSERT INTO inventory (company_id, warehouse_id, item_name, item_code, quantity, unit, notes)
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iississ", $company_id, $warehouse_id, $item_name, $item_code, $quantity, $unit, $notes);

    if ($stmt->execute()) {
        header("Location: inventory.php");
        exit();
    } else {
        $error = "Chyba při ukládání položky: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Přidat položku do skladu</title>
    <link rel="stylesheet" href="css/form-edit.css">
</head>
<body>
<div class="form-container">
    <h1>Přidat položku do skladu</h1>

    <?php if (!empty($error)): ?>
        <p style="color:red;"><?= $error ?></p>
    <?php endif; ?>

    <form method="post">
        <div class="form-group">
            <label for="warehouse_id">Sklad:</label>
            <select name="warehouse_id" id="warehouse_id" required>
                <option value="">-- Vyber sklad --</option>
                <?php foreach ($warehouses as $w): ?>
                    <option value="<?= $w['id'] ?>"><?= htmlspecialchars($w['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="item_name">Název položky:</label>
            <input type="text" name="item_name" id="item_name" required>
        </div>

        <div class="form-group">
            <label for="item_code">Kód položky:</label>
            <input type="text" name="item_code" id="item_code">
        </div>

        <div class="form-group">
            <label for="quantity">Množství:</label>
            <input type="number" name="quantity" id="quantity" required>
        </div>

        <div class="form-group">
            <label for="unit">Jednotka:</label>
            <input type="text" name="unit" id="unit" value="ks">
        </div>

        <div class="form-group">
            <label for="notes">Poznámky:</label>
            <textarea name="notes" id="notes" rows="3"></textarea>
        </div>

        <button type="submit" class="btn btn-save">💾 Uložit</button>
        <a href="inventory.php" class="btn btn-back">⬅️ Zpět</a>
    </form>
</div>
</body>
</html>

