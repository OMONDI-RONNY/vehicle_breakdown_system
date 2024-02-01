
<?php
// Include the connection file
include 'includes/connection.php';

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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Service Request Form</title>
    <style>
        /* Add your CSS styles here for an appealing format */
       /* Add your CSS styles here for an appealing format */
/* Add your CSS styles here for an appealing format */
body {
    font-family: 'Arial', sans-serif;
    background-color: #f5f5f5;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
}

form {
    max-width: 600px;
    width: 100%;
    background-color: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    margin-top: 50px;
}

h2 {
    text-align: center;
    color: #333;
    margin-bottom: 20px;
}

label {
    display: block;
    margin-bottom: 8px;
    color: #333;
}

select,
input {
    width: calc(100% - 22px); /* Adjust the width as needed */
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 16px;
    transition: border-color 0.3s ease;
}

select:hover,
input:hover,
select:focus,
input:focus {
    border-color: #ff9900;
}

input[type="submit"] {
    background-color: #ff9900;
    color: #fff;
    border: none;
    border-radius: 5px;
    padding: 10px 20px;
    cursor: pointer;
    font-size: 16px;
    transition: background-color 0.3s ease;
}

input[type="submit"]:hover {
    background-color: #e68a00;
}

    </style>
</head>
<body>

    <!-- HTML form with dependent select boxes -->
    <form action="process_request.php" method="post">
        <h2>Service Request Form</h2>

        <!-- Other form fields go here -->
         <label for="vehicle_registration">Vehicle Registration Number:</label>
    <input type="text" id="vehicle_registration" name="vehicle_registration" required>

     <label for="issue_description">Issue Description:</label>
        <select name="issue_description" id="issue_description">
            <option value="">Select Issue Description</option>
            <?php foreach ($services as $service) : ?>
                <option value="<?php echo $service['id']; ?>"><?php echo $service['service_name']; ?></option>
            <?php endforeach; ?>
        </select>

        <label for="county">County:</label>
        <select name="county" id="county" onchange="populateSubCounties()">
            <option value="">Select County</option>
            <?php foreach ($counties as $county) : ?>
                <option value="<?php echo $county['id']; ?>"><?php echo $county['county_name']; ?></option>
            <?php endforeach; ?>
        </select>

        <label for="sub_county">Sub-County:</label>
        <select name="sub_county" id="sub_county">
            <option value="">Select Sub-County</option>
        </select>
         <label for="contact_address">Contact Address:</label>
    <input type="text" id="contact_address" name="contact_address" required>

    <label for="req_date">Request Date:</label>
    <input type="date" id="req_date" name="req_date" required>

    <label for="req_time">Request Time:</label>
    <input type="time" id="req_time" name="req_time" required>

        <!-- Other form fields go here -->

        <input type="submit" value="Submit">
    </form>

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

</body>
</html>
