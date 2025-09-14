<?php
include 'database/db_connection.php';

$id = $_POST['id'];

$sql = "DELETE FROM notes WHERE id = $id";
$conn->query($sql);

$conn->close();
?>