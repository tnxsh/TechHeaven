<?php
session_start();
include "db_connection.php";

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: userlogin.php");
    exit();
}

// Check if the user has received any orders with status "Order Received"
$user_id = $_SESSION['user_id'];
$query = "SELECT orders.order_id, orders.status, products.product_id, products.product_name FROM orders
          INNER JOIN orderitems ON orders.order_id = orderitems.order_id
          INNER JOIN products ON orderitems.product_id = products.product_id
          WHERE orders.user_id = $user_id AND orders.status = 'Order Received'";

$result = mysqli_query($conn, $query);

// Fetch the product details for the selected order
$productOptions = '';
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $productOptions .= '<option value="' . $row['product_id'] . '">' . $row['product_name'] . '</option>';
    }
} else {
    // Handle case when no orders with status "Order Received" are found
    $error_message = "No orders with status 'Order Received' found.";
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST['product_id'];
    $rating = $_POST['rating'];
    $review = $_POST['review'];

    // Insert the review into the database
    $insertQuery = "INSERT INTO reviews (user_id, product_id, rating, comment) 
                    VALUES ($user_id, $product_id, $rating, '$review')";
    mysqli_query($conn, $insertQuery);

    // Redirect to the same page to avoid form resubmission
    header("Location: reviewsratings.php");
    exit();
}

// Retrieve reviews and ratings for each product
$query = "SELECT reviews.rating, reviews.comment, products.product_name
          FROM reviews
          INNER JOIN products ON reviews.product_id = products.product_id
          WHERE reviews.user_id = $user_id";

$result = mysqli_query($conn, $query);
$reviews = [];

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $reviews[] = $row;
    }
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reviews and Ratings</title>
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

        /* Styles for the reviews and ratings page */
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

        .form-group label {
            font-weight: bold;
        }

        .btn-primary {
            width: 100%;
        }

        .review {
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <!-- Navigation bar -->
   <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="reviewsratings.php">Reviews and Ratings</a>
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

    <!-- Reviews and Ratings -->
    <div class="container">
        <h3>Reviews and Ratings</h3>

        <?php if (isset($error_message)) : ?>
            <p><?php echo $error_message; ?></p>
        <?php else : ?>
            <form method="POST">
                <div class="form-group">
                    <label for="product_id">Select Product:</label>
                    <select class="form-control" id="product_id" name="product_id" required>
                        <option value="">Select a product</option>
                        <?php echo $productOptions; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="rating">Rating:</label>
                    <select class="form-control" id="rating" name="rating" required>
                        <option value="">Select a rating</option>
                        <option value="1">1 Star</option>
                        <option value="2">2 Stars</option>
                        <option value="3">3 Stars</option>
                        <option value="4">4 Stars</option>
                        <option value="5">5 Stars</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="review">Review:</label>
                    <textarea class="form-control" id="review" name="review" rows="5" required></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Submit Review</button>
            </form>

            <?php if (!empty($reviews)) : ?>
                <h4>Your Reviews</h4>
                <?php foreach ($reviews as $review) : ?>
                    <div class="review">
                        <h5><?php echo $review['product_name']; ?></h5>
                        <p>Rating: <?php echo $review['rating']; ?></p>
                        <p>Review: <?php echo $review['comment']; ?></p>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <p>No reviews found.</p>
            <?php endif; ?>
        <?php endif; ?>
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
