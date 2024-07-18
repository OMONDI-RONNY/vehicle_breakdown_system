<?php
session_start();
include '../includes/connection.php';
$id = $_GET['id'];
$_SESSION['req']=$id;
$sql_select = "SELECT * FROM request WHERE id=?";
$stmt_select = $conn->prepare($sql_select);
$stmt_select->bind_param("i", $id);
$stmt_select->execute();
$result_select = $stmt_select->get_result();
$row = $result_select->fetch_assoc();

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign a mechanic</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            background-image: url('bg.webp');
        }

        .container {
            max-width: 500px;
            margin: 50px auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out;
            border: 2px solid #3498db;
        }

        .container:hover {
            transform: scale(1.02);
            border-color: #007bff;
        }

        h2 {
            text-align: center;
            color: #007bff;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
        }

        input[type="text"],
        input[type="password"],
        select
         {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            transition: all 0.3s ease;
            background-color: #f9f9f9;
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }

        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            display: block;
            margin: 10px auto;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        a {
            color: #007bff;
            text-decoration: none;
            display: inline-block;
            transition: color 0.3s ease;
        }

        a:hover {
            color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Accept Request</h2>
        <?php if (!empty($error)) { ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php } ?>
        <form autocomplete="off" action="process-acceptance.php" method="POST" onsubmit="return validatePassword()">
             <label for="mechanicId">Owner ID:</label>
            <input type="text" id="mechanicId" value="<?php echo $row['id']; ?>" name="owner" required readonly>

            <label for="lastName">Last Name:</label>
            <input type="text" id="lastName" value="<?php echo $row['contact_name']; ?>" name="name" required readonly>

           
            <label for="phone">Phone:</label>
            <input type="text" id="phone" value="<?php echo $row['contact_phone']; ?>" name="phone" required readonly>

            <label for="email">Email:</label>
            <input type="text" id="email" value="<?php echo $row['contact_email']; ?>" name="email" required readonly>

            <label for="typeOfService">Address:</label>
            <input type="text" id="typeOfService" value="<?php echo $row['contact_address']; ?>" name="address" required readonly>

           

            <label for="typeOfService">Vehicle Registration:</label>
            <input type="text" id="typeOfService" value="<?php echo $row['vehicle_registration']; ?>" name="vreg" required readonly>

             <label for="typeOfService">Vehicle Model:</label>
            <input type="text" id="typeOfService" value="<?php echo $row['vehicle_model']; ?>" name="vmodel" required readonly>

            <label for="typeOfService">Services:</label>
            <input type="text" id="typeOfService" value="<?php echo $row['issue_desc']; ?>" name="services" required readonly>
             <label for="acceptReject">Accept/Reject:</label>
            <select id="acceptReject" name="acceptReject" required>
                <option value="1">Accept</option>
                <option value="0">Reject</option>
            </select>

           
            <input type="submit" value="Accept">
        </form>
        <a href="index.php">Back to User List</a>
    </div>
</body>
</html>
