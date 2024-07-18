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
     $status = $_POST['status'];
    $model=$_POST['typeOfService'];
    $password = $_POST['password'];
    $hash=password_hash($password, PASSWORD_DEFAULT);

    //echo $lname;

    // Insert data into the database
    $sql = "INSERT INTO mechanicreg (firstname, lastname,id,phone,mech_email,typeofservice,password,status) VALUES ('$fname', '$lname','$id','$phone','$email','$model','$hash','$status')";

    if ($conn->query($sql) === TRUE) {
         header("Location: mechlog.php");
    } else {
        $error= "User already Registered!";
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
        input[type="email"],
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
        .tick-list {
            list-style-type: none;
            padding: 0;
            margin-top: 5px;
        }

        .tick-list li {
            color: red;
            display: flex;
            align-items: center;
        }

        .tick-list li::before {
            content: "✓";
            margin-right: 5px;
        }

        #passwordRequirements {
            margin-top: 5px;
            font-size: 14px;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Mechanic Registration Form</h2>
          <?php if(isset($error)) { ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php } ?>
        <form id="myForm" onsubmit="return validateForm()" autocomplete="off" action="mechanicreg.php" method="POST"  onsubmit="return validatePassword()">
            <label for="firstName">First Name:</label>
            <input type="text" id="firstName" name="firstName" required>

            <label for="lastName">Last Name:</label>
            <input type="text" id="lastName" name="lastName" required>

            <label for="mechanicId">Mechanic ID:</label>
            <input type="text" id="mechanicId" name="mechanicId" pattern="[0-9]{8,10}" title="Please enter a valid national ID number" placeholder="e.g 39068465" required>

              <label for="lastName">Phone:</label>
            <input type="text" id="lastName" name="phone" pattern="[0-9]{10}" title="Please enter a 10-digit phone number" required placeholder="e.g 0796471436">

              <label for="lastName">Email:</label>
            <input type="email" id="lastName" name="email" placeholder="e.g omoron37@gmail.com" required>


           



            <label for="typeOfService">Type of Service:</label>
            <select id="typeOfService" name="typeOfService" required>
                <?php
                // Loop through the fetched services and create options in the select dropdown
                foreach ($services as $service) {
                    echo "<option value='$service'>$service</option>";
                }
                ?>
            </select>
             <!-- Add this code inside your form -->
           <label for="status">Status:</label>
           <select id="status" name="status" required>
           <option value="1">Active</option>
           <option value="0">Inactive</option>
           </select>

           <label for="password">Password:</label>
        <input type="password" id="password" name="password" required onkeyup="checkPasswordStrength(this.value)">
        <div id="passwordRequirements">At least 8 characters, one uppercase letter, one lowercase letter, one digit, and one special character.</div>
        <ul id="tickList" class="tick-list"></ul>

        <label for="confirmPassword">Confirm Password:</label>
        <input type="password" id="confirmPassword" name="confirmPassword" required onfocus="clearDescriptionAndTicks()">
<br><br>

            <input type="submit" value="Submit">
            <input type="reset" value="Clear">
        </form>
         <p class="create-account">
            Have an account? <a href="mechlog.php">Login</a>
        </p>
    </div>
    <script>
    function checkPasswordStrength(password) {
        var strength = 0;
        var tickList = document.getElementById("tickList");
        tickList.innerHTML = ""; // Clear previous tick list

        // Check for at least 8 characters
        if (password.length >= 8) {
            strength++;
            appendTick("At least 8 characters");
        }

        // Check for at least one uppercase letter
        if (/[A-Z]/.test(password)) {
            strength++;
            appendTick("At least one uppercase letter");
        }

        // Check for at least one lowercase letter
        if (/[a-z]/.test(password)) {
            strength++;
            appendTick("At least one lowercase letter");
        }

        // Check for at least one digit
        if (/\d/.test(password)) {
            strength++;
            appendTick("At least one digit");
        }

        // Check for at least one special character
        if (/[$@$!%*?&#]/.test(password)) {
            strength++;
            appendTick("At least one special character");
        }

        // Display a message if all requirements are met
        if (strength === 5) {
            document.getElementById("passwordRequirements").style.display = "none";
            return true;
        } else {
            document.getElementById("passwordRequirements").style.display = "block";
            return false;
        }
    }

    function appendTick(description) {
        var tickList = document.getElementById("tickList");
        var listItem = document.createElement("li");
        listItem.appendChild(document.createTextNode(description));
        tickList.appendChild(listItem);
    }

    function clearDescriptionAndTicks() {
        var tickList = document.getElementById("tickList");
        var passwordRequirements = document.getElementById("passwordRequirements");

        tickList.innerHTML = "";
        passwordRequirements.style.display = "none";
    }

    function validateForm() {
        var password = document.getElementById("password").value;
        var confirmPassword = document.getElementById("confirmPassword").value;

        // Check if password criteria are met
        if (!checkPasswordStrength(password)) {
            alert("Password does not meet the criteria");
            return false;
        }

        // Check if passwords match
        if (password !== confirmPassword) {
            alert("Passwords do not match");
            return false;
        }

        // Add more validation logic as needed

        return true; // Form submission will proceed if all validations pass
    }

    document.getElementById("password").addEventListener("input", function() {
        checkPasswordStrength(this.value);
    });
</script>
</body>
</html>
