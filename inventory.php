<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

include 'database/db_connection.php';

$company_id = $_SESSION['company_id'] ?? 1;

// Načteme všechny položky se skladem
$sql = "SELECT i.id, i.item_name, i.item_code, i.quantity, i.unit, i.notes,
               w.name AS warehouse_name
        FROM inventory i
        JOIN warehouses w ON i.warehouse_id = w.id
        WHERE i.company_id = ?
        ORDER BY w.name ASC, i.item_name ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $company_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sklad – Přehled položek</title>
    <link rel="stylesheet" href="css/inventory.css">
</head>
<body>
<div class="container">
    <h1>📦 Přehled skladu</h1>

    <div class="actions">
        <a href="inventory-add.php" class="btn btn-add">➕ Přidat položku</a>
        <a href="warehouses.php" class="btn btn-back">🏢 Přehled skladů</a>
        <a href="dashboard.php" class="btn btn-back">⬅️ Zpět na Dashboard</a>
        

    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>Název položky</th>
                <th>Kód</th>
                <th>Množství</th>
                <th>Jednotka</th>
                <th>Sklad</th>
                <th>Poznámky</th>
                <th>Akce</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['item_name']) ?></td>
                        <td><?= htmlspecialchars($row['item_code']) ?></td>
                        <td><?= htmlspecialchars($row['quantity']) ?></td>
                        <td><?= htmlspecialchars($row['unit']) ?></td>
                        <td><?= htmlspecialchars($row['warehouse_name']) ?></td>
                        <td><?= htmlspecialchars($row['notes']) ?></td>
                        <td>
                            <a href="inventory-edit.php?id=<?= $row['id'] ?>" class="btn btn-edit">✏️ Upravit</a>
                            <a href="inventory-delete.php?id=<?= $row['id'] ?>" class="btn btn-delete"
                               onclick="return confirm('Opravdu chcete smazat tuto položku?');">🗑️ Smazat</a>
                            <a href="inventory-log.php?id=<?= $row['id'] ?>" class="btn btn-log">📜 Historie</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7">Žádné položky ve skladu zatím nejsou.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
