<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}
include 'database/db_connection.php';

$company_id = $_SESSION['company_id'];
$id = (int)($_GET['id'] ?? 0);

// načtení zaměstnance
$stmt = $conn->prepare("SELECT * FROM employees WHERE id=? AND company_id=?");
$stmt->bind_param("ii", $id, $company_id);
$stmt->execute();
$employee = $stmt->get_result()->fetch_assoc();

if (!$employee) {
    die("Zaměstnanec nenalezen.");
}

// uložení změn
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = $_POST['first_name'] ?? '';
    $last_name = $_POST['last_name'] ?? '';
    $position = $_POST['position'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $address = $_POST['address'] ?? '';
    $hire_date = $_POST['hire_date'] ?? null;
    $salary = $_POST['salary'] ?? 0;
    $notes = $_POST['notes'] ?? '';

    $sql = "UPDATE employees 
            SET first_name=?, last_name=?, position=?, email=?, phone=?, address=?, hire_date=?, salary=?, notes=? 
            WHERE id=? AND company_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "ssssssssiii",
        $first_name, $last_name, $position, $email, $phone, $address, $hire_date, $salary, $notes,
        $id, $company_id
    );

    if ($stmt->execute()) {
        header("Location: employees.php");
        exit();
    } else {
        echo "Chyba: " . htmlspecialchars($conn->error);
    }
}
?>
<!DOCTYPE html>
<html lang="cs">
<head><meta charset="UTF-8"><title>Upravit zaměstnance</title></head>
<body>
<h1>Upravit zaměstnance</h1>
<form method="post">
    <label>Jméno:</label>
    <input type="text" name="first_name" value="<?= htmlspecialchars($employee['first_name']) ?>" required><br>

    <label>Příjmení:</label>
    <input type="text" name="last_name" value="<?= htmlspecialchars($employee['last_name']) ?>" required><br>

    <label>Pozice:</label>
    <input type="text" name="position" value="<?= htmlspecialchars($employee['position']) ?>"><br>

    <label>Email:</label>
    <input type="email" name="email" value="<?= htmlspecialchars($employee['email']) ?>"><br>

    <label>Telefon:</label>
    <input type="text" name="phone" value="<?= htmlspecialchars($employee['phone']) ?>"><br>

    <label>Adresa:</label>
    <input type="text" name="address" value="<?= htmlspecialchars($employee['address']) ?>"><br>

    <label>Datum nástupu:</label>
    <input type="date" name="hire_date" value="<?= htmlspecialchars($employee['hire_date']) ?>"><br>

    <label>Plat:</label>
    <input type="number" step="0.01" name="salary" value="<?= htmlspecialchars($employee['salary']) ?>"><br>

    <label>Poznámky:</label>
    <textarea name="notes"><?= htmlspecialchars($employee['notes']) ?></textarea><br>

    <button type="submit">Uložit</button>
</form>
<a href="employees.php">⬅️ Zpět</a>
</body>
</html>
