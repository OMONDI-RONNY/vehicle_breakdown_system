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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"> <!-- Add Font Awesome CSS -->
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: url('admin.jpg') no-repeat center center fixed;
            background-size: cover;
            background-color: #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            max-width: 500px;
            width: 100%;
            padding: 30px;
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
            animation: slideIn 1s ease-in-out;
            box-sizing: border-box;
        }

        @keyframes slideIn {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 10px;
            color: #555;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            padding-left: 40px;
            box-sizing: border-box;
            background: #fff url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="%23777" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><path d="M21 21l-4.35-4.35"></path></svg>') no-repeat 10px center;
            background-size: 20px;
            font-size: 16px;
            color: #333;
        }

        input[type="password"] {
            background: #fff url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="%23777" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>') no-repeat 10px center;
            background-size: 20px;
        }

        .button-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }

        input[type="submit"],
        input[type="reset"] {
            width: 48%; /* Adjust the width as needed */
            background-color: #007BFF;
            color: #fff;
            padding: 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover,
        input[type="reset"]:hover {
            background-color: #0056b3;
        }

        .forgot-password {
            text-align: center;
            margin-top: 20px;
            color: #007BFF;
            font-size: 14px;
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
            <label for="username">
                <i class="fas fa-user"></i> Username:
            </label>
            <input type="text" id="username" name="username" required>

            <label for="password">
                <i class="fas fa-lock"></i> Password:
            </label>
            <input type="password" id="password" name="password" required>

            <div class="button-container">
                <input type="submit" value="Login">
                <input type="reset" value="Clear">
            </div>

            <p class="forgot-password">
                <a href="passwordreset.php">Forgot Password</a>
            </p>
        </form>
    </div>
</body>
</html>
