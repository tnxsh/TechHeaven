<?php
session_start();
include "db_connection.php";

// Get the user ID from the session
$user_id = $_SESSION['user_id'];

if (isset($user_id)) {
    // Calculate the logout time
    date_default_timezone_set('Asia/Kuala_Lumpur'); // Set the time zone to Malaysia
    $logout_time = date('Y-m-d H:i:s'); // Current time in Malaysia

    // Retrieve the login record for the user
    $sql = "SELECT login_time FROM user_log WHERE user_id = '$user_id' ORDER BY login_time DESC LIMIT 1";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $login_time = $row['login_time'];

        // Calculate the duration
        $datetime1 = new DateTime($login_time);
        $datetime2 = new DateTime($logout_time);
        $interval = $datetime1->diff($datetime2);
        $duration = $interval->format('%H:%I:%S');

        // Update the logout_time and duration in the user_log table
        $update_query = "UPDATE user_log SET logout_time = '$logout_time', duration = '$duration' WHERE user_id = '$user_id' AND login_time = '$login_time'";
        $conn->query($update_query);
    }
}

// Destroy the session
session_destroy();

// Redirect to the login page
header("Location: userlogin.php");
exit();
?>
