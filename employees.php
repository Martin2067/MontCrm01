<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

include 'database/db_connection.php';

$sql = "SELECT * FROM employees ORDER BY last_name ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zaměstnanci</title>
    <link rel="stylesheet" href="css/employees.css">
</head>
<body>
    <div class="container">
        <h1>Přehled zaměstnanců</h1>
        <a href="employee-add.php" class="action-button">Přidat zaměstnance</a>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Jméno</th>
                    <th>Příjmení</th>
                    <th>Pozice</th>
                    <th>Email</th>
                    <th>Telefon</th>
                    <th>Adresa</th>
                    <th>Datum nástupu</th>
                    <th>Plat</th>
                    <th>Poznámky</th>
                    <th>Akce</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['first_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['last_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['position']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['phone']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['address']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['hire_date']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['salary']) . " Kč</td>";
                        echo "<td>" . htmlspecialchars($row['notes']) . "</td>";
                        echo "<td><a href='employee-edit.php?id=" . $row['id'] . "'>Upravit</a> | <a href='employee-delete.php?id=" . $row['id'] . "' onclick=\"return confirm('Opravdu chcete smazat tohoto zaměstnance?');\">Smazat</a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='10'>Žádní zaměstnanci nebyli nalezeni.</td></tr>";
                }
                ?>
                <a href="dashboard.php"><button>Zpět</button></a>
            </tbody>
        </table>
    </div>
</body>
</html>