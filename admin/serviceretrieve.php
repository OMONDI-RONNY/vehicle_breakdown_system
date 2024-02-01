<?php
// Include the database connection file
include '../includes/connection.php';

$results_per_page = 5; // Number of items per page

if (!isset($_GET['page'])) {
    $page = 1;
} else {
    $page = $_GET['page'];
}

$start_from = ($page - 1) * $results_per_page;

$search = '';
if (isset($_GET['search'])) {
    $search = $_GET['search'];
}

$sql = "SELECT service_id, service_name, description, provider_id, cost, date_added FROM services";
if (!empty($search)) {
    $sql .= " WHERE service_name LIKE '%$search%'";
}
$sql .= " LIMIT $start_from, $results_per_page";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Services List</title>
    <style>
           body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .action-buttons a {
            display: inline-block;
            padding: 5px 10px;
            margin-right: 5px;
            text-decoration: none;
            border-radius: 3px;
            color: #fff;
        }

        .view-button {
            background-color: #007bff;
        }

        .edit-button {
            background-color: #28a745;
        }

        .delete-button {
            background-color: #dc3545;
        }

        .pagination {
            margin-top: 20px;
        }

        .pagination a {
            text-decoration: none;
            padding: 5px 10px;
            margin-right: 5px;
            border: 1px solid #ddd;
            border-radius: 3px;
            color: #333;
        }

        .pagination a.active {
            background-color: #007bff;
            color: #fff;
        }
        .add-service-link {
            position: absolute;
            top: 20px;
            right: 20px;
            text-decoration: none;
            padding: 8px 16px;
            background-color: #007bff;
            color: #fff;
            border-radius: 5px;
        }
        /* Your CSS styles here */
        /* ... */
    </style>
</head>
<body>
     <a href="add_service.php" class="add-service-link">Add Service</a>
    <h2>Services List</h2>

    <form id="searchForm" method="GET">
        <input type="text" id="searchInput" name="search" placeholder="Search by service name" value="<?php echo $search; ?>">
        <input type="submit" value="Search">
    </form>

    <table id="servicesTable">
        <tr>
            <th>Service ID</th>
            <th>Service Name</th>
            <th>Description</th>
            <th>Provider ID</th>
            <th>Cost</th>
            <th>Date Added</th>
            <th>Action</th>
        </tr>

        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>".$row["service_id"]."</td>";
                echo "<td>".$row["service_name"]."</td>";
                echo "<td>".$row["description"]."</td>";
                echo "<td>".$row["provider_id"]."</td>";
                echo "<td>".$row["cost"]."</td>";
                echo "<td>".$row["date_added"]."</td>";
                echo "<td class='action-buttons'>";
                echo "<a href='view_service.php?id=".$row['service_id']."' class='view-button'>View</a>";
                echo "<a href='edit_service.php?id=".$row['service_id']."' class='edit-button'>Edit</a>";
                echo "<a href='delete_service.php?id=".$row['service_id']."' class='delete-button'>Delete</a>";
                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='7'>No results found.</td></tr>";
        }
        ?>
    </table>

    <?php
    $sql = "SELECT COUNT(*) AS total FROM services";
    if (!empty($search)) {
        $sql .= " WHERE service_name LIKE '%$search%'";
    }
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $total_pages = ceil($row["total"] / $results_per_page);
    ?>

    <div class='pagination'>
        <?php
        for ($i = 1; $i <= $total_pages; $i++) {
            echo "<a href='?page=".$i."&search=".$search."' class='".($page == $i ? 'active' : '')."'>".$i."</a>";
        }
        ?>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('searchInput');

            searchInput.addEventListener('input', function () {
                document.getElementById('searchForm').submit();
            });
        });
    </script>

</body>
</html>

<?php $conn->close(); ?>
