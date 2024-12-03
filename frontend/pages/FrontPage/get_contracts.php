<?php
session_start(); // Start the session

// Database connection settings
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "caregiver_web";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the user is logged in (assuming MID is set in the session)
if (isset($_SESSION['MID'])) {
    $MID = intval($_SESSION['MID']); // Sanitize MID
    $response = []; // Initialize response array

    // Query to get contracts where MID matches
    $sql1 = "SELECT Cno, startDate, endDate, status, CID, MID FROM contracts WHERE MID = ?";
    $stmt1 = $conn->prepare($sql1);
    $stmt1->bind_param("i", $MID);
    $stmt1->execute();
    $result1 = $stmt1->get_result();

    if ($result1->num_rows > 0) {
        while ($row = $result1->fetch_assoc()) {
            $response[] = [
                "Cno" => $row['Cno'],
                "startDate" => $row['startDate'],
                "endDate" => $row['endDate'],
                "status" => $row['status'],
                "CID" => $row['CID'],
                "MID" => $row['MID'],
                "isCaregiver" => false // These contracts are directly for the member
            ];
        }
    }
    $stmt1->close();

    // Query to get contracts where MID is linked as a caregiver
    $sql2 = "
        SELECT c.Cno, c.startDate, c.endDate, c.status, c.CID, c.MID
        FROM contracts c
        JOIN caregiver cg ON c.CID = cg.CID
        WHERE cg.MID = ?
    ";
    $stmt2 = $conn->prepare($sql2);
    $stmt2->bind_param("i", $MID);
    $stmt2->execute();
    $result2 = $stmt2->get_result();

    if ($result2->num_rows > 0) {
        while ($row = $result2->fetch_assoc()) {
            $response[] = [
                "Cno" => $row['Cno'],
                "startDate" => $row['startDate'],
                "endDate" => $row['endDate'],
                "status" => $row['status'],
                "CID" => $row['CID'],
                "MID" => $row['MID'],
                "isCaregiver" => true // These contracts are linked to the caregiver role
            ];
        }
    }
    $stmt2->close();

    echo json_encode($response); // Return contracts as JSON
} else {
    echo json_encode(["error" => "User not logged in."]);
}

$conn->close();
?>
