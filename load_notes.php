<?php
include 'database/db_connection.php';

$sql = "SELECT * FROM notes ORDER BY date DESC";
$result = $conn->query($sql);

$notes = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $notes[] = $row;
    }
}

echo json_encode($notes);
$conn->close();
?>
