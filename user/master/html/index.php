<?php
session_start();
include '../includes/connection.php';

if (!isset($_SESSION['email'])) {
    header("Location: ../../login.php");
    exit();
}

$email = $_SESSION['email'];

$sql = "SELECT id, firstname, lastname, phone, email, vehicleModel, vehicleID
        FROM vehicleowners
        WHERE email = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($id, $firstname, $lastname, $phone, $email, $vehicleModel, $vehicleID);
$stmt->fetch();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $vehicleRegistration = $_POST['vehicle_registration'];
    $issueDescription = $_POST['issue_description'];
    $county = $_POST['county'];
    $subCounty = $_POST['sub_county'];
    $contactAddress = $_POST['contact_address'];
    $requestDate = $_POST['req_date'];
    $requestTime = $_POST['req_time'];

    // Assuming you have the county and sub_county IDs available
    $selectedCountyId = $county; // Replace with the actual county ID
    $selectedSubCountyId = $subCounty; // Replace with the actual sub_county ID

    // Query to retrieve the county name
    $countyQuery = "SELECT county_name FROM counties WHERE id = ?";
    $countyStmt = $conn->prepare($countyQuery);
    $countyStmt->bind_param("i", $selectedCountyId);
    $countyStmt->execute();
    $countyResult = $countyStmt->get_result();
    $countyData = $countyResult->fetch_assoc();
    $countyName = $countyData['county_name'];

    // Query to retrieve the sub_county name
    $subCountyQuery = "SELECT constituency_name, ward FROM subcounties WHERE id = ?";
    $subCountyStmt = $conn->prepare($subCountyQuery);
    $subCountyStmt->bind_param("i", $selectedSubCountyId);
    $subCountyStmt->execute();
    $subCountyResult = $subCountyStmt->get_result();
    $subCountyData = $subCountyResult->fetch_assoc();
    $subCountyName = $subCountyData['constituency_name'] . ' - ' . $subCountyData['ward'];

    // Close prepared statements
    $countyStmt->close();
    $subCountyStmt->close();

    // Prepare and execute the SQL query
    $insertSql = "INSERT INTO request (user_id, vehicle_registration, issue_desc, county, sub_county, contact_email, contact_address, req_date, req_time, contact_name, contact_phone, vehicle_model)
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $insertStmt = $conn->prepare($insertSql);
    $insertStmt->bind_param("ssssssssssss", $id, $vehicleRegistration, $issueDescription, $countyName, $subCountyName, $email, $contactAddress, $requestDate, $requestTime, $firstname, $phone, $vehicleModel);

    if ($insertStmt->execute()) {
        header("Location: index.php");
    } else {
        echo "Error: " . $insertStmt->error;
    }

    // Close the statement
    $insertStmt->close();
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
    <title>User Dashboard</title>
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
        th.sortable {
    cursor: pointer;
    position: relative;
}

th.sortable:after {
    content: '\25B2'; /* Unicode for up arrow */
    position: absolute;
    right: 8px;
    opacity: 0.5;
}

th.sorted-asc:after {
    content: '\25B2'; /* Unicode for up arrow */
    opacity: 1;
}

th.sorted-desc:after {
    content: '\25BC'; /* Unicode for down arrow */
    opacity: 1;
}

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
                           <p style="color: white;">ronny</p>

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
                                <img src="../assets/images/users/user.jpeg" alt="user" class="profile-pic me-2"><?php echo $email;?>
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
            <!-- ============================================================== -->
            <div class="container-fluid">
                 <h2>My Requests</h2>

    <table>
       <thead>
    <tr>
        <th class="sortable" data-sort="id">ID</th>
        <th class="sortable" data-sort="vehicle_model">Vehicle Model</th>
        <th class="sortable" data-sort="vehicle_registration">Vehicle Registration</th>
        <!-- Add similar sortable classes for other columns as needed -->
        <th>Issue Description</th>
        <th>Mechanic Assigned</th>
        <th>Service Cost</th>
        <th>Request Date</th>
        <th>Request Time</th>
        <th>Status</th>
        <th>Print</th>
    </tr>
