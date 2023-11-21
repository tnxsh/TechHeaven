<?php
session_start();
include "db_connection.php";

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: userlogin.php");
    exit();
}

// Retrieve the order ID and status from the query string
if (!isset($_GET['order_id']) || !isset($_GET['status'])) {
    // Redirect or display an error message if the order ID or status is not provided
    // You can customize this part based on your requirements
    header("Location: vieworderhistory.php");
    exit();
}
$order_id = $_GET['order_id'];
$status = $_GET['status'];

// Update the order status in the database
$query = "UPDATE orders SET status = '$status' WHERE order_id = $order_id";
$result = mysqli_query($conn, $query);

// Close the database connection
mysqli_close($conn);

// Handle the response based on the query result
if ($result) {
    // Success message or further actions
    echo "Order status updated successfully";
} else {
    // Error message or error handling
    echo "Failed to update order status";
}
?>
