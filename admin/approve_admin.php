<?php
// Include the database connection
include '../includes/connection.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the submitted username and admin password
    $username = $_POST["username"];
    $adminPassword = $_POST["adminPassword"];

    // Validate admin password (you should replace this with your actual validation logic)
    $validAdminPassword = true; // Placeholder validation

    if ($validAdminPassword) {
        // Perform the approval process
        $sql = "UPDATE admins SET approve= 1 WHERE name = ?";
        $stmt = mysqli_prepare($conn, $sql);
        
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $username);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            // Redirect to index.php upon successful approval
            header("Location: index.php");
            exit(); // Ensure that script execution stops after redirection
        } else {
            echo "Error: Unable to prepare SQL statement.";
        }
    } else {
        echo "Invalid admin password. Approval process aborted.";
    }
}

// Close database connection
mysqli_close($conn);
?>
