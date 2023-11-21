<?php
session_start();
include "db_connection.php";

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page
    header("Location: login.php");
    exit();
}

// Check if the order_id is provided in the URL
if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];

    // Retrieve order details from the database
    $order_query = "SELECT * FROM Orders WHERE order_id = $order_id";
    $order_result = mysqli_query($conn, $order_query);
    $order = mysqli_fetch_assoc($order_result);

    // Retrieve order items from the database
    $order_items_query = "SELECT * FROM OrderItems WHERE order_id = $order_id";
    $order_items_result = mysqli_query($conn, $order_items_query);
    $order_items = mysqli_fetch_all($order_items_result, MYSQLI_ASSOC);
} else {
    // Redirect to the cart page
    header("Location: cart.php");
    exit();
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Order Success</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Styles for the order success page */
        /* ... CSS styles ... */
    </style>
</head>
<body>
    <!-- Navigation bar -->
    <!-- ... Navigation bar code ... -->

    <!-- Order success page -->
    <div class="container">
        <h3>Order Success</h3>

        <!-- Order details -->
        <h5>Order Information</h5>
        <p>Order ID: <?php echo $order['order_id']; ?></p>
        <p>Order Date: <?php echo $order['order_date']; ?></p>
        <p>Total Amount: $<?php echo $order['total_amount']; ?></p>
        <p>Payment Status: <?php echo $order['payment_status']; ?></p>

        <!-- Order items -->
        <h5>Order Items</h5>
        <table class="table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($order_items as $order_item) { ?>
                    <tr>
                        <td><?php echo $order_item['product_name']; ?></td>
                        <td><?php echo $order_item['quantity']; ?></td>
                        <td>$<?php echo $order_item['unit_price']; ?></td>
                        <td>$<?php echo $order_item['quantity'] * $order_item['unit_price']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
