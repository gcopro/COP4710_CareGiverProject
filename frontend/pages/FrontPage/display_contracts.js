 document.addEventListener("DOMContentLoaded", function () {
    fetchContracts();

    function fetchContracts() {
        fetch('get_contracts.php')
            .then(response => response.json())
            .then(data => {
                console.log(data); // Debugging output

                // Check for errors in the response
                if (data.error) {
                    const contractsList = document.getElementById('contracts-in-progress');
                    contractsList.innerHTML = `<li>Error: ${data.error}</li>`;
                    return;
                }

                // Update contracts section
                const contractsList = document.getElementById('contracts-in-progress');
                contractsList.innerHTML = ''; // Clear the current list

                // Check if there are any contracts to show
                if (data.length > 0) {
    // Create the table element
    const table = document.createElement('table');
    table.style.border = '1px solid black';
    table.style.borderCollapse = 'collapse';
    table.style.width = '100%';

    // Create the table header
    const thead = document.createElement('thead');
    const headerRow = document.createElement('tr');
    ['Contract#', 'Status', 'Action'].forEach(headerText => {
        const th = document.createElement('th');
        th.textContent = headerText;
        th.style.border = '1px solid black';
        th.style.padding = '8px';
        headerRow.appendChild(th);
    });
    thead.appendChild(headerRow);
    table.appendChild(thead);

    // Create the table body
    const tbody = document.createElement('tbody');
    data.forEach(contract => {
        const row = document.createElement('tr');

        // Add Contract#
        const contractCell = document.createElement('td');
        contractCell.textContent = contract.Cno;
        contractCell.style.border = '1px solid black';
        contractCell.style.padding = '8px';
        row.appendChild(contractCell);

        // Add Status

        const statusCell = document.createElement('td');

// Map status to its corresponding display text
let statusText;
switch (contract.status) {
    case 0:
        statusText = 'Pending';
        break;
    case 1:
        statusText = 'In Progress';
        break;
    case 2:
        statusTxt = 'Completed';
    case 3:
        statusText = 'Completed';
        break;
    default:
        statusText = 'Unknown'; // Handle unexpected statuses
}

// Set the status text
statusCell.textContent = statusText;
statusCell.style.border = '1px solid black';
statusCell.style.padding = '8px';
row.appendChild(statusCell);


        // Add Action
const actionCell = document.createElement('td');

if (contract.status === 0) {
    if (contract.isCaregiver) {
        // Accept/Decline buttons
        const acceptButton = document.createElement('button');
        acceptButton.textContent = 'Accept';
        acceptButton.addEventListener('click', () => {
            alert(`Accepting Contract# ${contract.Cno}`);
        });
        const declineButton = document.createElement('button');
        declineButton.textContent = 'Decline';
        declineButton.addEventListener('click', () => {
            alert(`Declining Contract# ${contract.Cno}`);
        });
        actionCell.appendChild(acceptButton);
        actionCell.appendChild(declineButton);
    } else {
        // Delete button
        const deleteButton = document.createElement('button');
        deleteButton.textContent = 'Delete';
        deleteButton.addEventListener('click', () => {
            alert(`Deleting Contract# ${contract.Cno}`);
        });
        actionCell.appendChild(deleteButton);
    }
} else if (contract.status === 1) {
    // View button
    const viewButton = document.createElement('button');
    viewButton.textContent = 'View';
    viewButton.addEventListener('click', () => {
        alert(`Viewing details for Contract# ${contract.Cno}`);
    });
    actionCell.appendChild(viewButton);
} else if (contract.status === 2) {
    if (contract.isCaregiver) {
        // View button
        const viewButton = document.createElement('button');
        viewButton.textContent = 'View';
        viewButton.addEventListener('click', () => {
            alert(`Viewing details for Contract# ${contract.Cno}`);
        });
        actionCell.appendChild(viewButton);
    } else {
        // Review button
        const reviewButton = document.createElement('button');
        reviewButton.textContent = 'Review';
        reviewButton.addEventListener('click', () => {
            alert(`Reviewing Contract# ${contract.Cno}`);
        });
        actionCell.appendChild(reviewButton);
    }
} else if (contract.status === 3) {
    // Remove button
    const removeButton = document.createElement('button');
    removeButton.textContent = 'Remove';
    removeButton.addEventListener('click', () => {
        alert(`Removing Contract# ${contract.Cno}`);
    });
    actionCell.appendChild(removeButton);
}

// Add styles and append to row
actionCell.style.border = '1px solid black';
actionCell.style.padding = '8px';
row.appendChild(actionCell);

tbody.appendChild(row);

    });
    table.appendChild(tbody);

    // Append the table to the contractsList container or another element
    contractsList.appendChild(table);
}
 else {
                    contractsList.innerHTML = '<li>No contracts available</li>';
                }
            })
            .catch(error => {
                console.error('Error fetching contracts:', error);
            });
    }
});