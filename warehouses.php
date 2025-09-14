<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

include 'database/db_connection.php';

$company_id = $_SESSION['company_id'] ?? 1;

// Načteme sklady firmy
$sql = "SELECT w.id, w.name, w.address, 
               COUNT(i.id) AS items_count
        FROM warehouses w
        LEFT JOIN inventory i ON w.id = i.warehouse_id
        WHERE w.company_id = ?
        GROUP BY w.id
        ORDER BY w.name ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $company_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Přehled skladů</title>
    <link rel="stylesheet" href="css/inventory.css">
</head>
<body>
<div class="container">
    <h1>🏢 Přehled skladů</h1>

    <div class="actions">
        <a href="warehouse-add.php" class="btn btn-add">➕ Přidat sklad</a>
        <a href="dashboard.php" class="btn btn-back">⬅️ Zpět na Dashboard</a>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>Název skladu</th>
                <th>Adresa</th>
                <th>Počet položek</th>
                <th>Akce</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['address']) ?></td>
                        <td><?= $row['items_count'] ?></td>
                        <td>
                            <a href="warehouse-edit.php?id=<?= $row['id'] ?>" class="btn btn-edit">✏️ Upravit</a>
                            <a href="warehouse-delete.php?id=<?= $row['id'] ?>" class="btn btn-delete"
                               onclick="return confirm('Opravdu chcete smazat tento sklad? (Položky ve skladu budou také odstraněny)');">🗑️ Smazat</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="4">Žádné sklady zatím nejsou.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
