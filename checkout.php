<?php
session_start();
include "db_connection.php";

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page
    header("Location: userlogin.php");
    exit();
}

// Retrieve user details from the database
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE user_id = $user_id";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

// Check if the cart is empty
if (empty($_SESSION['cart'])) {
    // Redirect to the cart page
    header("Location: cart.php");
    exit();
}

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
    $order_query = "INSERT INTO Orders (user_id, total_amount, payment_status) VALUES ('$user_id', '$total_amount', 'Pending')";
mysqli_query($conn, $order_query);
$order_id = mysqli_insert_id($conn);


    // Insert the order items into the database
    foreach ($_SESSION['cart'] as $product_id => $item) {
        $quantity = $item['quantity'];
        $unit_price = $item['unit_price'];

      $order_item_query = "INSERT INTO OrderItems (order_id, product_id, quantity, unit_price) VALUES ('$order_id', '$product_id', '$quantity', '$unit_price')";
mysqli_query($conn, $order_item_query);

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
    <title>Checkout</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Styles for the checkout page */
        /* ... CSS styles ... */
        
        body{
            background-color: black;
            color: white;
        }
        thead{
            background-color: white;
        }
        tbody{
            color: white;
        }
    </style>
</head>
<body>
    <!-- Navigation bar -->
    <!-- ... Navigation bar code ... -->

    <!-- Checkout page -->
    <div class="container">
        <h3>Checkout</h3>
        <h4>Delivery Address</h4>
        <form method="post">
            <div class="form-group">
                <label for="address">Address:</label>
                <input type="text" class="form-control" id="address" name="address" value="<?php echo $user['address']; ?>">
            </div>
            <button type="submit" class="btn btn-primary" name="update_address">Update Address</button>
        </form>

        <h4>Payment Options</h4>
        <form method="post">
            <div class="form-group">
                <label for="payment_method">Payment Method:</label>
                <select class="form-control" id="payment_method" name="payment_method">
                    <option value="online_banking">Online Banking</option>
                    <option value="cash_on_delivery">Cash on Delivery</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary" name="place_order">Place Order</button>
        </form>

        <?php if (isset($success_message)) { ?>
            <div class="alert alert-success mt-4" role="alert">
                <?php echo $success_message; ?>
                <a href="product_catalog.php" class="btn btn-primary">Continue Shopping</a>
            </div>
        <?php } ?>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // JavaScript to check for inactivity and redirect to logout after 3 minutes
        (function () {
            var inactivityTimeout = 1 * 60 * 1000; // 3 minutes (in milliseconds)

            function logoutAfterInactivity() {
                window.location.href = 'logout.php'; // Assuming logout.php handles the logout process
            }

            $(document).on('mousemove keydown', function () {
                resetInactivityTimer();
            });

            function resetInactivityTimer() {
                clearTimeout(window.logoutTimer);
                window.logoutTimer = setTimeout(logoutAfterInactivity, inactivityTimeout);
            }

            resetInactivityTimer();
        })();
    </script>

</body>
</html>
