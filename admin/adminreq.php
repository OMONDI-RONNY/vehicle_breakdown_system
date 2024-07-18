<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Approval Requests</title>
    <style>
        /* CSS styles for the modal */
        .modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
            padding-top: 60px;
        }
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto; /* 5% from the top and centered */
            padding: 20px;
            border-radius: 8px;
            max-width: 400px; /* Reduce width */
            width: 80%; /* Could be more or less, depending on screen size */
        }
        /* Close button */
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        .approve-btn {
            background-color: #4caf50;
            color: #fff;
            border: none;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
        }
        .approve-btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <h2>Admin Approval Requests</h2>
    <table>
        <thead>
            <tr>
                <th>Username</th>
                <th>Email</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Include the database connection
            include '../includes/connection.php';

            // Query to select users who have requested admin approval
            $sql = "SELECT name, email FROM admins";
            $result = mysqli_query($conn, $sql);

            // Check if there are any users
            if ($result && mysqli_num_rows($result) > 0) {
                // Output data of each row
                while ($row = mysqli_fetch_assoc($result)) {
                    $username = htmlspecialchars($row["name"]); // Sanitize username
                    $email = htmlspecialchars($row["email"]); // Sanitize email
                    echo "<tr>";
                    echo "<td>" . $username . "</td>";
                    echo "<td>" . $email . "</td>";
                    echo "<td><button class='approve-btn' onclick='openModal(\"$username\")'>Approve</button></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='3'>No users have requested admin approval.</td></tr>";
            }

            // Close database connection
            mysqli_close($conn);
            ?>
        </tbody>
    </table>

    <!-- Modal -->
    <div id="myModal" class="modal">
        <!-- Modal content -->
        <div class="modal-content">
            <span class="close">&times;</span>
            <h3>Approve User</h3>
            <form action="approve_admin.php" method="POST">
                <input type="hidden" id="approveUsername" name="username">
                <label for="adminPassword">Enter Admin Password:</label><br>
                <input type="password" id="adminPassword" name="adminPassword" required><br><br>
                <input type="submit" value="Approve">
            </form>
        </div>
    </div>

    <script>
        // Get the modal
        var modal = document.getElementById("myModal");

        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];

        // When the user clicks on the button, open the modal
        function openModal(username) {
            document.getElementById("approveUsername").value = username;
            modal.style.display = "block";
        }

        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
            modal.style.display = "none";
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
</body>
</html>
