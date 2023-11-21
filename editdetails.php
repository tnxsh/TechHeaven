<?php
session_start();
include "db_connection.php";

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Retrieve user information from the database
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE user_id = $user_id";
$result = mysqli_query($conn, $query);

// Fetch user data
if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $username = $row['username'];
    $email = $row['email'];
    $address = $row['address'];
    $phone_num = $row['phone_num'];
    $profile_pic = "profile_pic/" . $row['profile_pic']; // Updated path for profile picture
} else {
    // Handle error when user data is not found
    $error_message = "Failed to retrieve user information.";
}

// Update user details
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_email = $_POST['email'];
    $new_address = $_POST['address'];
    $new_phone_num = $_POST['phone_num'];
    $profile_pic_name = $_FILES['profile_pic']['name'];
    $profile_pic_tmp = $_FILES['profile_pic']['tmp_name'];
    $profile_pic_path = "profile_pic/" . $profile_pic_name;

    // Check if a new profile picture is uploaded
    if (!empty($profile_pic_name)) {
        // Move the uploaded profile picture to the desired location
        move_uploaded_file($profile_pic_tmp, $profile_pic_path);
    }

    // Check if the new email already exists in the database
    $check_email_query = "SELECT * FROM users WHERE email = '$new_email' AND user_id != $user_id";
    $email_result = mysqli_query($conn, $check_email_query);

    // Check if the new phone number already exists in the database
    $check_phone_query = "SELECT * FROM users WHERE phone_num = '$new_phone_num' AND user_id != $user_id";
    $phone_result = mysqli_query($conn, $check_phone_query);

    if (mysqli_num_rows($email_result) > 0) {
        echo '<script>alert("Email is already registered by another user. Please use a different email address.")</script>';
    } elseif (mysqli_num_rows($phone_result) > 0) {
        echo '<script>alert("Phone number is already registered by another user. Please use a different phone number.")</script>';
    } else {
        // Update the user details in the database
        $update_query = "UPDATE users SET email = '$new_email', address = '$new_address', phone_num = '$new_phone_num', profile_pic = '$profile_pic_name' WHERE user_id = $user_id";
        mysqli_query($conn, $update_query);

        // Redirect to the user dashboard
        header("Location: userdashboard.php");
        exit();
    }
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Details</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Styles for the navigation bar */
        .navbar {
            background-color: #f8f9fa;
            padding: 10px;
        }

        .navbar-brand {
            font-weight: bold;
        }

        /* Styles for the edit details form */
        body {
            background-color: black;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            margin-top: 100px;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <!-- Navigation bar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="editdetails.php">Edit Details</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="userdashboard.php">User Dashboard</a>
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

    <!-- Edit details form -->
    <div class="container">
        <h2>Edit Details</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Email</label>
                <input type="email" class="form-control" name="email" value="<?php echo $email; ?>" required>
            </div>
            <div class="form-group">
                <label>Address</label>
                <input type="text" class="form-control" name="address" value="<?php echo $address; ?>" required>
            </div>
            <div class="form-group">
                <label>Phone Number</label>
                <input type="text" class="form-control" name="phone_num" value="<?php echo $phone_num; ?>" required>
            </div>
            <div class="form-group">
                <label>Profile Picture</label>
                <input type="file" class="form-control-file" name="profile_pic">
            </div>
            <button type="submit" class="btn btn-primary">Save Details</button>
        </form>
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
