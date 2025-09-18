<?php

session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

include 'database/db_connection.php';

// Získání dat z databáze
$company_id = $_SESSION['company_id'];
$sql = "SELECT 
          o.id, o.order_name, o.start_date, o.end_date, o.status, o.price,
          c.name AS customer_name
        FROM orders o
        JOIN customers c ON o.customer_id = c.id
        WHERE o.company_id = ?
        ORDER BY o.id DESC";
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
    <title>Zakázky</title>
    <link rel="stylesheet" href="css/orders.css">
</head>
<body>
    <div class="dashboard-container">
        <h1>Přehled zakázek</h1>
        <div class="button-group">
            <a href="order-add.php" class="btn">Přidat zakázku</a>
            <a href="dashboard.php" class="btn btn-danger">Zpět</a>
        </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Název zakázky</th>
                        <th>Zákazník</th>
                        <th>Termín realizace</th>
                        <th>Stav</th>
                        <th>Cena</th>
                        <th>Akce</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td data-label='ID'>" . htmlspecialchars($row['id']) . "</td>";
                            echo "<td data-label='Název zakázky'>" . htmlspecialchars($row['order_name']) . "</td>";
                            echo "<td data-label='Zákazník'>" . htmlspecialchars($row['customer_name']) . "</td>";
                            echo "<td data-label='Termín realizace'>" . htmlspecialchars($row['start_date']) . " - " . htmlspecialchars($row['end_date']) . "</td>";
                            echo "<td data-label='Stav'>" . htmlspecialchars($row['status']) . "</td>";
                            echo "<td data-label='Cena'>" . htmlspecialchars($row['price']) . " €</td>";
                            echo "<td data-label='Akce'>"
                                . "<a href='order-detail.php?id=" . $row['id'] . "' class='btn'>Detail</a> "
                                . "<a href='order-edit.php?id=" . $row['id'] . "' class='btn'>Upravit</a>"
                                . "<a href='order-delete.php?id=" . $row['id'] . "' class='btn btn-danger'>Smazat</a>"
                                . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7'>Žádné zakázky</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>