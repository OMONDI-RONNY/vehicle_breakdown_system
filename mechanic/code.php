<?php
// Include the necessary files for database connection
include '../includes/connection.php';

// Start session
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Assuming you have a form that submits the entered code
    $enteredCode = $_POST['code']; // Replace with your actual form field name

    // Check if the entered code exists in the password_reset table
    $checkQuery = "SELECT * FROM password_reset WHERE reset_code = '$enteredCode'";
    $result = $conn->query($checkQuery);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $timestamp = strtotime($row['timestamp']);
        $currentTimestamp = time();

        // Check if the timestamp is within the valid time window (e.g., 5 minutes)
        if (($currentTimestamp - $timestamp) < 300) {
            // Code is valid, perform further actions if needed
            $_SESSION['reset_code'] = $enteredCode;

            // Redirect to set.php
            header('Location: set.php');
            exit();
        } else {
            $error = "Code has expired. Please request a new one.";
        }
    } else {
        $error = "Invalid code. Please try again.";
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
    <title>Enter Code</title>
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

        .enter-code-container {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 400px;
            transform: translateY(0);
            transition: transform 0.3s ease-in-out;
        }

        .enter-code-container:hover {
            transform: translateY(-5px);
        }

        .enter-code-header {
            background-color: #3498db;
            color: #ffffff;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }

        .enter-code-form {
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

<div class="enter-code-container">
    <div class="enter-code-header">
       
    </div>
    <div class="enter-code-form">
    <h2>Enter Code</h2>
        <?php if(isset($error)) { ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php } ?>
        <form action="code.php" method="post">
            <div class="form-group">
                <label for="code">Enter the code received via email:</label>
                <input type="text" id="code" name="code" required>
            </div>
            <button type="submit" class="submit-button">Submit</button>
        </form>
    </div>
</div>

</body>
</html>
