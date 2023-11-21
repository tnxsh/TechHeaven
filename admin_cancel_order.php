<?php
session_start();
include "db_connection.php";

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: adminlogin.php");
    exit();
}

// Retrieve the order ID from the query string
if (!isset($_GET['order_id'])) {
    header("Location: admin_view_orders.php");
    exit();
}
$order_id = $_GET['order_id'];

// Update the order status to "Cancelled" in the database
$update_query = "UPDATE orders SET status = 'Cancelled' WHERE order_id = $order_id";
mysqli_query($conn, $update_query);

// Redirect back to the admin_view_orders.php page
header("Location: admin_view_orders.php");
exit();

// Close the database connection
mysqli_close($conn);
?>


<!DOCTYPE html>
<html>
<head>
    <title>Cancel Order</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Styles for the navigation bar and cancel order page */
        /* ... CSS styles ... */
    </style>
</head>
<body>
    <!-- Navigation bar -->
    <!-- ... Navigation bar code ... -->

    <!-- Cancel order page -->
    <div class="container">
        <h3>Cancel Order</h3>
        <?php if (isset($error_message)) { ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php } ?>
        <p>Are you sure you want to cancel the order with ID: <?php echo $order['order_id']; ?>?</p>
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF'] . '?order_id=' . $order_id; ?>">
            <button type="submit" class="btn btn-danger">Cancel Order</button>
        </form>
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
