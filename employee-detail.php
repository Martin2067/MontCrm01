<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

include 'database/db_connection.php';

$company_id = $_SESSION['company_id'];
$id = (int)($_GET['id'] ?? 0);

// Načteme zaměstnance
$stmt = $conn->prepare("SELECT * FROM employees WHERE id=? AND company_id=?");
$stmt->bind_param("ii", $id, $company_id);
$stmt->execute();
$employee = $stmt->get_result()->fetch_assoc();

if (!$employee) {
    die("Zaměstnanec nenalezen nebo nemáte oprávnění.");
}
?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Detail zaměstnance</title>
    <link rel="stylesheet" href="css/detail.css">
</head>
<body>
<div class="detail-container">
    <h1>Detail zaměstnance</h1>

    <div class="detail-group">
        <span class="detail-label">Jméno:</span>
        <span class="detail-value"><?= htmlspecialchars($employee['first_name']) ?></span>
    </div>

    <div class="detail-group">
        <span class="detail-label">Příjmení:</span>
        <span class="detail-value"><?= htmlspecialchars($employee['last_name']) ?></span>
    </div>

    <div class="detail-group">
        <span class="detail-label">Pozice:</span>
        <span class="detail-value"><?= htmlspecialchars($employee['position']) ?></span>
    </div>

    <div class="detail-group">
        <span class="detail-label">Email:</span>
        <span class="detail-value"><?= htmlspecialchars($employee['email']) ?></span>
    </div>

    <div class="detail-group">
        <span class="detail-label">Telefon:</span>
        <span class="detail-value"><?= htmlspecialchars($employee['phone']) ?></span>
    </div>

    <div class="detail-group">
        <span class="detail-label">Adresa:</span>
        <span class="detail-value"><?= htmlspecialchars($employee['address']) ?></span>
    </div>

    <div class="detail-group">
        <span class="detail-label">Datum nástupu:</span>
        <span class="detail-value"><?= htmlspecialchars($employee['hire_date']) ?></span>
    </div>

    <div class="detail-group">
        <span class="detail-label">Plat:</span>
        <span class="detail-value"><?= htmlspecialchars($employee['salary']) ?> Kč</span>
    </div>

    <div class="detail-group">
        <span class="detail-label">Poznámky:</span>
        <span class="detail-value"><?= nl2br(htmlspecialchars($employee['notes'])) ?></span>
    </div>

    <div class="button-group">
        <a href="employees.php" class="back-button">⬅️ Zpět</a>
        <a href="employee-detail-pdf.php?id=<?= $employee['id'] ?>" class="submit-button">📄 Export do PDF</a>
    </div>
</div>
</body>
</html>
