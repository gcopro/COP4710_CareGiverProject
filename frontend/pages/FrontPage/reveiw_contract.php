<?php
session_start(); // Start the session

// Database connection settings
include('../../../backend/conn.php');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the contract number and review value from the GET request
$Cno = $_GET['Cno'] ?? null; 
$Rate = $_GET['reviewValue'] ?? null;

if ($Cno && $Rate) {
    // Start a transaction to ensure both queries are executed together
    $conn->begin_transaction();

    try {
        // Prepare the SQL statement to insert into the review table
        $stmt = $conn->prepare("INSERT INTO review (Cno, Rate) VALUES (?, ?)");
        $stmt->bind_param("ii", $Cno, $Rate);

        if (!$stmt->execute()) {
            throw new Exception("Failed to add review.");
        }

        // Prepare the SQL statement to update the contracts table
        $stmt = $conn->prepare("UPDATE contracts SET status = 3 WHERE Cno = ?");
        $stmt->bind_param("i", $Cno);

        if (!$stmt->execute()) {
            throw new Exception("Failed to update contract status.");
        }

        // Commit the transaction
        $conn->commit();

        echo json_encode(["success" => "Review added and contract status updated successfully."]);
        header("Location: front_page.html");
        exit();
    } catch (Exception $e) {
        // Rollback the transaction on failure
        $conn->rollback();
        echo json_encode(["error" => $e->getMessage()]);
    } finally {
        $stmt->close(); // Close the statement
    }
} else {
    echo json_encode(["error" => "Contract number or review value not provided."]);
}

$conn->close(); // Close the database connection
?>
