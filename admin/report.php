<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehicle Service Requests Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        h1 {
            text-align: center;
            margin-top: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            animation: fadeIn 0.5s ease-in-out;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #f5f5f5;
        }

        .filter-form {
            margin-top: 20px;
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-start; /* Adjusted alignment to the left */
        }

        .filter-form div {
            margin: 0 10px;
            text-align: center;
        }

        .filter-form label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .filter-form input[type="date"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .filter-form button {
            display: inline-block;
            padding: 10px 20px;
            margin-right: 10px;
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            text-decoration: none;
            cursor: pointer;
            border: none;
            border-radius: 5px;
            transition: background-color 0.3s ease-in-out;
            color: #fff;
        }

        .filter-form button:first-child {
            background-color: #007bff;
        }

        .filter-form button:last-child {
            background-color: #6c757d;
        }

        .filter-form button img {
            margin-right: 5px;
        }

        .no-data {
            text-align: center;
            color: red;
        }

        /* Animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        /* Print styles */
        @media print {
            body {
                font-size: 16px;
            }

            table {
                width: 100%;
                border-collapse: collapse;
            }

            th, td {
                border: 1px solid #ddd;
                padding: 8px;
                text-align: left;
            }

            th {
                background-color: #f2f2f2;
            }

            tr:hover {
                background-color: #f5f5f5;
            }

            .filter-form, .no-data {
                display: none;
            }
        }
    </style>
</head>
<body>

<h1>Vehicle Service Requests Report</h1>

<?php
// Include the connection file
include '../includes/connection.php';

// Handle filtering based on input dates
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $startDate = $_POST["start_date"];
    $endDate = $_POST["end_date"];

    // SQL query to fetch data within the specified date range
    $sql = "SELECT * FROM `request` 
            WHERE `req_date` BETWEEN ? AND ?
            ORDER BY `req_date`, `req_time`";

    // Prepare and bind the statement
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $startDate, $endDate);

    // Execute the query
    $stmt->execute();

    // Get the result set
    $result = $stmt->get_result();

    // Close statement
    $stmt->close();
} else {
    // If no filtering, fetch all data
    $sql = "SELECT * FROM `request`";
    $result = $conn->query($sql);
}

// Output HTML form for filtering
echo "<form class='filter-form' method='post' action='{$_SERVER["PHP_SELF"]}'>
        <div>
            <label for='start_date'>Start Date:</label>
            <input type='date' name='start_date' required>
            <button type='submit'>Filter</button>
        </div>

        <div>
            <label for='end_date'>End Date:</label>
            <input type='date' name='end_date' required>
            <button type='button' onclick='window.print()'>Print</button>
        </div>

        <div>
           
           
        </div>
      </form>";

// Check if there is data
if ($result->num_rows > 0) {
    // Output HTML table header
    echo "<table>
            <tr>
                <th>#</th>
                <th>Date</th>
                <th>Time</th>
                <th>Owner Name</th>
                <th>Vehicle Name</th>
                <th>Vehicle Reg. No.</th>
                <th>Assigned To</th>
                <th>Service</th>
                <th>Status</th>
                <th>Mechanic Assigned</th>
                <th>Service Cost</th>
                <th>Mechanic Notes</th>
                <th>Completion Date</th>
                <th>Completion Time</th>
                <th>Acceptance</th>
            </tr>";

    // Output data in HTML table rows
    $counter = 1;
    while($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$counter}</td>
                <td>{$row["req_date"]}</td>
                <td>{$row["req_time"]}</td>
                <td>{$row["contact_name"]}</td>
                <td>{$row["vehicle_model"]}</td>
                <td>{$row["vehicle_registration"]}</td>
                <td>{$row["mechanic_assigned"]}</td>
                <td>{$row["issue_desc"]}</td>
                <td>{$row["req_status"]}</td>
                <td>{$row["mechanic_assigned"]}</td>
                <td>{$row["service_cost"]}</td>
                <td>{$row["mechanic_notes"]}</td>
                <td>{$row["completion_date"]}</td>
                <td>{$row["completion_time"]}</td>
                <td>{$row["acceptance"]}</td>
              </tr>";
        $counter++;
    }

    // Close HTML table
    echo "</table>";
} else {
    // Display "No Data..." message within a table row
    echo "<table>
            <tr>
                <td class='no-data' colspan='15'>No Data...</td>
            </tr>
          </table>";
}

// Close connection
$conn->close();
?>

</body>
</html>
