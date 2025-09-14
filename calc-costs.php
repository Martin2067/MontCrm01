<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/calc_costs.css">
    <title>Kalkulátor nákladů</title>
    
</head>
<body>
    <div class="container">
        <h2>Kalkulátor nákladů zakázky</h2>
        <div id="monteri"></div>
        <button onclick="pridatMontera()">Přidat montéra</button>
        <h3>Další náklady</h3>
        <div class="naklady">
            <label>Ubytování (€): <input type="number" id="ubytovani" value="0"></label><br>
            <label>Doprava (€): <input type="number" id="doprava" value="0"></label><br>
            <label>Další náklady (€): <input type="number" id="dalsi" value="0"></label>
        </div>
        <button onclick="spocitatNaklady()">Spočítat</button>
        <h3>Výsledky</h3>
        <p>Celkové náklady v €: <span id="celkemEur">0</span></p>
        <p>Celkové náklady v Kč: <span id="celkemCzk">0</span></p>
        <div class="button-group">
            <button id="export-pdf-button">Export do PDF</button>
            <a href="dashboard.php"><button>Zpět</button></a>
        </div>
    </div>

    

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

    <script>
        let monterId = 0;
        const kurzEurCzk = 25;

        function pridatMontera() {
            monterId++;
            const div = document.createElement('div');
            div.classList.add('monter');
            div.innerHTML = `
                <h4>Montér ${monterId}</h4>
                <label>Hodinová sazba (€): <input type="number" class="sazba" value="0"></label><br>
                <label>Počet hodin: <input type="number" class="hodiny" value="0"></label>
                <button onclick="this.parentElement.remove(); spocitatNaklady();">Odstranit</button>
            `;
            document.getElementById('monteri').appendChild(div);
        }

        function spocitatNaklady() {
            let celkemEur = 0;
            document.querySelectorAll('.monter').forEach(monter => {
                const sazba = parseFloat(monter.querySelector('.sazba').value) || 0;
                const hodiny = parseFloat(monter.querySelector('.hodiny').value) || 0;
                celkemEur += sazba * hodiny;
            });

            celkemEur += parseFloat(document.getElementById('ubytovani').value) || 0;
            celkemEur += parseFloat(document.getElementById('doprava').value) || 0;
            celkemEur += parseFloat(document.getElementById('dalsi').value) || 0;
            
            document.getElementById('celkemEur').innerText = celkemEur.toFixed(2);
            document.getElementById('celkemCzk').innerText = (celkemEur * kurzEurCzk).toFixed(2);
        }

        // Automatické přepočítání na změnu
        document.addEventListener('DOMContentLoaded', () => {
            pridatMontera();
            document.getElementById('monteri').addEventListener('change', spocitatNaklady);
            document.getElementById('ubytovani').addEventListener('change', spocitatNaklady);
            document.getElementById('doprava').addEventListener('change', spocitatNaklady);
            document.getElementById('dalsi').addEventListener('change', spocitatNaklady);
        });

        // NOVÉ: Logika pro export do PDF
        document.getElementById('export-pdf-button').addEventListener('click', function() {
            const elementToPrint = document.querySelector('.container');
            const originalBackBtn = document.querySelector('a[href="dashboard.php"]');
            
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

                const fileName = `Kalkulace_nakladu.pdf`;

                pdf.save(fileName);
            }).catch(error => {
                console.error("Chyba při generování PDF:", error);
                alert("Došlo k chybě při generování PDF. Zkontrolujte konzoli prohlížeče.");
            }).finally(() => {
                if (originalBackBtn) {
                    originalBackBtn.style.display = 'block';
                }
            });
        });
    </script>
</body>
</html>