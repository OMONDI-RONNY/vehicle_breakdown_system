<?php
session_start();
$userEmail = $_SESSION['mech_id'];
// Include the database connection file
include 'includes/connection.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $phone = $_POST['phone'];
    $mech_email = $_POST['mech_email'];

    // Validate and sanitize data (You can add more validation as needed)

    // Example: Trim leading and trailing whitespaces
    $first_name = trim($first_name);
    $last_name = trim($last_name);
    $phone = trim($phone);
    $mech_email = trim($mech_email);

    // Example: Validate email format
    if (!filter_var($mech_email, FILTER_VALIDATE_EMAIL)) {
        // Invalid email format
        die("Invalid email format for Mechanic Email");
    }

    // Update user profile in the database (You need to customize this based on your database structure)
    // Example: Assume you have a table named 'users'
    $sql = "UPDATE mechanicreg SET firstname='$first_name', lastname='$last_name', phone='$phone', mech_email='$mech_email' WHERE id = $userEmail";
    $result = mysqli_query($conn, $sql);

    // Example: Check if the update was successful
    if ($result) {
        echo "<script>alert('Profile updated successfully!');</script>";
        
        // Redirect back to pages-profile.php after alert
        echo "<script>window.location.href='pages-profile.php';</script>";
        exit();
    } else {
        echo "Error updating profile: " . mysqli_error($conn);
    }

    // Close database connection
    mysqli_close($conn);
}
?>
