<?php
session_start();
include '../includes/connection.php';

// Fetch vehicle models from the database
$sql = "SELECT * FROM kenya_vehicle_models";
$result = $conn->query($sql);
$models = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $models[] = $row['model'];
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fname = $_POST['firstName'];
    $lname = $_POST['lastName'];
    $id = $_POST['id'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $model = $_POST['model'];
    $password = $_POST['password'];
    $hash = password_hash($password, PASSWORD_DEFAULT);

    // Insert data into the database
    $sql = "INSERT INTO vehicleowners (firstname, lastname,vehicleID,phone,email,vehicleModel,password) 
            VALUES ('$fname', '$lname','$id','$phone','$email','$model','$hash')";

    if ($conn->query($sql) === TRUE) {
        header("Location: login.php");
    } else {
        $error = "User details already exist!";
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
    <title>Customer Registration Form</title>
     <script>
    // Function to validate password and confirm password
    function validatePassword() {
      var password = document.getElementById("password").value;
      var confirmPassword = document.getElementById("confirmPassword").value;

      // Check if the passwords match
      if (password !== confirmPassword) {
        alert("Passwords do not match!");
        return false;
      }
      return true;
    }
  </script>
     <style>
        /* Paste your CSS code here */
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
        select {
    width: 104%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    /* Additional styles */
    background-color: #fff; /* Set the background color */
    color: #333; /* Set the text color */
    font-size: 14px; /* Adjust the font size */
    margin-bottom: 10px; /* Add margin to match other fields */
}

        input[type="text"],
        input[type="email"],
        input[type="number"],
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
        <h2>User Registration Form</h2>
         <?php if(isset($error)) { ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php } ?>
        <form action="customereg.php" method="POST" autocomplete="off"  onsubmit="return validatePassword()">
            <label for="firstName">First Name:</label>
            <input type="text" id="firstName" name="firstName" required>

            <label for="lastName">Last Name:</label>
            <input type="text" id="lastName" name="lastName" required>

            <label for="mechanicId">Customer ID:</label>
            <input type="text" id="mechanicId" name="id" required>

             <label for="mechanicId">Phone Number:</label>
            <input type="number" id="mechanicId" name="phone" required minlength="10">

             <label for="mechanicId">Email:</label>
            <input type="email" id="mechanicId" name="email" required>

             <label for="vehicleModel">Vehicle Model:</label>
            <select id="vehicleModel" name="model" required>
                <option value="" disabled selected>Select Vehicle Model</option>
                <?php foreach ($models as $model) { ?>
                    <option value="<?php echo $model; ?>"><?php echo $model; ?></option>
                <?php } ?>
            </select>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <label for="confirmPassword">Confirm Password:</label>
            <input type="password" id="confirmPassword" name="confirmPassword" required>

            <input type="submit" value="Submit" name="register">
            <input type="reset" value="Clear">
        </form>
         <p class="create-account">
            Have an account? <a href="login.php">Login</a>
        </p>
    </div>
</body>
</html>
