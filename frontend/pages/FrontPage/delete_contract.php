<?php
session_start(); // Start the session

// Database connection settings
include('../../../backend/conn.php');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the contract number from the GET request
$Cno = $_GET['Cno'] ?? null;  // Use null coalescing operator to avoid errors if Cno is not provided

if ($Cno) {
    // Prepare the SQL statement
    $stmt = $conn->prepare("DELETE FROM contracts WHERE Cno = ?");
    $stmt->bind_param("i", $Cno);

    if ($stmt->execute()) {
        echo json_encode(["success" => "Contract status updated successfully."]);
        header("Location: ..FrontPage/front_page.html");
    } else {
        echo json_encode(["error" => "Failed to update contract status."]);
    }

    $stmt->close(); // Close the statement
} else {
    echo json_encode(["error" => "Contract number not provided."]);
}


$conn->close(); // Close the database connection
?>
