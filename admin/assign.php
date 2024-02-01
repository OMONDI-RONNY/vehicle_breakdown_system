<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Assignment Form</title>
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
        input[type="text"] {
            width: 90%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        select {
            width: 95%;
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
        <h2>Request Assignment Form</h2>
        <form>
             <label for="requestId">Request ID:</label>
            <input type="text" id="requestId" name="requestId" required>

            <label for="requestType">Request Type:</label>
            <select id="requestType" name="requestType" required>
                <option value="">Select the request type</option>
                <option value="Maintenance">Maintenance</option>
                <option value="Repair">Repair</option>
                <option value="Service">Service</option>
                <!-- Add more request types as needed -->
            </select>

           
            <label for="vehicleType">Type of Vehicle:</label>
            <input type="text" id="vehicleType" name="vehicleType" required>

            <label for="assignMechanic">Assign Mechanic:</label>
              <select id="mechanic" name="mechanic" required>
                <option value="">Select Mechanic</option>
                <option value="Maintenance">Maurice Otieno</option>
                <option value="Repair">James Kamau</option>
                <option value="Service">John Okello</option>
                <!-- Add more request types as needed -->
            </select>
            <br><br>

            <input type="submit" value="Assign Request">
        </form>
    </div>
</body>
</html>
