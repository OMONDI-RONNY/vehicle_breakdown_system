<?php
  include '../includes/connection.php';
  session_start();
  $username = $_SESSION['username'];
 // include '../includes/connection.php';
if (!isset($_SESSION['id'])) {
    header("Location: adminlogin.php");
    exit();
}
$inactive = 1800; // 5 minutes in seconds
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $inactive)) {
    // Unset all of the session variables
    $_SESSION = array();

    // Destroy the session
    session_destroy();

    header("Location: adminlogin.php");
    exit();
}

// Update last activity time
$_SESSION['last_activity'] = time();

$id = $_SESSION['id'];
$sql7="SELECT * FROM admin WHERE id=$id";
$result7=mysqli_query($conn,$sql7);
$row7=mysqli_fetch_assoc($result7);
$name7=$row7['firstname'];
$name8=$row7['lastname'];


// SQL query to count records in a table (replace 'your_table' with your actual table name)
$sql = "SELECT COUNT(*) as count_records FROM mechanicreg";
$sql1 = "SELECT COUNT(*) as count_records FROM vehicleowners";
$sql2 = "SELECT COUNT(*) as count_records FROM feedback";
$sql3 = "SELECT COUNT(*) as count_records FROM request";

// Execute the query
$result = mysqli_query($conn, $sql);
$result1 = mysqli_query($conn, $sql1);
$result2 = mysqli_query($conn, $sql2);
$result3 = mysqli_query($conn, $sql3);

// Fetch the result as an associative array
$row = mysqli_fetch_assoc($result);
$row1 = mysqli_fetch_assoc($result1);
$row2 = mysqli_fetch_assoc($result2);
$row3 = mysqli_fetch_assoc($result3);

// Display the count of records
$mechanic= $row['count_records'];
$users= $row1['count_records'];
$feedback= $row2['count_records'];
$requests= $row3['count_records'];

$sql_select = "SELECT * FROM admin WHERE id=$id";
$result_select = $conn->query($sql_select);
$row = $result_select->fetch_assoc();

// Close the connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin Dashboard</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- JQVMap -->
  <link rel="stylesheet" href="plugins/jqvmap/jqvmap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
  <!-- summernote -->
  <link rel="stylesheet" href="plugins/summernote/summernote-bs4.min.css">
   <title>CRUD Application</title>
      <style>
        /* Existing styles remain unchanged */
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .action-buttons {
            display: flex;
            gap: 5px;
        }
        .action-buttons a {
            padding: 5px;
            border-radius: 3px;
            text-decoration: none;
        }
        .view-btn {
            background-color: #4caf50;
            color: white;
        }
        .edit-btn {
            background-color: #2196f3;
            color: white;
        }
        .delete-btn {
            background-color: #f44336;
            color: white;
        }
        /* Additional styles for search box */
        .search-container {
            margin-bottom: 15px;
            margin-left: 20%;
        }
        .search-container input[type=text] {
            padding: 6px;
            margin-right: 5px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }
        .search-container button {
            padding: 6px 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
            background-color: #f2f2f2;
            cursor: pointer;
        }
        /* Additional styles for filter */
        .filter-container {
            margin-bottom: 15px;
        }
        .filter-container select {
            padding: 6px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }
        /* Additional styles for pagination */
        .pagination {
            margin-top: 15px;
        }
        .pagination a {
            color: black;
            float: left;
            padding: 8px 16px;
            text-decoration: none;
            transition: background-color .3s;
            border: 1px solid #ddd;
            margin: 0 4px;
        }
        .pagination a.active {
            background-color: #4CAF50;
            color: white;
            border: 1px solid #4CAF50;
        }
        /* ... */
        /* Advanced CSS Styles */
.status-box {
    display: inline-block;
    padding: 5px 10px;
    border-radius: 3px;
    color: white;
    font-weight: bold;
}

