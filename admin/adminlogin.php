<?php
session_start();
include '../includes/connection.php';

// Set the maximum login attempts
$maxLoginAttempts = 3;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the login attempts session variable is set
    if (!isset($_SESSION['login_attempts'])) {
        $_SESSION['login_attempts'] = 0;
    }

    // If the maximum attempts are reached, block the account
    if ($_SESSION['login_attempts'] >= $maxLoginAttempts) {
        $error = "Account blocked. Too many unsuccessful login attempts.";
    } else {
        // Retrieve form data
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $password = $_POST['password'];

        // Fetch user data from the database
        $sql = "SELECT * FROM admin WHERE username = '$username'";
        $result = $conn->query($sql);

        if ($result && $result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $stored_password = $row['password'];
            $id = $row['id'];

            // Verify password
            if (password_verify($password, $stored_password)) {
                // Password is correct
                $_SESSION['username'] = $username; // Start a session for the logged-in user
                $_SESSION['id'] = $id;
                $_SESSION['login_attempts'] = 0; // Reset login attempts on successful login
                header("Location: index.php"); // Redirect to admin dashboard
                exit();
            } else {
                // Invalid password
                $error = "Invalid password!";
                $_SESSION['login_attempts']++; // Increment login attempts
            }
        } else {
            // Username not found
            $error = "User not found!";
            $_SESSION['login_attempts']++; // Increment login attempts
        }
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
    <title>Admin Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
        }
        .container {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
             margin-top: 15%;
        }
        h2 {
            text-align: center;
        }
        label {
            display: block;
            margin: 10px 0;
        }
        input[type="text"],
        input[type="password"] {
            width: 90%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="submit"] {
            background-color: #007BFF;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
         input[type="reset"] {
            background-color: #007BFF;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
         .forgot-password,
        .create-account {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Admin Login</h2>
        <?php if(isset($error)) { ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php } ?>
        <form autocomplete="off" action="adminlogin.php" method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br><br>

            <input type="submit" value="Login">
            <input type="reset" value="Clear">
            <p class="forgot-password">
                <a href="#">Forgot Password</a>
            </p>
        </form>
    </div>
</body>
</html>
