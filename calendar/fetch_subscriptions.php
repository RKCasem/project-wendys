<?php
include '../db.php'; // Include the database connection

// Check if 'date' is provided in the URL
if (!isset($_GET['date'])) {
    echo json_encode(['error' => 'Date parameter is missing.']);
    exit;
}

$date = filter_var($_GET['date'], FILTER_SANITIZE_STRING);

// Validate date format (YYYY-MM-DD)
if (!DateTime::createFromFormat('Y-m-d', $date)) {
    echo json_encode(['error' => 'Invalid date format.']);
    exit;
}

// Prepare SQL query with placeholder
$sql = "SELECT name, amount, start_date, end_date 
        FROM subscriptions 
        WHERE start_date <= ? AND end_date >= ?";

$stmt = $conn->prepare($sql);

if ($stmt === false) {
    echo json_encode(['error' => 'Failed to prepare the SQL query.']);
    exit;
}

// Bind parameters and execute the query
$stmt->bind_param("ss", $date, $date);
$stmt->execute();
$result = $stmt->get_result();

$subscriptions = [];
while ($row = $result->fetch_assoc()) {
    $subscriptions[] = $row;
}

// Return the results as a JSON response
echo json_encode($subscriptions);

$conn->close();
?>
