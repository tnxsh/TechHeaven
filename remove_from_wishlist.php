<?php
session_start();
include "db_connection.php";

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect the user to the login page
    header("Location: login.php");
    exit();
}

// Check if the product ID is provided
if (!isset($_GET['product_id'])) {
    // Redirect the user back to the wishlist page
    header("Location: wishlist.php");
    exit();
}

// Retrieve the user ID from the session
$user_id = $_SESSION['user_id'];

// Retrieve the product ID from the URL parameter
$product_id = $_GET['product_id'];

// Check if the product exists in the user's wishlist
$check_query = "SELECT * FROM wishlist WHERE user_id = $user_id AND product_id = $product_id";
$check_result = mysqli_query($conn, $check_query);

if (mysqli_num_rows($check_result) == 0) {
    // Redirect the user back to the wishlist page
    header("Location: wishlist.php");
    exit();
}

// Remove the product from the user's wishlist
$remove_query = "DELETE FROM wishlist WHERE user_id = $user_id AND product_id = $product_id";
mysqli_query($conn, $remove_query);

// Redirect the user back to the wishlist page with a success message
$_SESSION['success_message'] = "Product removed from wishlist successfully!";
header("Location: wishlist.php");
exit();

mysqli_close($conn);
?>
