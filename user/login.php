<?php
session_start();
include '../includes/connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $email = $_POST['email'];
    $password = $_POST['password'];
    $_SESSION['email'] = $email;

    // Fetch user data from the database
    $sql = "SELECT * FROM vehicleowners WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $stored_password = $row['password'];

        // Verify password
        if (password_verify($password, $stored_password)) {
            // Password is correct
            header("Location: master/html/index.php");
            // Redirect the user or perform necessary actions
            exit();
        } else {
            // Invalid password
            $error = "Invalid password!";
        }
    } else {
        // Username not found
        $error = "User not found!";
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
    <title>Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f0f0f0;
            background-image: url('car.jpg'); /* Path to your background image */
            background-size: cover;
            background-position: center;
        }

        .container {
            max-width: 500px; /* Increased maximum width */
            width: 80%; /* Adjusted width */
            padding: 40px; /* Increased padding */
            background-color: rgba(255, 255, 255, 0.7);
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease;
            box-sizing: border-box; /* Included box-sizing */
        }

        .container:hover {
            transform: scale(1.05);
        }

        h2 {
            text-align: center;
        }

        label {
            display: block;
            margin: 10px 0;
            color: #555;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%; /* Modified width */
            padding: 10px;
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

        input[type="submit"],
        input[type="reset"] {
            width: 100%; /* Modified width */
            box-sizing: border-box; /* Included box-sizing */
            background-color: #007BFF;
            color: #fff;
            padding: 12px 0; /* Adjusted padding */
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover,
        input[type="reset"]:hover {
            background-color: #0056b3;
        }

        .forgot-password,
        .create-account {
            text-align: center;
            margin-top: 20px;
            color: #007BFF;
            font-size: 14px;
        }

        .create-account a {
            text-decoration: none;
            color: #007BFF;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>User Login</h2>

        <?php if(isset($error)) { ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php } ?>

        <form action="login.php" method="post" autocomplete="off">
            <label for="email">
                <i class="fas fa-envelope"></i> Email:
            </label>
            <input type="email" id="email" name="email" required>

            <label for="password">
                <i class="fas fa-lock"></i> Password:
            </label>
            <input type="password" id="password" name="password" required><br><br>

            <input type="submit" value="Login">
            <input type="reset" value="Clear">

            <p class="forgot-password">
                <a href="passwordreset.php">Forgot Password</a>
            </p>
        </form>

        <p class="create-account">
            Don't have an account? <a href="customereg.php">Create one</a>
        </p>
    </div>
</body>
</html>
