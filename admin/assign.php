<?php
// ... (your existing code)

// Handle form submission
session_start();
 include '../includes/connection.php';
$mechanicId=$_SESSION['id'];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the assigned mechanic from the form submission
    $assignedMechanic = $_POST['typeOfService'];

    // Update the request table with the assigned mechanic
    $updateSql = "UPDATE request SET mechanic_assigned = ? WHERE id = ?";
    $stmt = $conn->prepare($updateSql);
    $stmt->bind_param("si", $assignedMechanic, $mechanicId);
    $stmt->execute();

    // Check if the update was successful
    if ($stmt->affected_rows > 0) {
         header("Location: request.php");
    } else {
        echo "Error assigning mechanic. Please try again.";
    }

    // Close the statement
    $stmt->close();
}
?>
