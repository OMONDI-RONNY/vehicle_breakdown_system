<?php
// Include the necessary files for database connection
include '../includes/connection.php';

// Start session
session_start();

// Check if the user is logged in (email is set in the session)
if (!isset($_SESSION['id'])) {
    // Redirect to the login page or show an error message
    header('Location: adminlogin.php');
    exit();
}

// Get the email from the session
$setter = $_SESSION['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Assuming you have form fields for the new password and confirmation
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    // Validate passwords match
    if ($newPassword === $confirmPassword) {
        // Hash the new password before updating the database
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // Update the password in the mechanicreg table
        $updateQuery = "UPDATE admin SET password = '$hashedPassword' WHERE email = '$setter'";
        $result = $conn->query($updateQuery);

        if ($result) {
            header('Location: index.php');
            exit();
        } else {
            $error="Error updating password: " . $conn->error;
        }
    } else {
        $error="Passwords do not match. Please try again.";
    }
}

// Close the database connection
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <style>
        /* Your CSS styles here */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .change-password-container {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 400px;
            transform: translateY(0);
            transition: transform 0.3s ease-in-out;
        }

        .change-password-container:hover {
            transform: translateY(-5px);
        }

        .change-password-header {
            background-color: #3498db;
            color: #ffffff;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }

        .change-password-form {
            padding: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-size: 16px;
            margin-bottom: 8px;
            color: #333;
        }

        input {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            transition: border-color 0.3s ease-in-out;
        }

        input:focus {
            border-color: #3498db;
        }

        .submit-button {
            background-color: #3498db;
            color: #ffffff;
            border: none;
            padding: 12px 20px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 4px;
            transition: background-color 0.3s ease-in-out;
        }

        .submit-button:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>

<div class="change-password-container">
    <div class="change-password-header">
        <h2>Change Password</h2>
    </div>
    <div class="change-password-form">
    <?php if(isset($error)) { ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php } ?>
        <form action="changeset.php" method="post">
            <div class="form-group">
                <label for="new_password">New Password:</label>
                <input type="password" id="new_password" name="new_password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm New Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            <button type="submit" class="submit-button">Change Password</button>
        </form>
    </div>
</div>

</body>
</html>
