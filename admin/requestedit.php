


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mechanic Details Form</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            max-width: 1000px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        h2 {
            background-color: #3498db;
            color: #ffffff;
            padding: 20px;
            margin: 0;
            font-size: 28px;
            text-align: center;
            border-radius: 10px 10px 0 0;
            width: 100%;
        }

        form {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            width: 100%;
            padding: 20px;
            box-sizing: border-box;
        }

        .column {
            flex: 1;
            box-sizing: border-box;
        }

        label {
            display: block;
            margin-bottom: 12px;
            color: #333;
            font-size: 16px;
        }

        input, select {
            width: calc(100% - 20px);
            padding: 14px;
            margin-bottom: 20px;
            border: 1px solid #3498db;
            border-radius: 4px;
            box-sizing: border-box;
            transition: all 0.3s ease;
            font-size: 16px;
            background-color: #f9f9f9;
        }

        input:focus, select:focus {
            border-color: #0078d4;
            box-shadow: 0 0 8px rgba(0, 120, 212, 0.5);
        }

        select {
            appearance: none;
            padding: 14px 20px;
            background: #fff url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%230078d4" width="18px" height="18px"><path d="M7 10l5 5 5-5z"/></svg>') no-repeat right 16px center;
            background-size: 18px;
        }

        .button-container {
            display: flex;
            justify-content: center;
            width: 100%;
        }

        input[type="submit"] {
            background-color: #3498db;
            color: #ffffff;
            padding: 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            font-size: 18px;
            font-weight: bold;
        }

        input[type="submit"]:hover {
            background-color: #0078d4;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Request Details Form</h2>

        <?php
        session_start();
            // Assuming you have a connection to the database
            include '../includes/connection.php';
            $sql = "SELECT * FROM mechanicreg";
              $mechanicId =$_GET['id']; // Adjust this based on your URL structure
              $_SESSION['id']=$mechanicId;
$result = $conn->query($sql);

// Store fetched services in an array
$services = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $services[] = $row['mech_id'];
    }
}

          

            $sql = "SELECT * FROM request WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $mechanicId);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();


            }
          
        ?>

        <form action="assign.php" method="post" class="form">
            <div class="column">
                <label for="firstname">User Name:</label>
                <input readonly type="text" id="firstname" name="firstname" value="<?php echo $row['contact_name']; ?>" required>

                <label for="lastname">User Email:</label>
                <input readonly type="text" id="lastname" name="lastname" value="<?php echo $row['contact_email']; ?>" required>

                <label for="mech_id">User ID:</label>
                <input readonly type="text" id="mech_id" name="mech_id" value="<?php echo $row['user_id']; ?>" required>

                <label for="phone">Phone:</label>
                <input readonly type="tel" id="phone" name="phone" value="<?php echo $row['contact_phone']; ?>" required>

                 <label for="firstname">Contact Address:</label>
                <input readonly type="text" id="firstname" name="firstname" value="<?php echo $row['contact_address']; ?>" required>

                 <label for="firstname">County:</label>
                <input readonly type="text" id="firstname" name="firstname" value="<?php echo $row['county']; ?>" required>

                



            </div>

            <div class="column">

                 <label for="firstname">Sub-county:</label>
                <input readonly type="text" id="firstname" name="firstname" value="<?php echo $row['sub_county']; ?>" required>


                 <label for="firstname">Vehicle Model:</label>
                <input readonly type="text" id="firstname" name="firstname" value="<?php echo $row['vehicle_model']; ?>" required>

                 <label for="firstname">Issue Reported:</label>
                <input readonly type="text" id="firstname" name="firstname" value="<?php echo $row['issue_desc']; ?>" required>

                 <label for="firstname">Date Reported:</label>
                <input readonly type="date" id="firstname" name="firstname" value="<?php echo $row['req_date']; ?>" required>

                 <label for="firstname">Time Reported:</label>
                <input readonly type="time" id="firstname" name="firstname" value="<?php echo $row['req_time']; ?>" required>

                


                   <label for="typeOfService">Assign Mechanic:</label>
            <select id="typeOfService" name="typeOfService" required>
                <?php
                // Loop through the fetched services and create options in the select dropdown
                foreach ($services as $service) {
                    echo "<option value='$service'>$service</option>";
                }
                ?>
            </select>
               




               
            </div>

            <div class="button-container">
                <input type="submit" value="Assign Mechanic">
            </div>
        </form>

        
    </div>
</body>
</html>
