<?php
session_start();

// Zruší všechny session proměnné
$_SESSION = [];

// Zničí session soubor
session_destroy();

// Přesměrování na login stránku
header("Location: index.php");
exit();
