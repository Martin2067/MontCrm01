<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

include 'database/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $company_id = $_SESSION['company_id'];

    $first_name = $_POST['first_name'] ?? '';
    $last_name = $_POST['last_name'] ?? '';
    $position = $_POST['position'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $address = $_POST['address'] ?? '';
    $hire_date = $_POST['hire_date'] ?? null;
    $salary = $_POST['salary'] ?? 0;
    $notes = $_POST['notes'] ?? '';

    $sql = "INSERT INTO employees
            (company_id, first_name, last_name, position, email, phone, address, hire_date, salary, notes)
            VALUES (?,?,?,?,?,?,?,?,?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "isssssssis",   // i=int, s=string, s=string..., i=salary, s=notes
        $company_id,
        $first_name,
        $last_name,
        $position,
        $email,
        $phone,
        $address,
        $hire_date,
        $salary,
        $notes
    );

    if ($stmt->execute()) {
        echo "<p class='success-message'>Nový zaměstnanec byl úspěšně přidán.</p>";
    } else {
        echo "<p class='error-message'>Chyba: " . htmlspecialchars($conn->error) . "</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Přidat zaměstnance</title>
    <link rel="stylesheet" href="css/employee-add.css">
</head>
<body>
    <div class="form-container">
        <h1>Přidat zaměstnance</h1>
        <form method="post">
            <div class="form-group">
                <label>Jméno:</label>
                <input type="text" name="first_name" required>
            </div>

            <div class="form-group">
                <label>Příjmení:</label>
                <input type="text" name="last_name" required>
            </div>

            <div class="form-group">
                <label>Pozice:</label>
                <input type="text" name="position">
            </div>

            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email">
            </div>

            <div class="form-group">
                <label>Telefon:</label>
                <input type="text" name="phone">
            </div>

            <div class="form-group">
                <label>Adresa:</label>
                <input type="text" name="address">
            </div>

            <div class="form-group">
                <label>Datum nástupu:</label>
                <input type="date" name="hire_date">
            </div>

            <div class="form-group">
                <label>Plat:</label>
                <input type="number" name="salary" step="0.01">
            </div>

            <div class="form-group">
                <label>Poznámky:</label>
                <textarea name="notes"></textarea>
            </div>

            <button type="submit" class="submit-button">Přidat zaměstnance</button>
            <a href="employees.php" class="back-button">⬅️ Zpět</a>
        </form>
    </div>
</body>
</html>
