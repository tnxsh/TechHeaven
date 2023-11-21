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
    $query = "SELECT * FROM user_log WHERE date = '$filter_date'";
} else {
    // Retrieve all user log data
    $query = "SELECT * FROM user_log";
}

$result = mysqli_query($conn, $query);

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Log</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <style>
        
        /* Styles for the navigation bar and manage users page */
        /* ... CSS styles ... */
        
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
        
    
        /* Add your CSS styles here */
        /* For example, you can style the table, headers, or other elements */
    </style>
</head>
<body>
    <!-- Navigation bar -->
    <!-- ... Navigation bar code ... -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="admin_user_log.php">User Log</a>
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

    <!-- User Log page -->
    <div class="container">
        <h3>User Log</h3>

        <!-- Date Picker -->
        <label for="datepicker">Select a Date: </label>
        <input type="text" id="datepicker" readonly="readonly" />

        <table class="table">
            <thead>
                <tr>
                    <th>Log ID</th>
                    <th>User ID</th>
                    <th>Login Time</th>
                    <th>Logout Time</th>
                    <th>Duration</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo $row['log_id']; ?></td>
                        <td><?php echo $row['user_id']; ?></td>
                        <td><?php echo $row['login_time']; ?></td>
                        <td><?php echo $row['logout_time']; ?></td>
                        <td><?php echo $row['duration']; ?></td>
                        <td><?php echo $row['date']; ?></td>
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
                    window.location.href = "admin_user_log.php?filter_date=" + dateText;
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
