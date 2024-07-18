<?php
session_start();
include '../includes/connection.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once 'phpmailer/src/Exception.php';
require_once 'phpmailer/src/PHPMailer.php';
require_once 'phpmailer/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id = $_SESSION['req'];  // Assuming 'owner' is the name attribute of the input containing the request ID
    $acceptanceValue = $_POST['acceptReject'];
    $mechanicId = $_SESSION['mech_id'];

    // Update the 'acceptance' column in the 'request' table
    $updateSql = "UPDATE request SET acceptance = ? WHERE id = ?";
    $stmt = $conn->prepare($updateSql);
    $stmt->bind_param("ii", $acceptanceValue, $id);
    $stmt->execute();

    // Check if the update was successful
    if ($stmt->affected_rows > 0) {
        // Fetch user details from the 'request' table
        $getUserDetailsSql = "SELECT contact_name, contact_email, contact_phone FROM request WHERE id = ?";
        $stmtUserDetails = $conn->prepare($getUserDetailsSql);
        $stmtUserDetails->bind_param("i", $id);
        $stmtUserDetails->execute();
        $resultUserDetails = $stmtUserDetails->get_result();

        if ($resultUserDetails->num_rows > 0) {
            $rowUserDetails = $resultUserDetails->fetch_assoc();
            $userName = $rowUserDetails['contact_name'];
            $userEmail = $rowUserDetails['contact_email'];
            $userPhone = $rowUserDetails['contact_phone'];

            // Fetch mechanic details from the 'mechanicreg' table using $_SESSION['mech_id']
            $getMechanicDetailsSql = "SELECT firstname, lastname, phone, mech_email FROM mechanicreg WHERE id = ?";
            $stmtMechanicDetails = $conn->prepare($getMechanicDetailsSql);
            $stmtMechanicDetails->bind_param("i", $mechanicId);
            $stmtMechanicDetails->execute();
            $resultMechanicDetails = $stmtMechanicDetails->get_result();

            if ($resultMechanicDetails->num_rows > 0) {
                $rowMechanicDetails = $resultMechanicDetails->fetch_assoc();
                $mechanicFirstName = $rowMechanicDetails['firstname'];
                $mechanicLastName = $rowMechanicDetails['lastname'];
                $mechanicPhone = $rowMechanicDetails['phone'];
                $mechanicEmail = $rowMechanicDetails['mech_email'];

                // Email sending logic
                $mail = new PHPMailer(true);

                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';

                $mail->SMTPAuth = true;
                $mail->Username = 'omoron37@gmail.com';
                $mail->Password = 'uxrgdwpdpujljjdf';
                $mail->SMTPSecure = 'ssl';
                $mail->Port = 465;

                $mail->setFrom('omoron37@gmail.com');
                $mail->addAddress($userEmail);
                $mail->isHTML(true);
                $mail->Subject = 'Mechanic On the Way';
                $mail->Body = "
                    <html lang='en'>
                    <head>
                        <meta charset='UTF-8'>
                        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                        <style>
                            body {
                                font-family: 'Arial', sans-serif;
                                background-color: #f2f2f2;
                                margin: 0;
                                padding: 0;
                            }

                            .container {
                                max-width: 600px;
                                background-color: #ffffff;
                                border-radius: 10px;
                                box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
                                margin: 20px auto;
                                padding: 20px;
                                box-sizing: border-box;
                            }

                            h2 {
                                background-color: #3498db;
                                color: #ffffff;
                                padding: 20px;
                                margin: 0;
                                font-size: 28px;
                                text-align: center;
                                border-radius: 10px 10px 0 0;
                            }

                            p {
                                color: #333;
                                font-size: 16px;
                            }
                        </style>
                    </head>
                    <body>
                        <div class='container'>
                            <h2>Mechanic On the Way</h2>
                            <p>Dear $userName,</p>
                            <p>We are pleased to inform you that the mechanic is on the way to address your vehicle breakdown. Here are the details of the assigned mechanic:</p>
                            <p><strong>Name:</strong> $mechanicFirstName $mechanicLastName</p>
                            <p><strong>Phone:</strong> $mechanicPhone</p>
                            <p><strong>Email:</strong> $mechanicEmail</p>
                            <p>Please be ready to provide any additional information and cooperate with the mechanic to facilitate a quick and effective resolution to your situation.</p>
                            <p>Thank you for choosing our Vehicle Breakdown Assistance service.</p>
                            <p>Best regards,<br/>[ Ease Us] Vehicle Breakdown Assistance Team</p>
                        </div>
                    </body>
                    </html>
                ";

                if ($mail->send()) {
                    // Redirect to wherever you need after the update and email sending
                    header("Location: index.php");
                } else {
                    echo "Error sending email. Please try again.";
                }
            } else {
                echo "Error getting mechanic details. Please try again.";
            }

            // Close the statement for getting mechanic details
            $stmtMechanicDetails->close();
        } else {
            echo "Error getting user details. Please try again.";
        }

        // Close the statement for getting user details
        $stmtUserDetails->close();
    } else {
        echo "Error updating acceptance status. Please try again.";
    }

    // Close the statement for updating request
    $stmt->close();
}

$conn->close();
?>
