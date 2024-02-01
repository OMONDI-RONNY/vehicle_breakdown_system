<?php
// Establish database connection (Replace these with your database credentials)
$servername = "localhost";
$username = "admin";
$password = "BShyBA4idrBzPJ6w";
$dbname = "admin";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>