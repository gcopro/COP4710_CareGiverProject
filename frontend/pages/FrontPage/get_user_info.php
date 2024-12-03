<?php
session_start(); // Start the session

include('../../../backend/conn.php');

// Check if the user is logged in
if (isset($_SESSION['MID'])) {
    $MID = $_SESSION['MID'];

    // Prepare the SQL statement
    $stmt = $conn->prepare("SELECT firstName, lastName, email, CDBalance FROM member WHERE MID = ?");
    $stmt->bind_param("i", $MID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Return user data as JSON
        echo json_encode($user);
    } else {
        echo json_encode(["error" => "User not found."]);
    }

    $stmt->close();
} else {
    echo json_encode(["error" => "User not logged in."]);
}

$conn->close();
?>
