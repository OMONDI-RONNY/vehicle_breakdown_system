<?php
session_start();
include '../includes/connection.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once 'phpmailer/src/Exception.php';
require_once 'phpmailer/src/PHPMailer.php';
require_once 'phpmailer/src/SMTP.php';

if (!isset($_SESSION['email'])) {
    header("Location: ../../login.php");
    exit();
}

$email = $_SESSION['email'];

$sql = "SELECT id, firstname, lastname, phone, email, vehicleModel
        FROM vehicleowners
        WHERE email = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($id, $firstname, $lastname, $phone, $email,$vehiclemodel);
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
    $lat = $_POST['latitude'];
    $long = $_POST['longitude'];

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
    $insertSql = "INSERT INTO request (user_id, vehicle_registration, issue_desc, county, sub_county, contact_email, contact_address, req_date, req_time, contact_name, contact_phone, vehicle_model, latitude, longitude)
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $insertStmt = $conn->prepare($insertSql);
    $insertStmt->bind_param(
        "ssssssssssssss",
        $id,
        $vehicleRegistration,
        $issueDescription,
        $countyName,
        $subCountyName,
        $email,
        $contactAddress,
        $requestDate,
        $requestTime,
        $firstname,
        $phone,
        $vehiclemodel,
        $lat,
        $long
    );

    if ($insertStmt->execute()) {
        // Send email to user with nearby mechanics
        $nearbyMechanics = findNearbyMechanics($lat, $long, $countyName, $subCountyName); // Fetch nearby mechanics from the database
        sendUserNearbyMechanicsEmail($email, $nearbyMechanics);

        $endpoint = 'https://api.tiaraconnect.io/api/messaging/sendsms';
        $apiKey = 'eyJhbGciOiJIUzUxMiJ9.eyJzdWIiOiIzNTEiLCJvaWQiOjM1MSwidWlkIjoiN2Y5ZGQ1ZmMtM2QwMi00ZGZiLTg1YjItY2FjMDBlYjU0NDhkIiwiYXBpZCI6MjQxLCJpYXQiOjE3MTExOTQyMTAsImV4cCI6MjA1MTE5NDIxMH0._BW3-yd5JJmAnRsL_trguFXmTLKFmz_a4EAJVmoIk7H66Lpccj3uKiwuTJjgYoxKLU6ZH0EhAC3pkDU2wQcPXQ';
        $from = 'TIARACONECT';
        $message = 'We have received your request. Please check your email to view the list of available mechanics. Thank you for choosing our service';
        $to = $phone;
        
        sendSMS($endpoint, $apiKey, $to, $from, $message);

        echo "<script>alert('Request submitted successfully');</script>";
        header("Location: index.php");
    } else {
        echo "<script>alert('Error: {$insertStmt->error}');</script>";
    }

    // Close the statement
    $insertStmt->close();
}

function findNearbyMechanics($userLat, $userLong, $county, $subCounty)
{
    global $conn;

    // Example: Select mechanics within a radius of 10 kilometers
    $radius = 10; // Adjust this based on your requirements
    $earthRadius = 6371; // Earth's radius in kilometers

    $query = "SELECT firstname, mech_email,phone,typeofservice,status,lastname, ({$earthRadius} * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) AS distance
              FROM mechanicreg
              HAVING distance < ?
              ORDER BY distance";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("dddi", $userLat, $userLong, $userLat, $radius);
    $stmt->execute();
    $result = $stmt->get_result();
    $mechanics = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    return $mechanics;
}

