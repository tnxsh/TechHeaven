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
    $query = "SELECT * FROM user_activity WHERE activity_date = '$filter_date'";
} else {
    // Retrieve all user log data
    $query = "SELECT * FROM user_activity";
}

$result = mysqli_query($conn, $query);
// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Activity</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <style>
        body {
            background-color: black;
            color: white;
        }
        thead {
            color: black;
        }
        tbody {
            color: black;
        }
        table {
            background-color: white;
            color: black;
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
                <!-- Add other navigation links if needed -->
            </ul>
        </div>
    </nav>

    <!-- User Activity section -->
    <div class="container">
        <h3>User Activity</h3>
        
        <label for="datepicker">Select a Date: </label>
        <input type="text" id="datepicker" readonly="readonly" />
        
        <button class="btn btn-primary" onclick="location.href='admin_user_search_report.php?filter_date=<?php echo $filter_date; ?>'">Search Report</button>
        <button class="btn btn-primary" onclick="location.href='admin_user_seen_product_report.php?filter_date=<?php echo $filter_date; ?>'">Seen Product Report</button>
        
        
        <table class="table">
            <thead>
                <tr>
                    <th>Activity ID</th>
                    <th>User ID</th>
                    <th>Searches</th>
                    <th>Seen Products</th>
                    <th>Time</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo $row['activity_id']; ?></td>
                        <td><?php echo $row['user_id']; ?></td>
                        <td><?php echo $row['searches']; ?></td>
                        <td><?php echo $row['seen_products']; ?></td>
                        <td><?php echo $row['activity_time']; ?></td>
                        <td><?php echo $row['activity_date']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    
    <script>
        $(function() {
            $("#datepicker").datepicker({
                dateFormat: "yy-mm-dd", // Date format to match your database date format
                onSelect: function(dateText) {
                    window.location.href = "admin_user_activity.php?filter_date=" + dateText;
                }
            });

            const urlParams = new URLSearchParams(window.location.search);
            const filterDate = urlParams.get("filter_date");
            if (filterDate) {
                $("#datepicker").datepicker("setDate", filterDate);
            }
        });
    </script>
     <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
