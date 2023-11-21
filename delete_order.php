<?php
session_start();
include "db_connection.php";

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: userlogin.php");
    exit();
}

// Retrieve the order ID from the query string
if (!isset($_GET['order_id'])) {
    // Redirect or display an error message if the order ID is not provided
    // You can customize this part based on your requirements
    header("Location: vieworderhistory.php");
    exit();
}
$order_id = $_GET['order_id'];

// Delete the order from the database
$query = "DELETE FROM orderitems WHERE order_id = $order_id";
$result = mysqli_query($conn, $query);

// Close the database connection
mysqli_close($conn);

// Handle the response based on the query result
if ($result) {
    // Success message or further actions
    echo "Order deleted successfully";
} else {
    // Error message or error handling
    echo "Failed to delete order";
}
?>