</thead>

        <tbody>
            <?php
            // Include the database connection
           // include 'includes/connection.php';

            // Fetch data from the database
            $sql = "SELECT id,user_id, vehicle_model, vehicle_registration, issue_desc, mechanic_assigned, service_cost, req_date, req_time FROM request where contact_email='$email'";
            $result = mysqli_query($conn, $sql);

            // Display data in the table
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>{$row['user_id']}</td>";
                echo "<td>{$row['vehicle_model']}</td>";
                echo "<td>{$row['vehicle_registration']}</td>";
                echo "<td>{$row['issue_desc']}</td>";
                echo "<td>{$row['mechanic_assigned']}</td>";
                echo "<td>{$row['service_cost']}</td>";
                echo "<td>{$row['req_date']}</td>";
                echo "<td>{$row['req_time']}</td>";
                echo "<td>{$row['req_time']}</td>";
                 echo "<td><button class='print-btn' data-id='{$row['id']}'>Print</button></td>";
                echo "</tr>";
            }

            // Close the database connection
            $conn->close();
            ?>
        </tbody>
    </table><div class="modal fade" id="serviceRequestModal" tabindex="-1" role="dialog" aria-labelledby="serviceRequestModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="serviceRequestModalLabel">Service Request Form</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Include your form here -->
                <form action="index.php" method="post" class="advanced-form">
                    <!-- Form fields go here -->

                    <div class="form-group">
                       <!-- <label for="vehicle_registration" class="form-label">Vehicle Registration Number:</label>-->
                        <input type="hidden" class="form-control" value="<?php echo $vehicleID;?>" id="vehicle_registration" name="vehicle_registration" required>
                    </div>

                   <div class="form-group">
    <label for="issue_description" class="form-label">Issue Description:</label>
    <select name="issue_description" id="issue_description" class="form-control" required>
        <option value="" disabled selected>Select Issue Description</option>
        <?php foreach ($services as $service) : ?>
            <option value="<?php echo $service['service_name']; ?>"><?php echo $service['service_name']; ?></option>
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
        
    
    document.querySelectorAll('.print-btn').forEach(function (button) {
        button.addEventListener('click', function () {
            var userId = button.getAttribute('data-id');

            // Call a function to fetch data for the selected row
            fetchRowData(userId);
        });
    });

    function fetchRowData(userId) {
        // You can make an AJAX request to the server to fetch data for the selected row
        // In this example, I'll simulate fetching data (replace this with your actual AJAX logic)

        // Simulating AJAX request
        // You need to replace this with your actual AJAX logic to fetch data from the server
        var url = 'fetch_row_data.php'; // Replace with the actual server endpoint
        var params = 'user_id=' + encodeURIComponent(userId);

        var xhr = new XMLHttpRequest();
        xhr.open('GET', url + '?' + params, true);

        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                // Once data is fetched, call the print function
                printRow(JSON.parse(xhr.responseText));
            }
        };

        xhr.send();
    }

    function printRow(rowData) {
        // Open a new window for printing
        var printWindow = window.open('', '_blank');

        // Construct the printable content
        var printableContent = '<h2>Printable Content</h2>';
        printableContent += '<p>ID: ' + rowData.user_id + '</p>';
        printableContent += '<p>Vehicle Model: ' + rowData.vehicle_model + '</p>';
        printableContent += '<p>Vehicle Registration: ' + rowData.vehicle_registration + '</p>';
        // Add more fields as needed

        // Set the content of the new window
       // printWindow.document.write(printableContent);

        // Close the document to ensure proper rendering
        printWindow.document.close();

        // Print the new window
        printWindow.print();
    }
</script>
<script>
    $(document).ready(function () {
        $('th.sortable').on('click', function () {
            var column = $(this).data('sort');
            var sortOrder = $(this).hasClass('sorted-asc') ? 'desc' : 'asc';

            // Make an AJAX request to fetch sorted data (replace this with actual logic)
            // Here, I'm using a placeholder URL 'sort_data.php', and you need to replace it
            var url = 'sort_data.php';
            var params = 'column=' + encodeURIComponent(column) + '&order=' + encodeURIComponent(sortOrder);

            $.get(url + '?' + params, function (data) {
                // Update the table with sorted data
                $('tbody').html(data);

                // Update the sorting indicator
                $('th.sortable').removeClass('sorted-asc sorted-desc');
                $(this).addClass(sortOrder === 'asc' ? 'sorted-asc' : 'sorted-desc');
            });
        });
    });
</script>





</body>

</html>