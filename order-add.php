<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

include 'database/db_connection.php';

$company_id = $_SESSION['company_id'];

// 🔹 Zákazníci firmy
$customers = [];
$stmt = $conn->prepare("SELECT id, name FROM customers WHERE company_id=? ORDER BY name ASC");
$stmt->bind_param("i", $company_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $customers[] = $row;
}

// 🔹 Zaměstnanci firmy
$employees = [];
$stmt = $conn->prepare("SELECT id, first_name, last_name FROM employees WHERE company_id=? ORDER BY last_name ASC");
$stmt->bind_param("i", $company_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $employees[] = $row;
}

// 🔹 Uložení zakázky
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer_id = $_POST['customer_id'];
    $employee_id = !empty($_POST['employee_id']) ? $_POST['employee_id'] : null; // může být NULL
    $order_name = $_POST['order_name'];
    $description = $_POST['description'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $status = $_POST['status'];
    $price = $_POST['price'];
    $notes = $_POST['notes'];

    $sql = "INSERT INTO orders 
            (customer_id, employee_id, order_name, description, start_date, end_date, status, price, notes, company_id) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "iisssssdis",
        $customer_id,
        $employee_id,
        $order_name,
        $description,
        $start_date,
        $end_date,
        $status,
        $price,
        $notes,
        $company_id
    );

    if ($stmt->execute()) {
        header("Location: orders.php");
        exit();
    } else {
        echo "<p class='error-message'>Chyba při přidávání zakázky: " . htmlspecialchars($stmt->error) . "</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Přidat zakázku</title>
    <link rel="stylesheet" href="css/order-add.css">
</head>
<body>
<div class="form-container">
    <h1>Přidat novou zakázku</h1>
    <form method="post">

        <!-- Zákazník -->
        <div class="form-group">
            <label for="customer_id">Zákazník:</label>
            <select name="customer_id" id="customer_id" required>
                <option value="">-- Vyberte zákazníka --</option>
                <?php foreach ($customers as $c): ?>
                    <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Zaměstnanec (volitelné) -->
        <div class="form-group">
            <label for="employee_id">Přiřazený zaměstnanec:</label>
            <select name="employee_id" id="employee_id">
                <option value="">-- Nepřiřazeno --</option>
                <?php foreach ($employees as $e): ?>
                    <option value="<?= $e['id'] ?>"><?= htmlspecialchars($e['first_name'] . " " . $e['last_name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="order_name">Název zakázky:</label>
            <input type="text" name="order_name" id="order_name" required>
        </div>

        <div class="form-group">
            <label for="description">Popis:</label>
            <textarea name="description" id="description" rows="4"></textarea>
        </div>

        <div class="form-group">
            <label for="start_date">Datum zahájení:</label>
            <input type="date" name="start_date" id="start_date">
        </div>

        <div class="form-group">
            <label for="end_date">Datum ukončení:</label>
            <input type="date" name="end_date" id="end_date">
        </div>

        <div class="form-group">
            <label for="status">Stav:</label>
            <select name="status" id="status">
                <option value="Nová">Nová</option>
                <option value="Probíhá">Probíhá</option>
                <option value="Dokončená">Dokončená</option>
                <option value="Zrušená">Zrušená</option>
            </select>
        </div>

               <div class="form-group">
            <label for="price">Cena:</label>
            <input type="number" step="0.01" name="price" id="price">
        </div>

        <div class="form-group">
            <label for="notes">Poznámky:</label>
            <textarea name="notes" id="notes" rows="3"></textarea>
        </div>

        <!-- Tlačítka -->
        <button type="submit" class="submit-button">💾 Přidat zakázku</button>
        <a href="orders.php" class="back-button">⬅️ Zpět</a>
    </form>
</div>
</body>
</html>
