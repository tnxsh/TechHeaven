<?php
session_start();
include "db_connection.php";

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: adminlogin.php");
    exit();
}

if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];

    // Retrieve user details from the database
    $user_query = "SELECT * FROM users WHERE user_id = $user_id";
    $user_result = mysqli_query($conn, $user_query);
    $user = mysqli_fetch_assoc($user_result);

    if (!$user) {
        // Handle error when user is not found
        $error_message = "User not found.";
    } else {
        // Retrieve user activity from the user_activity table
        $activity_query = "SELECT * FROM user_activity WHERE user_id = $user_id ORDER BY activity_date DESC, activity_time DESC";
        $activity_result = mysqli_query($conn, $activity_query);
        $activities = mysqli_fetch_all($activity_result, MYSQLI_ASSOC);
    }
} else {
    // Redirect to manage users page if user_id is not provided
    header("Location: admin_manage_users.php");
    exit();
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Activity</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Styles for the navigation bar and user activity page */
        /* ... CSS styles ... */
        
        body {
            background-color: black;
            color: white;
        }
       
        table {
            background-color: white;
            color: black;
        }
    </style>
</head>
<body>
    <!-- Navigation bar -->
    <!-- ... Navigation bar code ... -->
    
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="admin_manage_users.php">Manage Users</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="admin_dashboard.php">Admin Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="admin_manage_products.php">Manage Products</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="admin_view_orders.php">View Orders</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="admin_warrantyclaims.php">User Warranty Claims</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="admin_logout.php">Logout</a>
            </li>
        </ul>
    </div>
</nav>

    <!-- User activity page -->
    <div class="container">
        <h3>User Activity - <?php echo $user['username']; ?></h3>
        <?php if (isset($error_message)) { ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php } else { ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Activity ID</th>
                        <th>Searches</th>
                        <th>Seen Products</th>
                        <th>Time</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($activities as $activity) { ?>
                        <tr>
                            <td><?php echo $activity['activity_id']; ?></td>
                            <td><?php echo $activity['searches']; ?></td>
                            <td><?php echo $activity['seen_products']; ?></td>
                            <td><?php echo $activity['activity_time']; ?></td>
                            <td><?php echo $activity['activity_date']; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } ?>
    </div>
    
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
