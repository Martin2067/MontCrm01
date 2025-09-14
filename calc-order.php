<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/calc_order.css">
    <title>Kalkulátor zakázek</title>
    
</head>
<body>
    <div class="container">
        <h1>Kalkulátor zakázek</h1>
        <div id="zakazka-info" class="employee-form">
            <input type="text" id="cislo-zakazky" placeholder="Číslo zakázky">
            <input type="text" id="nazev-zakazky" placeholder="Název zakázky">
            <input type="text" id="misto-zakazky" placeholder="Místo">
        </div>
        <div id="ukony">
            <h2>Úkony</h2>
            <table id="ukony-tabulka">
                <thead>
                    <tr>
                        <th>Úkon</th>
                        <th>Počet ks</th>
                        <th>Počet min/ks</th>
                        <th>Celkový čas (min)</th>
                        <th>Celkový čas (hod)</th>
                        <th>Akce</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <button id="pridat-ukon">Přidat úkon</button>
        </div>
        <div id="vypocet">
            <h2>Výpočet</h2>
            <input type="number" id="hodinova-sazba" placeholder="Hodinová sazba (€)" value="30">
            <input type="number" id="rezerva" placeholder="Rezerva (%)" value="15">
            <p>Celkový čas (min): <span id="celkovy-cas-min">0</span></p>
            <p>Celkový čas (hod): <span id="celkovy-cas-hod">0</span></p>
            <p>Celková cena (€): <span id="celkova-cena">0</span></p>
        </div>
        ¨<div class="button-group">
            <button id="export-pdf-button">Export do PDF</button>
            <a href="dashboard.php"><button>Zpět</button></a>
        </div>
    </div>

    

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const ukonyTabulka = document.getElementById('ukony-tabulka').getElementsByTagName('tbody')[0];
        const pridatUkonButton = document.getElementById('pridat-ukon');
        const celkovyCasMinSpan = document.getElementById('celkovy-cas-min');
        const celkovyCasHodSpan = document.getElementById('celkovy-cas-hod');
        const hodinovaSazbaInput = document.getElementById('hodinova-sazba');
        const rezervaInput = document.getElementById('rezerva');
        const celkovaCenaSpan = document.getElementById('celkova-cena');

        // NOVÉ: Tlačítko pro export do PDF
        const exportPdfButton = document.getElementById('export-pdf-button');

        function aktualizovatVypocet() {
            let celkovyCasMin = 0;
            let celkovyCasHod = 0;

            const radky = ukonyTabulka.getElementsByTagName('tr');
            for (let i = 0; i < radky.length; i++) {
                const pocetKs = parseInt(radky[i].querySelector('.pocet-ks').value) || 0;
                const pocetMinKs = parseInt(radky[i].querySelector('.pocet-min-ks').value) || 0;
                const casMin = pocetKs * pocetMinKs;
                const casHod = casMin / 60;

                radky[i].querySelector('.cas-min').textContent = casMin;
                radky[i].querySelector('.cas-hod').textContent = casHod.toFixed(2);

                celkovyCasMin += casMin;
                celkovyCasHod += casHod;
            }

            celkovyCasMinSpan.textContent = celkovyCasMin;
            celkovyCasHodSpan.textContent = celkovyCasHod.toFixed(2);

            const hodinovaSazba = parseFloat(hodinovaSazbaInput.value) || 0;
            const rezerva = parseFloat(rezervaInput.value) || 0;
            let celkovaCena = celkovyCasHod * hodinovaSazba;
            celkovaCena = celkovaCena + (celkovaCena * (rezerva / 100));

            celkovaCenaSpan.textContent = celkovaCena.toFixed(2);
        }

        function pridatUkonRadek() {
            const radek = ukonyTabulka.insertRow();
            radek.innerHTML = `
                <td><input type="text" class="ukon"></td>
                <td><input type="number" class="pocet-ks" value="1"></td>
                <td><input type="number" class="pocet-min-ks" value="60"></td>
                <td class="cas-min">60</td>
                <td class="cas-hod">1.00</td>
                <td><button class="odebrat-ukon">Odebrat</button></td>
            `;

            radek.querySelectorAll('input').forEach(input => {
                input.addEventListener('change', aktualizovatVypocet);
            });

            radek.querySelector('.odebrat-ukon').addEventListener('click', function() {
                ukonyTabulka.removeChild(radek);
                aktualizovatVypocet();
            });

            aktualizovatVypocet();
        }

        pridatUkonButton.addEventListener('click', pridatUkonRadek);
        hodinovaSazbaInput.addEventListener('change', aktualizovatVypocet);
        rezervaInput.addEventListener('change', aktualizovatVypocet);

        pridatUkonRadek(); // Přidá první řádek při načtení stránky

        // NOVÉ: Logika pro export do PDF
        exportPdfButton.addEventListener('click', function() {
            const elementToPrint = document.querySelector('.container');
            const originalBackBtn = document.querySelector('a[href="dashboard.php"]');

            // Dočasně skryjeme tlačítko "Zpět", aby se neobjevilo v PDF
            if (originalBackBtn) {
                originalBackBtn.style.display = 'none';
            }

            html2canvas(elementToPrint, { scale: 2 }).then(canvas => {
                const imgData = canvas.toDataURL('image/png');
                const { jsPDF } = window.jspdf;
                const pdf = new jsPDF('p', 'mm', 'a4');
                const imgWidth = 210;
                const pageHeight = 297;
                const imgHeight = canvas.height * imgWidth / canvas.width;
                let heightLeft = imgHeight;
                let position = 0;

                pdf.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
                heightLeft -= pageHeight;

                while (heightLeft >= 0) {
                    position = heightLeft - imgHeight;
                    pdf.addPage();
                    pdf.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
                    heightLeft -= pageHeight;
                }

                // Získání informací o zakázce pro název souboru
                const cisloZakazky = document.getElementById('cislo-zakazky').value || 'Bez_cisla';
                const nazevZakazky = document.getElementById('nazev-zakazky').value || 'Bez_nazvu';
                const fileName = `Kalkulace_zakazky_${cisloZakazky}_${nazevZakazky}.pdf`;

                pdf.save(fileName);
            }).catch(error => {
                console.error("Chyba při generování PDF:", error);
                alert("Došlo k chybě při generování PDF. Zkontrolujte konzoli prohlížeče.");
            }).finally(() => {
                // Po dokončení (nebo chybě) opět zobrazíme tlačítko "Zpět"
                if (originalBackBtn) {
                    originalBackBtn.style.display = 'block';
                }
            });
        });
    });
    </script>
</body>
</html>