document.getElementById('apply-filters-btn').addEventListener('click', function() {
    const hours = document.getElementById('hours').value;
    
    // Fetch the filtered data
    fetch(`get_average_reviews.php?hours=${hours}`)
        .then(response => response.json())
        .then(data => {
            const jobList = document.getElementById('job-list');
            jobList.innerHTML = ''; // Clear previous listings
    
            const table = document.createElement('table');
            table.classList.add('nurse-table');
            
            // Add table headers (including a column for the "Offer Contract" button)
            const headerRow = document.createElement('tr');
            headerRow.innerHTML = `
                <th>Caregiver ID</th>
                <th>Review Score</th>
                <th>Name</th>
                <th>Offer Contract</th>
            `;
            table.appendChild(headerRow);
    
            // Add data rows (including "Offer Contract" button)
            data.data.forEach(nurse => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${nurse.cid}</td>
                    <td>${nurse.average_review_score}</td>
                    <td>${nurse.firstName} ${nurse.lastName}</td>
                `;
                
                // Create "Offer Contract" button
                const offerContractCell = document.createElement('td');
                const offerContractBtn = document.createElement('button');
                offerContractBtn.textContent = 'Offer Contract';
                
                // Add event listener to open the modal when clicked
                offerContractBtn.addEventListener('click', function() {
                    // Set the hidden fields to pass user ID and cid
                    document.getElementById('userId').value = 'USER_ID'; // Replace 'USER_ID' with actual logged-in user's ID
                    document.getElementById('cid').value = nurse.cid;
                    
                    // Show the modal
                    document.getElementById('offerContractModal').style.display = 'flex';
                });
                
                // Append the button to the new cell
                offerContractCell.appendChild(offerContractBtn);
                row.appendChild(offerContractCell);
    
                // Append the row to the table
                table.appendChild(row);
            });
    
            // Append the table to the job-list div
            jobList.appendChild(table);
        })
        .catch(error => console.error('Error fetching filtered nurses:', error));
});

// Handle modal close button
document.getElementById('closeModal').addEventListener('click', function() {
    document.getElementById('offerContractModal').style.display = 'none';
});

// Handle form submission
document.getElementById('offerContractForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent form submission

    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;
    const hours = document.getElementById('hours').value;
    const userId = document.getElementById('userId').value;
    const cid = document.getElementById('cid').value;

    // You can now send this data via AJAX to process the contract offering
    fetch('offer_contract.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            userId: userId,
            cid: cid,
            startDate: startDate,
            endDate: endDate,
            hours: hours
        })
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message); // Display response from the server
        document.getElementById('offerContractModal').style.display = 'none'; // Close the modal
    })
    .catch(error => console.error('Error offering contract:', error));
});
