<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}
include 'database/db_connection.php';

$company_id = $_SESSION['company_id'];
$id = (int)($_GET['id'] ?? 0);

// načtení zákazníka
$stmt = $conn->prepare("SELECT * FROM customers WHERE id=? AND company_id=?");
$stmt->bind_param("ii", $id, $company_id);
$stmt->execute();
$customer = $stmt->get_result()->fetch_assoc();

if (!$customer) {
    die("Zákazník nenalezen.");
}

// uložení změn
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $ico = $_POST['ico'] ?? '';
    $dic = $_POST['dic'] ?? '';
    $address = $_POST['address'] ?? '';
    $email = $_POST['email'] ?? '';
    $web = $_POST['web'] ?? '';
    $contact_person = $_POST['contact_person'] ?? '';
    $contact_person_email = $_POST['contact_person_email'] ?? '';
    $contact_person_phone = $_POST['contact_person_phone'] ?? '';
    $notes = $_POST['notes'] ?? '';

    $sql = "UPDATE customers 
            SET name=?, ico=?, dic=?, address=?, email=?, web=?, 
                contact_person=?, contact_person_email=?, contact_person_phone=?, notes=? 
            WHERE id=? AND company_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "ssssssssssii",
        $name, $ico, $dic, $address, $email, $web,
        $contact_person, $contact_person_email, $contact_person_phone, $notes,
        $id, $company_id
    );

    if ($stmt->execute()) {
        header("Location: customers.php");
        exit();
    } else {
        echo "Chyba: " . htmlspecialchars($conn->error);
    }
}
?>
<!DOCTYPE html>
<html lang="cs">
<head><meta charset="UTF-8"><title>Upravit zákazníka</title></head>
<body>
<h1>Upravit zákazníka</h1>
<form method="post">
    <label>Název:</label>
    <input type="text" name="name" value="<?= htmlspecialchars($customer['name']) ?>"><br>

    <label>IČO:</label>
    <input type="text" name="ico" value="<?= htmlspecialchars($customer['ico']) ?>"><br>

    <label>DIČ:</label>
    <input type="text" name="dic" value="<?= htmlspecialchars($customer['dic']) ?>"><br>

    <label>Adresa:</label>
    <input type="text" name="address" value="<?= htmlspecialchars($customer['address']) ?>"><br>

    <label>Email:</label>
    <input type="email" name="email" value="<?= htmlspecialchars($customer['email']) ?>"><br>

    <label>Web:</label>
    <input type="text" name="web" value="<?= htmlspecialchars($customer['web']) ?>"><br>

    <label>Kontaktní osoba:</label>
    <input type="text" name="contact_person" value="<?= htmlspecialchars($customer['contact_person']) ?>"><br>

    <label>Email kontaktní osoby:</label>
    <input type="email" name="contact_person_email" value="<?= htmlspecialchars($customer['contact_person_email']) ?>"><br>

    <label>Telefon kontaktní osoby:</label>
    <input type="text" name="contact_person_phone" value="<?= htmlspecialchars($customer['contact_person_phone']) ?>"><br>

    <label>Poznámky:</label>
    <textarea name="notes"><?= htmlspecialchars($customer['notes']) ?></textarea><br>

    <button type="submit">Uložit</button>
</form>
<a href="customers.php">⬅️ Zpět</a>
</body>
</html>
