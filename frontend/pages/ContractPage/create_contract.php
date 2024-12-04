<?php
include('../../../backend/conn.php');
session_start();

// Ensure that the user is logged in
if (!isset($_SESSION['MID'])) {
    die("You must be logged in to create a contract.");
}

// Fetch logged-in user's MID from session
// Temp values
$MID = 1;
$CID = 1;

if (isset($_GET['userId']) && isset($_GET['cid'])) {
    // Retrieve the userId and cid from the URL query parameters
    $MID = $_GET['userId'];
    $CID = $_GET['cid'];
}

// Fetch other form data
$patientName = $_POST['patientName'];
$patientAge = $_POST['patientAge'];
$patientAddress = $_POST['patientAddress'];
$startDate = $_POST['startDate'];
$endDate = $_POST['endDate'];
$hoursPerWeek = $_POST['hoursPerWeek'];
$hourlyRate = 30; // Example hourly rate
$transactions = $hoursPerWeek * $hourlyRate; // Calculate total weekly cost
$status = 0; // Initially set to pending status

// Begin transaction to ensure atomicity of the inserts
$conn->begin_transaction();

try {
    // Step 1: Insert parent information into the 'parent' table
    $sql_parent = "INSERT INTO parent (MID, NAME, Age, Address) VALUES (?, ?, ?, ?)";
    $stmt_parent = $conn->prepare($sql_parent);
    $stmt_parent->bind_param("isis", $MID, $patientName, $patientAge, $patientAddress);
    $stmt_parent->execute();
    $PID = $conn->insert_id; // Get the Parent ID from the insert (auto-increment)

    // Step 2: Insert contract details into the 'contracts' table
    $sql_contract = "INSERT INTO contracts (MID, CID, PID, startDate, endDate, transactions, status, hrs)
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt_contract = $conn->prepare($sql_contract);
    $stmt_contract->bind_param("iiissiii", $MID, $CID, $PID, $startDate, $endDate, $transactions, $status, $hoursPerWeek);
    $stmt_contract->execute();

    // Commit the transaction after both inserts are successful
    $conn->commit();

    // Success message and redirect
    echo "Contract successfully created!";
    header("Location: ../FrontPage/front_page.html"); // Redirect to the front page after success
    exit; // Always call exit after header redirect

} catch (Exception $e) {
    // Rollback transaction in case of error
    $conn->rollback();
    echo "Error: " . $e->getMessage(); // Display error message if any
}

// Close the connection
$conn->close();
?>
