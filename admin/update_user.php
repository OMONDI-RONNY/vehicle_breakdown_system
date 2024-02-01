<?php
// Database connection code remains unchanged
include '../includes/connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve other form data
    $id = $_POST['id'];
    $firstName = $_POST['firstname'];
    $lastName = $_POST['lastname'];
    $phoneNumber = $_POST['phone'];
    $email = $_POST['email'];
    $model=$_POST['model'];
  //  $password = $_POST['password'];

    // File upload handling
    $targetDir = "uploads/"; // Directory where uploaded files will be saved
    $targetFile = $targetDir . basename($_FILES["photo"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    if (isset($_POST["submit"])) {
        $check = getimagesize($_FILES["photo"]["tmp_name"]);
        if ($check !== false) {
            echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            $error= "File is not an image.";
            $uploadOk = 0;
        }
    }

    // Check file size (limit it to 5MB here, change as needed)
    if ($_FILES["photo"]["size"] > 5000000) {
        $error= "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats (you can specify allowed formats here)
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif") {
        $error= "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        $error= "Sorry, your file was not uploaded.";
    } else {
        // If everything is ok, try to upload file
        if (move_uploaded_file($_FILES["photo"]["tmp_name"], $targetFile)) {
            $error="The file " . htmlspecialchars(basename($_FILES["photo"]["name"])) . " has been uploaded.";

            // SQL to update data in the table including the photo path
            $sql = "UPDATE vehicleowners SET firstname='$firstName', lastname='$lastName', phone='$phoneNumber', email='$email', vehicleModel='$model', photo='$targetFile' WHERE vehicleID='$id'";

            if ($conn->query($sql) === TRUE) {
                $error= "Record updated successfully";
            } else {
                $error= "Error updating record: " . $conn->error;
            }
        } else {
            $error= "Sorry, there was an error uploading your file.";
        }
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update User Information</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
        }
        .container {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }
        h2 {
            text-align: center;
        }
        label {
            display: block;
            margin: 10px 0;
        }
        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 90%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="submit"] {
            background-color: #007BFF;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Update User Information</h2>
        <?php if(isset($error)) { ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php } ?>
          <form action="update_user.php" method="post" enctype="multipart/form-data">
            <label for="id">vehicleID:</label>
            <input type="text" id="id" name="id" required>

            <label for="firstName">First Name:</label>
            <input type="text" id="firstName" name="firstname" required>

            <label for="lastName">Last Name:</label>
            <input type="text" id="lastName" name="lastname" required>

            <label for="phoneNumber">Phone Number:</label>
            <input type="text" id="phoneNumber" name="phone" required>

            <label for="phoneNumber">Vehicle Model:</label>
            <input type="text" id="phoneNumber" name="model" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

             <label for="photo">Upload Photo:</label>
            <input type="file" id="photo" name="photo"><br><br>

           
            <input type="submit" value="Update Information">
        </form>
    </div>
</body>
</html>

