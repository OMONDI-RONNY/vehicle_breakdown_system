<?php
// Include the connection file
include 'includes/connection.php';

// Fetch services from the database
$sql = "SELECT * FROM services";
$result = mysqli_query($conn, $sql);

$services = array();
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $services[] = $row['service_name'];
    }
}

// Close the database connection
mysqli_close($conn);
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Vehicle Breakdown Assistance</title>
  <style>
    /* Reset some default styles */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    /* Apply styles to the body */
    body {
      font-family: Arial, sans-serif;
      color: #333;
      margin: 0;
      padding: 0;
      cursor: pointer;
    }

    /* Header styles */
    header {
      background-color: #f5f5f5;
      padding: 20px;
      text-align: right;
      box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
      position: relative;
    }

    header img {
      max-width: 200px;
      margin-right: 20px;
      vertical-align: middle;
    }

    header nav {
      display: inline-block;
      margin-right: 20px;
    }

    header nav a {
      text-decoration: none;
      color: #333;
      margin-left: 10px;
    }

    .login {
      display: inline-block;
    }

    .login #loginDropdownBtn {
      cursor: pointer;
      padding: 8px 12px;
      border: 1px solid #ccc;
      border-radius: 4px;
      background-color: #fff;
    }

    .login-dropdown {
      position: absolute;
      top: 100%;
      right: 0;
      background-color: #fff;
      border: 1px solid #ccc;
      border-top: none;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
      display: none;
      z-index: 1;
    }

    .login-dropdown.active {
      display: block;
    }

    .login-dropdown ul {
      list-style: none;
      padding: 0;
      margin: 0;
    }

    .login-dropdown li {
      padding: 10px;
      text-align: center;
    }

    .login-dropdown li:hover {
      background-color: #f9f9f9;
      cursor: pointer;
    }

    /* Hero section styles */
    .hero {
      background-image: url('car2.jpg');
      background-size: cover;
      background-position: center;
      background-attachment: fixed;
      height: 100vh;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      text-align: center;
      color: #fff;
      padding: 50px;
    }

    .hero h1 {
      font-size: 3em;
      margin-bottom: 20px;
    }

    .hero p {
      font-size: 1.5em;
      margin-bottom: 20px;
    }

    .hero button {
      padding: 10px 20px;
      font-size: 1.2em;
      background-color: #ff9900;
      color: #fff;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    /* Main content container */
    .container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 20px;
    }

    /* Section styles with borders */
    .section {
      border: 1px solid #ccc;
      border-radius: 5px;
      padding: 20px;
      margin-bottom: 20px;
    }
    
  
    .section#contact {
      text-align: center;
      padding: 50px;
      
    }

    .section#contact h2 {
      font-size: 2em;
      margin-bottom: 20px;
    }

    .section#contact p {
      margin-bottom: 20px;
    }

    .social-media {
      margin-bottom: 30px;
    }

    .social-media a {
      display: inline-block;
      text-decoration: none;
      color: #333;
      background-color: #e6e6e6;
      padding: 10px 20px;
      border-radius: 5px;
      margin-right: 10px;
      transition: background-color 0.3s ease;
    }

    .social-media a:hover {
      background-color: #ccc;
    }

    /* Contact Form styles */
    form {
      display: flex;
      flex-direction: column;
      align-items: center;
      width: 100%;
      max-width: 400px;
      margin: 0 auto;
    }

    label {
      margin-bottom: 5px;
      font-weight: bold;
    }

    input[type="text"],
    input[type="email"],
    textarea {
      width: 100%;
      padding: 12px;
      margin-bottom: 15px;
      border: 1px solid #ccc;
      border-radius: 5px;
      resize: none; /* Disable textarea resizing */
    }

    input[type="submit"] {
      padding: 12px 24px;
      background-color: #ff9900;
      color: #fff;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    input[type="submit"]:hover {
      background-color: #e68a00; /* Darker shade of submit button color on hover */
    }

    /* Footer styles */
    footer {
      background-color: rgba(51, 51, 51, 0.9);
      color: #fff;
      text-align: center;
      padding: 20px 0;
      width: 100%;
    }

    footer p {
      font-size: 1.2em;
    }

    footer a {
      color: #fff;
      text-decoration: none;
    }

    footer a:hover {
      color: #ff9900;
    }
    .no-underline {
  text-decoration: none;
}
 #serviceSearch {
        padding: 10px;
        margin-bottom: 10px;
        width: 300px;
        border-radius: 5px;
        border: 1px solid #ccc;
        font-size: 16px;
    }

    #serviceList {
        list-style: none;
        padding: 0;
        max-height: 200px;
        overflow-y: auto;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    #serviceList li {
        padding: 8px 12px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    #serviceList li:hover {
        background-color: #f4f4f4;
    }
  </style>
