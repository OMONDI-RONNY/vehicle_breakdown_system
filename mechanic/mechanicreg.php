<?php
session_start();
include '../includes/connection.php';

// Fetch service data from the database
$sql = "SELECT * FROM services";
$result = $conn->query($sql);

// Store fetched services in an array
$services = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $services[] = $row['service_name'];
    }
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fname=$_POST['firstName'];
    $lname=$_POST['lastName'];
    $id=$_POST['mechanicId'];
    $phone=$_POST['phone'];
    $email=$_POST['email'];
    $model=$_POST['typeOfService'];
    $password = $_POST['password'];
    $hash=password_hash($password, PASSWORD_DEFAULT);

    //echo $lname;

    // Insert data into the database
    $sql = "INSERT INTO mechanicreg (firstname, lastname,mech_id,phone,mech_email,typeofservice,password) VALUES ('$fname', '$lname','$id','$phone','$email','$model','$hash')";

    if ($conn->query($sql) === TRUE) {
         header("Location: mechlog.php");
    } else {
        $error=  $conn->error;
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
    <title>Mechanic Registration Form</title>
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
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-image: url('mec.jpeg'); /* Path to your background image */
            background-size: cover;
            background-position: center;
        }

        /* Your other CSS styles... */
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
          select {
            width: 95%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        option {
            padding: 5px;
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

        input[type="submit"],
        input[type="reset"] {
            background-color: #007BFF;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .create-account {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Mechanic Registration Form</h2>
          <?php if(isset($error)) { ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php } ?>
        <form autocomplete="off" action="mechanicreg.php" method="POST"  onsubmit="return validatePassword()">
            <label for="firstName">First Name:</label>
            <input type="text" id="firstName" name="firstName" required>

            <label for="lastName">Last Name:</label>
            <input type="text" id="lastName" name="lastName" required>

            <label for="mechanicId">Mechanic ID:</label>
            <input type="text" id="mechanicId" name="mechanicId" required>

              <label for="lastName">Phone:</label>
            <input type="text" id="lastName" name="phone" required>

              <label for="lastName">Email:</label>
            <input type="text" id="lastName" name="email" required>



            <label for="typeOfService">Type of Service:</label>
            <select id="typeOfService" name="typeOfService" required>
                <?php
                // Loop through the fetched services and create options in the select dropdown
                foreach ($services as $service) {
                    echo "<option value='$service'>$service</option>";
                }
                ?>
            </select>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <label for="confirmPassword">Confirm Password:</label>
            <input type="password" id="confirmPassword" name="confirmPassword" required><br><br>

            <input type="submit" value="Submit">
            <input type="reset" value="Clear">
        </form>
         <p class="create-account">
            Have an account? <a href="mechlog.php">Login</a>
        </p>
    </div>
</body>
</html>
