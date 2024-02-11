<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehicle Breakdown Assistance FAQs</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f2f2f2;
        }

        h1 {
            color: #2C3E50;
            text-align: center;
        }

        .faq-container {
            max-width: 800px;
            margin: 20px auto;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
            padding: 20px;
        }

        .faq-question {
            background-color: #3498db;
            color: #fff;
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .faq-question:hover {
            background-color: #217dbb;
        }

        .faq-answer {
            display: none;
            padding: 15px;
            border: 1px solid #3498db;
            border-radius: 5px;
            margin-top: 5px;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
    </style>
</head>
<body>
    <h1>Vehicle Breakdown Assistance FAQs</h1>

    <div class="faq-container">
        <?php
            // Include the database connection file
            include 'includes/connection.php';

            // Array of keywords to filter FAQs
            $keywords = ["towing", "flat tire", "jump-start", "fuel delivery", "lockout", "mechanical issues"];

            // Build the WHERE clause for SQL query
            $whereClause = implode(" OR ", array_map(function ($keyword) {
                return "queries LIKE '%$keyword%'";
            }, $keywords));

            // Query to fetch frequently repeated questions related to the keywords
            $sql = "SELECT DISTINCT queries FROM chatbot WHERE $whereClause";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // Output data of each row
                while($row = $result->fetch_assoc()) {
                    echo '<div class="faq-question" onclick="toggleAnswer(this)">' . $row["queries"] . '</div>';
                    echo '<div class="faq-answer">' . getAnswers($conn, $row["queries"]) . '</div>';
                }
            } else {
                echo "No frequently asked questions related to the provided keywords found.";
            }

            // Close connection
            $conn->close();

            // Function to get answers for a specific question
            function getAnswers($conn, $question) {
                $sql = "SELECT replies FROM chatbot WHERE queries = '$question'";
                $result = $conn->query($sql);

                $answers = "";
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        $answers .= '<p>' . $row["replies"] . '</p>';
                    }
                }

                return $answers;
            }
        ?>
    </div>

    <script>
        function toggleAnswer(element) {
            var answer = element.nextElementSibling;
            answer.style.display = (answer.style.display === 'none' || answer.style.display === '') ? 'block' : 'none';
        }
    </script>
</body>
</html>
