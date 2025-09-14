<?php
$servername = "a058um.forpsi.com";
$username = "f187182";
$password = "B9Mxt4DP";
$dbname = "f187182";

// Vytvoření připojení
$conn = new mysqli($servername, $username, $password, $dbname);

// Kontrola připojení
if ($conn->connect_error) {
    die("Připojení selhalo: " . $conn->connect_error);
}
echo "Připojení k databázi bylo úspěšné!";

$conn->close();
?>