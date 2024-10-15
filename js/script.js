document.addEventListener('DOMContentLoaded', function () {
    const addRowBtn = document.getElementById('addRowBtn');
    const requestTable = document.getElementById('requestTable').getElementsByTagName('tbody')[0];

    // Add new row
    addRowBtn.addEventListener('click', function () {
        // Clone the first row
        const firstRow = requestTable.rows[0];
        const newRow = firstRow.cloneNode(true);

        // Update the row number
        const rowCount = requestTable.rows.length + 1;
        newRow.cells[0].innerText = rowCount;

        // Clear the inputs in the new row
        const selects = newRow.querySelectorAll('select');
        const inputs = newRow.querySelectorAll('input');
        selects.forEach(select => select.selectedIndex = 0); // Reset selects to default option
        inputs.forEach(input => input.value = ""); // Clear input values

        // Append the cloned row to the table
        requestTable.appendChild(newRow);

        // Attach remove event to the new row
        attachRemoveEvent(newRow);
    });

    // Attach remove event to each "Remove" button
    function attachRemoveEvent(row) {
        const removeBtn = row.querySelector('.remove-row-btn');
        removeBtn.addEventListener('click', function () {
            row.remove();
            updateRowNumbers();
        });
    }

    // Update row numbers after removal
    function updateRowNumbers() {
        const rows = requestTable.getElementsByTagName('tr');
        for (let i = 1; i < rows.length; i++) {
            rows[i].cells[0].innerText = i; // Update row index
        }
    }

    // Initial row's "Remove" button
    attachRemoveEvent(requestTable.rows[0]);
});
