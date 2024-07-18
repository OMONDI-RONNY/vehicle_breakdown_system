
<?php
session_start();
$userEmail = $_SESSION['email'];
// Include the database connection
include 'includes/connection.php';

// SQL query to fetch user profile information
$sql = "SELECT * FROM vehicleowners WHERE email = '$userEmail'";

$result = mysqli_query($conn, $sql);

// Check if the query was successful
if ($result) {
    $row = mysqli_fetch_assoc($result);
    $firstname = $row['firstname'];
    $lastname = $row['lastname'];
    $phone = $row['phone'];
    $email = $row['email']; // Use a different variable name to avoid conflicts
    $vehicle_model = $row['vehicleModel'];
   // $image=$row['image'];
} else {
    echo "Error: " . mysqli_error($conn);
}

// Close the database connection
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['fname'];
    $lname = $_POST['lname'];
   // $model=$_POST['model'];
  //  $service = $_POST['services'];
  //  $phone = $_POST['phone'];
    $email = $_POST['email'];
  //  $model = $_POST['vmodel'];
   // $rtype = $_POST['rtype'];
   // $vreg = $_POST['vreg'];
   // $vname = $_POST['vname'];
   // $vehicle_type = $_POST['vehicle_type'];
    $address = $_POST['address'];
    //$password = $_POST['password'];
   // $confirmPassword = $_POST['confirmPassword'];

  

        $sql = "UPDATE vehicleowners SET  firstname=?, lastname=?, email=?  WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $name, $lname,$email,$id);
        $result = $stmt->execute();

        if ($result) {
            header("Location: request.php");
            exit();
        } else {
            $error = "Error updating record: " . $conn->error;
        }
    
}
// Fetch vehicle models from the database
$sql = "SELECT * FROM kenya_vehicle_models";
$result = $conn->query($sql);
$models = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $models[] = $row['model'];
    }
}


// Fetch counties from the database
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
    <title>User Profile</title>
    <link rel="canonical" href="https://www.wrappixel.com/templates/materialpro-lite/" />
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/images/favicon.png">
    <!-- Custom CSS -->
    <link href="css/style.min.css" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <style type="text/css"></style>
