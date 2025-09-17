<?php
session_start();
if (!isset($_SESSION['username'])) {
    http_response_code(403);
    exit("Nejste přihlášen.");
}

include 'database/db_connection.php';

$company_id = $_SESSION['company_id'] ?? null;

if ($company_id) {
    $stmt = $conn->prepare("SELECT id, date, note FROM notes WHERE company_id=? ORDER BY date DESC");
    $stmt->bind_param("i", $company_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $notes = [];
    while ($row = $result->fetch_assoc()) {
        $notes[] = $row;
    }

    header("Content-Type: application/json; charset=UTF-8");
    echo json_encode($notes);
} else {
    http_response_code(400);
    echo "Neplatný požadavek.";
}

$conn->close();
?>
