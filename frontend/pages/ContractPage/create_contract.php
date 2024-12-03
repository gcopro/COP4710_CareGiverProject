<?php
include('../../../backend/conn.php');

// Fetch form data
$patientName = $_POST['patientName'];
$patientAge = $_POST['patientAge'];
$patientAddress = $_POST['patientAddress'];

// Example: default values for a new contract
$MID = 1; // Replace with dynamic Member ID
$CID = 1; // Replace with selected Caregiver ID
$hrs = 10; // Example hours
$transactions = $hrs * 30;
$status = 0; // Initially pending
$startDate = date("Y-m-d");
$endDate = date("Y-m-d", strtotime("+7 days"));

// Begin transaction
$conn->begin_transaction();

try {
    // Step 1: Insert parent (if required)
    $sql_parent = "INSERT INTO parent (MID, NAME, Age, Address) VALUES (?, ?, ?, ?)";
    $stmt_parent = $conn->prepare($sql_parent);
    $stmt_parent->bind_param("isis", $MID, $patientName, $patientAge, $patientAddress);
    $stmt_parent->execute();
    $PID = $conn->insert_id;

    // Step 2: Insert contract
    $sql_contract = "INSERT INTO contracts (MID, CID, PID, startDate, endDate, transactions, status, hrs)
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt_contract = $conn->prepare($sql_contract);
    $stmt_contract->bind_param("iiissiii", $MID, $CID, $PID, $startDate, $endDate, $transactions, $status, $hrs);
    $stmt_contract->execute();

    // Commit transaction
    $conn->commit();

    echo "Contract successfully created!";
    header("Location: ../FrontPage/front_page.html");
} catch (Exception $e) {
    // Rollback transaction on error
    $conn->rollback();
    echo "Error: " . $e->getMessage();
}

// Close connection
$conn->close();
?>
