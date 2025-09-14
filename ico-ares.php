<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ARES Formulář</title>
    <style>
body { 
    font-family: Arial, sans-serif; 
    max-width: 200px; 
    margin: 40px auto; 
    padding: 20px; 
    background: #f4f4f4; 
}

form {
    background: white;
    padding: 0px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    display: flex;
    flex-direction: column;
}

h2 {
    text-align: center;
    color: #333;
}

label {
    font-weight: bold;
    margin-top: 10px;
    color: #555;
}

input {
    padding: 10px;
    margin-top: 5px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 16px;
    transition: all 0.3s ease;
}

input:focus {
    border-color: #007BFF;
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.3);
}

button {
    margin-top: 15px;
    padding: 10px;
    background: #4CAF50;
    color: white;
    font-size: 16px;
    font-weight: bold;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: all 0.3s ease;
}

button:hover {
    background: #0056b3;
}

@media (max-width: 500px) {
    body { 
        max-width: 90%;
    }

    form {
        padding: 15px;
    }

    input, button {
        font-size: 14px;
    }
}

    </style>
</head>
<body>

    <h2>Vyhledání firmy dle IČO</h2>
    <label for="ico">Zadejte IČO:</label>
    <input type="text" id="ico" placeholder="Např. 27637445" maxlength="8">
    <button onclick="getCompanyData()">Vyhledat</button>

    <h3>Výsledky</h3>
    <label>Název firmy:</label>
    <input type="text" id="nazev" readonly>
    
    <label>Adresa:</label>
    <input type="text" id="adresa" readonly>
    
    <label>DIČ:</label>
    <input type="text" id="dic" readonly>

    <a href="dashboard.php"><button>Zpět</button></a>

    <script>
        async function getCompanyData() {
            let ico = document.getElementById("ico").value.trim();
            if (!ico.match(/^\d{8}$/)) {
                alert("Zadejte platné IČO (8 číslic)");
                return;
            }

            try {
                let response = await fetch(`https://ares.gov.cz/ekonomicke-subjekty-v-be/rest/ekonomicke-subjekty/${ico}`);
                let data = await response.json();

                console.log("Odpověď API:", data);

                if (!data.obchodniJmeno) {
                    alert("Firma nebyla nalezena");
                    return;
                }

                document.getElementById("nazev").value = data.obchodniJmeno;
                document.getElementById("adresa").value = data.sidlo.textovaAdresa || "Neznámá adresa";
                document.getElementById("dic").value = data.dic || "Nezjištěno";

            } catch (error) {
                console.error("Chyba při načítání dat:", error);
                alert("Nepodařilo se získat data. Zkuste to znovu.");
            }
        }
    </script>

</body>
</html>