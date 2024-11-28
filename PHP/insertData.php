<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "caregiver_web";

// Create a connection
$con = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Reset foreign key checks and delete existing data
$con->query("SET FOREIGN_KEY_CHECKS = 0;");
$con->query("DELETE FROM review;");
$con->query("DELETE FROM contracts;");
$con->query("DELETE FROM parent;");
$con->query("DELETE FROM caregiver;");
$con->query("DELETE FROM member;");
$con->query("ALTER TABLE member AUTO_INCREMENT = 1;");
$con->query("ALTER TABLE caregiver AUTO_INCREMENT = 1;");
$con->query("ALTER TABLE parent AUTO_INCREMENT = 1;");
$con->query("ALTER TABLE contracts AUTO_INCREMENT = 1;");
$con->query("ALTER TABLE review AUTO_INCREMENT = 1;");
$con->query("SET FOREIGN_KEY_CHECKS = 1;");

// MANUAL INSRET
$sql = "

-- MID
INSERT INTO member (NAME, Phone, Address, PASSWORD) 
VALUES 
('bob', '1234567890', '123 Main St', 'password123');

-- CID
INSERT INTO caregiver (MID, Availble_hours_per_week) 
VALUES 
(1, 40);  

-- PID
INSERT INTO parent (MID, NAME, Age, Address) 
VALUES 
(1, 'Parent One', 65, '456 Target St'); 

-- Cno
INSERT INTO contracts (MID, CID, PID, startDate, endDate, earnedCD, spentCD) 
VALUES 
(1, 1, 1, '2024-11-01', '2024-11-30', 500, 30);

-- Rno
INSERT INTO review (Cno, Rate) VALUES (1, 3);
";

// Execute the multi-query
if ($con->multi_query($sql)) {
    echo "Data inserted successfully!";
} else {
    echo "Error: " . $con->error;
}

// Close the connection
$con->close();

?>
