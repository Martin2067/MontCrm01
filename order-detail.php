<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

include 'database/db_connection.php';

$company_id = $_SESSION['company_id'];
$id = (int)($_GET['id'] ?? 0);

// Načteme zakázku s vazbou na zákazníka a zaměstnance
$sql = "SELECT 
            o.*, 
            c.name AS customer_name,
            e.first_name, e.last_name
        FROM orders o
        JOIN customers c ON o.customer_id = c.id
        LEFT JOIN employees e ON o.employee_id = e.id
        WHERE o.id = ? AND o.company_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id, $company_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

if (!$order) {
    die("Zakázka nenalezena nebo nemáte oprávnění.");
}
?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Detail zakázky</title>
    <link rel="stylesheet" href="css/detail.css">
</head>
<body>
<div class="detail-container">
    <h1>Detail zakázky</h1>

    <div class="detail-group">
        <span class="detail-label">Název zakázky:</span>
        <span class="detail-value"><?= htmlspecialchars($order['order_name']) ?></span>
    </div>

    <div class="detail-group">
        <span class="detail-label">Zákazník:</span>
        <span class="detail-value"><?= htmlspecialchars($order['customer_name']) ?></span>
    </div>

    <div class="detail-group">
        <span class="detail-label">Přiřazený zaměstnanec:</span>
        <span class="detail-value">
            <?= $order['first_name'] ? htmlspecialchars($order['first_name'] . " " . $order['last_name']) : "Nepřiřazeno" ?>
        </span>
    </div>

    <div class="detail-group">
        <span class="detail-label">Popis:</span>
        <span class="detail-value"><?= nl2br(htmlspecialchars($order['description'])) ?></span>
    </div>

    <div class="detail-group">
        <span class="detail-label">Datum zahájení:</span>
        <span class="detail-value"><?= htmlspecialchars($order['start_date']) ?></span>
    </div>

    <div class="detail-group">
        <span class="detail-label">Datum ukončení:</span>
        <span class="detail-value"><?= htmlspecialchars($order['end_date']) ?></span>
    </div>

    <div class="detail-group">
        <span class="detail-label">Stav:</span>
        <span class="detail-value"><?= htmlspecialchars($order['status']) ?></span>
    </div>

    <div class="detail-group">
        <span class="detail-label">Cena:</span>
        <span class="detail-value"><?= htmlspecialchars($order['price']) ?> €</span>
    </div>

    <div class="detail-group">
        <span class="detail-label">Poznámky:</span>
        <span class="detail-value"><?= nl2br(htmlspecialchars($order['notes'])) ?></span>
    </div>

    <div class="button-group">
        <a href="orders.php" class="back-button">⬅️ Zpět</a>
        <a href="order-detail-pdf.php?id=<?= $order['id'] ?>" class="submit-button">📄 Export do PDF</a>
    </div>
</div>
</body>
</html>
