<?php
// fetch_row_data.php

// Include the connection file
include 'includes/connection.php';

// Retrieve user ID from the AJAX request
$userId = $_GET['user_id'];

// Prepare and execute the SQL query to fetch data for the selected row
$sql = "SELECT user_id, vehicle_model, vehicle_registration FROM request WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$stmt->bind_result($userId, $vehicleModel, $vehicleRegistration);
$stmt->fetch();
$stmt->close();

// Close the database connection
$conn->close();

// Return the fetched data as JSON
echo json_encode(array('user_id' => $userId, 'vehicle_model' => $vehicleModel, 'vehicle_registration' => $vehicleRegistration));
?>
