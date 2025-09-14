<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

include 'database/db_connection.php';

$customers = [];
$employees = [];

// Zákazníci
$customer_result = $conn->query("SELECT id, name FROM customers ORDER BY name ASC");
if ($customer_result->num_rows > 0) {
    while($row = $customer_result->fetch_assoc()) {
        $customers[] = $row;
    }
}

// Zaměstnanci (jen z aktuální firmy, pokud máš $_SESSION['company_id'])
$company_id = $_SESSION['company_id'] ?? 1; // fallback 1
$stmt = $conn->prepare("SELECT id, first_name, last_name FROM employees WHERE company_id = ? ORDER BY last_name ASC");
$stmt->bind_param("i", $company_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $employees[] = $row;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer_id = $_POST['customer_id'];
    $employee_id = $_POST['employee_id'] ?: "NULL"; // může být prázdné
    $order_name = $_POST['order_name'];
    $description = $_POST['description'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $status = $_POST['status'];
    $price = $_POST['price'];
    $notes = $_POST['notes'];

    // Zabezpečení proti SQL injekci
    $customer_id = $conn->real_escape_string($customer_id);
    $order_name = $conn->real_escape_string($order_name);
    $description = $conn->real_escape_string($description);
    $start_date = $conn->real_escape_string($start_date);
    $end_date = $conn->real_escape_string($end_date);
    $status = $conn->real_escape_string($status);
    $price = $conn->real_escape_string($price);
    $notes = $conn->real_escape_string($notes);

    $sql = "INSERT INTO orders (customer_id, employee_id, order_name, description, start_date, end_date, status, price, notes)
            VALUES ('$customer_id', " . ($employee_id === "NULL" ? "NULL" : "'$employee_id'") . ", '$order_name', '$description', '$start_date', '$end_date', '$status', '$price', '$notes')";

    if ($conn->query($sql) === TRUE) {
        echo "<p class='success-message'>Nová zakázka byla úspěšně přidána!</p>";
        // header("Location: orders.php"); exit();
    } else {
        echo "<p class='error-message'>Chyba při přidávání zakázky: " . $conn->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Přidat zakázku</title>
    <link rel="stylesheet" href="css/order-add.css">
</head>
<body>
    <div class="form-container">
        <h1>Přidat novou zakázku</h1>
        <form method="post">
            <div class="form-group">
                <label for="customer_id">Zákazník:</label>
                <select name="customer_id" id="customer_id" required>
                    <?php if (empty($customers)): ?>
                        <option value="">Nejsou k dispozici žádní zákazníci. Prosím, nejprve přidejte zákazníka.</option>
                    <?php else: ?>
                        <option value="">Vyberte zákazníka...</option>
                        <?php foreach ($customers as $customer): ?>
                            <option value="<?php echo htmlspecialchars($customer['id']); ?>">
                                <?php echo htmlspecialchars($customer['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="employee_id">Přiřazený zaměstnanec:</label>
                <select name="employee_id" id="employee_id">
                    <option value="">-- Nepřiřazeno --</option>
                    <?php foreach ($employees as $emp): ?>
                        <option value="<?php echo $emp['id']; ?>">
                            <?php echo htmlspecialchars($emp['first_name'] . " " . $emp['last_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="order_name">Název zakázky:</label>
                <input type="text" name="order_name" id="order_name" required>
            </div>

            <div class="form-group">
                <label for="description">Popis zakázky:</label>
                <textarea name="description" id="description" rows="5"></textarea>
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
                <label for="status">Stav zakázky:</label>
                <select name="status" id="status">
                    <option value="Nová">Nová</option>
                    <option value="Probíhá">Probíhá</option>
                    <option value="Dokončená">Dokončená</option>
                    <option value="Zrušená">Zrušená</option>
                </select>
            </div>

            <div class="form-group">
                <label for="price">Cena zakázky:</label>
                <input type="number" name="price" id="price" step="0.01">
            </div>

            <div class="form-group">
                <label for="notes">Poznámky:</label>
                <textarea name="notes" id="notes" rows="5"></textarea>
            </div>

            <button type="submit" class="submit-button">Přidat zakázku</button>
            <a href="orders.php" class="back-button">Zpět na přehled zakázek</a>
        </form>
    </div>
</body>
</html>
