<?php

include '../includes/connection.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email=$_POST['email'];
    $name=$_POST['name'];
    $comment=$_POST['comment'];
  

    // Insert data into the database
    $sql = "INSERT INTO feedback (email,name,message) VALUES ('$email', '$name','$comment')";

    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close the database connection
$conn->close();



?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback Form</title>
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
            margin-top: 10%;
        }
        h2 {
            text-align: center;
        }
        label {
            display: block;
            margin: 10px 0;
        }
        input[type="text"],
        textarea {
            width: 90%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        textarea {
            height: 150px; /* Adjust the height as needed */
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
        <h2>Feedback Form</h2>
        <form action="feed.php" method="POST">
            <label for="email">Email:</label>
            <input type="text" id="email" name="email" required>

            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="comment">Comment:</label>
            <textarea id="comment" name="comment" required></textarea><br><br>

            <input type="submit" value="Submit">
        </form>
    </div>
</body>
</html>
