<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

include 'database/db_connection.php';

$id = intval($_GET['id']);

// Načtení zákazníka
$sql = "SELECT * FROM customers WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$customer = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $ico = $_POST['ico'];
    $dic = $_POST['dic'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $web = $_POST['web'];
    $contact_person = $_POST['contact_person'];
    $contact_person_email = $_POST['contact_person_email'];
    $contact_person_phone = $_POST['contact_person_phone'];
    $notes = $_POST['notes'];

    $sql = "UPDATE customers SET name=?, ico=?, dic=?, address=?, email=?, web=?, contact_person=?, contact_person_email=?, contact_person_phone=?, notes=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssssi", $name, $ico, $dic, $address, $email, $web, $contact_person, $contact_person_email, $contact_person_phone, $notes, $id);
    $stmt->execute();

    header("Location: customers.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="cs">
<head>
<meta charset="UTF-8"><title>Upravit zákazníka</title>
<link rel="stylesheet" href="css/form-edit.css">

</head>
<body>
  <div class="form-container">
<h1>Upravit zákazníka</h1>
<form method="post">
  <div class="form-group">
  <label>Název: <input type="text" name="name" value="<?= htmlspecialchars($customer['name']) ?>"></label><br>
  </div>
  <div class="form-group">
  <label>IČO: <input type="text" name="ico" value="<?= htmlspecialchars($customer['ico']) ?>"></label><br>
  </div>
  <div class="form-group">
  <label>DIČ: <input type="text" name="dic" value="<?= htmlspecialchars($customer['dic']) ?>"></label><br>
  </div>
  <div class="form-group">
  <label>Adresa: <input type="text" name="address" value="<?= htmlspecialchars($customer['address']) ?>"></label><br>
  </div>
  <div class="form-group">
  <label>Email: <input type="email" name="email" value="<?= htmlspecialchars($customer['email']) ?>"></label><br>
  </div>
  <div class="form-group">
  <label>Web: <input type="text" name="web" value="<?= htmlspecialchars($customer['web']) ?>"></label><br>
  </div>
  <div class="form-group">
  <label>Kontaktní osoba: <input type="text" name="contact_person" value="<?= htmlspecialchars($customer['contact_person']) ?>"></label><br>
  </div>
  <div class="form-group">
  <label>Email osoby: <input type="email" name="contact_person_email" value="<?= htmlspecialchars($customer['contact_person_email']) ?>"></label><br>
  </div>
  <div class="form-group">
  <label>Telefon osoby: <input type="text" name="contact_person_phone" value="<?= htmlspecialchars($customer['contact_person_phone']) ?>"></label><br>
  </div>
  <div class="form-group">
  <label>Poznámky: <textarea name="notes"><?= htmlspecialchars($customer['notes']) ?></textarea></label><br>
  <button type="submit" class="btn btn-save">💾 Uložit</button>
        <a href="orders.php" class="btn btn-back">⬅️ Zpět</a>
</form>
</body>
</html>
