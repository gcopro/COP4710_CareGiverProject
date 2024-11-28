<?php
// Database connection details
$servername = "localhost"; // Change if using a different host
$username = "root";        // Use the database username
$password = "";            // Use the database password
$dbname = "caregiver_web"; // Database name

// Create a connection
$con = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// SQL queries to fetch data
$sqlQueries = [
    "members" => "SELECT * FROM member",
    "caregivers" => "SELECT * FROM caregiver",
    "contracts" => "SELECT * FROM contracts",
    "parents" => "SELECT * FROM parent",
    "reviews" => "SELECT * FROM review"
];

echo "<h1>Database Information</h1>";
foreach ($sqlQueries as $table => $query) {

    echo "<h2>Table: $table</h2>";

    $result = $con->query($query);

    if ($result->num_rows > 0) {
        // Print table headers
        echo "<table border='1'><tr>";
        while ($field = $result->fetch_field()) {
            echo "<th>{$field->name}</th>";
        }
        echo "</tr>";

        // Print rows
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            foreach ($row as $value) {
                echo "<td>{$value}</td>";
            }
            echo "</tr>";
        }
        echo "</table>";

    } 
    else {
        echo "No data in $table.<br>";
    }
}
$con->close();
?>
