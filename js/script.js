// Kalendář (FullCalendar)
document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    if (calendarEl) {
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'cs',
            displayEventTime: false,
            displayEventEnd: false,
            height: 'auto',
            firstDay: 1 // Pondělí jako první den v týdnu
        });
        calendar.render();
    }
});

// --- Poznámky ---
const notesTable = document.querySelector('#notes-table tbody');
const noteInput = document.getElementById('note-input');
const addNoteButton = document.getElementById('add-note-button');

// Načíst poznámky
function loadNotes() {
    fetch('load_notes.php')
        .then(response => {
            if (!response.ok) throw new Error("Chyba při načítání poznámek");
            return response.json();
        })
        .then(notes => {
            notesTable.innerHTML = ''; // vyčistit tabulku
            if (notes.length === 0) {
                notesTable.innerHTML = "<tr><td colspan='3'>Žádné poznámky</td></tr>";
                return;
            }
            notes.forEach(note => {
                const row = notesTable.insertRow();
                row.innerHTML = `
                    <td>${note.date}</td>
                    <td>${note.note}</td>
                    <td><button class="delete-btn" data-id="${note.id}">🗑️ Smazat</button></td>
                `;
            });
        })
        .catch(err => {
            console.error(err);
            notesTable.innerHTML = "<tr><td colspan='3'>Chyba při načítání poznámek</td></tr>";
        });
}

// Přidat poznámku
function addNote() {
    const note = noteInput.value.trim();
    if (!note) {
        alert("Zadejte poznámku!");
        return;
    }

    fetch('add_note.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `note=${encodeURIComponent(note)}`
    })
    .then(response => response.text())
    .then(resp => {
        if (resp.trim() === "OK") {
            noteInput.value = '';
            loadNotes();
        } else {
            alert("Chyba při přidávání poznámky: " + resp);
        }
    })
    .catch(err => {
        console.error(err);
        alert("Nepodařilo se přidat poznámku.");
    });
}

// Smazat poznámku
function deleteNote(id) {
    fetch('delete_note.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `id=${id}`
    })
    .then(response => response.text())
    .then(resp => {
        if (resp.trim() === "OK") {
            loadNotes();
        } else {
            alert("Chyba při mazání poznámky: " + resp);
        }
    })
    .catch(err => {
        console.error(err);
        alert("Nepodařilo se smazat poznámku.");
    });
}

// Delegace událostí na tlačítko "Smazat"
if (notesTable) {
    notesTable.addEventListener("click", (e) => {
        if (e.target.classList.contains("delete-btn")) {
            const id = e.target.dataset.id;
            if (confirm("Opravdu chcete smazat poznámku?")) {
                deleteNote(id);
            }
        }
    });
}

// Event pro přidání poznámky
if (addNoteButton) {
    addNoteButton.addEventListener("click", addNote);
}

// Inicializace
if (notesTable) {
    loadNotes();
}
