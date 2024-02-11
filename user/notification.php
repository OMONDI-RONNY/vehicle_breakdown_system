<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notification System</title>
    <style>
        #notification-container {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 300px;
            padding: 10px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            display: none;
            z-index: 999;
        }

        #notification-circle {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 40px;
            height: 40px;
            background-color: #007bff;
            color: #fff;
            text-align: center;
            line-height: 40px;
            border-radius: 50%;
            cursor: pointer;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            z-index: 1000;
        }

        #notification-circle:hover {
            background-color: #0056b3;
        }

        #notification-count {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>
<body>

<div id="notification-circle">&#128276;<span id="notification-count">0</span></div>
<div id="notification-container"></div>

<?php
 include '../includes/connection.php'; // Database configuration

$lastNotificationId = $_POST['lastNotificationId'] ?? 0;

$sql = "SELECT * FROM notifications WHERE id > $lastNotificationId ORDER BY id DESC";
$result = mysqli_query($conn, $sql);

$notifications = array();

while ($row = mysqli_fetch_assoc($result)) {
    $notifications[] = $row;
}

foreach ($notifications as $notification) {
    echo '<script>
            $(document).ready(function () {
                showNotification("' . $notification['message'] . '");
            });
          </script>';
}

?>

<script>
    function showNotification(message) {
        var container = $('#notification-container');
        container.html('<div>' + message + '</div>');
        container.fadeIn().delay(3000).fadeOut();

        // Update notification count
        var count = parseInt($('#notification-count').text());
        count++;
        $('#notification-count').text(count);
    }

    $('#notification-circle').click(function () {
        // Reset notification count on click
        $('#notification-count').text('0');
        $('#notification-container').toggle();
    });

    $(document).ready(function () {
        function getNotifications() {
            $.ajax({
                url: 'index.php',
                type: 'POST',
                data: { lastNotificationId: <?php echo $lastNotificationId; ?> },
                dataType: 'json',
                success: function (data) {
                    if (data.length > 0) {
                        for (let i = 0; i < data.length; i++) {
                            const notification = data[i];
                            showNotification(notification.message);
                            lastNotificationId = notification.id;
                        }
                    }
                }
            });
        }

        // Check for new notifications every 5 seconds
        setInterval(getNotifications, 5000);
    });
</script>

</body>
</html>
