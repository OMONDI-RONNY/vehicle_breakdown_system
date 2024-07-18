<?php
include '../includes/connection.php';
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: adminlogin.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "DELETE FROM mechanicreg WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        header("Location: mechanic.php");
        exit();
    } else {
        // Check if the error is due to a foreign key constraint
        if ($conn->errno == 1451) {
            // Display an alert and then redirect
            echo "<script>
                    alert('Error: This mechanic is associated with some requests. Please delete or update the associated requests first.');
                    window.location.href='mechanic.php';
                  </script>";
        } else {
            // For other errors, display the error message
            echo "Error deleting record: " . $conn->error;
        }
    }
} else {
    echo "User ID not specified";
}
?>
