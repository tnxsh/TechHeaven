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

    // Delete user from the database
    $query = "DELETE FROM users WHERE user_id = $user_id";
    $result = mysqli_query($conn, $query);

    if ($result) {
        // User deleted successfully
        header("Location: admin_manage_users.php");
        exit();
    } else {
        // Handle error when failed to delete user
        $error_message = "Failed to delete user.";
    }
} else {
    // Redirect to manage users page if user_id is not provided
    header("Location: admin_manage_users.php");
    exit();
}

// Close the database connection
mysqli_close($conn);
?>
