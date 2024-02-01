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
    $sql = "SELECT * FROM mechanicreg WHERE id = $mechanicId";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        // Fetch the mechanic details including the image path
        $mechanicDetails = mysqli_fetch_assoc($result);
        // Close the database connection
        mysqli_close($conn);
    } else {
        // Mechanic not found or query error
        $error = "Mechanic not found";
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
    <title>View Mechanic Details</title>
    <style>
        /* Add your CSS styles here */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #333;
            text-align: center;
        }

        p {
            color: #555;
            margin-bottom: 10px;
        }

        /* Define animation */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        /* Apply animation to specific elements */
        .fadeInAnimation {
            animation: fadeIn 1s ease-out;
        }

        /* Styling for back button link */
        .back-link {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 20px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .back-link:hover {
            background-color: #0056b3;
        }
           table {
        border-collapse: separate;
        border-spacing: 0 30px; /* Adjust the vertical spacing here */
    }

    td {
        padding: 10px; /* Adjust the cell padding if needed */
    }
      .print-button {
        position: absolute;
        top: 10px;
        right: 10px;
        padding: 10px;
        text-align: center;
        background-color: #007bff;
        color: #fff;
        text-decoration: none;
        border-radius: 5px;
        transition: background-color 0.3s ease;
    }

    .print-button:hover {
        background-color: #0056b3;
    }
    </style>
</head>
<body>
    <div class="container">
        <?php if (isset($mechanicDetails) && !isset($error)) { ?>
              <a href="#" class="print-button" onclick="window.print()">Print</a>
            <h2>Mechanic Details</h2>
            <?php
            if (!empty($mechanicDetails['image'])) {
                echo '<img src="' . $mechanicDetails['image'] . '" alt="Mechanic Image" style="width: 200px; height: auto; display: block; margin: 0 auto 20px;" class="fadeInAnimation">';
            } else {
                echo '<p>No image available</p>';
            }
            ?>
            <table class="fadeInAnimation">
                <tr>
                    <td>ID:</td>
                    <td><?php echo $mechanicDetails['id']; ?></td>
                </tr>
                <tr>
                    <td>First Name:</td>
                    <td><?php echo $mechanicDetails['firstname']; ?></td>
                </tr>
                <tr>
                    <td>Last Name:</td>
                    <td><?php echo $mechanicDetails['lastname']; ?></td>
                </tr>
                <tr>
                    <td>Email:</td>
                    <td><?php echo $mechanicDetails['mech_email']; ?></td>
                </tr>
                <tr>
                    <td>Phone Number:</td>
                    <td><?php echo $mechanicDetails['phone']; ?></td>
                </tr>
                <tr>
                    <td>Type Of Service:</td>
                    <td><?php echo $mechanicDetails['typeofservice']; ?></td>
                </tr>
                <!-- Add other details you want to display -->
            </table>


            
            <!-- Back button to navigate to mechanic.php -->
            <a href="mechanic.php" class="back-link">Back to Mechanics</a>
        <?php } else { ?>
            <p><?php echo isset($error) ? $error : 'No details available'; ?></p>
        <?php } ?>
    </div>
</body>
</html>
