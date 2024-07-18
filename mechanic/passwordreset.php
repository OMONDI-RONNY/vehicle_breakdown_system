<?php
session_start();
// Include the necessary files for database connection and email sending
include '../includes/connection.php';
require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Function to generate a random code
function generateRandomCode($length = 6) {
    return bin2hex(random_bytes($length / 2));
}

$error = ''; // Initialize the error variable

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Assuming you have a form that submits the user's email
    $userEmail = $_POST['email']; // Replace with your actual form field name
    $_SESSION['setter']=$userEmail;
    

    // Check if the email exists in the database
    $checkQuery = "SELECT * FROM mechanicreg WHERE mech_email = '$userEmail'";
    $result = $conn->query($checkQuery);

    if ($result->num_rows > 0) {
        // Generate a random code
        $resetCode = generateRandomCode();

        // Store the user's email and reset code in the database
        $insertQuery = "INSERT INTO password_reset (email, reset_code, timestamp) VALUES ('$userEmail', '$resetCode', NOW())";
        $insertResult = $conn->query($insertQuery);

        if ($insertResult) {
            // Send the reset code to the user via email
            $mail = new PHPMailer(true);

            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'omoron37@gmail.com';
            $mail->Password = 'uxrgdwpdpujljjdf';
            $mail->SMTPSecure = 'ssl';
            $mail->Port = 465;

            $mail->setFrom('omoron37@gmail.com');
            $mail->addAddress($userEmail);
            $mail->isHTML(true);
            $mail->Subject = "Password Reset Code";
            $mail->Body = "Your password reset code is: $resetCode";

            try {
                $mail->send();
                echo "
                <script>
                alert('Reset code have been sent to you email');
                window.location.href = 'code.php'; // Redirect to code.php
                </script>
                ";
            } catch (Exception $e) {
                $error = "Error sending email: {$mail->ErrorInfo}";
            }
        } else {
            $error = "Error storing reset code in the database.";
        }
    } else {
        $error = "Email is not registered.";
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>

    <!-- Head content remains the same -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
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
       
    </div>
    <div class="password-reset-form">
    <h2>Password Reset</h2>
        <?php if ($error) { ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php } ?>
        <form action="passwordreset.php" method="post">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <button type="submit" class="reset-button">Reset Password</button>
        </form>
        <div class="back-to-login">
            <a href="mechlog.php">Back to Login</a>
        </div>
    </div>
</div>

</body>
</html>
