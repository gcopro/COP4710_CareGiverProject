<?php
session_start(); // Start the session

// Database connection settings
$servername = "localhost";
$username = "root"; // Update with your database username
$password = ""; // Update with your database password
$dbname = "testdb";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname, 3307);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare the SQL statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT MID, password FROM member WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();

        // Verify the password
        if ($password === $row['password']) {
            $_SESSION['MID'] = $row['MID']; // Store user ID in the session
            header("Location: ../FrontPage/front_page.html");
            //echo "Login successful!";
            exit();
        } else {
            $error = "Invalid email or password.";
            //echo "bad";
            header("Location: ../LoginPage/login_page.html");
        }
    } else {
        $error = "Invalid email or password.";
    }

    $stmt->close();
}
$conn->close();
?>