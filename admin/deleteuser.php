<?php
// Include the database connection file
include '../includes/connection.php';

// Check if ID and type are provided and valid
if(isset($_GET['id']) && isset($_GET['type']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];
    $type = $_GET['type'];

    // Check the type and perform delete operation accordingly
    if ($type === 'admin') {
        $table = 'admin';
    } elseif ($type === 'mechanic') {
        $table = 'mechanicreg';
    } else {
        // If type is invalid, redirect to the homepage or appropriate error page
        header("Location: index.php");
        exit();
    }

    // Delete user based on ID
    $sql = "DELETE FROM $table WHERE id = $id";
    if ($conn->query($sql) === TRUE) {
        // Redirect to display.php after successful deletion
        header("Location: display.php");
        exit();
    } else {
        // Show warning alert that the record cannot be deleted
        echo "<script>alert('This record cannot be deleted.');</script>";
        // Redirect back to display.php
        header("Location: display.php");
        exit();
    }
} else {
    // If ID or type is not provided or invalid, redirect to the homepage or appropriate error page
    header("Location: index.php");
    exit();
}
?>
