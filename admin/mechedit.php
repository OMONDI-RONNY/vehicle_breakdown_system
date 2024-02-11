<?php
session_start();
include '../includes/connection.php';
//session_start();

if (!isset($_SESSION['id'])) {
    header("Location: adminlogin.php");
    exit();
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fname = $_POST['firstName'];
    $lname = $_POST['lastName'];
    $id = $_POST['mechanicId'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $model = $_POST['typeOfService'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    if ($password !== $confirmPassword) {
        $error = "Passwords do not match!";
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $sql = "UPDATE mechanicreg SET firstname=?, lastname=?, phone=?, mech_email=?, typeofservice=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssi", $fname, $lname, $phone, $email, $model, $id);
        $result = $stmt->execute();

        if ($result) {
            header("Location: mechanic.php");
            exit();
        } else {
            $error = "Error updating record: " . $conn->error;
        }
    }
}

$id = $_GET['id'];
$sql_select = "SELECT * FROM mechanicreg WHERE id=?";
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
    <title>Update Profile</title>
    <style>
        /* Your CSS styles */
     body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f0f0f0;
    margin: 0;
    padding: 0;
}

.container {
    max-width: 500px;
    margin: 50px auto;
    padding: 30px;
    background-color: #f9f9f9;
    border-radius: 10px;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease-in-out;
}

.container:hover {
    transform: scale(1.02);
}

h2 {
    text-align: center;
    color: #007bff; /* Smart blue color */
    margin-bottom: 20px;
    font-size: 24px;
}

label {
    display: block;
    margin-bottom: 8px;
    color: #555;
}

input[type="text"],
input[type="password"] {
    width: 100%;
    padding: 12px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 6px;
    transition: all 0.3s ease;
}

input[type="text"]:focus,
input[type="password"]:focus {
    border-color: #007bff;
    box-shadow: 0 0 8px rgba(0, 123, 255, 0.5);
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
    padding: 12px 20px;
    border: none;
    border-radius: 6px;
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

/* Advanced styling for form elements */
form {
    display: flex;
    flex-direction: column;
}

label,
input[type="submit"] {
    margin-top: 15px;
}

/* Style error message */
p {
    color: #d9534f; /* Bootstrap red color */
    margin-top: 10px;
}

/* Add fadeIn animation for the container */
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.container {
    animation: fadeIn 0.8s ease-out;
}

    </style>
</head>
<body>
    <div class="container">
        <h2>Update Profile</h2>
        <?php if (!empty($error)) { ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php } ?>
        <form autocomplete="off" action="mechedit.php" method="POST" onsubmit="return validatePassword()">
             <label for="mechanicId">Mechanic ID:</label>
            <input type="text" id="mechanicId" value="<?php echo $row['id']; ?>" name="mechanicId" required readonly>

            <label for="firstName">First Name:</label>
            <input type="text" id="firstName" value="<?php echo $row['firstname']; ?>" name="firstName" required>

            <label for="lastName">Last Name:</label>
            <input type="text" id="lastName" value="<?php echo $row['lastname']; ?>" name="lastName" required>

           
            <label for="phone">Phone:</label>
            <input type="text" id="phone" value="<?php echo $row['phone']; ?>" name="phone" required>

            <label for="email">Email:</label>
            <input type="text" id="email" value="<?php echo $row['mech_email']; ?>" name="email" required>

            <label for="typeOfService">Type of Service:</label>
            <input type="text" id="typeOfService" value="<?php echo $row['typeofservice']; ?>" name="typeOfService" required>

            <input type="submit" value="Update">
        </form>
        <a href="mechanic.php">Back to Mechanic List</a>
    </div>
</body>
</html>
