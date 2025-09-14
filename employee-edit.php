<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

include 'database/db_connection.php';

// Načtení existujícího zaměstnance
$id = intval($_GET['id']);
$sql = "SELECT * FROM employees WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$employee = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = $_POST['first_name'];
    $last_name  = $_POST['last_name'];
    $position   = $_POST['position'];
    $email      = $_POST['email'];
    $phone      = $_POST['phone'];
    $address    = $_POST['address'];
    $hire_date  = $_POST['hire_date'];
    $salary     = $_POST['salary'];
    $notes      = $_POST['notes'];

    $sql = "UPDATE employees 
            SET first_name=?, last_name=?, position=?, email=?, phone=?, address=?, hire_date=?, salary=?, notes=?
            WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssssi", $first_name, $last_name, $position, $email, $phone, $address, $hire_date, $salary, $notes, $id);
    $stmt->execute();

    header("Location: employees.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="cs">
<head><meta charset="UTF-8"><title>Upravit zaměstnance</title>
<link rel="stylesheet" href="css/form-edit.css">

</head>
<body>
  <div class="form-container">
<h1>Upravit zaměstnance</h1>
<form method="POST">
  <div class="form-group">
    <label>Jméno: <input type="text" name="first_name" value="<?= htmlspecialchars($employee['first_name']) ?>"></label>
  </div>
  <div class="form-group">
    <label>Příjmení: <input type="text" name="last_name" value="<?= htmlspecialchars($employee['last_name']) ?>"></label>
  </div>
  <div class="form-group">
    <label>Pozice: <input type="text" name="position" value="<?= htmlspecialchars($employee['position']) ?>"></label>
  </div>
  <div class="form-group">
    <label>Email: <input type="email" name="email" value="<?= htmlspecialchars($employee['email']) ?>"></label>
  </div>
  <div class="form-group">
    <label>Telefon: <input type="text" name="phone" value="<?= htmlspecialchars($employee['phone']) ?>"></label>
  </div>
  <div class="form-group">
  <label>Adresa: <input type="text" name="address" value="<?= htmlspecialchars($employee['address']) ?>"></label><br>
  </div>
  <div class="form-group">
  <label>Datum nástupu: <input type="date" name="hire_date" value="<?= htmlspecialchars($employee['hire_date']) ?>"></label><br>
  </div>

  <div class="form-group">
  <label>Plat: <input type="number" name="salary" value="<?= htmlspecialchars($employee['salary']) ?>"></label><br>
  </div>
  <div class="form-group">
  <label>Poznámky: <textarea name="notes"><?= htmlspecialchars($employee['notes']) ?></textarea></label><br>
  <button type="submit" class="btn btn-save">💾 Uložit</button>
        <a href="orders.php" class="btn btn-back">⬅️ Zpět</a>
</form>
</body>
</html>
