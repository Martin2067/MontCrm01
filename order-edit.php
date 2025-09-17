<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

include 'database/db_connection.php';

$id = intval($_GET['id']);
$company_id = $_SESSION['company_id'];

// 🔹 Načtení zakázky jen pro firmu
$sql = "SELECT * FROM orders WHERE id=? AND company_id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id, $company_id);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();

if (!$order) {
    die("Zakázka nenalezena nebo nemáte oprávnění.");
}

// 🔹 Načtení zákazníků firmy
$customers = [];
$stmt = $conn->prepare("SELECT id, name FROM customers WHERE company_id=? ORDER BY name ASC");
$stmt->bind_param("i", $company_id);
$stmt->execute();
$custRes = $stmt->get_result();
while ($row = $custRes->fetch_assoc()) {
    $customers[] = $row;
}

// 🔹 Načtení zaměstnanců firmy
$employees = [];
$stmt = $conn->prepare("SELECT id, first_name, last_name FROM employees WHERE company_id=? ORDER BY last_name ASC");
$stmt->bind_param("i", $company_id);
$stmt->execute();
$empRes = $stmt->get_result();
while ($row = $empRes->fetch_assoc()) {
    $employees[] = $row;
}

// 🔹 Uložení změn
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer_id = $_POST['customer_id'];
    $employee_id = $_POST['employee_id'] ?: null;
    $order_name = $_POST['order_name'];
    $description = $_POST['description'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $status = $_POST['status'];
    $price = $_POST['price'];
    $notes = $_POST['notes'];

    $sql = "UPDATE orders 
            SET customer_id=?, employee_id=?, order_name=?, description=?, start_date=?, end_date=?, status=?, price=?, notes=? 
            WHERE id=? AND company_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "iisssssd sii",
        $customer_id,
        $employee_id,
        $order_name,
        $description,
        $start_date,
        $end_date,
        $status,
        $price,
        $notes,
        $id,
        $company_id
    );
    $stmt->execute();

    header("Location: orders.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Upravit zakázku</title>
    <link rel="stylesheet" href="css/form-edit.css">
</head>
<body>
<div class="form-container">
    <h1>Upravit zakázku</h1>
    <form method="post">

        <div class="form-group">
            <label for="customer_id">Zákazník:</label>
            <select name="customer_id" id="customer_id" required>
                <?php foreach ($customers as $c): ?>
                    <option value="<?= $c['id'] ?>" <?= $c['id'] == $order['customer_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($c['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="employee_id">Přiřazený zaměstnanec:</label>
            <select name="employee_id" id="employee_id">
                <option value="">-- Nepřiřazeno --</option>
                <?php foreach ($employees as $e): ?>
                    <option value="<?= $e['id'] ?>" <?= $e['id'] == $order['employee_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($e['first_name'] . " " . $e['last_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="order_name">Název zakázky:</label>
            <input type="text" name="order_name" id="order_name" value="<?= htmlspecialchars($order['order_name']) ?>" required>
        </div>

        <div class="form-group">
            <label for="description">Popis zakázky:</label>
            <textarea name="description" id="description" rows="5"><?= htmlspecialchars($order['description']) ?></textarea>
        </div>

        <div class="form-group">
            <label for="start_date">Datum zahájení:</label>
            <input type="date" name="start_date" id="start_date" value="<?= htmlspecialchars($order['start_date']) ?>">
        </div>

        <div class="form-group">
            <label for="end_date">Datum ukončení:</label>
            <input type="date" name="end_date" id="end_date" value="<?= htmlspecialchars($order['end_date']) ?>">
        </div>

        <div class="form-group">
            <label for="status">Stav zakázky:</label>
            <select name="status" id="status">
                <?php foreach (["Nová", "Probíhá", "Dokončená", "Zrušená"] as $s): ?>
                    <option value="<?= $s ?>" <?= $s == $order['status'] ? 'selected' : '' ?>><?= $s ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="price">Cena zakázky:</label>
            <input type="number" step="0.01" name="price" id="price" value="<?= htmlspecialchars($order['price']) ?>">
        </div>

        <div class="form-group">
            <label for="notes">Poznámky:</label>
            <textarea name="notes" id="notes" rows="4"><?= htmlspecialchars($order['notes']) ?></textarea>
        </div>

        <button type="submit" class="btn btn-save">💾 Uložit</button>
        <a href="orders.php" class="btn btn-back">⬅️ Zpět</a>
    </form>
</div>
</body>
</html>
