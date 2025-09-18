<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

include 'database/db_connection.php';
$company_id = $_SESSION['company_id'] ?? 1;

// Statistiky
$customers_count = $conn->query("SELECT COUNT(*) AS c FROM customers WHERE company_id=$company_id")->fetch_assoc()['c'];
$orders_count = $conn->query("SELECT COUNT(*) AS c FROM orders WHERE company_id=$company_id")->fetch_assoc()['c'];
$active_orders = $conn->query("SELECT COUNT(*) AS c FROM orders WHERE company_id=$company_id AND status='Probíhá'")->fetch_assoc()['c'];
$employees_count = $conn->query("SELECT COUNT(*) AS c FROM employees WHERE company_id=$company_id")->fetch_assoc()['c'];
$items_count = $conn->query("SELECT COUNT(*) AS c FROM inventory WHERE company_id=$company_id")->fetch_assoc()['c'];
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
            <p id="clock"></p>
        </div>

        <!-- Statistiky -->
        <!-- <div class="stats-cards">
            <div class="card"><h3>👥 Zákazníci</h3><p><?= $customers_count ?></p></div>
            <div class="card"><h3>📋 Zakázky</h3><p><?= $orders_count ?> (<?= $active_orders ?> aktivní)</p></div>
            <div class="card"><h3>👨‍💼 Zaměstnanci</h3><p><?= $employees_count ?></p></div>
            <div class="card"><h3>📦 Položky</h3><p><?= $items_count ?></p></div>
        </div> -->

        <div class="content-section">
            <div class="left-section">
                <div id="calendar"></div>
            </div>
            <div class="right-section">
                <div class="buttons-section">
                    <a href="customers.php" class="dashboard-button">Zákazníci</a>
                    <a href="orders.php" class="dashboard-button">Zakázky</a> 
                    <a href="employees.php" class="dashboard-button">Zaměstnanci</a>
                    <a href="inventory.php" class="dashboard-button-inventory">Sklad</a>
                    <a href="calc-order.php" class="dashboard-button">Kalkulace zakázky</a>
                    <a href="calc-pay.php" class="dashboard-button">Kalkulace mezd</a>
                    <a href="calc-costs.php" class="dashboard-button">Kalkulace nákladů</a>
                    <a href="ico-ares.php" class="dashboard-button">Firma dle IČO</a>
                    <a href="logout.php" class="dashboard-button-end">Odhlásit se</a>
                </div>

                <!-- Kurzy měn -->
                <div class="currency-section">
                    <h2>Aktuální kurzy měn (ČNB)</h2>
                    <?php
                    $wantedCurrencies = ["EUR", "USD", "GBP", "PLN"];
                    $rates = [];
                    $data = @file_get_contents("https://www.cnb.cz/en/financial_markets/foreign_exchange_market/exchange_rate_fixing/daily.txt");
                    if ($data) {
                        $lines = explode("\n", $data);
                        foreach ($lines as $line) {
                            $cols = explode("|", $line);
                            if (count($cols) >= 5) {
                                $code = trim($cols[3]);
                                $rate = str_replace(",", ".", $cols[4]);
                                $qty  = intval($cols[2]);
                                if (in_array($code, $wantedCurrencies)) {
                                    $rates[$code] = $rate / $qty;
                                }
                            }
                        }
                    }
                    ?>
                    <?php if (!empty($rates)): ?>
                        <table class="currency-table">
                            <thead><tr><th>Měna</th><th>Kurz (CZK)</th></tr></thead>
                            <tbody>
                            <?php foreach ($wantedCurrencies as $code): ?>
                                <?php if (isset($rates[$code])): ?>
                                    <tr><td><?= $code ?></td><td><strong><?= number_format($rates[$code], 2, ',', ' ') ?> CZK</strong></td></tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p>Nepodařilo se načíst aktuální kurzy. <a href="https://www.kurzy.cz/" target="_blank">kurzy.cz</a></p>
                    <?php endif; ?>
                </div>

                <!-- Poznámky -->
                <div class="notes-section">
                    <h2>Poznámky</h2>
                    <table id="notes-table">
                        <thead><tr><th>Datum</th><th>Poznámka</th><th>Akce</th></tr></thead>
                        <tbody></tbody>
                    </table>
                    <textarea id="note-input" placeholder="Nová poznámka"></textarea>
                    <button id="add-note-button">Přidat poznámku</button>
                </div>
            </div>
        </div>
    </div>
    <script src="js/script.js"></script>
    <script>
        // Živý hodinový čas
        function updateClock() {
            const now = new Date();
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            document.getElementById('clock').innerHTML =
                now.toLocaleDateString('cs-CZ', options) + " " + now.toLocaleTimeString('cs-CZ');
        }
        setInterval(updateClock, 1000);
        updateClock();

        // Kalendář init
        document.addEventListener('DOMContentLoaded', function() {
            let calendarEl = document.getElementById('calendar');
            let calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'cs',
                firstDay: 1
            });
            calendar.render();
        });
    </script>
</body>
</html>
