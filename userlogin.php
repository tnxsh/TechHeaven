<?php
session_start();
include "db_connection.php";

date_default_timezone_set('Asia/Kuala_Lumpur'); // Set the time zone to Malaysia

// Check if the user is already logged in, redirect to dashboard if true
if (isset($_SESSION['user_id'])) {
    // Check for inactivity and logout if necessary
    checkInactivityAndLogout();
    
    header("Location: userdashboard.php");
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form inputs
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Retrieve user data from the database
    $sql = "SELECT user_id, password FROM Users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $hashed_password = $row['password'];
        if (password_verify($password, $hashed_password)) {
            // Authentication successful, set session variables and redirect to dashboard
            $_SESSION['user_id'] = $row['user_id'];
            
            // Insert login record into user_log
            $user_id = $row['user_id'];
            $login_time = date('Y-m-d H:i:s'); // Current time in Malaysia
            $date = date('Y-m-d'); // Current date in Malaysia
            $insert_query = "INSERT INTO user_log (user_id, login_time, date) VALUES ('$user_id', '$login_time', '$date')";
            $conn->query($insert_query);
            
            header("Location: userdashboard.php");
            exit();
        } else {
            // Authentication failed, handle the error (e.g., display an error message)
            $error_message = "Invalid email or password";
        }
    } else {
        // Authentication failed, handle the error (e.g., display an error message)
        $error_message = "Invalid email or password";
    }
}

function checkInactivityAndLogout() {
    $inactivity_timeout = 1 * 60; // 3 minutes (in seconds)
    
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $inactivity_timeout)) {
        // User has been inactive for too long, destroy the session and log them out
        session_unset();
        session_destroy();
        header("Location: login.php"); // Redirect to the login page
        exit();
    }

    // Update last activity time
    $_SESSION['last_activity'] = time();
}

$conn->close();
?>

<!-- login.php -->
<!DOCTYPE html>
<html>
<head>
    <title>User Login</title>
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

        /* Styles for the login form */
        body {
            background-color: black;
        }

        .container {
            max-width: 400px;
            margin: 0 auto;
            margin-top: 250px;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .container h3 {
            text-align: center;
            margin-bottom: 20px;
        }

        .form-group label {
            font-weight: bold;
        }

        .btn-primary {
            width: 100%;
        }

        .alert {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <!-- Navigation bar -->
    

    <!-- Login form -->
    <div class="container">
        <h3>User Login</h3>
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
            <p class="text-center mt-3">Don't have an account? <a href="userregister.php">Register</a></p>
        </form>
    </div>
</body>
<script>
// JavaScript to check for inactivity and redirect to logout after 3 minutes
(function() {
    var inactivityTimeout = 3 * 60 * 1000; // 3 minutes (in milliseconds)
    
    function logoutAfterInactivity() {
        setTimeout(function() {
            window.location.href = 'logout.php'; // Assuming logout.php handles the logout process
        }, inactivityTimeout);
    }

    document.addEventListener('mousemove', function() {
        resetInactivityTimer();
    });

    document.addEventListener('keydown', function() {
        resetInactivityTimer();
    });

    function resetInactivityTimer() {
        clearTimeout(logoutTimer);
        logoutAfterInactivity();
    }

    resetInactivityTimer();
})();
</script>
</html>
