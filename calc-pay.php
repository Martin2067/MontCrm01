<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/calc_pay.css">
    <title>Výpočet mezd</title>
    


</head>
<body>
    <div class="container">
        <h1>Výpočet mezd</h1>
        <div id="employee-form">
            <input type="text" id="project-name" placeholder="Název zakázky">
            <input type="text" id="employee-name" placeholder="Jméno zaměstnance">
            <input type="number" id="hourly-rate" placeholder="Hodinová sazba (€)">
            <input type="number" id="hours-worked" placeholder="Odpracované hodiny">
            <button id="add-employee">Přidat zaměstnance</button>
        </div>
        <div id="employee-list">
            <h2>Seznam zaměstnanců</h2>
            <table>
                <thead>
                    <tr>
                        <th>Název zakázky</th>
                        <th>Jméno</th>
                        <th>Hodinová sazba (€)</th>
                        <th>Odpracované hodiny</th>
                        <th>Výplata (€)</th>
                    </tr>
                </thead>
                <tbody id="employee-table-body">
                </tbody>
            </table>
        </div>
        <div id="total-cost">
            <h2>Celkové náklady: <span id="total-cost-value">0</span> €</h2>
        </div>
        <div class="button-group">
            <button id="export-pdf-button">Export do PDF</button>
            <a href="dashboard.php"><button>Zpět</button></a>
        </div>
    </div>

    

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const projectNameInput = document.getElementById('project-name');
            const employeeNameInput = document.getElementById('employee-name');
            const hourlyRateInput = document.getElementById('hourly-rate');
            const hoursWorkedInput = document.getElementById('hours-worked');
            const addEmployeeButton = document.getElementById('add-employee');
            const employeeTableBody = document.getElementById('employee-table-body');
            const totalCostValue = document.getElementById('total-cost-value');
            const exportPdfButton = document.getElementById('export-pdf-button'); // Nové

            let employees = [];

            addEmployeeButton.addEventListener('click', () => {
                const projectName = projectNameInput.value;
                const name = employeeNameInput.value;
                const hourlyRate = parseFloat(hourlyRateInput.value);
                const hoursWorked = parseFloat(hoursWorkedInput.value);

                if (projectName && name && !isNaN(hourlyRate) && !isNaN(hoursWorked)) {
                    const employee = { projectName, name, hourlyRate, hoursWorked };
                    employees.push(employee);
                    updateEmployeeList();
                    projectNameInput.value = '';
                    employeeNameInput.value = '';
                    hourlyRateInput.value = '';
                    hoursWorkedInput.value = '';
                } else {
                    alert('Vyplňte prosím všechna pole správně.');
                }
            });

            function updateEmployeeList() {
                employeeTableBody.innerHTML = '';
                let totalCost = 0;

                employees.forEach(employee => {
                    const payment = employee.hourlyRate * employee.hoursWorked;
                    totalCost += payment;

                    const row = employeeTableBody.insertRow();
                    const projectNameCell = row.insertCell(0);
                    const nameCell = row.insertCell(1);
                    const hourlyRateCell = row.insertCell(2);
                    const hoursWorkedCell = row.insertCell(3);
                    const paymentCell = row.insertCell(4);

                    projectNameCell.textContent = employee.projectName;
                    nameCell.textContent = employee.name;
                    hourlyRateCell.textContent = employee.hourlyRate.toFixed(2);
                    hoursWorkedCell.textContent = employee.hoursWorked.toFixed(2);
                    paymentCell.textContent = payment.toFixed(2);
                });

                totalCostValue.textContent = totalCost.toFixed(2);
            }

            // NOVÉ: Logika pro export do PDF
            exportPdfButton.addEventListener('click', function() {
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

                    const projectName = projectNameInput.value || 'Bez_nazvu_projektu';
                    const fileName = `Kalkulace_mezd_${projectName}.pdf`;

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
        });
    </script>
</body>
</html>