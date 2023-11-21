<?php
session_start();
include "db_connection.php";

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: adminlogin.php");
    exit();
}

if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];

    // Retrieve order details from the database
    $order_query = "SELECT * FROM orders WHERE order_id = $order_id";
    $order_result = mysqli_query($conn, $order_query);
    $order = mysqli_fetch_assoc($order_result);

    if (!$order) {
        // Handle error when order is not found
        $error_message = "Order not found.";
    } else {
        // Retrieve order items with product details
        $order_items_query = "SELECT oi.*, p.product_name FROM orderitems oi
                             JOIN products p ON oi.product_id = p.product_id
                             WHERE oi.order_id = $order_id";
        $order_items_result = mysqli_query($conn, $order_items_query);
        $order_items = mysqli_fetch_all($order_items_result, MYSQLI_ASSOC);
    }
} else {
    // Redirect to admin_user_orders.php if order_id is not provided
    header("Location: admin_user_orders.php");
    exit();
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Order Details</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Styles for the navigation bar and order details page */
        /* ... CSS styles ... */
        
        body {
            background-color: black;
            color: white;
        }
       
        table {
            background-color: white;
            color: black;
        }
    </style>
</head>
<body>
    <!-- Navigation bar -->
    <!-- ... Navigation bar code ... -->
    
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="admin_manage_users.php">Manage Users</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="admin_dashboard.php">Admin Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="admin_manage_products.php">Manage Products</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="admin_warrantyclaims.php">User Warranty Claims</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="admin_logout.php">Logout</a>
            </li>
        </ul>
    </div>
</nav>

    <!-- Order details page -->
    <div class="container">
        <h3>Order Details - Order ID: <?php echo $order_id; ?></h3>
        <?php if (isset($error_message)) { ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php } else { ?>
            <table class="table">
                <tr>
                    <th>Order ID:</th>
                    <td><?php echo $order['order_id']; ?></td>
                </tr>
                <tr>
                    <th>Order Date:</th>
                    <td><?php echo $order['order_date']; ?></td>
                </tr>
                <tr>
                    <th>Total Amount:</th>
                    <td>RM<?php echo $order['total_amount']; ?></td>
                </tr>
                <tr>
                    <th>Status:</th>
                    <td><?php echo $order['status']; ?></td>
                </tr>
            </table>

            <h4>Order Items</h4>
            <?php if (empty($order_items)) { ?>
                <p>No items in this order.</p>
            <?php } else { ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Product ID</th>
                            <th>Product Name</th>
                            <th>Quantity</th>
                            <th>Unit Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($order_items as $order_item) { ?>
                            <tr>
                                <td><?php echo $order_item['product_id']; ?></td>
                                <td><?php echo $order_item['product_name']; ?></td>
                                <td><?php echo $order_item['quantity']; ?></td>
                                <td>RM<?php echo $order_item['unit_price']; ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php } ?>
        <?php } ?>
    </div>
    
     <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // JavaScript to check for inactivity and redirect to logout after 3 minutes
        (function () {
            var inactivityTimeout = 1 * 60 * 1000; // 3 minutes (in milliseconds)

            function logoutAfterInactivity() {
                window.location.href = 'admin_logout.php'; // Assuming logout.php handles the logout process
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
