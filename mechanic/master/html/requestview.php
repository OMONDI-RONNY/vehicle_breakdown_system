<?php
// Include the connection file
include '../includes/connection.php';
session_start();



if (isset($_GET['id'])) {
    $mechanicId = $_GET['id'];

    // Query to fetch details of the selected mechanic
    $sql = "SELECT * FROM request WHERE id = $mechanicId";
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
       body {
            font-family: Arial, sans-serif;
            background-color: #ecf0f1; /* Updated background color */
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
            border: 2px solid #3498db;
        }

        h2 {
            color: #333;
            text-align: center;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
        }

        p {
            color: #555;
            margin-bottom: 10px;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .fadeInAnimation {
            animation: fadeIn 1s ease-out;
        }

        .back-link {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 20px;
            background-color: #3498db;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .back-link:hover {
            background-color: #2980b9;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #3498db;
            color: #fff;
        }

        .print-button {
            position: absolute;
            top: 10px;
            right: 10px;
            padding: 10px;
            text-align: center;
            background-color: #3498db;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .print-button:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if (isset($mechanicDetails) && !isset($error)) { ?>
              <a href="#" class="print-button" onclick="window.print()">Print</a>
            <h2>User Details</h2>
            <?php
            if (!empty($mechanicDetails['photo'])) {
                echo '<img src="' . $mechanicDetails['photo'] . '" alt="Mechanic Image" style="width: 200px; height: auto; display: block; margin: 0 auto 20px;" class="fadeInAnimation">';
            } else {
                echo '<p>No image available</p>';
            }
            ?>
            <table class="fadeInAnimation">
                <tr>
                    <td>User ID:</td>
                    <td><?php echo $mechanicDetails['id']; ?></td>
                </tr>
                <tr>
                    <td>Request ID:</td>
                    <td><?php echo $mechanicDetails['user_id']; ?></td>
                </tr>
                <tr>
                    <td>User Name:</td>
                    <td><?php echo $mechanicDetails['contact_name']; ?></td>
                </tr>
                <tr>
                    <td>User Email:</td>
                    <td><?php echo $mechanicDetails['contact_email']; ?></td>
                </tr>
                <tr>
                    <td>User Phone Number:</td>
                    <td><?php echo $mechanicDetails['contact_phone']; ?></td>
                </tr>
                <tr>
                    <td>Vehicle Model:</td>
                    <td><?php echo $mechanicDetails['vehicle_model']; ?></td>
                </tr>
                 <tr>
                    <td>Vehicle Registration:</td>
                    <td><?php echo $mechanicDetails['vehicle_registration']; ?></td>
                </tr>
                
                 <tr>
                    <td>Issue Encounterd:</td>
                    <td><?php echo $mechanicDetails['issue_desc']; ?></td>
                </tr>
                 <tr>
                    <td>Request Date:</td>
                    <td><?php echo $mechanicDetails['req_date']; ?></td>
                </tr>
                 <tr>
                    <td>Request Time:</td>
                    <td><?php echo $mechanicDetails['req_time']; ?></td>
                </tr>
                 <tr>
                    <td>Address:</td>
                    <td><?php echo $mechanicDetails['contact_address']; ?></td>
                </tr>
                <!-- Add other details you want to display -->
            </table>


            
            <!-- Back button to navigate to mechanic.php -->
            <a href="index.php" class="back-link">Back</a>
        <?php } else { ?>
            <p><?php echo isset($error) ? $error : 'No details available'; ?></p>
        <?php } ?>
    </div>
</body>
</html>
