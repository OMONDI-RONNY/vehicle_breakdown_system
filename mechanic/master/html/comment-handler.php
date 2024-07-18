<?php
include 'includes/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $comment = $_POST['comment'];
    $serviceCharge = $_POST['service_charge'];
    $id = $_POST['request_id']; // Change from $_GET to $_POST

    // Prepare and execute the SQL query to update the request table
    $updateSql = "UPDATE request 
                  SET mechanic_notes = ?, service_cost = ?, acceptance = 3 
                  WHERE id = ?";

    $updateStmt = $conn->prepare($updateSql);

    // Check for prepare errors
    if (!$updateStmt) {
        die('Error in prepare statement: ' . $conn->error);
    }

    $updateStmt->bind_param("ssi", $comment, $serviceCharge, $id);

    // Execute the update query
    if ($updateStmt->execute()) {
         header("Location: index.php");
    exit();
    } else {
        echo "Error updating record: " . $updateStmt->error;
    }

    // Close the update statement
    $updateStmt->close();
    
    // Close the database connection
    $conn->close();
} else {
    // Redirect if accessed directly without POST request
    header("Location: index.php");
    exit();
}
?>
