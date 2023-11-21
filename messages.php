<?php
session_start();
include "db_connection.php";

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: userlogin.php");
    exit();
}

// Retrieve user messages from orders table
$user_id = $_SESSION['user_id'];
$orderQuery = "SELECT * FROM orders WHERE user_id = $user_id";
$orderResult = mysqli_query($conn, $orderQuery);

// Retrieve user messages from WarrantyClaims table
$warrantyQuery = "SELECT * FROM warrantyclaims WHERE user_id = $user_id";
$warrantyResult = mysqli_query($conn, $warrantyQuery);

// Check if a message is marked for deletion
if (isset($_GET['delete'])) {
    $messageId = $_GET['delete'];

    // Delete the message from the corresponding table (orders or WarrantyClaims)
    $tableName = $messageId[0] === 'O' ? 'orders' : 'warrantyclaims';
    $deleteQuery = "DELETE FROM $tableName WHERE " . ($tableName === 'orders' ? 'order_id' : 'claim_id') . " = '$messageId'";
    mysqli_query($conn, $deleteQuery);
}

// Close the database connection
mysqli_close($conn);
?>


<!DOCTYPE html>
<html>
<head>
    <title>Messages</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Styles for the navigation bar */
        .navbar {
            background-color: tomato;
            padding: 5px;
        }

        .navbar-brand {
            font-weight: bold;
        }

        /* Styles for the messages page */
        body {
            background-color: black;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            margin-top: 100px;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h3 {
            text-align: center;
            margin-bottom: 20px;
        }

        .message {
            margin-bottom: 20px;
        }

        .message p {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .delete-btn {
            color: red;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <!-- Navigation bar -->
      <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="messages.php">Notification</a>
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
                <a class="nav-link" href="vieworderhistory.php">View Order History</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="reviewsratings.php">Reviews and Ratings</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="warrantyclaim.php">Warranty Claim</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="logout.php">Logout</a>
            </li>
        </ul>
    </div>
</nav>

    <!-- Messages -->
    <div class="container">
        <h3>Messages</h3>

        <!-- Display order messages -->
                <?php while ($orderRow = mysqli_fetch_assoc($orderResult)) : ?>
            <div class="message">
                <p>Order Details:</p>
                <p>Your order with ID <?php echo $orderRow['order_id']; ?> has been <?php echo $orderRow['status']; ?>.</p>
            

                    <span class="delete-btn" onclick="deleteMessage('<?php echo $orderRow['order_id']; ?>')">Delete</span>
                </p>
            </div>
        <?php endwhile; ?>

        <!-- Display warranty claim messages -->
        <?php while ($warrantyRow = mysqli_fetch_assoc($warrantyResult)) : ?>
            <div class="message">
                <p>Warranty Claim Request:</p>
                <p>Your request for <?php echo $warrantyRow['claim_type']; ?> of product with ID <?php echo $warrantyRow['product_id']; ?> has been <?php echo $warrantyRow['claim_status']; ?>.</p>
                    <span class="delete-btn" onclick="deleteMessage('<?php echo $warrantyRow['claim_id']; ?>')">Delete</span>
                </p>
            </div>
        <?php endwhile; ?>
    </div>

    <script>
        function deleteMessage(messageId) {
            if (confirm("Are you sure you want to delete this message?")) {
                window.location.href = 'messages.php?delete=' + messageId;
            }
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
