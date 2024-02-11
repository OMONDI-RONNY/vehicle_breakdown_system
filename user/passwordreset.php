<?php
// Include the necessary files for database connection and email sending
// Replace 'your_email_sending_function' with the actual function you use for sending emails
include '../includes/connection.php';
//require 'path/to/email/sending/function.php';

// Function to generate a random code
function generateRandomCode($length = 6) {
    return bin2hex(random_bytes($length / 2));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Assuming you have a form that submits the user's phone number
    $userPhone = $_POST['phone']; // Replace with your actual form field name

    // Check if the phone number exists in the database
    $checkQuery = "SELECT * FROM mechanicreg WHERE phone = '$userPhone'";
    $result = $conn->query($checkQuery);

    if ($result->num_rows > 0) {
        // Generate a random code
        $resetCode = generateRandomCode();

        // Hash the reset code before storing it in the database
       // $hashedCode = password_hash($resetCode, PASSWORD_DEFAULT);

        // Store the user's phone and hashed code in the database
        $insertQuery = "INSERT INTO password_reset (phone_number, reset_code, timestamp) VALUES ('$userPhone', '$resetCode', NOW())";
        $insertResult = $conn->query($insertQuery);

        if ($insertResult) {
            // Send the reset code to the user via email or SMS
            $emailSubject = "Password Reset Code";
            $emailBody = "Your password reset code is: $resetCode"; // Customize the message as needed
            $emailRecipient = $userPhone; // Change this to the user's phone number

            // Replace 'your_email_sending_function' with the actual function you use for sending emails
            your_email_sending_function($emailRecipient, $emailSubject, $emailBody);

            echo "Reset code generated and sent successfully. Check your phone for instructions.";
        } else {
            echo "Error storing reset code in the database.";
        }
    } else {
        echo "Phone number not found in the database.";
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
    <title>Password Reset</title>
    <style>
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

        .password-reset-container {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 400px;
            transform: translateY(0);
            transition: transform 0.3s ease-in-out;
        }

        .password-reset-container:hover {
            transform: translateY(-5px);
        }

        .password-reset-header {
            background-color: #3498db;
            color: #ffffff;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }

        .password-reset-form {
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

        .reset-button {
            background-color: #3498db;
            color: #ffffff;
            border: none;
            padding: 12px 20px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 4px;
            transition: background-color 0.3s ease-in-out;
        }

        .reset-button:hover {
            background-color: #2980b9;
        }

        .back-to-login {
            text-align: center;
            margin-top: 20px;
        }

        .back-to-login a {
            color: #333;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s ease-in-out;
        }

        .back-to-login a:hover {
            color: #3498db;
        }
    </style>
</head>
<body>

<div class="password-reset-container">
    <div class="password-reset-header">
        <h2>Password Reset</h2>
    </div>
    <div class="password-reset-form">
        <form action="passwordreset.php" method="post">
          

            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="tel" id="phone" name="phone" required>
            </div>

            <button type="submit" class="reset-button">Reset Password</button>
        </form>
        <div class="back-to-login">
            <a href="login.php">Back to Login</a>
        </div>
    </div>
</div>

</body>
</html>
