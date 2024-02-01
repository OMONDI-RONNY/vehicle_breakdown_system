<?php
 include '../includes/connection.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "DELETE FROM feedback WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        header("Location: feedback.php");
        exit();
    } else {
        echo "Error deleting record: " . $conn->error;
    }
} else {
    echo "User ID not specified";
}
?>