function sendUserNearbyMechanicsEmail($userEmail, $nearbyMechanics)
{
    // Use PHPMailer to send email
    $mail = new PHPMailer(true);

    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com'; // Update with your email host
    $mail->SMTPAuth = true;
    $mail->Username = 'omoron37@gmail.com'; // Update with your email
    $mail->Password = 'uxrgdwpdpujljjdf'; // Update with your email password
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;

    $mail->setFrom('omoron37@gmail.com');
    $mail->addAddress($userEmail);
    $mail->isHTML(true);
    $mail->Subject = 'Nearby Mechanics Information';
    $mail->Body = '
    <html>
    <head>
        <style>
            /* Add your custom CSS styles here */
            body {
                font-family: Arial, sans-serif;
                background-color: #f4f4f4;
                color: #333;
                margin: 0;
                padding: 0;
            }

            .container {
                max-width: 600px;
                margin: 20px auto;
                padding: 20px;
                background-color: #fff;
                border-radius: 5px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }

            h1 {
                color: #3498db;
            }

            .mechanic-container {
                background-color: #f9f9f9;
                border-radius: 8px;
                padding: 15px;
                margin-bottom: 20px;
            }

            .mechanic-name {
                font-size: 18px;
                font-weight: bold;
                color: #333;
            }

            .mechanic-email {
                font-size: 14px;
                color: #555;
            }

            .mechanic-phone {
                font-size: 14px;
                color: #555;
            }

            hr {
                border: 0.5px solid #ddd;
                margin: 10px 0;
            }

            p {
                line-height: 1.6;
            }

            /* Add more styles as needed */
        </style>
    </head>
    <body>
    <div class="container">
    <h1>Nearby Mechanics Information</h1>
    <p>Hello,</p>
    <p>We found some amazing mechanics in your area who are ready to assist you. Check out their details below:</p>';

    if (empty($nearbyMechanics)) {
        // If no mechanics are found, include a message
        $mail->Body .= '<p>No mechanics are currently available in your area, but our admin team will handle your request shortly. Thank you for your patience.</p>';
    } else {
        foreach ($nearbyMechanics as $mechanic) {
            // Only display available mechanics (status=1)
            if ($mechanic['status'] == 1) {
                $mail->Body .= '
                    <div class="mechanic-container">
                        <p class="mechanic-details">Firstname: ' . $mechanic['firstname'] . '</p>
                        <p class="mechanic-details">Lastname: ' . $mechanic['lastname'] . '</p>
                        <p class="mechanic-details">Type of Service: ' . $mechanic['typeofservice'] . '</p>
                        <p class="mechanic-status">Status: Available</p>
                    </div>
                    <hr>';
            }
        }
    }

    $mail->Body .= '
    <p>These skilled professionals are eager to help you with your vehicle needs. Please be patient as our admin team processes your request.</p>
    <p>Thank you for choosing our services. We look forward to providing you with top-notch assistance!</p>
    </div>
    </body>
    </html>';


    try {
        $mail->send();
        echo "<script>alert('Email sent successfully');</script>";
    } catch (Exception $e) {
        echo "<script>alert('Error: {$mail->ErrorInfo}');</script>";
        error_log("Email Error: " . $mail->ErrorInfo, 0);
    }
}


