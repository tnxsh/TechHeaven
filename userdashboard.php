<?php
session_start();
include "db_connection.php";

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: userlogin.php");
    exit();
}

/// Retrieve user details from the database
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE user_id = $user_id";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $username = $row['username'];
    $email = $row['email'];
    $address = $row['address'];
    $phone_num = $row['phone_num'];
    $profile_pic = "http://localhost/tech_heaven/profile_pic/" . $row['profile_pic']; // Updated path for profile picture

} else {
    // Handle error when user data is not found
    $error_message = "Failed to retrieve user information.";
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Styles for the navigation bar */
        .navbar {
            background-color: tomato;
            padding: 5px;
        }

        .navbar-brand {
            font-weight: bold;
        }

        /* Styles for the user dashboard */
        body {
            background-color: black;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            margin-top: 100px;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h3 {
            text-align: center;
            margin-bottom: 20px;
        }

        .profile-pic {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            object-position: center;
            margin-bottom: 20px;
        }

        .profile-details {
            margin-bottom: 20px;
        }

        .profile-details label {
            font-weight: bold;
        }

        .btn-primary {
            width: 100%;
        }
    </style>
</head>

<body>
    <!-- Navigation bar -->
   <!-- Navigation bar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="userdashboard.php">User Dashboard</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="editdetails.php">Edit Details</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="resetpassword.php">Reset Password</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="vieworderhistory.php">Order History</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="reviewsratings.php">Reviews and Ratings</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="warrantyclaim.php">Warranty Claim</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="messages.php">Notifications</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="logout.php">Logout</a>
            </li>
        </ul>
    </div>
</nav>


    <!-- User dashboard -->
    <div class="container">
        <!-- Profile Picture -->
        <div class="profile-picture">
            <?php if (!empty($profile_pic)): ?>
                <img src="<?php echo $profile_pic; ?>" alt="Profile Picture" class="profile-pic">
            <?php else: ?>
                <img src="default_profile_pic.jpg" alt="Default Profile Picture" class="profile-pic">
            <?php endif; ?>
        </div>

        <div class="profile-details">
            <label>Email:</label> <?php echo $email; ?><br>
            <label>Address:</label> <?php echo $address; ?><br>
            <label>Phone Number:</label> <?php echo $phone_num; ?><br>
            <a href="product_catalog.php" class="btn btn-primary">Continue Shopping</a>
        </div>
    </div>
    
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // JavaScript to check for inactivity and redirect to logout after 3 minutes
        (function () {
            var inactivityTimeout = 1 * 60 * 1000; // 3 minutes (in milliseconds)

            function logoutAfterInactivity() {
                window.location.href = 'logout.php'; // Assuming logout.php handles the logout process
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
