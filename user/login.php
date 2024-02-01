<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
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
        }

        input[type="email"],
        input[type="password"] {
            width: 100%; /* Modified width */
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="submit"],
        input[type="reset"] {
            width: 100%; /* Modified width */
            box-sizing: border-box; /* Included box-sizing */
            background-color: #007BFF;
            color: #fff;
            padding: 10px 0; /* Adjusted padding */
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
        }

        .forgot-password,
        .create-account {
            text-align: center;
            margin-top: 20px;
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
        <?php
        session_start();
        include '../includes/connection.php';
        

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $email = $_POST['email'];
    $password = $_POST['password'];
    $_SESSION['email']=$email;
  

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
        } else {
            // Invalid password
            $error="Invalid password!";
           //echo "Invalid password!";
            // Redirect the user or show an error message
        }
    } else {
        // Username not found
        $error="User not found!";
       // echo "User not found!";
        // Redirect the user or show an error message
    }
}

// Close the database connection
$conn->close();
?>

        

        <?php if(isset($error)) { ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php } ?>

        <form action="login.php" method="post" autocomplete="off">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br><br>

            <input type="submit" value="Login">
            <input type="reset" value="Clear">

            <p class="forgot-password">
                <a href="#">Forgot Password</a>
            </p>
        </form>

        <p class="create-account">
            Don't have an account? <a href="customereg.php">Create one</a>
        </p>
    </div>
</body>
</html>
