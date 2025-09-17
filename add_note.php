<?php
session_start();
if (!isset($_SESSION['username'])) {
    http_response_code(403);
    echo "Nejste přihlášen.";
    exit();
}

include 'database/db_connection.php';

$company_id = $_SESSION['company_id'] ?? null;
$note = trim($_POST['note'] ?? '');

if ($note !== '' && $company_id) {
    $stmt = $conn->prepare("INSERT INTO notes (date, note, company_id) VALUES (NOW(), ?, ?)");
    $stmt->bind_param("si", $note, $company_id);
    $stmt->execute();
    echo "OK";
} else {
    http_response_code(400);
    echo "Neplatný požadavek.";
}

$conn->close();
?>
