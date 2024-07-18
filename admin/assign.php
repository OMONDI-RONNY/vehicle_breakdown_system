<?php
// ... (your existing code)

// Handle form submission
session_start();
include '../includes/connection.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
$mechanicId = $_SESSION['mech'];
$name=$_SESSION['contact_name'];
$email=$_SESSION['contact_email'];
$id=$_SESSION['user_id'] ;
$phone=$_SESSION['contact_phone'] ;
$address=$_SESSION['contact_address'];
$county=$_SESSION['county'];
$subcounty=$_SESSION['sub_county'];
$model=$_SESSION['vehicle_model'] ;
$description=$_SESSION['issue_desc'] ;
$date=$_SESSION['req_date'];
$time=$_SESSION['req_time'];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the assigned mechanic from the form submission
    $assignedMechanic = $_POST['typeOfService'];

    // Update the request table with the assigned mechanic
    $updateRequestSql = "UPDATE request SET mechanic_assigned = ? WHERE id = ?";
    $stmtRequest = $conn->prepare($updateRequestSql);
    $stmtRequest->bind_param("si", $assignedMechanic, $mechanicId);
    $stmtRequest->execute();

    // Check if the update was successful
    if ($stmtRequest->affected_rows > 0) {
        // Update the mechanicreg table and set the status to zero
        $updateMechanicSql = "UPDATE mechanicreg SET status = 0 WHERE id = ?";
        $stmtMechanic = $conn->prepare($updateMechanicSql);
        $stmtMechanic->bind_param("i", $assignedMechanic);
        $stmtMechanic->execute();

        // Check if the update was successful
        if ($stmtMechanic->affected_rows > 0) {
            // Fetch email directly from the database
            $mechanicEmail = '';
            $sqlMechanicEmail = "SELECT mech_email FROM mechanicreg WHERE id = ?";
            $stmtMechanicEmail = $conn->prepare($sqlMechanicEmail);
            $stmtMechanicEmail->bind_param("i", $assignedMechanic);
            $stmtMechanicEmail->execute();
            $resultMechanicEmail = $stmtMechanicEmail->get_result();

            if ($resultMechanicEmail->num_rows > 0) {
                $mechanicEmailRow = $resultMechanicEmail->fetch_assoc();
                $mechanicEmail = $mechanicEmailRow['mech_email'];
            }

            // Your email configuration
           
            require_once 'phpmailer/src/Exception.php';
            require_once 'phpmailer/src/PHPMailer.php';
            require_once 'phpmailer/src/SMTP.php';

            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'omoron37@gmail.com'; // Replace with your email
            $mail->Password = 'uxrgdwpdpujljjdf'; // Replace with your email password
            $mail->SMTPSecure = 'ssl';
            $mail->Port = 465;

            $mail->setFrom('omoron37@gmail.com');
            $mail->addAddress($mechanicEmail); // Use the fetched mechanic's email
            $mail->isHTML(true);
            $mail->Subject = 'Task Assignment';
            
            // Advanced CSS styles for the message body
            $mail->Body = '
                <html lang="en">
                    <head>
                        <meta charset="UTF-8">
                        <meta name="viewport" content="width=device-width, initial-scale=1.0">
                        <style>
                            body {
                                font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
                                background-color: #f4f4f4;
                                margin: 0;
                                padding: 0;
                                text-align: center;
                            }
                            .container {
                                max-width: 600px;
                                margin: 0 auto;
                                background-color: #fff;
                                border-radius: 8px;
                                box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
                                overflow: hidden;
                            }
                            header {
                                background-color: #3498db;
                                color: #fff;
                                padding: 20px;
                                font-size: 24px;
                            }
                            .content {
                                padding: 20px;
                            }
                            .greeting {
                                font-size: 18px;
                                color: #555;
                                margin-bottom: 20px;
                            }
                            .details {
                                text-align: left;
                                color: #555;
                            }
                            .task-details {
                                margin-top: 20px;
                                text-align: left;
                            }
                            .task-details p {
                                margin-bottom: 10px;
                            }
                            .cta-button {
                                display: inline-block;
                                padding: 10px 20px;
                                background-color: #3498db;
                                color: #fff;
                                text-decoration: none;
                                border-radius: 5px;
                                margin-top: 20px;
                            }
                            .footer {
                                margin-top: 20px;
                                font-size: 14px;
                                color: #777;
                            }
                        </style>
                    </head>
                    <body>
                        <div class="container">
                            <header>
                                Task Assignment
                            </header>
                            <div class="content">
                                <div class="greeting">
                                    <p>Hello ' .  $mechanicEmail . ',</p>
                                    <p>Congratulations! You have been assigned a new task.</p>
                                    
                                </div>
                                <div class="details">
                                    <p><strong>Name:</strong> ' . $name . '</p>
                                    <p><strong>Email:</strong> ' . $email . '</p>
                                    <p><strong>Phone:</strong> ' . $phone . '</p>
                                    <p><strong>Address:</strong> ' . $address . '</p>
                                    <p><strong>County:</strong> ' . $county . '</p>
                                    <p><strong>Subcounty:</strong> ' . $subcounty . '</p>
                                    <p><strong>Vehicle Model:</strong> ' . $model . '</p>
                                    <p><strong>Preferred Date:</strong> ' . $date . '</p>
                                    <p><strong>Preferred Time:</strong> ' . $time . '</p>
                                </div>
                                <div class="task-details">
                                    <p><strong>Task Details:</strong></p>
                                    <div>' . $description . '</div>
                                </div>
                                <a href="http://yourwebsite.com/request.php" class="cta-button">View Task Details</a>
                            </div>
                            <div class="footer">
                                <p>If you have any questions or concerns, please contact us at support@yourwebsite.com or call +1 (555) 123-4567.</p>
                            </div>
                        </div>
                    </body>
                </html>
            ';
            
            if ($mail->send()) {
                echo "<script>alert('Email sent successfully');</script>";
            } else {
                echo "<script>alert('Error: {$mail->ErrorInfo}');</script>";
            }
            

            // Close the statement for fetching mechanic's email
            $stmtMechanicEmail->close();

            // Redirect to the desired page after successful assignment and update
            header("Location: request.php");
        } else {
            echo "Error updating mechanic status. Please try again.";
        }

        // Close the statement for updating mechanicreg
        $stmtMechanic->close();
    } else {
        echo "Error assigning mechanic. Please try again.";
    }

    // Close the statement for updating request
    $stmtRequest->close();
}
?>
