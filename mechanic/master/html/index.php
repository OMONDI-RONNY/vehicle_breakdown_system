<?php
// Include the connection file
include 'includes/connection.php';
session_start();
if (!isset($_SESSION['mech_id'])) {
    header("Location: ../../mechlog.php");
    exit();
}

$id=$_SESSION['mech_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $vehicleRegistration = $_POST['vehicle_registration'];
    $issueDescription = $_POST['issue_description'];
    $county = $_POST['county'];
    $subCounty = $_POST['sub_county'];
    $contactAddress = $_POST['contact_address'];
    $requestDate = $_POST['req_date'];
    $requestTime = $_POST['req_time'];

    // Prepare and execute the SQL query
    $sql = "INSERT INTO request (vehicle_registration, issue_description, county, sub_county, contact_address, req_date, req_time)
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $vehicleRegistration, $issueDescription, $county, $subCounty, $contactAddress, $requestDate, $requestTime);
    
    if ($stmt->execute()) {
        echo "Request submitted successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
    
    // Close the database connection
    //$conn->close();
}



// Fetch counties from the database
$countyQuery = "SELECT * FROM counties";
$countyResult = mysqli_query($conn, $countyQuery);

$counties = array();
while ($countyRow = mysqli_fetch_assoc($countyResult)) {
    $counties[] = $countyRow;
}

// Close the county query connection
mysqli_free_result($countyResult);

// Fetch sub-counties from the database
$subCountyQuery = "SELECT * FROM subcounties";
$subCountyResult = mysqli_query($conn, $subCountyQuery);

$subCounties = array();
while ($subCountyRow = mysqli_fetch_assoc($subCountyResult)) {
    $subCounties[$subCountyRow['county_id']][] = $subCountyRow;
}

// Close the sub-county query connection
mysqli_free_result($subCountyResult);



$servicesQuery = "SELECT * FROM services";
$servicesResult = mysqli_query($conn, $servicesQuery);

$services = array();
while ($serviceRow = mysqli_fetch_assoc($servicesResult)) {
    $services[] = $serviceRow;
}

// Close the services query connection
mysqli_free_result($servicesResult);
?>




<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords"
        content="wrappixel, admin dashboard, html css dashboard, web dashboard, bootstrap 5 admin, bootstrap 5, css3 dashboard, bootstrap 5 dashboard, materialpro admin bootstrap 5 dashboard, frontend, responsive bootstrap 5 admin template, materialpro admin lite design, materialpro admin lite dashboard bootstrap 5 dashboard template">
    <meta name="description"
        content="Material Pro Lite is powerful and clean admin dashboard template, inpired from Bootstrap Framework">
    <meta name="robots" content="noindex,nofollow">
    <title>Mechanic Dashboard</title>
    <link rel="canonical" href="https://www.wrappixel.com/templates/materialpro-lite/" />
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/images/favicon.png">
    <!-- chartist CSS -->
    <link href="../assets/plugins/chartist-js/dist/chartist.min.css" rel="stylesheet">
    <link href="../assets/plugins/chartist-js/dist/chartist-init.css" rel="stylesheet">
    <link href="../assets/plugins/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.css" rel="stylesheet">
    <!--This page css - Morris CSS -->
    <link href="../assets/plugins/c3-master/c3.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="css/style.min.css" rel="stylesheet">
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
    </style>
</head>

