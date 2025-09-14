<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js"></script>
</head>
<body>
    <div class="dashboard-container">
        <div class="welcome-section">
            <h1>Vítejte v MontCRM 01.25</h1>
        </div>
        <div class="content-section">
            <div class="left-section">
                <div id="calendar"></div>
            </div>
            <div class="right-section">
                <div class="buttons-section">
                    <a href="customers.php" class="dashboard-button">Zákazníci</a>
                    <!-- <a href="customer-add.php" class="dashboard-button">Přidat zákazníka</a> -->
                    <a href="orders.php" class="dashboard-button">Zakázky</a> 
                    <!-- <a href="order-add.php" class="dashboard-button">Přidat zakázku</a> -->
                    <a href="employees.php" class="dashboard-button">Zaměstnanci</a>
                    <!-- <a href="employee-add.php" class="dashboard-button">Přidat zaměstnance</a>                -->
                    <a href="calc-order.php" class="dashboard-button">Kalkulace zakázky</a>
                    <a href="calc-pay.php" class="dashboard-button">Kalkulace mezd</a>
                    <a href="calc-costs.php" class="dashboard-button">Kalkulace nákladů</a>
                    <a href="ico-ares.php" class="dashboard-button">Firma dle IČO</a>
                    <a href="index.php" class="dashboard-button">Odhlásit se</a>
                </div>
                <div class="currency-section">
                    <h2>Aktuální kurzy měn</h2>
                    <a href="https://www.kurzy.cz/" target="_blank" >kurzy.cz</a>
                </div>
                <div class="notes-section">
                    <h2>Poznámky</h2>
                    <table id="notes-table">
                        <thead>
                            <tr>
                                <th>Datum</th>
                                <th>Poznámka</th>
                                <th>Akce</th>
                            </tr>
                        </thead>
                        <tbody>
                            </tbody>
                    </table>
                    <textarea id="note-input" placeholder="Nová poznámka"></textarea>
                    <button id="add-note-button">Přidat poznámku</button>
                </div>
            </div>
        </div>
    </div>
     
    <!-- <div class="footer-section">
        <p>&copy; 2024 MontCRM01. Všechna práva vyhrazena.</p> -->

    </div>
    <script src="js/script.js"></script>
</body>
</html>