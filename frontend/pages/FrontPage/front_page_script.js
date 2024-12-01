document.addEventListener("DOMContentLoaded", function() {
    // Fetch user data
    fetch('get_user_info.php') // Update with the correct path to your PHP script
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                console.log(data.error); // Handle error if the user is not logged in or no data is found
            } else {
                // Populate the user data in the HTML
                document.getElementById("user-name").textContent = `${data.firstName} ${data.lastName}`;
                document.getElementById("user-email").textContent = data.email;
                document.getElementById("cd-balance").textContent = data.CDBalance;
                document.getElementById("welcome-name").textContent = `${data.firstName} ${data.lastName}`;
            }
        })
        .catch(error => console.error('Error fetching user data:', error));
});
