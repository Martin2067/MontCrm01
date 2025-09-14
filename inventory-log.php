<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

include 'database/db_connection.php';

$item_id = intval($_GET['id']);

$sql = "SELECT l.*, u.username 
        FROM inventory_log l
        LEFT JOIN users u ON l.user_id = u.id
        WHERE l.inventory_id=?
        ORDER BY l.created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $item_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Historie pohybů</title>
    <link rel="stylesheet" href="css/inventory.css">
</head>
<body>
<div class="container">
    <h1>📜 Historie pohybů položky</h1>

    <a href="inventory.php" class="btn btn-back">⬅️ Zpět</a>

    <table class="data-table">
        <thead>
            <tr>
                <th>Datum</th>
                <th>Uživatel</th>
                <th>Změna množství</th>
                <th>Poznámka</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['created_at'] ?></td>
                        <td><?= htmlspecialchars($row['username'] ?? "Neznámý") ?></td>
                        <td style="color:<?= $row['change_qty'] > 0 ? 'green' : 'red' ?>">
                            <?= $row['change_qty'] ?>
                        </td>
                        <td><?= htmlspecialchars($row['note']) ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="4">Žádné záznamy o pohybech.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
