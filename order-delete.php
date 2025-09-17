<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

include 'database/db_connection.php';

$company_id = $_SESSION['company_id'];
$id = (int)($_GET['id'] ?? 0);

if ($id > 0 && $company_id) {
    $stmt = $conn->prepare("DELETE FROM orders WHERE id=? AND company_id=?");
    $stmt->bind_param("ii", $id, $company_id);
    $stmt->execute();
}

header("Location: orders.php");
exit();
