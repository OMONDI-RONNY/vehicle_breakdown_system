<?php
session_start();
include '../includes/connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $id = $_POST['id'];
    $password = $_POST['password'];
    $_SESSION['mech_id'] = $id;

    // Fetch user data from the database
    $sql = "SELECT * FROM mechanicreg WHERE id = '$id'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $stored_password = $row['password'];

        // Verify password
        if (password_verify($password, $stored_password)) {
            // Password is correct
        
            // Update longitude and latitude in the database
            $latitude = $_POST['latitude'];
            $longitude = $_POST['longitude'];
            echo "Latitude: $latitude, Longitude: $longitude<br>";
         
            $update_sql = "UPDATE mechanicreg SET latitude = '$latitude', longitude = '$longitude' WHERE id = '$id'";
            if ($conn->query($update_sql) === TRUE) {
                // Redirect the user or perform necessary actions
                header("Location: master/html/index.php");
                exit();
            } else {
                $error = "Error updating location: " . $conn->error;
            }
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

        .container {
            max-width: 500px; /* Increased maximum width */
            width: 80%; /* Adjusted width */
            padding: 40px; /* Increased padding */
            background-color: rgba(255, 255, 255, 0.7);
            border-radius: 5px;
            animation: slideIn 2s ease-in-out;
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
            color: #555;
        }

        input[type="number"],
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
        #location {
            display: none; /* Hide the location paragraph */
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Mechanic Login</h2>
        

        <?php if(isset($error)) { ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php } ?>
              <!-- Display the detected location -->
              
    <p id="location">Fetching location...</p>

        <form action="mechlog.php" method="post" autocomplete="off">
            <label for="id">
                <i class="fas fa-id-card"></i> Mech ID:
            </label>
            <input type="number" id="id" name="id" required>

            <label for="password">
                <i class="fas fa-lock"></i> Password:
            </label>
            <input type="password" id="password" name="password" required><br><br>
            <input type="hidden" id="latitude" name="latitude">
                    <input type="hidden" id="longitude" name="longitude">


            <input type="submit" value="Login">
            <input type="reset" value="Clear">

            <p class="forgot-password">
                <a href="passwordreset.php">Forgot Password</a>
            </p>
        </form>

        <p class="create-account">
            Don't have an account? <a href="mechanicreg.php">Create one</a>
        </p>
    </div>
    <script>document.addEventListener("DOMContentLoaded", function() {
            getLocation(); // Call the function when the page loads
        });

        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition, showError);
            } else {
                alert("Geolocation is not supported by this browser.");
            }
        }

        function showPosition(position) {
            var latitude = position.coords.latitude;
            var longitude = position.coords.longitude;

            // Display the latitude and longitude on the webpage
            document.getElementById("location").innerHTML = "Latitude: " + latitude + "<br>Longitude: " + longitude;

            // Set the values of latitude and longitude in the form fields
            document.getElementById("latitude").value = latitude;
            document.getElementById("longitude").value = longitude;
        }

        function showError(error) {
            switch (error.code) {
                case error.PERMISSION_DENIED:
                    alert("User denied the request for Geolocation.");
                    break;
                case error.POSITION_UNAVAILABLE:
                    alert("Location information is unavailable.");
                    break;
                case error.TIMEOUT:
                    alert("The request to get user location timed out.");
                    break;
                case error.UNKNOWN_ERROR:
                    alert("An unknown error occurred.");
                    break;
            }
        }</script>
</body>
</html>
