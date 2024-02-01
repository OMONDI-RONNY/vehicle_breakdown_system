<?php
session_start();
include '../includes/connection.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['mechanicId'];
    $fname = $_POST['firstName'];
    $lname = $_POST['lastName'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $photo = $_FILES['photo']['name'];

    if (empty($fname) || empty($lname) || empty($phone) || empty($email) || empty($photo)) {
        $error = "All fields are required!";
    } else {
        $targetDir = "uploads/";
        $targetFile = $targetDir . basename($_FILES["photo"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        $check = getimagesize($_FILES["photo"]["tmp_name"]);
        if ($check === false) {
            $error = "File is not an image.";
            $uploadOk = 0;
        }

        if ($_FILES["photo"]["size"] > 5000000) {
            $error = "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            $error = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        if ($uploadOk == 0) {
            $error = "Sorry, your file was not uploaded.";
        } else {
            if (move_uploaded_file($_FILES["photo"]["tmp_name"], $targetFile)) {
                $sql = "UPDATE admin SET firstname=?, lastname=?, phone=?, email=?, photo=? WHERE id=?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssssi", $fname, $lname, $phone, $email, $targetFile, $id);
                $result = $stmt->execute();

                if ($result) {
                    header("Location: index.php");
                    exit();
                } else {
                    $error = "Error updating record: " . $conn->error;
                }
            } else {
                $error = "Sorry, there was an error uploading your file.";
            }
        }
    }
}

$id = $_SESSION['id'];
$sql_select = "SELECT * FROM admin WHERE id=?";
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
    <title>Admin Profile</title>
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
        input[type="file"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            transition: all 0.3s ease;
        }

        input[type="text"]:focus,
        input[type="file"]:focus {
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }

        input[type="text"],
        input[type="file"],
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
        <h2>Update Profile</h2>
        <?php if (!empty($error)) { ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php } ?>
        <form autocomplete="off" action="adminprofile.php" method="POST" enctype="multipart/form-data">
            <label for="mechanicId">ID:</label>
            <input type="text" id="mechanicId" value="<?php echo $row['id']; ?>" name="mechanicId" required readonly>

            <label for="firstName">First Name:</label>
            <input type="text" id="firstName" value="<?php echo $row['firstname']; ?>" name="firstName" required>

            <label for="lastName">Last Name:</label>
            <input type="text" id="lastName" value="<?php echo $row['lastname']; ?>" name="lastName" required>

            <label for="phone">Phone:</label>
            <input type="text" id="phone" value="<?php echo $row['phone']; ?>" name="phone" required>

            <label for="email">Email:</label>
            <input type="text" id="email" value="<?php echo $row['email']; ?>" name="email" required>

            <label for="photo">Photo:</label>
            <input type="file" id="photo" name="photo" value="<?php echo $row['photo'];?>" accept="image/*" required>

            <input type="submit" value="Update">
        </form>
        <a href="index.php">Back to User List</a>
    </div>
</body>
</html>
