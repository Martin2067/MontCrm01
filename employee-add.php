<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

include 'database/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $position = $_POST['position'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $hire_date = $_POST['hire_date'];
    $salary = $_POST['salary'];
    $notes = $_POST['notes'];

    // Zabezpečení proti SQL injekci
    $first_name = $conn->real_escape_string($first_name);
    $last_name = $conn->real_escape_string($last_name);
    $position = $conn->real_escape_string($position);
    $email = $conn->real_escape_string($email);
    $phone = $conn->real_escape_string($phone);
    $address = $conn->real_escape_string($address);
    $hire_date = $conn->real_escape_string($hire_date);
    $salary = $conn->real_escape_string($salary);
    $notes = $conn->real_escape_string($notes);

    $sql = "INSERT INTO employees (first_name, last_name, position, email, phone, address, hire_date, salary, notes)
            VALUES ('$first_name', '$last_name', '$position', '$email', '$phone', '$address', '$hire_date', '$salary', '$notes')";

    if ($conn->query($sql) === TRUE) {
        echo "<p class='success-message'>Nový zaměstnanec byl úspěšně přidán!</p>";
        // Volitelně přesměrovat na přehled zaměstnanců
        // header("Location: employees.php");
        // exit();
    } else {
        echo "<p class='error-message'>Chyba při přidávání zaměstnance: " . $conn->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Přidat zaměstnance</title>
    <link rel="stylesheet" href="css/employee-add.css">
</head>
<body>
    <div class="form-container">
        <h1>Přidat nového zaměstnance</h1>
        <form method="post">
            <div class="form-group">
                <label for="first_name">Jméno:</label>
                <input type="text" name="first_name" id="first_name" required>
            </div>

            <div class="form-group">
                <label for="last_name">Příjmení:</label>
                <input type="text" name="last_name" id="last_name" required>
            </div>

            <div class="form-group">
                <label for="position">Pozice:</label>
                <input type="text" name="position" id="position">
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email">
            </div>

            <div class="form-group">
                <label for="phone">Telefon:</label>
                <input type="text" name="phone" id="phone">
            </div>

            <div class="form-group">
                <label for="address">Adresa:</label>
                <input type="text" name="address" id="address">
            </div>

            <div class="form-group">
                <label for="hire_date">Datum nástupu:</label>
                <input type="date" name="hire_date" id="hire_date">
            </div>

            <div class="form-group">
                <label for="salary">Plat (Kč):</label>
                <input type="number" name="salary" id="salary" step="0.01">
            </div>

            <div class="form-group">
                <label for="notes">Poznámky:</label>
                <textarea name="notes" id="notes" rows="5"></textarea>
            </div>

            <button type="submit" class="submit-button">Přidat zaměstnance</button>
            <a href="employees.php" class="back-button">Zpět na přehled zaměstnanců</a>
        </form>
    </div>
</body>
</html>