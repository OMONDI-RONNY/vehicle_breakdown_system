<?php
 include '../includes/connection.php';
 session_start();

if (!isset($_SESSION['id'])) {
    header("Location: adminlogin.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "DELETE FROM categories WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        header("Location: categori.php");
        exit();
    } else {
        echo "Error deleting record: " . $conn->error;
    }
} else {
    echo "User ID not specified";
}
?>
