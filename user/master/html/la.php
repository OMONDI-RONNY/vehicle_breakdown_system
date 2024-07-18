<?php
// submitLocation.php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve data from the form
    $userName = $_POST["userName"];
    $latitude = $_POST["latitude"];
    $longitude = $_POST["longitude"];

    // Assuming you have a database connection
    include '../includes/connection.php';

    // Perform database insertion (example using MySQLi)
    $stmt = $conn->prepare("INSERT INTO user_locations (name, latitude, longitude) VALUES (?, ?, ?)");
    $stmt->bind_param("sdd", $userName, $latitude, $longitude);

    if ($stmt->execute()) {
        echo "Location data submitted successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Location Detection and Storage</title>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
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
        }
    </script>
</head>
<body>
    <h1>Location:</h1>
    <p id="location">Fetching location...</p>

    <!-- Your form goes here -->
    <form method="post" action="la.php">
        <label for="userName">Your Name:</label>
        <input type="text" id="userName" name="userName" required>

        <!-- Hidden fields for latitude and longitude -->
        <input type="hidden" id="latitude" name="latitude">
        <input type="hidden" id="longitude" name="longitude">

        <input type="submit" value="Submit">
    </form>

    <!-- The rest of your HTML content goes here -->
</body>
</html>
