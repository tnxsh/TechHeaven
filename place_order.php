<?php
session_start();
include "db_connection.php";

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page
    header("Location: login.php");
    exit();
}

// Check if the cart is empty
if (empty($_SESSION['cart'])) {
    // Redirect to the cart page
    header("Location: cart.php");
    exit();
}

// Retrieve user details from the database
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE user_id = $user_id";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

// Handle the checkout process
if (isset($_POST['place_order'])) {
    // Generate a unique order ID
    $order_id = uniqid();

    // Retrieve the total amount from the cart
    $total_amount = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total_amount += $item['quantity'] * $item['unit_price'];
    }

    // Insert the order details into the database
    $insert_query = "INSERT INTO orders (order_id, user_id, total_amount, payment_status) VALUES ('$order_id', $user_id, $total_amount, 'Pending')";
    mysqli_query($conn, $insert_query);

    // Insert the order items into the database
    foreach ($_SESSION['cart'] as $product_id => $item) {
        $quantity = $item['quantity'];
        $unit_price = $item['unit_price'];

        $insert_item_query = "INSERT INTO order_items (order_id, product_id, quantity, unit_price) VALUES ('$order_id', $product_id, $quantity, $unit_price)";
        mysqli_query($conn, $insert_item_query);
    }

    // Clear the cart
    $_SESSION['cart'] = [];

    // Show the success message
    $success_message = "Your order has been placed successfully!";
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Place Order</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Styles for the place order page */
        /* ... CSS styles ... */
    </style>
</head>
<body>
    <!-- Navigation bar -->
    <!-- ... Navigation bar code ... -->

    <!-- Place order page -->
    <div class="container">
        <h3>Place Order</h3>
        <h4>Delivery Address</h4>
        <p><?php echo $user['address']; ?></p>

        <h4>Payment Method</h4>
        <p><?php echo $_POST['payment_method']; ?></p>

        <h4>Order Summary</h4>
        <table class="table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total = 0;
                foreach ($_SESSION['cart'] as $product_id => $item) {
                    $product_name = $item['product_name'];
                    $quantity = $item['quantity'];
                    $price = $item['unit_price'];
                    $subtotal = $quantity * $price;
                    $total += $subtotal;
                ?>
                    <tr>
                        <td><?php echo $product_name; ?></td>
                        <td><?php echo $quantity; ?></td>
                        <td><?php echo $price; ?></td>
                        <td><?php echo $subtotal; ?></td>
                    </tr>
                <?php } ?>
                <tr>
                    <td colspan="3" class="text-right"><strong>Total</strong></td>
                    <td><strong><?php echo $total; ?></strong></td>
                </tr>
            </tbody>
        </table>

        <?php if (isset($success_message)) { ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php } ?>

        <a href="product_catalog.php" class="btn btn-primary">Continue Shopping</a>
    </div>
</body>
</html>
