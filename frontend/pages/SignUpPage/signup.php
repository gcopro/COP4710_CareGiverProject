<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "testdb";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname, 3307);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    // Optional caregiver fields
    $isCaregiver = isset($_POST['isCaregiver']) ? true : false;
    $ableHours = $isCaregiver ? $_POST['ableHours'] : null;
    $limitHours = $isCaregiver ? $_POST['limitHours'] : null;
    $rate = 30; // Static hourly rate for caregivers

    // Ensure all required fields are provided
    if (empty($firstName) || empty($lastName) || empty($email) || empty($password) || empty($confirmPassword)) {
        echo "Fields required";
        exit();
    }

    // Ensure passwords match
    if ($password != $confirmPassword) {
        echo "Passwords do not match";
        exit();
    }

    // Secure password hashing
    $hashPass = password_hash($password, PASSWORD_BCRYPT);

    // Insert member data
    $sqlMember = "INSERT INTO member(firstName, lastName, email, password) VALUES(?, ?, ?, ?)";
    $stmtMember = $conn->prepare($sqlMember);
    if (!$stmtMember) {
        echo "Failed to prepare member statement: " . $conn->error;
        exit();
    }

    $stmtMember->bind_param("ssss", $firstName, $lastName, $email, $password);

    if ($stmtMember->execute()) {
        $memberId = $stmtMember->insert_id; // Get the inserted member's ID

        // If the user is a caregiver, insert caregiver-specific data
        if ($isCaregiver) {
            $sqlCaregiver = "INSERT INTO caregiver(MID, ableHours, rate, limitHours) VALUES(?, ?, ?, ?)";
            $stmtCaregiver = $conn->prepare($sqlCaregiver);
            if (!$stmtCaregiver) {
                echo "Failed to prepare caregiver statement: " . $conn->error;
                exit();
            }

            $stmtCaregiver->bind_param("iidi", $memberId, $ableHours, $rate, $limitHours);

            if (!$stmtCaregiver->execute()) {
                echo "Error inserting caregiver data: " . $stmtCaregiver->error;
                exit();
            }

            $stmtCaregiver->close();
        }

        // Redirect to login page after successful registration
        header("Location: ../LoginPage/login_page.html");
        exit();
    } else {
        echo "Error inserting member data: " . $stmtMember->error;
        exit();
    }

    $stmtMember->close();
}
$conn->close();
?>
