<?php
session_start();
include '../includes/connection.php';

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
$username = $_SESSION['username'];

// Check for existing active session for this user
$sql_check_session = "SELECT * FROM active_sessions WHERE user_id = $id";
$result_check_session = $conn->query($sql_check_session);

if ($result_check_session && $result_check_session->num_rows > 0) {
    // Invalidate previous session
    $conn->query("DELETE FROM active_sessions WHERE user_id = $id");
}

// Store the new session
$session_id = session_id();


$conn->query("INSERT INTO active_sessions (user_id, session_id) VALUES ($id, '$session_id')");

// Rest of your code remains unchanged below...
// ...

$sql_select = "SELECT * FROM admin WHERE id=$id";
$result7=mysqli_query($conn,$sql_select);
$row7=mysqli_fetch_assoc($result7);
$name7=$row7['firstname'];
$name8=$row7['lastname'];
$result_select = $conn->query($sql_select);
$row = $result_select->fetch_assoc();


 

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
  <style type="text/css">
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

      <!-- Messages Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-comments"></i>
          <span class="badge badge-danger navbar-badge">3</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <a href="#" class="dropdown-item">
            <!-- Message Start -->
            <div class="media">
              <img src="dist/img/user1-128x128.jpg" alt="User Avatar" class="img-size-50 mr-3 img-circle">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  Brad Diesel
                  <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
                </h3>
                <p class="text-sm">Call me whenever you can...</p>
                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
              </div>
            </div>
            <!-- Message End -->
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <!-- Message Start -->
            <div class="media">
              <img src="dist/img/user8-128x128.jpg" alt="User Avatar" class="img-size-50 img-circle mr-3">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  John Pierce
                  <span class="float-right text-sm text-muted"><i class="fas fa-star"></i></span>
                </h3>
                <p class="text-sm">I got your message bro</p>
                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
              </div>
            </div>
            <!-- Message End -->
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <!-- Message Start -->
            <div class="media">
              <img src="dist/img/user3-128x128.jpg" alt="User Avatar" class="img-size-50 img-circle mr-3">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  Nora Silvester
                  <span class="float-right text-sm text-warning"><i class="fas fa-star"></i></span>
                </h3>
                <p class="text-sm">The subject goes here</p>
                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
              </div>
            </div>
            <!-- Message End -->
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
        </div>
      </li>
      <!-- Notifications Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-bell"></i>
          <span class="badge badge-warning navbar-badge">15</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header">15 Notifications</span>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-envelope mr-2"></i> 4 new messages
            <span class="float-right text-muted text-sm">3 mins</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-users mr-2"></i> 8 friend requests
            <span class="float-right text-muted text-sm">12 hours</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-file mr-2"></i> 3 new reports
            <span class="float-right text-muted text-sm">2 days</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
        </div>
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
                        <img src="<?php echo $row['photo']; ?>" alt="Profile Image" class="profile-img">
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
              <li class="nav-item">
                <a href="feedback.php" class="nav-link active">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Report</p>
                </a>
              </li>
           </ul>
         
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-table"></i>
              <p>
                Maintainance
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="na1v nav-treeview">
              
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
              <li class="breadcrumb-item active"><a href="logout.php">Logout</a></li>
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

                <p>Feedback</p>
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
        <div class="row">
        <h1>Reports</h1><br><br>

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
            <button type='button' onclick='window.print()'>Print</button>
        </div>

        <div>
            <label for='end_date'>End Date:</label>
            <input type='date' name='end_date' required>
           
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
