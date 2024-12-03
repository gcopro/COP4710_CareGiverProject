<?php
include('../../../backend/conn.php');

// Set the response header to return JSON
header('Content-Type: application/json');

// Check if the database connection was successful
if (!$conn) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed']);
    exit;
}

// Get the parameters from the request
$hours_range = isset($_GET['hours']) ? $_GET['hours'] : '';

// Initialize the base query
$query = "
    SELECT cg.cid, m.firstName, m.lastName, AVG(r.Rate) AS average_review_score
    FROM caregiver cg
    JOIN contracts c ON cg.cid = c.cid
    JOIN review r ON c.cno = r.cno
    JOIN member m ON cg.mid = m.mid
";

// Initialize an array for the conditions
$conditions = [];

// Apply hours filter if provided, except when "Any" is selected
if ($hours_range && $hours_range !== 'Any') {
    if ($hours_range === '0-10') {
        $conditions[] = "cg.limitHours BETWEEN 0 AND 10";
    } elseif ($hours_range === '10-20') {
        $conditions[] = "cg.limitHours BETWEEN 10 AND 20";
    } elseif ($hours_range === '20-30') {
        $conditions[] = "cg.limitHours BETWEEN 20 AND 30";
    } elseif ($hours_range === '30') {
        // Ensure no other conditions are included when '30+' is selected
        $conditions[] = "cg.limitHours >= 30";
    }
}

// Add the conditions to the query if any
if ($conditions) {
    // Ensure the WHERE clause is only applied once
    $query .= " WHERE " . implode(' OR ', $conditions);
}

// Group the results by caregiver's id
$query .= " GROUP BY cg.cid ASC";

// Prepare and execute the query
$stmt = $conn->prepare($query);
if (!$stmt) {
    echo json_encode(['status' => 'error', 'message' => 'Query preparation failed']);
    exit;
}

// Execute the query
$stmt->execute();
if ($stmt->errno) {
    echo json_encode(['status' => 'error', 'message' => 'Query execution failed: ' . $stmt->error]);
    exit;
}

// Fetch the results
$result = $stmt->get_result();
$caregivers = [];
while ($row = $result->fetch_assoc()) {
    $caregivers[] = $row;
}

// Check if no results were found
if (empty($caregivers)) {
    echo json_encode(['status' => 'success', 'message' => 'No results found', 'data' => []]);
    exit;
}

// Return the results as JSON
echo json_encode([
    'status' => 'success',
    'data' => $caregivers
]);

$conn->close();
?>
