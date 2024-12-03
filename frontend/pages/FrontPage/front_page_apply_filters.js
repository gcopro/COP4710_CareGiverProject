// Handle the "Apply Filters" button click
document.getElementById('apply-filters-btn').addEventListener('click', function () {
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

                // Add event listener to redirect to the contract creation page
                offerContractBtn.addEventListener('click', function () {
                    const userId = 'USER_ID'; // Replace 'USER_ID' with the actual logged-in user's ID
                    const cid = nurse.cid;

                    // Redirect to the new page with query parameters
                    window.location.href = `../ContractPage/contract_page.html?userId=${userId}&cid=${cid}`;
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
