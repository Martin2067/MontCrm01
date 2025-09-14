<?php
session_start();
include 'database/db_connection.php';

$username = $_POST['username'];
$password = $_POST['password'];

$sql = "SELECT * FROM users WHERE username = '$username' AND role = 'admin'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if (password_verify($password, $row['password'])) {
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $row['role'];
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Nesprávné heslo!";
    }
} else {
    echo "Nesprávné uživatelské jméno nebo heslo!";
}

$conn->close();
?>