<body>
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <div class="lds-ripple">
            <div class="lds-pos"></div>
            <div class="lds-pos"></div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="absolute" data-header-position="absolute" data-boxed-layout="full">
        <!-- ============================================================== -->
        <!-- Topbar header - style you can find in pages.scss -->
        <!-- ============================================================== -->
        <header class="topbar" data-navbarbg="skin6">
            <nav class="navbar top-navbar navbar-expand-md navbar-dark">
                <div class="navbar-header" data-logobg="skin6">
                    <!-- ============================================================== -->
                    <!-- Logo -->
                    <!-- ============================================================== -->
                    <a class="navbar-brand ms-4" href="index.html">
                        <!-- Logo icon -->
                        <b class="logo-icon">
                            <!--You can put here icon as well // <i class="wi wi-sunset"></i> //-->
                            <!-- Dark Logo icon -->
                           <!-- <img src="../assets/images/logo-light-icon.png" alt="homepage" class="dark-logo" />-->
                           <p id="demo" style="color: white;">ronny</p>

                        </b>
                        <!--End Logo icon -->
                        <!-- Logo text -->
                        <span class="logo-text">
                            <!-- dark Logo text -->
                         <!--   <img src="../assets/images/logo-light-text.png" alt="homepage" class="dark-logo" />-->

                        </span>
                    </a>
                    <!-- ============================================================== -->
                    <!-- End Logo -->
                    <!-- ============================================================== -->
                    <!-- ============================================================== -->
                    <!-- toggle and nav items -->
                    <!-- ============================================================== -->
                    <a class="nav-toggler waves-effect waves-light text-white d-block d-md-none"
                        href="javascript:void(0)"><i class="ti-menu ti-close"></i></a>
                </div>
                <!-- ============================================================== -->
                <!-- End Logo -->
                <!-- ============================================================== -->
                <div class="navbar-collapse collapse" id="navbarSupportedContent" data-navbarbg="skin5">
                    <ul class="navbar-nav d-lg-none d-md-block ">
                        <li class="nav-item">
                            <a class="nav-toggler nav-link waves-effect waves-light text-white "
                                href="javascript:void(0)"><i class="ti-menu ti-close"></i></a>
                        </li>
                    </ul>
                    <!-- ============================================================== -->
                    <!-- toggle and nav items -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav me-auto mt-md-0 ">
                        <!-- ============================================================== -->
                        <!-- Search -->
                        <!-- ============================================================== -->

                        <li class="nav-item search-box">
                            <a class="nav-link text-muted" href="javascript:void(0)"><i class="ti-search"></i></a>
                            <form class="app-search" style="display: none;">
                                <input type="text" class="form-control" placeholder="Search &amp; enter"> <a
                                    class="srh-btn"><i class="ti-close"></i></a> </form>
                        </li>
                    </ul>

                    <!-- ============================================================== -->
                    <!-- Right side toggle and nav items -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav">
                        <!-- ============================================================== -->
                        <!-- User profile and search -->
                        <!-- ============================================================== -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="../assets/images/users/1.jpg" alt="user" class="profile-pic me-2"><?php echo $id;?>
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown"></ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <!-- ============================================================== -->
        <!-- End Topbar header -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <aside class="left-sidebar" data-sidebarbg="skin6">
            <!-- Sidebar scroll-->
            <div class="scroll-sidebar">
                <!-- Sidebar navigation-->
                <nav class="sidebar-nav">
                    <ul id="sidebarnav">
                        <!-- User Profile-->
                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                href="index.php" aria-expanded="false"><i class="mdi me-2 mdi-gauge"></i><span
                                    class="hide-menu">Dashboard</span></a></li>
                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                href="pages-profile.php" aria-expanded="false">
                                <i class="mdi me-2 mdi-account-check"></i><span class="hide-menu">Profile</span></a>
                        </li>
                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                href="#" aria-expanded="false" data-bs-toggle="modal" data-bs-target="#serviceRequestModal" ><i class="mdi me-2 mdi-table"></i><span
                                    class="hide-menu">Request Service</span></a></li>
                       <!-- <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                href="icon-material.html" aria-expanded="false"><i
                                    class="mdi me-2 mdi-emoticon"></i><span class="hide-menu">Icon</span></a></li>-->
                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                href="geolocation.php" aria-expanded="false"><i class="mdi me-2 mdi-earth"></i><span
                                    class="hide-menu">Find Location</span></a></li>
                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                href="#" aria-expanded="false"><i
                                    class="mdi me-2 mdi-book-open-variant"></i><span class="hide-menu">Notifications</span></a>
                        </li>
                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                href="feebacks.php" aria-expanded="false"><i class="mdi me-2 mdi-help-circle"></i><span
                                    class="hide-menu">Feed Back</span></a>
                        </li>
                         <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                href="#" aria-expanded="false"><i class="mdi me-2 mdi-help-circle"></i><span
                                    class="hide-menu">FAQs</span></a>
                        </li>
                       
                    </ul>

                </nav>
                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
            <div class="sidebar-footer">
                <div class="row">
                    <div class="col-4 link-wrap">
                        <!-- item-->
                        <a href="" class="link" data-toggle="tooltip" title="" data-original-title="Settings"><i
                                class="ti-settings"></i></a>
                    </div>
                    <div class="col-4 link-wrap">
                        <!-- item-->
                        <a href="" class="link" data-toggle="tooltip" title="" data-original-title="Email"><i
                                class="mdi mdi-gmail"></i></a>
                    </div>
                    <div class="col-4 link-wrap">
                        <!-- item-->
                        <a href="logout.php" class="link" data-toggle="tooltip" title="" data-original-title="Logout"><i
                                class="mdi mdi-power"></i></a>
                    </div>
                </div>
            </div>
        </aside>
        <!-- ============================================================== -->
        <!-- End Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Page wrapper  -->
        <!-- ============================================================== -->
        <div class="page-wrapper">
            <!-- ============================================================== -->
            <!-- Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <div class="page-breadcrumb">
                <div class="row align-items-center">
                    <div class="col-md-6 col-8 align-self-center">
                        <h3 class="page-title mb-0 p-0">Dashboard</h3>
                        <div class="d-flex align-items-center">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                    <div class="col-md-6 col-4 align-self-center">
                        <div class="text-end upgrade-btn">
                           ronny
                        </div>
                    </div>
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- End Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Container fluid  -->
          <div style="margin-left:3%;" class="row">
          
   
   
    <!-- Search Box -->
    


    <!-- Search Box -->
    <div style="margin-left:0%;" class="search-container">
        <h2>Manage Requests</h2>
        <input type="text" id="searchInput" placeholder="Search...">
        <button onclick="searchUsers()"><i class="fas fa-search"></i> Search</button>
    <!--</div>-->

    <!-- Filter Records -->
   <!-- <div class="filter-container">-->
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


        $mech="SELECT * FROM mechanicreg WHERE mech_id='$id'";
        $mechresult=mysqli_query($conn,$mech);
        $mechfetch=mysqli_fetch_assoc($mechresult);
        $mechID=$mechfetch['mech_id'];

        $limit = isset($_GET['limit']) ? $_GET['limit'] : 5; // Records per page (default: 5)
        $page = isset($_GET['page']) ? $_GET['page'] : 1; // Current page, default is 1

        $start = ($page - 1) * $limit;

        $search = isset($_GET['search']) ? $_GET['search'] : ''; // Search keyword

        // Modify the SQL query to include search and limit
        $sql = "SELECT * FROM request WHERE mechanic_assigned='$mechID'";
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
                    <th>Requested Service</th>
                    <th>Actions</th>
                </tr>";

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . $row['id'] . "</td>
                        <td>" . $row['contact_name'] . "</td>
                        <td>" . $row['contact_phone'] . "</td>
                        <td>" . $row['issue_desc'] . "</td>
                        <td class='action-buttons'>
                            <a href='requestview.php?action=view&id=" . $row['id'] . "' class='view-btn'><i class='fas fa-eye'></i> View</a>
                             <a href='requestedit.php?action=edit&id=" . $row['id'] . "' class='edit-btn'><i class='fas fa-edit'></i> Accept</a>
                           
                        </td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No Requests found</td></tr>";
        }

        echo "</table>";

        // Count total number of records
        $sqlCount = "SELECT COUNT(*) AS total FROM request";
        $resultCount = $conn->query($sqlCount);
        $row = $resultCount->fetch_assoc();
        $totalRecords = $row['total'];

        // Calculate total pages
        $totalPages = ceil($totalRecords / $limit);

        // Pagination links
        echo "<div class='pagination'>";
        for ($i = 1; $i <= $totalPages; $i++) {
            echo "<a href='index.php?page=$i&limit=$limit'";
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
            window.location.href = 'index.php?search=' + searchInput + '&limit=' + value;
        }
    </script>



          
  </div>
        <div class="modal fade" id="serviceRequestModal" tabindex="-1" role="dialog" aria-labelledby="serviceRequestModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="serviceRequestModalLabel">Service Request Form</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Include your form here -->
                <form action="process_request.php" method="post" class="advanced-form">
                    <!-- Form fields go here -->

                    <div class="form-group">
                        <label for="vehicle_registration" class="form-label">Vehicle Registration Number:</label>
                        <input type="text" class="form-control" id="vehicle_registration" name="vehicle_registration" required>
                    </div>

                    <div class="form-group">
                        <label for="issue_description" class="form-label">Issue Description:</label>
                        <select name="issue_description" id="issue_description" class="form-control" required>
                            <option value="" disabled selected>Select Issue Description</option>
                            <?php foreach ($services as $service) : ?>
                                <option value="<?php echo $service['id']; ?>"><?php echo $service['service_name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="county" class="form-label">County:</label>
                        <select name="county" id="county" class="form-control" onchange="populateSubCounties()" required>
                            <option value="" disabled selected>Select County</option>
                            <?php foreach ($counties as $county) : ?>
                                <option value="<?php echo $county['id']; ?>"><?php echo $county['county_name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="sub_county" class="form-label">Sub-County:</label>
                        <select name="sub_county" id="sub_county" class="form-control" required>
                            <option value="" disabled selected>Select Sub-County</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="contact_address" class="form-label">Contact Address:</label>
                        <input type="text" class="form-control" id="contact_address" name="contact_address" required>
                    </div>

                    <div class="form-group">
                        <label for="req_date" class="form-label">Request Date:</label>
                        <input type="date" class="form-control" id="req_date" name="req_date" required>
                    </div>

                    <div class="form-group">
                        <label for="req_time" class="form-label">Request Time:</label>
                        <input type="time" class="form-control" id="req_time" name="req_time" required>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
                <!-- End of the form -->
            </div>
        </div>
    </div>
</div>

<script>
    // Trigger the modal when the page is loaded
    $(document).ready(function () {
        $('#serviceRequestModal').modal('show');
    });
</script>


    <script src="../assets/plugins/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="../assets/plugins/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/app-style-switcher.js"></script>
    <!--Wave Effects -->
    <script src="js/waves.js"></script>
    <!--Menu sidebar -->
    <script src="js/sidebarmenu.js"></script>
   
    <script src="../assets/plugins/chartist-js/dist/chartist.min.js"></script>
    <script src="../assets/plugins/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.min.js"></script>
    <!--c3 JavaScript -->
    <script src="../assets/plugins/d3/d3.min.js"></script>
    <script src="../assets/plugins/c3-master/c3.min.js"></script>
    <!--Custom JavaScript -->
    <script src="js/pages/dashboards/dashboard1.js"></script>
    <script src="js/custom.js"></script>
    <script>
        function populateSubCounties() {
            var countySelect = document.getElementById('county');
            var subCountySelect = document.getElementById('sub_county');
            var selectedCountyId = countySelect.value;

            // Clear existing sub-county options
            subCountySelect.innerHTML = '<option value="">Select Sub-County</option>';

            // Populate sub-counties based on the selected county
            if (selectedCountyId !== '') {
                var subCounties = <?php echo json_encode($subCounties); ?>;
                subCounties[selectedCountyId].forEach(function (subCounty) {
                    var option = document.createElement('option');
                    option.value = subCounty.id;
                    option.textContent = subCounty.constituency_name + ' - ' + subCounty.ward;
                    subCountySelect.appendChild(option);
                });
            }
        }
    </script>
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