<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

include 'database/db_connection.php';

$company_id = $_SESSION['company_id'] ?? 1;
$id = intval($_GET['id']);

$sql = "DELETE FROM warehouses WHERE id=? AND company_id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id, $company_id);

if ($stmt->execute()) {
    header("Location: warehouses.php");
    exit();
} else {
    echo "Chyba při mazání skladu: " . $conn->error;
}
