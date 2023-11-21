<?php
session_start();
include "db_connection.php";

// Check if the user is already logged in, redirect to dashboard if true
if (isset($_SESSION['user_id'])) {
    header("Location: userdashboard.php");
    exit();
}

// Define a regular expression pattern for a strong password
$password_pattern = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{8,}$/";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form inputs
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $profile_pic = $_FILES['profile_pic'];

    // Check if the username already exists in the database
    $check_username_sql = "SELECT * FROM Users WHERE username = '$username'";
    $result_username = $conn->query($check_username_sql);

    // Check if the email already exists in the database
    $check_email_sql = "SELECT * FROM Users WHERE email = '$email'";
    $result_email = $conn->query($check_email_sql);

    if ($result_username->num_rows > 0) {
        echo '<script>alert("Username is already taken. Please choose a different username.")</script>';
    } elseif ($result_email->num_rows > 0) {
        echo '<script>alert("Email is already registered. Please use a different email address.")</script>';
    } else {
        // Validate the password against the pattern
        if (!preg_match($password_pattern, $password)) {
            echo '<script>alert("Password must contain at least one uppercase letter, one lowercase letter, one numeric digit, and be at least 8 characters long.")</script>';
        } else {
            // Upload profile picture
            $profile_pic_path = "";
            if ($profile_pic['name']) {
                $profile_pic_dir = "C:/xampp/htdocs/tech_heaven/profile_pic/";
                $profile_pic_name = $profile_pic['name'];
                $profile_pic_tmp_name = $profile_pic['tmp_name'];
                $profile_pic_path = $profile_pic_dir . $profile_pic_name;
                move_uploaded_file($profile_pic_tmp_name, $profile_pic_path);
            }

            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert user data into the database
            $sql = "INSERT INTO Users (username, email, password, profile_pic) VALUES ('$username', '$email', '$hashed_password', '$profile_pic_path')";
            if ($conn->query($sql) === TRUE) {
                // Registration successful, redirect to login page
                header("Location: userlogin.php");
                exit();
            } else {
                // Registration failed, handle the error (e.g., display an error message)
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    }
}

$conn->close();
?>

<!-- register.php -->
<!DOCTYPE html>
<html>
<head>
    <title>User Registration</title>
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

        /* Styles for the registration form */
        body {
            background-color: black;
        }

        .container {
            max-width: 400px;
            margin: 0 auto;
            margin-top: 200px;
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
    

    <!-- Registration form -->
    <div class="container">
        <h3>User Registration</h3>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="profile_pic">Profile Picture:</label>
                <input type="file" class="form-control-file" id="profile_pic" name="profile_pic">
            </div>
            <button type="submit" class="btn btn-primary">Register</button>
            <p class="text-center mt-3">Already have an account? <a href="userlogin.php">Login</a></p>
        </form>
    </div>
</body>
</html>
