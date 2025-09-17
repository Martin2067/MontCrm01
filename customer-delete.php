<?php
session_start();
include 'database/db_connection.php';

$company_id = $_SESSION['company_id'];
$id = (int)($_GET['id'] ?? 0);

$stmt = $conn->prepare("DELETE FROM customers WHERE id=? AND company_id=?");
$stmt->bind_param("ii", $id, $company_id);
$stmt->execute();

header("Location: customers.php");
exit();
