<?php
session_start();
include 'database/db_connection.php';

// Načti vstupy
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

// Připravíme dotaz (bezpečně)
$sql = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    // Ověření hesla
    if (password_verify($password, $user['password'])) {
        // Nastavení session
        $_SESSION['username']   = $user['username'];
        $_SESSION['role']       = $user['role'];
        $_SESSION['company_id'] = $user['company_id']; // 🔑 multi-tenant klíč

        header("Location: dashboard.php");
        exit();
    } else {
        echo "<p style='color:red;'>Nesprávné heslo!</p>";
    }
} else {
    echo "<p style='color:red;'>Uživatel neexistuje!</p>";
}

$conn->close();
?>