</head>
<body>

  <!-- Header Section -->
  <header>
    <nav>
      <a href="#home">Home</a>
      <a href="#Services">Services</a>
      <a href="#about">About Us</a>
      <a href="#contact">Contact</a>
    </nav>
    <div class="login">
      <span id="loginDropdownBtn">Login &#9662;</span>
      <div class="login-dropdown" id="loginDropdown">
        <ul>
          <li><a class="no-underline" href="admin/adminlogin.php">Login as Admin</a></li>
          <li><a class="no-underline" href="user/login.php">Login as User</a></li>
          <li><a class="no-underline" href="mechanic/mechlog.php">Login as Mechanic</a></li>
        </ul>
      </div>
    </div>
  </header>

  <!-- Hero Section -->
  <section class="hero" id="home">
    <h1>Reliable Vehicle Breakdown Assistance</h1>
    <p>24/7 Emergency Services for Your Peace of Mind</p>
    <button>Contact Us</button>
  </section>

  <!-- Main Content Container -->
  <div class="container">
    <section class="section" id="Services">
    <!-- Services Section -->
   <!-- Search input field for auto-search -->
<label for="serviceSearch">Search Services:</label>
<input type="text" id="serviceSearch" name="serviceSearch" onkeyup="searchServices(this.value)">
<ul id="serviceList">
    <?php
    // Array of available services (dummy data)
    $services = array(
        "Towing", "Jump-starts", "Fuel Delivery", "Flat Tire Assistance", "Lockout Service", "Battery Replacement",
        "Engine Diagnostics", "Roadside Repairs", "Vehicle Recovery", "Emergency Assistance", "Transmission Service",
        "Brake Repair", "Wheel Alignment", "Exhaust System Repair", "Oil Change & Maintenance"
    );

    // Output all services initially
    if (!empty($services)) {
        foreach ($services as $service) {
            echo "<li>$service</li>";
        }
    } else {
        echo "<li>No services available</li>";
    }
    ?>
</ul>

<script>
    // Function to perform auto-search for services
    function searchServices(searchTerm) {
        let input, filter, ul, li, i, txtValue;
        input = searchTerm.toLowerCase();
        ul = document.getElementById("serviceList");
        li = ul.getElementsByTagName("li");

        // Loop through all list items, hide those that don't match the search query
        for (i = 0; i < li.length; i++) {
            txtValue = li[i].textContent || li[i].innerText;
            if (txtValue.toLowerCase().indexOf(input) > -1) {
                li[i].style.display = "";
            } else {
                li[i].style.display = "none";
            }
        }
    }
</script>
</section>


    <!-- About Us Section -->
   <!-- About Us Section -->
<section class="section" id="about">
  <h2>About Us</h2>
  <p>
    Welcome to <strong>EaseUs</strong>, your dedicated partner for reliable vehicle breakdown assistance. At our core, we understand the stress and inconvenience that accompany unexpected vehicle issues. Our committed team of professionals is focused on providing swift, top-notch solutions, ensuring your safety and peace of mind.
    <br><br>
    Our mission is simple: to redefine breakdown assistance by offering efficient and comprehensive services tailored to your needs. With years of industry experience, we've honed our expertise to handle various vehicle-related problems, from minor roadside assistance to more complex repairs, ensuring you're back on the road in no time.
    <br><br>
    We pride ourselves on our rapid response times, transparent communication, and fair pricing. Your convenience and satisfaction are our priorities. Trust us to deliver exceptional service, maintaining the highest standards in the industry.
  </p>
</section>


    <!-- Contact Section -->
   <!-- Contact Section -->
<section class="section" id="contact">
  <h2>Contact Us</h2>
  <p>For immediate assistance or inquiries, please reach out to us:</p>
  <p>Phone: <a href="tel:123456789">123-456-789</a><br>Email: <a href="mailto:info@example.com">info@example.com</a><br>Address: 123 Street, City, Country</p>

  <!-- Social Media Links -->
 
</section>

  </div>

  <!-- Footer Section -->
  <footer>
    <p>&copy; 2024 Vehicle Breakdown Assistance | All Rights Reserved</p>
    <p><a href="#">Privacy Policy</a> | <a href="#">Terms of Service</a></p>
  </footer>

  <script>
    // JavaScript to handle dropdown functionality
    document.addEventListener('DOMContentLoaded', function () {
      var loginDropdownBtn = document.getElementById('loginDropdownBtn');
      var loginDropdown = document.getElementById('loginDropdown');

      loginDropdownBtn.addEventListener('click', function () {
        loginDropdown.classList.toggle('active');
      });

      // Close the dropdown when clicking outside
      document.addEventListener('click', function (event) {
        if (!loginDropdown.contains(event.target) && !loginDropdownBtn.contains(event.target)) {
          loginDropdown.classList.remove('active');
        }
      });

      // Scroll to top when clicking on the Home link
      var homeLink = document.querySelector('header nav a[href="#home"]');
      homeLink.addEventListener('click', function (event) {
        event.preventDefault();
        window.scrollTo({
          top: 0,
          behavior: 'smooth'
        });
      });
    });
  </script>
</body>
</html>