function sendSMS($endpoint, $apiKey, $to, $from, $message)
{
    $request = [
        'to' => $to,
        'from' => $from,
        'message' => $message
    ];
    $requestBody = json_encode($request);

    error_log("request|msisdn: $to|request: $requestBody | url: $endpoint");

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => $endpoint,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $requestBody,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $apiKey
        ],
    ]);

    $response_body = curl_exec($curl);
    $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    if ($response_body === false) {
        error_log('Curl failed: ' . curl_error($curl));
    } elseif ($http_status !== 200) {
        error_log('HTTP Error: ' . $http_status . ', Response: ' . $response_body);
    } else {
        error_log("request|msisdn: $to|response: $response_body | url: $endpoint");
    }

    curl_close($curl);
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

    th, td {
        padding: 15px;
        text-align: left;
        border: 1px solid #ddd;
    }

    th {
        background-color: #3498db; /* Change this color to your desired smart color */
        color: #fff; /* Text color for better visibility */
        position: relative;
        cursor: pointer;
    }

   

    tbody tr:hover {
        background-color: #f5f5f5; /* Light gray background on hover */
    }

    .print-btn {
        background-color: #4CAF50;
        color: white;
        padding: 8px 12px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .print-btn:hover {
        background-color: #45a049;
    }
     .status-label {
        display: inline-block;
        padding: 8px 12px;
        border-radius: 4px;
        font-weight: bold;
        text-align: center;
    }

    .status-0 {
        background-color: #e74c3c; /* Red color for status 0 */
        color: #ffffff;
    }

    .status-1 {
        background-color: #2ecc71; /* Green color for status 1 */
        color: #ffffff;
    }

    .status-2 {
        background-color: #f39c12; /* Orange color for status 2 */
        color: #ffffff;
    }

    .status-3 {
        background-color: #3498db; /* Blue color for status 3 */
        color: #ffffff;
    }

    .status-4 {
        background-color: #8e44ad; /* Purple color for status 4 */
        color: #ffffff;
    }

    .status-5 {
        background-color: #27ae60; /* Emerald color for status 5 */
        color: #ffffff;
    }

    .status-button {
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
        cursor: pointer;
    }
    #location {
            display: none; /* Hide the location paragraph */
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
                           <p id="demo" style="color: white;">V.B.S</p>

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
                                    class="mdi mdi-bell me-2"></i><span class="hide-menu">Notifications</span></a>
                        </li>
                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                href="feebacks.php" aria-expanded="false"><i class="mdi mdi-comment-check-outline me-2"></i><span
                                    class="hide-menu">Feed Back</span></a>

                        </li>
                         <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                href="freq.php" aria-expanded="false"><i class="mdi mdi-help-circle me-2"></i><span
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
                           <a href="logout.php">LOGOUT</a>
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
            <th>Mechanic notes</th>
            <th>Request Date</th>
            <th>Request Time</th>
            <th>Status</th>
           
        </tr>
    </thead>

    <tbody>
        <?php
        // Include the database connection
        //include 'includes/connection.php';

        // Fetch data from the database
        $sql = "SELECT id, user_id, vehicle_model, vehicle_registration, issue_desc, mechanic_assigned,mechanic_notes, service_cost, req_date, req_time, acceptance FROM request WHERE contact_email='$email'";
        $result = mysqli_query($conn, $sql);

        // Display data in the table
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>{$row['user_id']}</td>";
                echo "<td>{$row['vehicle_model']}</td>";
                echo "<td>" . strtoupper($row['vehicle_registration']) . "</td>";
                echo "<td>{$row['issue_desc']}</td>";
                echo "<td>{$row['mechanic_assigned']}</td>";
                echo "<td>{$row['service_cost']}</td>";
                echo "<td>{$row['mechanic_notes']}</td>";
                echo "<td>{$row['req_date']}</td>";
                echo "<td>{$row['req_time']}</td>";
                echo "<td><button class='status-button status-label status-{$row['acceptance']}'>" . getStatusLabel($row['acceptance']) . "</button></td>";
               // echo "<td><button class='print-btn' data-id='{$row['id']}' onclick='printRecord({$row['id']})'>Print</button></td>";
                echo "</tr>";
            }
        } else {
            // Display a message if there are no records
            echo "<tr><td colspan='10'>No requests found</td></tr>";
        }

        // Close the database connection
        $conn->close();

        function getStatusLabel($acceptance)
        {
            switch ($acceptance) {
                case 0:
                    return 'Pending';
                case 1:
                    return 'Accepted';
                case 2:
                    return 'In Progress';
                case 3:
                    return 'Completed';
                case 4:
                    return 'Rejected';
                case 5:
                    return 'Cancelled';
                default:
                    return 'Unknown Status';
            }
        }
        ?>
    </tbody>
</table>

