<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

include 'database/db_connection.php';

// Zpracování formuláře pro přidání zákazníka
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $ico = $_POST['ico'];
    $dic = $_POST['dic'];
    $contact_term = $_POST['contact_term'];
    $contact_info = $_POST['contact_info'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $web = $_POST['web'];
    $contact_person = $_POST['contact_person'];
    $contact_person_email = $_POST['contact_person_email'];
    $contact_person_phone = $_POST['contact_person_phone'];
    $request_info = $_POST['request_info'];
    $request_term = $_POST['request_term'];
    $offer = $_POST['offer'];
    $offer_price = $_POST['offer_price'];
    $realization_term = $_POST['realization_term'];
    $notes = $_POST['notes'];
    $email_conversation = $_POST['email_conversation'];

    $sql = "INSERT INTO customers (name, ico, dic, contact_term, contact_info, address, email, web, contact_person, contact_person_email, contact_person_phone, request_info, request_term, offer, offer_price, realization_term, notes, email_conversation) VALUES ('$name', '$ico', '$dic', '$contact_term', '$contact_info', '$address', '$email', '$web', '$contact_person', '$contact_person_email', '$contact_person_phone', '$request_info', '$request_term', '$offer', '$offer_price', '$realization_term', '$notes', '$email_conversation')";

    if ($conn->query($sql) === TRUE) {
        echo "Nový zákazník byl úspěšně přidán.";
    } else {
        echo "Chyba: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Přidat zákazníka</title>
    <link rel="stylesheet" href="css/customer-add.css">
</head>
<body>
    <div class="customers-container">
        <h1>Přidat zákazníka</h1>
        <form method="post">
            <label for="name">Jméno/Název:</label>
            <input type="text" name="name" required><br>

            <label for="ico">IČO:</label>
            <input type="text" name="ico"><br>

            <label for="dic">DIČ:</label>
            <input type="text" name="dic"><br>

            <label for="contact_term">Termín kontaktu:</label>
            <input type="date" name="contact_term"><br>

            <label for="contact_info">Kontaktní informace:</label>
            <textarea name="contact_info"></textarea><br>

            <label for="address">Adresa:</label>
            <input type="text" name="address"><br>

            <label for="email">Email:</label>
            <input type="email" name="email"><br>

            <label for="web">Web:</label>
            <input type="text" name="web"><br>

            <label for="contact_person">Kontaktní osoba:</label>
            <input type="text" name="contact_person"><br>

            <label for="contact_person_email">Email kontaktní osoby:</label>
            <input type="email" name="contact_person_email"><br>

            <label for="contact_person_phone">Telefon kontaktní osoby:</label>
            <input type="text" name="contact_person_phone"><br>

            <label for="request_info">Informace o požadavku:</label>
            <textarea name="request_info"></textarea><br>

            <label for="request_term">Termín vyřízení požadavku:</label>
            <input type="date" name="request_term"><br>

            <label for="offer">Nabídka:</label>
            <textarea name="offer"></textarea><br>

            <label for="offer_price">Cena nabídky:</label>
            <input type="number" name="offer_price" step="0.01"><br>

            <label for="realization_term">Termín realizace:</label>
            <input type="date" name="realization_term"><br>

            <label for="notes">Poznámky:</label>
            <textarea name="notes"></textarea><br>

            <label for="email_conversation">Emailová konverzace:</label>
            <textarea name="email_conversation"></textarea><br>

            <button type="submit">Přidat zákazníka</button>
        </form>
    </div>
    <a class="back" href="dashboard.php"><button>Zpět</button></a>
</body>
</html>