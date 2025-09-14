<?php
$password = "martin123"; // Nahraďte "heslo_uzivatele" skutečným heslem

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

echo "Hash hesla: " . $hashed_password;
?>