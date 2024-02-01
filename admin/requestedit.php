<?php
session_start();
include '../includes/connection.php';

if (!isset($_SESSION['id'])) {
    header("Location: adminlogin.php");
    exit();
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $id = $_POST['owner'];
    $service = $_POST['services'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $model = $_POST['vmodel'];
    $rtype = $_POST['rtype'];
    $vreg = $_POST['vreg'];
   // $vname = $_POST['vname'];
   // $vehicle_type = $_POST['vehicle_type'];
    $address = $_POST['address'];
    //$password = $_POST['password'];
   // $confirmPassword = $_POST['confirmPassword'];

  /**  if ($password !== $confirmPassword) {
        $error = "Passwords do not match!";
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);**/

        $sql = "UPDATE request SET  contact_name=?, contact_phone=?, contact_email=?, contact _address=?,vehicle_registration=?, vehicle_model=?, issue_desc=? ,req_status=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssssi", $name, $phone,$email, $address,$vreg, $model, $service, $rtype,$id);
        $result = $stmt->execute();

        if ($result) {
            header("Location: request.php");
            exit();
        } else {
            $error = "Error updating record: " . $conn->error;
        }
    
}

$id = $_GET['id'];
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
        /* Your CSS styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 500px;
            margin: 50px auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out;
        }

        .container:hover {
            transform: scale(1.02);
        }

        h2 {
            text-align: center;
            color: #333;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            transition: all 0.3s ease;
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }

        input[type="text"],
        input[type="password"],
        input[type="submit"],
        input[type="reset"] {
            display: block;
            margin: 10px auto;
        }

        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
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
        <h2>Assign a Mechanic</h2>
        <?php if (!empty($error)) { ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php } ?>
        <form autocomplete="off" action="requestedit.php" method="POST" onsubmit="return validatePassword()">
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

             <label for="typeOfService">Request type:</label>
            <input type="text" id="typeOfService" value="<?php echo $row['req_status']; ?>" name="rtype" required readonly>



            <input type="submit" value="Assign">
        </form>
        <a href="request.php">Back to User List</a>
    </div>
</body>
</html>
