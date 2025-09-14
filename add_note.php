<?php
include 'database/db_connection.php';

$note = $_POST['note'];
$date = date('Y-m-d');

$sql = "INSERT INTO notes (date, note) VALUES ('$date', '$note')";
$conn->query($sql);

$conn->close();
?>