<![endif]-->
    <style type="text/css">
     .profile-details {
        padding: 20px;
    }

    .profile-details h1 {
        font-size: 24px;
        margin-bottom: 20px;
        color: #333;
    }

    .profile-details table {
        width: 100%;
        margin-top: 20px;
        border-collapse: collapse;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .profile-details th,
    .profile-details td {
        border: 1px solid #e2e2e2;
        padding: 12px;
        text-align: left;
        text-transform: uppercase;
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    .profile-details th {
        background-color: #f0f0f0;
        color: #333;
    }
    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    .profile-details tr:hover {
        background-color: #f5f5f5;
    }

    .profile-details td:first-child {
        font-weight: bold;
        color: #007bff;
    }

    .profile-details .edit-button {
        background-color: #28a745;
        color: #fff;
        border: none;
        padding: 8px 12px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .profile-details .edit-button:hover {
        background-color: #218838;
    }

    .profile-details .edit-button:focus {
        
        outline: none;
    }
    label {
            display: block;
            margin: 10px 0;
        }
        select {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    /* Additional styles */
    background-color: #fff; /* Set the background color */
    color: #333; /* Set the text color */
    font-size: 14px; /* Adjust the font size */
    margin-bottom: 10px; /* Add margin to match other fields */
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
    <div id="main-wrapper" data-layout="vertical" data-navbarbg="skin5" data-sidebartype="full"
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
                         <!--   <img src="../assets/images/logo-light-icon.png" alt="homepage" class="dark-logo" />-->

                        </b>
                        <!--End Logo icon -->
                        <!-- Logo text -->
                        <span class="logo-text">
                            <!-- dark Logo text -->
                           <!-- <img src="../assets/images/logo-light-text.png" alt="homepage" class="dark-logo" />-->
                           <p id="demo" style="color:white;">ronny omondi</p>

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
                                <img src="../assets/images/users/user.jpeg" alt="user" class="profile-pic me-2"> <?php echo $email; ?>
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
                      <!--  <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                href="#" aria-expanded="false" data-bs-toggle="modal" data-bs-target="#serviceRequestModal"><i class="mdi me-2 mdi-table"></i><span
                                    class="hide-menu">Request Service</span></a></li>-->
                       <!-- <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                href="icon-material.html" aria-expanded="false"><i
                                    class="mdi me-2 mdi-emoticon"></i><span class="hide-menu">Icon</span></a></li>-->
                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                href="geolocation.php" aria-expanded="false"><i class="mdi me-2 mdi-earth"></i><span
                                    class="hide-menu">Find Location</span></a></li>
                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                href="#" aria-expanded="false"><i
                                    class="mdi mdi-bell me-2"></i><span class="hide-menu">Notification</span></a>
                        </li>
                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                href="feebacks.php" aria-expanded="false"><i class="mdi mdi-comment-check-outline me-2"></i><span
                                    class="hide-menu">Feed Back</span></a>
                        </li>
                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                href="freq.php" aria-expanded="false"><i class="mdi me-2 mdi-help-circle"></i><span
                                    class="hide-menu">FAQs</span></a>
                        </li>
                         <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                href="bot.html" aria-expanded="false"><i
                                    class="mdi mdi-comment me-2"></i><span class="hide-menu">Chats Us</span></a>
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
                        <h3 class="page-title mb-0 p-0">Profile</h3>
                        <div class="d-flex align-items-center">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Profile</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                    <div class="col-md-6 col-4 align-self-center">
                        <div class="text-end upgrade-btn">
                            <a href="#"
                                class="btn btn-danger d-none d-md-inline-block text-white" target="_blank" data-bs-toggle="modal" data-bs-target="#serviceRequestModal">Update Profile</a>
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
                <!-- ============================================================== -->
                <!-- Start Page Content -->
                <!-- ============================================================== -->
                <!-- Row -->
                <div class="row">
                    <!-- Column -->
                    <div class="col-lg-4 col-xlg-3 col-md-5">
                        <div class="card">
                            <div class="card-body profile-card">
                                <center class="mt-4"> <img src="../assets/images/users/user.jpeg"
                                        class="rounded-circle" width="150" />
                                   <h4 class="card-title mt-2" style="font-size: 28px; color: #007bff; text-transform: uppercase; font-weight: bold;"><?php echo $firstname . ' ' . $lastname; ?></h4>

                                  <!--  <h6 class="card-subtitle">Accoubts Manager Amix corp</h6>-->
                                    <div class="row text-center justify-content-center">
                                        
                                    </div>
                                </center>
                            </div>
                        </div>
                    </div>
                    <!-- Column -->
                    <!-- Column -->
                    <div class="col-lg-8 col-xlg-9 col-md-7">
                        <div class="card">
                            <div class="card-body">
                                <h1>My Profile</h1>
                                  <div class="profile-details">
            <table class="table table-bordered">
                <tr>
                    <th>Field</th>
                    <th>Details</th>
                </tr>
                <tr>
                    <td>First Name</td>
                    <td><?php echo $firstname; ?></td>
                </tr>
                <tr>
                    <td>Last Name</td>
                    <td><?php echo $lastname; ?></td>
                </tr>
                <tr>
                    <td>Phone</td>
                    <td><?php echo $phone; ?></td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td><?php echo $userEmail; ?></td>
                </tr>
                <tr>
                    <td>Vehicle Model</td>
                    <td><?php echo $vehicle_model; ?></td>
                </tr>
            </table>
        </div>
        
                                
                            </div>
                        </div>
                    </div>
                    <!-- Column -->
                </div>
                <div class="modal fade" id="serviceRequestModal" tabindex="-1" role="dialog" aria-labelledby="serviceRequestModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="serviceRequestModalLabel">Update Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Include your form here -->
                <form action="process_profile_update.php" method="post" class="advanced-form">
   

   <div class="form-group">
       <label for="first_name" class="form-label">First Name:</label>
       <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo $firstname; ?>" required>
   </div>

   <div class="form-group">
       <label for="last_name" class="form-label">Last Name:</label>
       <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo $lastname; ?>" required>
   </div>

   
  

   <div class="form-group">
       <label for="phone" class="form-label">Phone:</label>
       <input type="text" class="form-control" id="phone" name="phone" value="<?php echo $phone; ?>">
   </div>

  <!-- <div class="form-group">
       <label for="phone" class="form-label">Vehicle Model:</label>
       <select id="vehicleModel" name="model" required value="<?php echo $vehicle_model; ?>">
                <option value="" disabled selected>Select Vehicle Model</option>
                <?php foreach ($models as $model) { ?>
                    <option value="<?php echo $model; ?>"><?php echo $model; ?></option>
                <?php } ?>
            </select>
   </div>-->


   <div class="form-group">
       <!--<label for="mech_email" class="form-label">Email:</label>-->
       <input type="hidden" class="form-control" id="mech_email" name="mech_email" value="<?php echo $email; ?>" readonly>
   </div>

   <!-- Other existing form fields -->

   <div class="form-group">
       <button type="submit" class="btn btn-primary">Update Profile</button>
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
                <!-- Row -->
                <!-- ============================================================== -->
                <!-- End PAge Content -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- Right sidebar -->
                <!-- ============================================================== -->
                <!-- .right-sidebar -->
                <!-- ============================================================== -->
                <!-- End Right sidebar -->
                <!-- ============================================================== -->
            </div>
            <!-- ============================================================== -->
            <!-- End Container fluid  -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- footer -->
            <!-- ============================================================== -->
            <footer class="footer"> © 2024 Vehicle Breakdown Assistance <a href="#">Easus.com </a>
            </footer>
            <!-- ============================================================== -->
            <!-- End footer -->
            <!-- ============================================================== -->
        </div>
        <!-- ============================================================== -->
        <!-- End Page wrapper  -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <script src="../assets/plugins/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="../assets/plugins/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/app-style-switcher.js"></script>
    <!--Wave Effects -->
    <script src="js/waves.js"></script>
    <!--Menu sidebar -->
    <script src="js/sidebarmenu.js"></script>
    <!--Custom JavaScript -->
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