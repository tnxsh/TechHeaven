<?php
session_start();
include "db_connection.php";

// Add a product to the cart
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_to_cart'])) {
        $product_id = $_POST['product_id'];
        $quantity = 1;

        // Retrieve product details from the database
        $product_query = "SELECT * FROM products WHERE product_id = $product_id";
        $product_result = mysqli_query($conn, $product_query);
        $product = mysqli_fetch_assoc($product_result);

        // Check if the product exists and quantity is valid
        if ($product && $quantity > 0) {
            // Create an array representing the item to add to the cart
            $item = array(
                'product_id' => $product['product_id'],
                'product_name' => $product['product_name'],
                'quantity' => $quantity,
                'unit_price' => $product['price']
            );

            // Add the item to the cart session
            $_SESSION['cart'][$product_id] = $item;

            // Show success message
            $success_message = "Product added to cart successfully!";
        }
    }

    // Update the quantity of a product in the cart
    if (isset($_POST['update_quantity'])) {
        $product_id = $_POST['product_id'];
        $quantity = $_POST['quantity'];

        // Check if the product exists in the cart
        if (isset($_SESSION['cart'][$product_id])) {
            // Update the quantity if it's a valid positive integer
            if ($quantity > 0 && filter_var($quantity, FILTER_VALIDATE_INT) !== false) {
                $_SESSION['cart'][$product_id]['quantity'] = $quantity;
            } else {
                // Remove the product from the cart if the quantity is invalid or zero
                unset($_SESSION['cart'][$product_id]);
            }
        }
    }

    // Remove a product from the cart
    if (isset($_POST['remove_from_cart'])) {
        $product_id = $_POST['product_id'];

        // Check if the product exists in the cart
        if (isset($_SESSION['cart'][$product_id])) {
            // Remove the product from the cart
            unset($_SESSION['cart'][$product_id]);

            // Show success message
            $success_message = "Product removed from cart successfully!";
        }
    }
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cart</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        
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
   

    
    <div class="container">
        <h3>Cart</h3>
        <?php if (empty($_SESSION['cart'])) { ?>
            <p>Your cart is empty.</p>
            <a href="product_catalog.php" class="btn btn-primary">Continue Shopping</a>
        <?php } else { ?>
            <?php if (isset($success_message)) { ?>
                <div class="alert alert-success"><?php echo $success_message; ?></div>
            <?php } ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($_SESSION['cart'] as $product_id => $item) { ?>
                        <tr>
                            <td><?php echo $item['product_name']; ?></td>
                            <td>
                                <form method="post" action="">
                                    <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                                    <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1" max="100">
                                    <button type="submit" class="btn btn-primary" name="update_quantity">Update</button>
                                </form>
                            </td>
                            <td>RM<?php echo $item['unit_price']; ?></td>
                            <td>RM<?php echo $item['quantity'] * $item['unit_price']; ?></td>
                            <td>
                                <form method="post" action="">
                                    <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                                    <button type="submit" class="btn btn-danger" name="remove_from_cart">Remove</button>
                                     <a href="checkout.php" class="btn btn-primary">Proceed to Checkout</a>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <a href="checkout.php" class="btn btn-primary">Proceed to Checkout</a>
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
