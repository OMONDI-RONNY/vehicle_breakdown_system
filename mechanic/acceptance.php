<!DOCTYPE html>
<html>
<head>
    <title>Mechanic Acceptance Form</title>
    <style>
         <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
        }
        .container {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }
        h2 {
            text-align: center;
        }
        label {
            display: block;
            margin: 10px 0;
        }
        input[type="text"],
        input[type="tel"]

         {
            width: 90%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        select {
            width: 95%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="submit"],
        input[type="reset"]

         {
            background-color: #007BFF;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
    </style>
    </style>
</head>
<body>
    <div class="container">
    <h1>Mechanic Acceptance Form</h1>
    <form onsubmit="return validateForm()">
        <label for="serviceType">Service Type:</label>
         <input type="text" id="service" name="service" placeholder="change oil" required>


        <label for="carModel">Car Model:</label>
        <input type="text" id="carModel" name="carModel" required placeholder="BMW"><br><br>

        <label for="ownerName">Name of the Owner:</label>
        <input type="text" id="ownerName" name="ownerName" required placeholder="maurice otieno"><br><br>

        <label>Vehicle:</label><br>
        <label for="country">Registration Number:</label>
        <input type="text" id="reg" name="vehiclereg" required placeholder="Number plate"><br>
        <label for="country">Country:</label>
        <input type="text" id="country" name="country" required placeholder="kenya"><br>
        <label for="county">County:</label>
        <input type="text" id="county" name="county" required placeholder="kisumu"><br>
        <label for="streetAddress">Street Address:</label>
        <input type="text" id="streetAddress" name="streetAddress" required placeholder="232,kombewa"><br><br>

        <label for="phoneNumber">Phone Number:</label>
        <input type="tel" id="phoneNumber" name="phoneNumber" required placeholder="0796471436"><br><br>

        <label>Accept (Yes/No):</label><br>
        <input type="radio" id="acceptYes" name="acceptance" value="yes" required>
        <label for="acceptYes">Yes</label>
        <input type="radio" id="acceptNo" name="acceptance" value="no" required>
        <label for="acceptNo">No</label><br><br>

        <input type="submit" value="Submit">
        <input type="reset" value="Clear" onclick="clearForm()">
    </form>

    <script>
        function validateForm() {
            // Basic validation to ensure required fields are not empty
            var serviceType = document.getElementById("serviceType").value;
            var carModel = document.getElementById("carModel").value;
            var ownerName = document.getElementById("ownerName").value;
            var country = document.getElementById("country").value;
            var county = document.getElementById("county").value;
            var streetAddress = document.getElementById("streetAddress").value;
            var phoneNumber = document.getElementById("phoneNumber").value;
            var acceptance = document.querySelector('input[name="acceptance"]:checked');

            if (!serviceType || !carModel || !ownerName || !country || !county || !streetAddress || !phoneNumber || !acceptance) {
                alert("Please fill in all required fields.");
                return false; // Prevent form submission
            }

            // You can add more specific validation here, e.g., phone number format

            // Sanitize user inputs (basic HTML escaping)
            carModel = carModel.replace(/</g, "&lt;").replace(/>/g, "&gt;");
            ownerName = ownerName.replace(/</g, "&lt;").replace(/>/g, "&gt;");
            country = country.replace(/</g, "&lt;").replace(/>/g, "&gt;");
            county = county.replace(/</g, "&lt;").replace(/>/g, "&gt;");
            streetAddress = streetAddress.replace(/</g, "&lt;").replace(/>/g, "&gt;");
            phoneNumber = phoneNumber.replace(/</g, "&lt;").replace(/>/g, "&gt;");

            // Optionally, you can do further sanitization based on your needs.

            return true; // Form submission will proceed if all checks pass
        }

        function clearForm() {
            document.getElementById("serviceType").value = "";
            document.getElementById("carModel").value = "";
            document.getElementById("ownerName").value = "";
            document.getElementById("country").value = "";
            document.getElementById("county").value = "";
            document.getElementById("streetAddress").value = "";
            document.getElementById("phoneNumber").value = "";
            document.querySelector('input[name="acceptance"]:checked').checked = false;
        }
    </script>
</body>
</html>
