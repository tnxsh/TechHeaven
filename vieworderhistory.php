<?php
session_start();
include "db_connection.php";

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: userlogin.php");
    exit();
}

// Retrieve user's order history from the database
$user_id = $_SESSION['user_id'];
$query = "SELECT orders.order_id, products.product_name, orderitems.quantity, orders.total_amount, orders.status FROM orders
          INNER JOIN orderitems ON orders.order_id = orderitems.order_id
          INNER JOIN products ON orderitems.product_id = products.product_id
          WHERE orders.user_id = $user_id";

$result = mysqli_query($conn, $query);

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Order History</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Styles for the navigation bar */
        .navbar {
            background-color: #f8f9fa;
            padding: 10px;
        }

        .navbar-brand {
            font-weight: bold;
        }

        /* Styles for the order history page */
        body {
            background-color: black;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            margin-top: 100px;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .container h3 {
            text-align: center;
            margin-bottom: 20px;
        }

        .order {
            margin-bottom: 20px;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }

        .order h4 {
            margin-bottom: 10px;
        }

        .order p {
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <!-- Navigation bar -->
   <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="vieworderhistory.php">View Order History</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="userdashboard.php">User Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="editdetails.php">Edit Details</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="resetpassword.php">Reset Password</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="reviewsratings.php">Reviews and Ratings</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="warrantyclaim.php">Warranty Claim</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="messages.php">Notifications</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="logout.php">Logout</a>
            </li>
        </ul>
    </div>
</nav>
    <!-- Order history -->
    <div class="container">
        <h3>Order History</h3>
        <?php
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $orderId = $row['order_id'];
                $cancelUrl = "update_order_status.php?order_id=$orderId&status=Cancelled";
                $receiveUrl = "update_order_status.php?order_id=$orderId&status=Order Received";
                $deleteUrl = "delete_order.php?order_id=$orderId";

                echo '<div class="order">';
                echo '<h4>Order ID: ' . $row['order_id'] . '</h4>';
                echo '<p>Product: ' . $row['product_name'] . '</p>';
                echo '<p>Quantity: ' . $row['quantity'] . '</p>';
                echo '<p>Total Amount: ' . $row['total_amount'] . '</p>';
                echo '<p>Status: ' . $row['status'] . '</p>';
                echo '<button onclick="cancelOrder(' . $orderId . ')" class="btn btn-danger">Cancel</button>';
                echo '<button onclick="receiveOrder(' . $orderId . ')" class="btn btn-success">Order Received</button>';
                echo '<button onclick="deleteOrder(' . $orderId . ')" class="btn btn-secondary">Delete</button>';
                echo '</div>';
            }
        } else {
            echo '<p>No orders found.</p>';
        }
        ?>
    </div>

   <script>
    function cancelOrder(orderId) {
        // Perform an AJAX request to update the order status to "Cancelled"
        // You can use your preferred method to send the AJAX request (e.g., jQuery.ajax, fetch API)
        // Upon success, you can update the status text dynamically or refresh the page

        // Example using fetch API:
        fetch('update_order_status.php?order_id=' + orderId + '&status=Cancelled')
            .then(response => {
                // Handle the response or update the status text dynamically
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }

    function receiveOrder(orderId) {
        // Perform an AJAX request to update the order status to "Order Received"
        // You can use your preferred method to send the AJAX request (e.g., jQuery.ajax, fetch API)
        // Upon success, you can update the status text dynamically or refresh the page

        // Example using fetch API:
        fetch('update_order_status.php?order_id=' + orderId + '&status=Order Received')
            .then(response => {
                // Handle the response or update the status text dynamically
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }

    function deleteOrder(orderId) {
        // Perform an AJAX request to delete the order
        // You can use your preferred method to send the AJAX request (e.g., jQuery.ajax, fetch API)
        // Upon success, you can remove the order element from the DOM or refresh the page

        // Example using fetch API:
        fetch('delete_order.php?order_id=' + orderId)
            .then(response => {
                // Handle the response or remove the order element from the DOM
                if (response.ok) {
                    // If the response is successful, remove the order element from the DOM
                    const orderElement = document.getElementById('order-' + orderId);
                    if (orderElement) {
                        orderElement.remove();
                    }
                } else {
                    console.error('Error:', response.statusText);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }
</script>

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
