<?php
session_start();
include "db_connection.php";

// Check if the product ID is provided
if (!isset($_GET['product_id'])) {
    // Redirect the user back to the cart page
    header("Location: cart.php");
    exit();
}

// Retrieve the product ID from the URL parameter
$product_id = $_GET['product_id'];

// Check if the product exists in the cart
if (isset($_SESSION['cart'][$product_id])) {
    // Remove the product from the cart
    unset($_SESSION['cart'][$product_id]);

    // Redirect the user back to the cart page with a success message
    $_SESSION['success_message'] = "Product removed from cart successfully!";
    header("Location: cart.php");
    exit();
} else {
    // Redirect the user back to the cart page
    header("Location: cart.php");
    exit();
}

mysqli_close($conn);
?>

