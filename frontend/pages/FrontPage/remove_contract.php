<?php
session_start(); // Start the session

// Database connection settings
include('../../../backend/conn.php');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the contract number from the GET request
$Cno = $_GET['Cno'] ?? null; 

if ($Cno) {
    // Start a transaction to ensure that the query is executed
    $conn->begin_transaction();

    // Prepare the SQL statement to update the contract status
    $stmt = $conn->prepare("UPDATE contracts SET status = 4 WHERE Cno = ?");
    $stmt->bind_param("i", $Cno);

    // Execute the statement
    if ($stmt->execute()) {
        // Commit the transaction
        $conn->commit();
        echo json_encode(["success" => "Contract status updated successfully."]);
        
        // Redirect to the front page
        header("Location: front_page.html");
        exit(); // Ensure no further code is executed
    } else {
        // Rollback the transaction if there's an error
        $conn->rollback();
        echo json_encode(["error" => "Failed to update contract status."]);
    }

    $stmt->close(); // Close the statement
} else {
    echo json_encode(["error" => "Contract number not provided."]);
}

$conn->close(); // Close the database connection
?>
