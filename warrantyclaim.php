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
$query = "SELECT orders.order_id, orders.status, products.product_id, products.product_name
          FROM orders
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
    $claim_type = $_POST['claim_type'];
    $claim_reason = $_POST['claim_reason'];

    // Upload the prove image
    $image_path = uploadImage($_FILES['prove_image']);

    if ($image_path) {
        // Get the order_id for the selected product
        $order_id_query = "SELECT order_id FROM orders WHERE user_id = $user_id AND status = 'Order Received' LIMIT 1";
        $order_id_result = mysqli_query($conn, $order_id_query);
        $order_id_row = mysqli_fetch_assoc($order_id_result);
        $order_id = $order_id_row['order_id'];

        // Insert the warranty claim into the database
        $insertQuery = "INSERT INTO warrantyclaims (user_id, product_id, order_id, claim_type, claim_reason, claim_status, prove_image)
                        VALUES ($user_id, $product_id, $order_id, '$claim_type', '$claim_reason', 'Pending', '$image_path')";
        mysqli_query($conn, $insertQuery);

        // Redirect to the user dashboard or display a success message
        header("Location: userdashboard.php");
        exit();
    } else {
        // Handle error when image upload fails
        $error_message = "Failed to upload the prove image.";
    }
}

// Function to handle image upload
function uploadImage($file)
{
    $target_dir = "warranty_claim_pic/";
    $target_file = $target_dir . basename($file["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    $check = getimagesize($file["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        $uploadOk = 0;
    }

    // Check if file already exists
    if (file_exists($target_file)) {
        $uploadOk = 0;
    }

    // Check file size
    if ($file["size"] > 500000) {
        $uploadOk = 0;
    }

    // Allow only certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        $uploadOk = 0;
    }

    // Move the uploaded file to the target directory
    if ($uploadOk) {
        if (move_uploaded_file($file["tmp_name"], $target_file)) {
            return $target_file;
        }
    }

    return false;
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Warranty Claim</title>
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

        /* Styles for the warranty claim page */
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

        .error-message {
            color: red;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <!-- Navigation bar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="warrantyclaim.php">Warranty Claim</a>
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
                <a class="nav-link" href="messages.php">Notifications</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="logout.php">Logout</a>
            </li>
        </ul>
    </div>
</nav>

    <!-- Warranty Claim -->
    <div class="container">
        <h3>Warranty Claim</h3>

        <?php if (isset($error_message)) : ?>
            <p class="error-message"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="product_id">Select Product:</label>
                <select class="form-control" id="product_id" name="product_id" required>
                    <option value="" disabled selected>Select a product</option>
                    <?php echo $productOptions; ?>
                </select>
            </div>

          <div class="form-group">
                <label for="claim_type">Claim Type:</label>
                <select class="form-control" id="claim_type" name="claim_type" required>
                    <option value="">Select a claim type</option>
                    <option value="Refund">Refund</option>
                    <option value="Replacement">Replacement</option>
                </select>
            </div>
            <div class="form-group">
                <label for="claim_reason">Claim Reason:</label>
                <input type="text" class="form-control" id="claim_reason" name="claim_reason" required>
            </div>

            <div class="form-group">
                <label for="prove_image">Prove Image:</label>
                <input type="file" class="form-control" id="prove_image" name="prove_image" accept="image/*" required>
            </div>

            <button type="submit" class="btn btn-primary">Submit Warranty Claim</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
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