.status-text {
    margin: 0; /* Remove default margin */
}

    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Preloader -->
  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60">
  </div>

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="index.php" class="nav-link">Home</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Contact</a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Navbar Search -->
      <li class="nav-item">
        <a class="nav-link" data-widget="navbar-search" href="#" role="button">
          <i class="fas fa-search"></i>
        </a>
        <div class="navbar-search-block">
          <form class="form-inline">
            <div class="input-group input-group-sm">
              <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
              <div class="input-group-append">
                <button class="btn btn-navbar" type="submit">
                  <i class="fas fa-search"></i>
                </button>
                <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                  <i class="fas fa-times"></i>
                </button>
              </div>
            </div>
          </form>
        </div>
      </li>

     
      </li>
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
          <i class="fas fa-th-large"></i>
        </a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index.php" class="brand-link">
      <img src="dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light"><p id="demo"></p></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <?php if ($row['photo'] !== '') { ?>
                        <img style="border-radius:50%;" src="<?php echo $row['photo']; ?>" alt="Profile Image" class="profile-img">
                    <?php } else { ?>
                        <p><img style="border-radius: 50%;" src="dist/img/avatar.png"></p>
                    <?php } ?>
        </div>
        <div class="info">
          <a href="#" class="d-block"><?php echo strtoupper($name7.' '.$name8); ?></a>
        </div>
      </div>

      <!-- SidebarSearch Form -->
      <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item menu-open">
            <a href="#" class="nav-link active">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
               <li class="nav-item">
                <a href="./index.php" class="nav-link active">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Dashboard</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="mechanic.php" class="nav-link active">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Mechanics</p>
                </a>
              </li>
               <li class="nav-item">
                <a href="users.php" class="nav-link active">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Users</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="request.php" class="nav-link active">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Requests</p>
                </a>
              </li>
               <li class="nav-item">
                <a href="feedback.php" class="nav-link active">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Feed Back</p>
                </a>
              </li>
             
             
              
            </ul>
          </li>
         
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-table"></i>
              <p>
                Maintainance
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              
              <li class="nav-item">
                <a href="categori.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Category List</p>
                </a>
              </li>
               <li class="nav-item">
                <a href="servicelist.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Service List</p>
                </a>
              </li>
               <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>User List</p>
                </a>
              </li>
               <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Settings</p>
                </a>
              </li>
             
            </ul>
          </li>
          
         
          
         
         
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Dashboard</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active"><a href="logout.PHP">Logout</a></li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3> <?php echo $feedback ?></h3>

                <p> Feedback</p>
                <p></p>
              </div>
              <div class="icon">
                <i class="ion ion-bag"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3><?php echo $requests; ?><sup style="font-size: 20px"></sup></h3>

                <p>Requests</p>
              </div>
              <div class="icon">
                <i class="ion ion-stats-bars"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3><?php echo $users; ?></h3>

                <p>Users</p>
              </div>
              <div class="icon">
                <i class="ion ion-person-add"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
                <h3><?php echo $mechanic; ?></h3>

                <p>Mechanics</p>
              </div>
              <div class="icon">
                <i class="ion ion-pie-graph"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
        </div>
        <!-- /.row -->
        <!-- Main row -->
        <div style="margin-left: 0.5%;" class="row">
           <h2>Manage Mechanics</h2>
   
   
    <!-- Search Box -->
    


    <!-- Search Box -->
    <div class="search-container">
        <input type="text" id="searchInput" placeholder="Search...">
        <button onclick="searchUsers()"><i class="fas fa-search"></i> Search</button>
    </div>

    <!-- Filter Records -->
    <div class="filter-container">
        Show: 
        <select onchange="changeLimit(this.value)">
            <option value="5">5</option>
            <option value="7">7</option>
            <option value="14">14</option>
            <!-- Add more options as needed -->
        </select>
       
    </div>

    <!-- Display Records -->
    <?php
        include '../includes/connection.php';

        $limit = isset($_GET['limit']) ? $_GET['limit'] : 5; // Records per page (default: 5)
        $page = isset($_GET['page']) ? $_GET['page'] : 1; // Current page, default is 1

        $start = ($page - 1) * $limit;

        $search = isset($_GET['search']) ? $_GET['search'] : ''; // Search keyword

        // Modify the SQL query to include search and limit
        $sql = "SELECT * FROM mechanicreg";
        if ($search !== '') {
            $sql .= " WHERE name LIKE '%$search%' OR email LIKE '%$search%' OR age LIKE '%$search%'";
        }
        $sql .= " LIMIT $start, $limit";

        $result = $conn->query($sql);

        echo "<table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>";

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $status = $row['status'];
            $statusText = $status == 1 ? 'Active' : 'Inactive';
            $statusColor = $status == 1 ? 'green' : 'red';

            echo "<tr>
                    <td>" . $row['id'] . "</td>
                    <td>" . $row['firstname'] . "</td>
                    <td>" . $row['mech_email'] . "</td>
                    <td>
                        <div class='status-box' style='background-color: $statusColor;'>
                            <span class='status-text'>$statusText</span>
                        </div>
                    </td>
                    <td class='action-buttons'>
                        <a href='mechanicview.php?action=view&id=" . $row['id'] . "' class='view-btn'><i class='fas fa-eye'></i> View</a>
                        <a href='mechedit.php?action=edit&id=" . $row['id'] . "' class='edit-btn'><i class='fas fa-edit'></i> Edit</a>
                        <a href='delete.php?action=delete&id=" . $row['id'] . "' class='delete-btn' onclick='return confirm(\"Are you sure you want to delete?\");'><i class='fas fa-trash-alt'></i> Delete</a>
                    </td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='5'>No Mechanics found</td></tr>";
    }

    echo "</table>";

        // Count total number of records
        $sqlCount = "SELECT COUNT(*) AS total FROM mechanicreg";
        $resultCount = $conn->query($sqlCount);
        $row = $resultCount->fetch_assoc();
        $totalRecords = $row['total'];

        // Calculate total pages
        $totalPages = ceil($totalRecords / $limit);

        // Pagination links
        echo "<div class='pagination'>";
        for ($i = 1; $i <= $totalPages; $i++) {
            echo "<a href='mechanic.php?page=$i&limit=$limit'";
            if ($page == $i) {
                echo " class='active'";
            }
            echo ">$i</a>";
        }
        echo "</div>";
    ?>

    <script>
        function searchUsers() {
            var input, filter, table, tr, td, i, j, txtValue;
            input = document.getElementById("searchInput");
            filter = input.value.toUpperCase();
            table = document.querySelector("table");
            tr = table.getElementsByTagName("tr");

            for (i = 1; i < tr.length; i++) {
                var found = false;
                td = tr[i].getElementsByTagName("td");
                for (j = 0; j < td.length; j++) {
                    if (td[j]) {
                        txtValue = td[j].textContent || td[j].innerText;
                        if (txtValue.toUpperCase().indexOf(filter) > -1) {
                            found = true;
                            break;
                        }
                    }
                }
                if (found) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }

        function changeLimit(value) {
            var searchInput = document.getElementById('searchInput').value;
            window.location.href = 'mechanic.php?search=' + searchInput + '&limit=' + value;
        }
    </script>



          
  </div>
 

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- ChartJS -->
<script src="plugins/chart.js/Chart.min.js"></script>
<!-- Sparkline -->
<script src="plugins/sparklines/sparkline.js"></script>
<!-- JQVMap -->
<script src="plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<!-- jQuery Knob Chart -->
<script src="plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="plugins/moment/moment.min.js"></script>
<script src="plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<script src="plugins/summernote/summernote-bs4.min.js"></script>
<!-- overlayScrollbars -->
<script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="dist/js/pages/dashboard.js"></script>
<script type="text/javascript">
    const hour = new Date().getHours();
    let greeting;
    if (hour<6) {
      greeting = "Good Morning";
    }else if (hour<12) {
      greeting = "Good Morning!";
    }else if (hour<14) {
      greeting = "Good Afternoon!";
    }else{
      greeting = "Good Evening!";
    }
    document.getElementById("demo").innerHTML = greeting;


    </script>

</body>
</html>
