<?php
session_start();
include "db_connection.php";

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: adminlogin.php");
    exit();
}

if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];

    // Retrieve product details from the database
    $query = "SELECT * FROM products WHERE product_id = $product_id";
    $result = mysqli_query($conn, $query);
    $product = mysqli_fetch_assoc($result);

    if (!$product) {
        // Handle error when product is not found
        $error_message = "Product not found.";
    } else {
        // Delete the product from the database
        $delete_query = "DELETE FROM products WHERE product_id = $product_id";
        $delete_result = mysqli_query($conn, $delete_query);

        if ($delete_result) {
            // Product deleted successfully
            header("Location: admin_manage_products.php");
            exit();
        } else {
            // Handle error when failed to delete product
            $error_message = "Failed to delete product.";
        }
    }
} else {
    // Redirect to manage products page if product_id is not provided
    header("Location: admin_manage_products.php");
    exit();
}

// Close the database connection
mysqli_close($conn);
?>
