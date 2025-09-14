<?php


session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

include 'database/db_connection.php';

// Získání dat z databáze
$sql = "SELECT * FROM customers";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zákazníci</title>
    <link rel="stylesheet" href="css/customers.css">
</head>
<body>
    <div class="dashboard-container">
        <h1>Přehled zákazníků</h1>
        <div class="button-group">
            <a href="customer-add.php" class="btn">Přidat zákazníka</a>
            <a href="dashboard.php" class="btn btn-danger">Zpět</a>
        </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Zákazník</th>
                        <th>Termín kontaktu</th>
                        <th>Kontaktní informace</th>
                        <th>Adresa</th>
                        <th>Email</th>
                        <th>Web</th>
                        <th>Kontaktní osoba</th>
                        <th>Email (osoba)</th>
                        <th>Telefon (osoba)</th>
                        <th>Info o požadavku</th>
                        <th>Termín vyřízení</th>
                        <th>Nabídka</th>
                        <th>Cena nabídky</th>
                        <th>Termín realizace</th>
                        <th>Poznámky</th>
                        <th>Emailová konverzace</th>
                        <th>Akce</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td data-label='Zákazník'>" . htmlspecialchars($row['name']) . "<br>IČO: " . htmlspecialchars($row['ico']) . "<br>DIČ: " . htmlspecialchars($row['dic']) . "</td>";
                            echo "<td data-label='Termín kontaktu'>" . htmlspecialchars($row['contact_term']) . "</td>";
                            echo "<td data-label='Kontaktní info'>" . htmlspecialchars($row['contact_info']) . "</td>";
                            echo "<td data-label='Adresa'>" . htmlspecialchars($row['address']) . "</td>";
                            echo "<td data-label='Email'>" . htmlspecialchars($row['email']) . "</td>";
                            echo "<td data-label='Web'>" . htmlspecialchars($row['web']) . "</td>";
                            echo "<td data-label='Kontaktní osoba'>" . htmlspecialchars($row['contact_person']) . "</td>";
                            echo "<td data-label='Email (osoba)'>" . htmlspecialchars($row['contact_person_email']) . "</td>";
                            echo "<td data-label='Telefon (osoba)'>" . htmlspecialchars($row['contact_person_phone']) . "</td>";
                            echo "<td data-label='Info o požadavku'>" . htmlspecialchars($row['request_info']) . "</td>";
                            echo "<td data-label='Termín vyřízení'>" . htmlspecialchars($row['request_term']) . "</td>";
                            echo "<td data-label='Nabídka'>" . htmlspecialchars($row['offer']) . "</td>";
                            echo "<td data-label='Cena nabídky'>" . htmlspecialchars($row['offer_price']) . "</td>";
                            echo "<td data-label='Termín realizace'>" . htmlspecialchars($row['realization_term']) . "</td>";
                            echo "<td data-label='Poznámky'>" . htmlspecialchars($row['notes']) . "</td>";
                            echo "<td data-label='Emailová konverzace'>" . htmlspecialchars($row['email_conversation']) . "</td>";
                            echo "<td data-label='Akce'>"
                                . "<a href='customer-edit.php?id=" . $row['id'] . "' class='btn'>Upravit</a>"
                                . "<a href='customer-delete.php?id=" . $row['id'] . "' class='btn btn-danger'>Smazat</a>"
                                . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='17'>Žádní zákazníci</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>