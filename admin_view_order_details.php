<?php
session_start();
include "db_connection.php";

// Check if the user is logged in
if (!isset($_SESSION['admin_id'])) {
    // Redirect to the login page or display an error message
    // You can customize this part based on your requirements
     header("Location: adminlogin.php");
    exit();
}

// Retrieve the order ID from the query string
if (!isset($_GET['order_id'])) {
    // Redirect or display an error message if the order ID is not provided
    // You can customize this part based on your requirements
    header("Location: admin_view_orders.php");
    exit();
}
$order_id = $_GET['order_id'];

// Retrieve order details from the database
$order_query = "SELECT * FROM orders WHERE order_id = $order_id";
$order_result = mysqli_query($conn, $order_query);
$order = mysqli_fetch_assoc($order_result);

// Retrieve order items from the database
$order_items_query = "SELECT * FROM orderitems WHERE order_id = $order_id";
$order_items_result = mysqli_query($conn, $order_items_query);

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Order Details</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style> body {
            background-color: black;
            color: white;
            
        }
        thead {
            color: black;
            
        }
        tbody {
            color: black;
        }
        table {
            background-color: white;
            color: black;
        }</style>
</head>
<body>
    <!-- Navigation bar -->
    <!-- ... Navigation bar code ... -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="admin_view_orders.php">View Orders</a>
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
                <a class="nav-link" href="admin_manage_users.php">Manage Users</a>
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

    <!-- Order Details page -->
    <div class="container">
        <h3>Order Details</h3>
        <h4>Order ID: <?php echo $order['order_id']; ?></h4>
        <p><strong>Customer ID:</strong> <?php echo $order['user_id']; ?></p>
        <p><strong>Order Date:</strong> <?php echo $order['order_date']; ?></p>
        <table class="table">
            <thead>
                <tr>
                    <th>Product ID</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($order_item = mysqli_fetch_assoc($order_items_result)) { ?>
                    <tr>
                        <td><?php echo $order_item['product_id']; ?></td>
                        <td><?php echo $order_item['quantity']; ?></td>
                        <td><?php echo $order_item['unit_price']; ?></td>
                        <td><?php echo $order['total_amount']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <a href="admin_view_orders.php" class="btn btn-primary">Back to Orders</a>
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
