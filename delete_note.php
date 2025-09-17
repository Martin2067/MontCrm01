<?php
session_start();
if (!isset($_SESSION['username'])) {
    http_response_code(403);
    echo "Nejste přihlášen.";
    exit();
}

include 'database/db_connection.php';

$company_id = $_SESSION['company_id'] ?? null;
$id = intval($_POST['id'] ?? 0);

if ($id > 0 && $company_id) {
    $stmt = $conn->prepare("DELETE FROM notes WHERE id=? AND company_id=?");
    $stmt->bind_param("ii", $id, $company_id);
    $stmt->execute();
    echo "OK";
} else {
    http_response_code(400);
    echo "Neplatný požadavek.";
}

$conn->close();
?>
