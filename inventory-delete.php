<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

include 'database/db_connection.php';

$company_id = $_SESSION['company_id'] ?? 1;
$id = intval($_GET['id']);

// smažeme pouze položku, která patří do firmy přihlášeného uživatele
$sql = "DELETE FROM inventory WHERE id=? AND company_id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id, $company_id);

if ($stmt->execute()) {
    header("Location: inventory.php");
    exit();
} else {
    echo "Chyba při mazání položky: " . $conn->error;
}
