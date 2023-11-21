<?php
session_start();
include "db_connection.php";

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: adminlogin.php");
    exit();
}

// Check if a date filter is set
$filter_date = "";
if (isset($_GET['filter_date'])) {
    $filter_date = $_GET['filter_date'];
    // Modify the SQL query to filter by the selected date
    $query = "SELECT seen_products, COUNT(*) AS seen_count FROM user_activity WHERE activity_date = '$filter_date' GROUP BY seen_products";
} else {
    // Retrieve all user seen product data
    $query = "SELECT seen_products, COUNT(*) AS seen_count FROM user_activity GROUP BY seen_products";
}

$result = mysqli_query($conn, $query);
// Close the database connection
mysqli_close($conn);

// Prepare data for chart
$seenProducts = array();
$seenCount = array();

while ($row = mysqli_fetch_assoc($result)) {
    $seenProducts[] = $row['seen_products'];
    $seenCount[] = $row['seen_count'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Seen Product Report</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <style>
        .container {
            margin-top: 50px;
            color: white;
        }

        h2 {
            margin-bottom: 20px;
            color: white;
        }

        body {
            background-color: black;
        }

        .chart-container {
            width: 100%;
            height: 400px;
            margin-top: 50px;
        }
    </style>
</head>

<body>
    <!-- Navigation bar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="admin_user_activity.php">User Activity</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="admin_manage_users.php">Manage Users</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="admin_dashboard.php">Admin Dashboard</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <h2>User Seen Product Report</h2>

        <label for="datepicker">Select a Date: </label>
        <input type="text" id="datepicker" readonly="readonly" />

        <div class="chart-container">
            <canvas id="seenChart"></canvas>
        </div>
    </div>
    
    

    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.5.1"></script>
    
    <script>
        $(document).ready(function () {
            // Datepicker initialization
            $("#datepicker").datepicker({
                dateFormat: "yy-mm-dd", // Date format to match your database date format
                onSelect: function(dateText) {
                    window.location.href = "admin_user_seen_product_report.php?filter_date=" + dateText;
                }
            });

            const urlParams = new URLSearchParams(window.location.search);
            const filterDate = urlParams.get("filter_date");
            if (filterDate) {
                $("#datepicker").datepicker("setDate", filterDate);
            }

            // Chart initialization
            var ctx = document.getElementById('seenChart').getContext('2d');
            var seenChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode($seenProducts); ?>,
                    datasets: [{
                        label: 'Number of Seen Products',
                        data: <?php echo json_encode($seenCount); ?>,
                        backgroundColor: 'rgba(255, 99, 132, 0.6)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            precision: 0
                        }
                    }
                }
            });
        });
    </script>
    
    
    <script>
        // JavaScript to check for inactivity and redirect to logout after 3 minutes
        (function () {
            var inactivityTimeout = 1 * 60 * 1000; // 3 minutes (in milliseconds)

            function logoutAfterInactivity() {
                window.location.href = 'admin_logout.php'; // Assuming logout.php handles the logout process
            }

            $(document).on('mousemove keydown', function () {
                resetInactivityTimer();
            });

            function resetInactivityTimer() {
                clearTimeout(window.logoutTimer);
                window.logoutTimer = setTimeout(logoutAfterInactivity, inactivityTimeout);
            }

            resetInactivityTimer();
        })();
    </script>
</body>
</html>
