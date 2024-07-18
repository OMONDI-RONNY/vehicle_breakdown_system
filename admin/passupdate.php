<?php
session_start();
$id = $_SESSION['id'];
// Include the database connection file
include '../includes/connection.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $currentPassword = $_POST['currentPassword'];
    $newPassword = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];
    
    // Perform some basic validation
    if ($newPassword !== $confirmPassword) {
        echo "<script>alert('Error: New password and confirm password do not match.');</script>";
        echo "<script>window.location.href = 'index.php';</script>";
        exit;
    }
    
    // You may need to implement further validation for the password complexity
    
    // Authenticate the current password
    $userId = $id; // Assuming user id is 1, you should retrieve this dynamically based on user authentication
    $sql = "SELECT password FROM admin WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $userId);
        if ($stmt->execute()) {
            $stmt->store_result();
            if ($stmt->num_rows == 1) {
                $stmt->bind_result($hashedPassword);
                $stmt->fetch();
                if (password_verify($currentPassword, $hashedPassword)) {
                    // Current password is correct, proceed with updating password
                    $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT); // Hash the new password
                    $updateSql = "UPDATE admin SET password = ? WHERE id = ?";
                    $updateStmt = $conn->prepare($updateSql);
                    if ($updateStmt) {
                        $updateStmt->bind_param("si", $hashedNewPassword, $userId);
                        if ($updateStmt->execute()) {
                            // Password updated successfully, redirect to index.php
                            echo "<script>alert('Password updated successfully.');</script>";
                            echo "<script>window.location.href = 'index.php';</script>";
                            exit;
                        } else {
                            echo "<script>alert('Error updating password: " . $conn->error . "');</script>";
                            echo "<script>window.location.href = 'index.php';</script>";
                            exit;
                        }
                        $updateStmt->close();
                    } else {
                        echo "<script>alert('Error: " . $conn->error . "');</script>";
                        echo "<script>window.location.href = 'index.php';</script>";
                        exit;
                    }
                } else {
                    echo "<script>alert('Error: Current password is incorrect.');</script>";
                    echo "<script>window.location.href = 'index.php';</script>";
                    exit;
                }
            } else {
                echo "<script>alert('Error: User not found.');</script>";
                echo "<script>window.location.href = 'index.php';</script>";
                exit;
            }
            $stmt->close();
        } else {
            echo "<script>alert('Error executing SQL: " . $conn->error . "');</script>";
            echo "<script>window.location.href = 'index.php';</script>";
            exit;
        }
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
        echo "<script>window.location.href = 'index.php';</script>";
        exit;
    }
    
    // Close database connection
    $conn->close();
}
?>
