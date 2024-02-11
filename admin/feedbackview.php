<?php
// Include the connection file
include '../includes/connection.php';
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: adminlogin.php");
    exit();
}

if (isset($_GET['id'])) {
    $mechanicId = $_GET['id'];

    // Query to fetch details of the selected mechanic
    $sql = "SELECT * FROM feedback WHERE id = $mechanicId";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        // Fetch the mechanic details including the image path
        $mechanicDetails = mysqli_fetch_assoc($result);
        // Close the database connection
        mysqli_close($conn);
    } else {
        // Mechanic not found or query error
        $error = "Feedback not found";
    }
} else {
    // If ID is not provided in the URL
    $error = "Invalid request";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Feedback Details</title>
    <style>
        /* Add your CSS styles here */
     body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
}

.container {
    max-width: 800px;
    margin: 50px auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

h2 {
    color: #fff;
    background-color: #007bff; /* Background color for h2 */
    text-align: center;
    padding: 15px; /* Add padding for a better visual */
    border-radius: 8px; /* Optional: Add border-radius for rounded corners */
    margin-bottom: 20px; /* Add margin to separate from the content below */
    animation: fadeInUp 1s ease-out; /* Animation for fade-in effect */
}

p {
    color: #555;
    margin-bottom: 10px;
}

@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(-20px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.fadeInAnimation {
    animation: fadeIn 1s ease-out;
}

.back-link, .print-button {
    display: inline-block;
    padding: 10px 20px;
    margin-top: 20px;
    border-radius: 5px;
    text-decoration: none;
    transition: background-color 0.3s ease;
}

.back-link {
    background-color: #007bff;
    color: #fff;
}

.print-button {
    background-color: #007bff;
    color: #fff;
    position: absolute;
    top: 10px;
    right: 10px;
}

.back-link:hover, .print-button:hover {
    background-color: #0056b3;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

th, td {
    padding: 15px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

th {
    background-color: #007bff;
    color: #fff;
}

/* Style alternate rows for better readability */
tr:nth-child(even) {
    background-color: #f9f9f9;
}

img {
    width: 200px;
    height: auto;
    display: block;
    margin: 0 auto 20px;
}
    </style>
</head>
<body>
    <div class="container">
        <?php if (isset($mechanicDetails) && !isset($error)) { ?>
              <a href="#" class="print-button" onclick="window.print()">Print</a>
            <h2>Feedback Details</h2>
            <?php
            if (!empty($mechanicDetails['photo'])) {
                echo '<img src="' . $mechanicDetails['photo'] . '" alt="Mechanic Image" style="width: 200px; height: auto; display: block; margin: 0 auto 20px;" class="fadeInAnimation">';
            } else {
                echo '<p>No image available</p>';
            }
            ?>
            <table class="fadeInAnimation">
                
                <tr>
                    <td>Name:</td>
                    <td><?php echo $mechanicDetails['name']; ?></td>
                </tr>
               
                <tr>
                    <td>Email:</td>
                    <td><?php echo $mechanicDetails['email']; ?></td>
                </tr>
              
                <tr>
                    <td>Comment:</td>
                    <td><?php echo $mechanicDetails['comment']; ?></td>
                </tr>
                <!-- Add other details you want to display -->
            </table>


            
            <!-- Back button to navigate to mechanic.php -->
            <a href="feedback.php" class="back-link">Back</a>
        <?php } else { ?>
            <p><?php echo isset($error) ? $error : 'No Feedback available'; ?></p>
        <?php } ?>
    </div>
</body>
</html>
