<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

include 'database/db_connection.php';

$company_id = $_SESSION['company_id'] ?? 1;
$id = intval($_GET['id']);

// Načteme položku
$sql = "SELECT * FROM inventory WHERE id=? AND company_id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id, $company_id);
$stmt->execute();
$result = $stmt->get_result();
$item = $result->fetch_assoc();

if (!$item) {
    die("Položka nenalezena.");
}

// Načteme seznam skladů
$warehouses = [];
$stmt = $conn->prepare("SELECT id, name FROM warehouses WHERE company_id=? ORDER BY name ASC");
$stmt->bind_param("i", $company_id);
$stmt->execute();
$res = $stmt->get_result();
while ($w = $res->fetch_assoc()) {
    $warehouses[] = $w;
}

// Zpracování formuláře
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $warehouse_id = $_POST['warehouse_id'];
    $item_name    = $_POST['item_name'];
    $item_code    = $_POST['item_code'];
    $new_quantity = (int)$_POST['quantity'];
    $unit         = $_POST['unit'];
    $notes        = $_POST['notes'];

    $old_quantity = (int)$item['quantity'];
    $change_qty   = $new_quantity - $old_quantity;

    // Update položky
    $sql = "UPDATE inventory 
            SET warehouse_id=?, item_name=?, item_code=?, quantity=?, unit=?, notes=?
            WHERE id=? AND company_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ississii", $warehouse_id, $item_name, $item_code, $new_quantity, $unit, $notes, $id, $company_id);

    if ($stmt->execute()) {
        // Pokud se změnilo množství, zapíšeme log
        if ($change_qty != 0) {
            $user_id = $_SESSION['user_id'] ?? null;
            $note = "Úprava skladu přes inventory-edit";
            $sqlLog = "INSERT INTO inventory_log (inventory_id, user_id, change_qty, note) VALUES (?, ?, ?, ?)";
            $stmtLog = $conn->prepare($sqlLog);
            $stmtLog->bind_param("iiis", $id, $user_id, $change_qty, $note);
            $stmtLog->execute();
        }

        header("Location: inventory.php");
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
    <title>Upravit položku</title>
    <link rel="stylesheet" href="css/form-edit.css">
</head>
<body>
<div class="form-container">
    <h1>Upravit položku</h1>

    <?php if (!empty($error)): ?>
        <p style="color:red;"><?= $error ?></p>
    <?php endif; ?>

    <form method="post">
        <div class="form-group">
            <label for="warehouse_id">Sklad:</label>
            <select name="warehouse_id" id="warehouse_id" required>
                <?php foreach ($warehouses as $w): ?>
                    <option value="<?= $w['id'] ?>" <?= $w['id'] == $item['warehouse_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($w['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="item_name">Název položky:</label>
            <input type="text" name="item_name" id="item_name" value="<?= htmlspecialchars($item['item_name']) ?>" required>
        </div>

        <div class="form-group">
            <label for="item_code">Kód položky:</label>
            <input type="text" name="item_code" id="item_code" value="<?= htmlspecialchars($item['item_code']) ?>">
        </div>

        <div class="form-group">
            <label for="quantity">Množství:</label>
            <input type="number" name="quantity" id="quantity" value="<?= htmlspecialchars($item['quantity']) ?>" required>
        </div>

        <div class="form-group">
            <label for="unit">Jednotka:</label>
            <input type="text" name="unit" id="unit" value="<?= htmlspecialchars($item['unit']) ?>">
        </div>

        <div class="form-group">
            <label for="notes">Poznámky:</label>
            <textarea name="notes" id="notes" rows="3"><?= htmlspecialchars($item['notes']) ?></textarea>
        </div>

        <button type="submit" class="btn btn-save">💾 Uložit změny</button>
        <a href="inventory.php" class="btn btn-back">⬅️ Zpět</a>
    </form>
</div>
</body>
</html>
