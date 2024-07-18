<?php
include '../includes/connection.php';
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: adminlogin.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "DELETE FROM vehicleowners WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        header("Location: users.php");
        exit();
    } else {
        $error_message = "Error deleting record: " . $conn->error;

        // Paraphrase the foreign key constraint error
        if (strpos($error_message, "foreign key constraint fails") !== false) {
            $error_message = "Cannot delete the user because there are related requests. Please delete associated requests first.";
        }

        // Alert the user and redirect back to users.php
        echo "<script>alert('$error_message'); window.location.href='users.php';</script>";
    }
} else {
    echo "User ID not specified";
}
?>
