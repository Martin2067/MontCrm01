<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}
include 'database/db_connection.php';

$company_id = $_SESSION['company_id'];
$id = (int)($_GET['id'] ?? 0);

// Načtení zákazníka
$stmt = $conn->prepare("SELECT * FROM customers WHERE id=? AND company_id=?");
$stmt->bind_param("ii", $id, $company_id);
$stmt->execute();
$customer = $stmt->get_result()->fetch_assoc();

if (!$customer) {
    die("Zákazník nenalezen nebo nemáte oprávnění.");
}
?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Detail zákazníka</title>
    <link rel="stylesheet" href="css/detail.css">
</head>
<body>
<div class="detail-container">
    <h1>Detail zákazníka</h1>

    <div class="detail-group">
        <span class="detail-label">Zákazník:</span>
        <span class="detail-value"><?= htmlspecialchars($customer['name']) ?></span>
    </div>

    <div class="detail-group">
        <span class="detail-label">IČO:</span>
        <span class="detail-value"><?= htmlspecialchars($customer['ico']) ?></span>
    </div>

    <div class="detail-group">
        <span class="detail-label">DIČ:</span>
        <span class="detail-value"><?= htmlspecialchars($customer['dic']) ?></span>
    </div>

    <div class="detail-group">
        <span class="detail-label">Termín kontaktu:</span>
        <span class="detail-value"><?= htmlspecialchars($customer['contact_term']) ?></span>
    </div>

    <div class="detail-group">
        <span class="detail-label">Kontaktní informace:</span>
        <span class="detail-value"><?= nl2br(htmlspecialchars($customer['contact_info'])) ?></span>
    </div>

    <div class="detail-group">
        <span class="detail-label">Adresa:</span>
        <span class="detail-value"><?= htmlspecialchars($customer['address']) ?></span>
    </div>

    <div class="detail-group">
        <span class="detail-label">Email:</span>
        <span class="detail-value"><?= htmlspecialchars($customer['email']) ?></span>
    </div>

    <div class="detail-group">
        <span class="detail-label">Web:</span>
        <span class="detail-value"><?= htmlspecialchars($customer['web']) ?></span>
    </div>

    <h3>Kontaktní osoba</h3>
    <div class="detail-group">
        <span class="detail-label">Jméno:</span>
        <span class="detail-value"><?= htmlspecialchars($customer['contact_person']) ?></span>
    </div>

    <div class="detail-group">
        <span class="detail-label">Email:</span>
        <span class="detail-value"><?= htmlspecialchars($customer['contact_person_email']) ?></span>
    </div>

    <div class="detail-group">
        <span class="detail-label">Telefon:</span>
        <span class="detail-value"><?= htmlspecialchars($customer['contact_person_phone']) ?></span>
    </div>

    <h3>Požadavky a nabídka</h3>
    <div class="detail-group">
        <span class="detail-label">Info o požadavku:</span>
        <span class="detail-value"><?= nl2br(htmlspecialchars($customer['request_info'])) ?></span>
    </div>

    <div class="detail-group">
        <span class="detail-label">Termín vyřízení:</span>
        <span class="detail-value"><?= htmlspecialchars($customer['request_term']) ?></span>
    </div>

    <div class="detail-group">
        <span class="detail-label">Nabídka:</span>
        <span class="detail-value"><?= nl2br(htmlspecialchars($customer['offer'])) ?></span>
    </div>

    <div class="detail-group">
        <span class="detail-label">Cena nabídky:</span>
        <span class="detail-value"><?= htmlspecialchars($customer['offer_price']) ?></span>
    </div>

    <div class="detail-group">
        <span class="detail-label">Termín realizace:</span>
        <span class="detail-value"><?= htmlspecialchars($customer['realization_term']) ?></span>
    </div>

    <h3>Další informace</h3>
    <div class="detail-group">
        <span class="detail-label">Poznámky:</span>
        <span class="detail-value"><?= nl2br(htmlspecialchars($customer['notes'])) ?></span>
    </div>

    <div class="detail-group">
        <span class="detail-label">Emailová konverzace:</span>
        <span class="detail-value"><?= nl2br(htmlspecialchars($customer['email_conversation'])) ?></span>
    </div>

    <div class="button-group">
        <a href="customers.php" class="back-button">⬅️ Zpět</a>
        <a href="customer-detail-pdf.php?id=<?= $customer['id'] ?>" class="submit-button">📄 Export do PDF</a>
    </div>
</div>
</body>
</html>
