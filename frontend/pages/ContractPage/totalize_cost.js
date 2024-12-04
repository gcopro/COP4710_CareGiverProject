document.addEventListener('DOMContentLoaded', () => {
    const hoursInput = document.getElementById('hoursPerWeek');
    const startDateInput = document.getElementById('startDate');
    const endDateInput = document.getElementById('endDate');
    const calculateButton = document.getElementById('calculateCostButton');  // Get the button by ID
    const totalCostDisplay = document.getElementById('totalCostDisplay');
    
    const hourlyRate = 30; // Set hourly rate (for calculation purposes)
    
    // Function to calculate total cost
    const updateTotalCost = () => {
        const hoursPerWeek = parseFloat(hoursInput.value) || 0;
        const startDate = new Date(startDateInput.value);
        const endDate = new Date(endDateInput.value);
        
        // Check if the dates are valid
        if (isNaN(startDate.getTime()) || isNaN(endDate.getTime())) {
            totalCostDisplay.textContent = "Please select valid start and end dates.";
            return;
        }

        // Calculate the difference between the start and end dates in days
        const timeDiff = endDate - startDate;
        if (timeDiff <= 0) {
            totalCostDisplay.textContent = "End date must be later than the start date.";
            return;
        }

        // Calculate the duration in weeks (7 days in a week)
        const totalWeeks = Math.floor(timeDiff / (1000 * 3600 * 24 * 7));
        
        // Total hours over the entire contract duration
        const totalHours = totalWeeks * hoursPerWeek;
        
        // Calculate total cost
        const totalCost = totalHours * hourlyRate;
        
        // Display the total cost
        totalCostDisplay.textContent = `CD ${totalCost.toFixed(2)} for ${totalWeeks} week(s)`;
    };

    // Event listener for calculate button
    calculateButton.addEventListener('click', updateTotalCost);
});
