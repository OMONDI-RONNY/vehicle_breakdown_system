<?php
// Include the database connection file
include '../includes/connection.php';

// Retrieve data from admin table
$sql_admin = "SELECT * FROM admin";
$result_admin = $conn->query($sql_admin);

// Retrieve data from mechanicreg table
$sql_mechanic = "SELECT * FROM mechanicreg";
$result_mechanic = $conn->query($sql_mechanic);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Data</title>
    <style>
        /* Basic reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Centering the table */
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f0f0f0;
        }

        /* Styling the table */
        table {
            width: 80%;
            border-collapse: collapse;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
        }

        /* Border styling for table */
        table {
            border: 2px solid #3498db;
            border-radius: 5px;
        }

        th, td {
            border-right: 2px solid #3498db;
            border-bottom: 2px solid #3498db;
        }

        th:last-child, td:last-child {
            border-right: none;
        }

        /* Responsive styles */
        @media screen and (max-width: 768px) {
            table {
                width: 100%;
            }
        }

        /* Button styles */
        .action-btn {
            padding: 6px 12px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .action-btn.edit {
            background-color: #008CBA;
        }

        .action-btn.delete {
            background-color: #f44336;
        }
    </style>
</head>
<body>
    <table>
        <thead>
            <tr>
                <th>Type</th>
                <th>Name</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Display admin data with action buttons
            while ($row = $result_admin->fetch_assoc()) {
                echo "<tr><td>Admin</td><td>".$row["firstname"]."</td><td>".$row["email"]."</td><td><a href='edit.php?type=admin&id=".$row["id"]."'><button class='action-btn edit'>Edit</button></a><a href='deleteuser.php?type=admin&id=".$row["id"]."'><button class='action-btn delete'>Delete</button></a></td></tr>";
            }

            // Display mechanic data with action buttons
            while ($row = $result_mechanic->fetch_assoc()) {
                echo "<tr><td>Mechanic</td><td>".$row["firstname"]."</td><td>".$row["mech_email"]."</td><td><a href='edit.php?type=mechanic&id=".$row["id"]."'><button class='action-btn edit'>Edit</button></a><a href='deleteuser.php?type=mechanic&id=".$row["id"]."'><button class='action-btn delete'>Delete</button></a></td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>
