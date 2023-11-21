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
    $query = "SELECT * FROM users WHERE user_id = $user_id";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);

    if (!$user) {
        // Handle error when user is not found
        $error_message = "User not found.";
    } else {
        // Retrieve the user's last login time from user_log table
        $last_login_query = "SELECT MAX(login_time) AS last_login FROM user_log WHERE user_id = $user_id";
        $last_login_result = mysqli_query($conn, $last_login_query);
        $last_login_data = mysqli_fetch_assoc($last_login_result);

        if ($last_login_data['last_login']) {
            // Calculate the time difference
            $last_login_time = strtotime($last_login_data['last_login']);
            $current_time = time();
            $time_diff = $current_time - $last_login_time;

            // Format the time difference as "x minutes/hours/days ago" based on the duration
            if ($time_diff < 60) {
                $last_login = "Just logged in";
            } elseif ($time_diff < 3600) {
                $minutes = floor($time_diff / 60);
                $last_login = "$minutes minutes ago";
            } elseif ($time_diff < 86400) {
                $hours = floor($time_diff / 3600);
                $last_login = "$hours hours ago";
            } else {
                $days = floor($time_diff / 86400);
                $last_login = "$days days ago";
            }

            $user['last_login'] = $last_login;
        } else {
            $user['last_login'] = "No login data available";
        }
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
    <title>User Details</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Styles for the navigation bar and user details page */
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

    <!-- User details page -->
    <div class="container">
        <h3>User Details</h3>
        <?php if (isset($error_message)) { ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php } else { ?>
            <table class="table">
                <tr>
                    <th>User ID:</th>
                    <td><?php echo $user['user_id']; ?></td>
                </tr>
                <tr>
                    <th>Username:</th>
                    <td><?php echo $user['username']; ?></td>
                </tr>
                <tr>
                    <th>Email:</th>
                    <td><?php echo $user['email']; ?></td>
                </tr>
                <tr>
                    <th>Phone:</th>
                    <td><?php echo $user['phone_num']; ?></td>
                </tr>
                <tr>
                    <th>Address:</th>
                    <td><?php echo $user['address']; ?></td>
                </tr>
                <tr>
                    <th>Last Login:</th>
                    <td><?php echo $user['last_login']; ?></td>
                </tr>
                <td>
                <a href="user_id_log.php?user_id=<?php echo $user_id; ?>" class="btn btn-primary">User Log</a>
                
                                <a href="user_id_activity.php?user_id=<?php echo $user_id; ?>" class="btn btn-primary">User Activity</a>
                                
                                <a href="admin_user_orders.php?user_id=<?php echo $user_id; ?>" class="btn btn-primary">User Orders</a>
                </td>

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
