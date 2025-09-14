// Kalendář (FullCalendar)
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'cs',
        displayEventTime: false,
        displayEventEnd: false,
        height: 'auto',
        firstDay: 1 // Pondělí jako první den v týdnu (0 = neděle)
    });
    calendar.render();
});

// Aktuální kurzy měn (příklad s API)
// fetch('https://api.exchangerate-api.com/v4/latest/CZK')
//     .then(response => response.json())
//     .then(data => {
//         const rates = data.rates;
//         const currencyRatesDiv = document.getElementById('currency-rates');
//         currencyRatesDiv.innerHTML = `
//             <p>EUR: ${rates.EUR.toFixed(2)}</p>
//             <p>USD: ${rates.USD.toFixed(2)}</p>
//             <p>GBP: ${rates.GBP.toFixed(2)}</p>
//         `;
//     });ˇ

// Počasí (příklad s API OpenWeatherMap)
// fetch('https://api.openweathermap.org/data/2.5/weather?q=Prague&appid=YOUR_API_KEY&units=metric')    

// Poznámky
const notesTable = document.getElementById('notes-table').getElementsByTagName('tbody')[0];
const noteInput = document.getElementById('note-input');
const addNoteButton = document.getElementById('add-note-button');

function loadNotes() {
    fetch('load_notes.php')
        .then(response => response.json())
        .then(notes => {
            notes.forEach(note => {
                const newRow = notesTable.insertRow();
                const dateCell = newRow.insertCell(0);
                const noteCell = newRow.insertCell(1);

                dateCell.textContent = note.date;
                noteCell.textContent = note.note;
            });
        });
}

function addNote() {
    const note = noteInput.value;
    if (note.trim() === '') return;

    fetch('add_note.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `note=${encodeURIComponent(note)}`,
    })
        .then(() => {
            notesTable.innerHTML = '';
            loadNotes();
            noteInput.value = '';
        });
}

loadNotes();
addNoteButton.addEventListener('click', addNote);

// ... (kód pro kalendář, kurzy měn a přidávání poznámek) ...

function deleteNote(id) {
    fetch('delete_note.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `id=${id}`,
    })
        .then(() => {
            notesTable.innerHTML = '';
            loadNotes();
        });
}

function loadNotes() {
    fetch('load_notes.php')
        .then(response => response.json())
        .then(notes => {
            notes.forEach(note => {
                const newRow = notesTable.insertRow();
                const dateCell = newRow.insertCell(0);
                const noteCell = newRow.insertCell(1);
                const actionCell = newRow.insertCell(2);

                dateCell.textContent = note.date;
                noteCell.textContent = note.note;
                actionCell.innerHTML = `<button onclick="deleteNote(${note.id})">Smazat</button>`;
            });
        });
}