</div>
<div class="modal fade" id="serviceRequestModal" tabindex="-1" role="dialog" aria-labelledby="serviceRequestModalLabel" aria-hidden="true" onload="getLocation()">
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

                    <!-- Add hidden input fields for latitude and longitude -->
                    <input type="hidden" id="latitude" name="latitude">
                    <input type="hidden" id="longitude" name="longitude">

                    <!-- Display the detected location -->
                   <!-- <h1>Location:</h1>-->
    <p id="location">Fetching location...</p>


                    <div class="form-group">
                       <label for="vehicle_registration" class="form-label">Vehicle Registration Number:</label>
                        <input type="text" class="form-control" placeholder="KAT 226T"  id="vehicle_registration" name="vehicle_registration" required>
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

                    <!-- ... Your other form fields ... -->

                    <div class="form-group">
                        <label for="contact_address" class="form-label">Contact Address:</label>
                        <input type="text" class="form-control" id="contact_address" name="contact_address" required>
                    </div>

                    <div class="form-group">
                        <label for="req_date" class="form-label">Request Date:</label>
                        <input type="date" class="form-control" id="req_date" name="req_date" required min="<?php echo date('Y-m-d'); ?>" onchange="validateDate()">
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
    function validateDate() {
    var inputDate = document.getElementById('date').value;
    var today = new Date().toISOString().split('T')[0];
    
    if (inputDate < today) {
        alert("Please select a date in the future.");
        document.getElementById('date').value = '';
    }
}
        document.addEventListener("DOMContentLoaded", function() {
            getLocation(); // Call the function when the page loads
        });

        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition, showError);
            } else {
                alert("Geolocation is not supported by this browser.");
            }
        }

        function showPosition(position) {
            var latitude = position.coords.latitude;
            var longitude = position.coords.longitude;

            // Display the latitude and longitude on the webpage
            document.getElementById("location").innerHTML = "Latitude: " + latitude + "<br>Longitude: " + longitude;

            // Set the values of latitude and longitude in the form fields
            document.getElementById("latitude").value = latitude;
            document.getElementById("longitude").value = longitude;
        }

        function showError(error) {
            switch (error.code) {
                case error.PERMISSION_DENIED:
                    alert("User denied the request for Geolocation.");
                    break;
                case error.POSITION_UNAVAILABLE:
                    alert("Location information is unavailable.");
                    break;
                case error.TIMEOUT:
                    alert("The request to get user location timed out.");
                    break;
                case error.UNKNOWN_ERROR:
                    alert("An unknown error occurred.");
                    break;
            }
        }
    </script>
   <script>
    // JavaScript function to handle printing
    function printRecord(id) {
        // Find the table row corresponding to the record ID
        var row = document.querySelector('tr[data-id="' + id + '"]');
        if (row) {
            // Extract information from the row
            var userId = row.cells[0].innerText;
            var vehicleModel = row.cells[1].innerText;
            var vehicleRegistration = row.cells[2].innerText;
            var issueDesc = row.cells[3].innerText;
            var mechanicAssigned = row.cells[4].innerText;
            var serviceCost = row.cells[5].innerText;
            var mechanicNotes = row.cells[6].innerText;
            var reqDate = row.cells[7].innerText;
            var reqTime = row.cells[8].innerText;

            // Format the information for printing
            var printContent = "User ID: " + userId + "\n" +
                               "Vehicle Model: " + vehicleModel + "\n" +
                               "Vehicle Registration: " + vehicleRegistration + "\n" +
                               "Issue Description: " + issueDesc + "\n" +
                               "Mechanic Assigned: " + mechanicAssigned + "\n" +
                               "Service Cost: " + serviceCost + "\n" +
                               "Mechanic Notes: " + mechanicNotes + "\n" +
                               "Request Date: " + reqDate + "\n" +
                               "Request Time: " + reqTime;

            // Print the content
            console.log("Printing record:");
            console.log(printContent);

            // Trigger browser's print dialog
            window.print();
        } else {
            console.log("Record with ID " + id + " not found.");
        }
    }

    // Add event listeners to print buttons
    var printButtons = document.querySelectorAll('.print-btn');
    printButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            var recordId = this.getAttribute('data-id');
            printRecord(recordId);
        });
    });
</script>

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
          $(document).ready(function () {
        $('.print-btn').click(function () {
            var requestId = $(this).data('id');
            // AJAX request to generate PDF
            $.ajax({
                url: 'generate_pdf.php',
                type: 'POST',
                data: {id: requestId},
                success: function (response) {
                    // Handle the response (you can open the PDF in a new window or perform other actions)
                    console.log(response);
                },
                error: function (error) {
                    console.error(error);
                }
            });
        });
    });

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