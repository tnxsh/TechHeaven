<!DOCTYPE html>
<html>
<head>
    <title>Add to Wishlist</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* CSS styles */
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
        <h3>Add to Wishlist</h3>

        <?php
        session_start();
        include "db_connection.php";

        // Check if the user is logged in
        if (!isset($_SESSION['user_id'])) {
            echo '<p>You need to be logged in to add items to your wishlist. <a href="userlogin.php">Login</a></p>';
        } else {
            // Retrieve the user ID from the session
            $user_id = $_SESSION['user_id'];

            // Check if the product ID is provided
            if (!isset($_GET['product_id'])) {
                echo '<p>Invalid request.</p>';
            } else {
                $product_id = $_GET['product_id'];

                // Check if the product exists
                $product_query = "SELECT * FROM products WHERE product_id = $product_id";
                $product_result = mysqli_query($conn, $product_query);

                if (mysqli_num_rows($product_result) == 0) {
                    echo '<p>Product not found.</p>';
                } else {
                    // Check if the product is already in the user's wishlist
                    $wishlist_query = "SELECT * FROM wishlist WHERE user_id = $user_id AND product_id = $product_id";
                    $wishlist_result = mysqli_query($conn, $wishlist_query);

                    if (mysqli_num_rows($wishlist_result) > 0) {
                        echo '<p>This product is already in your wishlist.</p>';
                    } else {
                        // Add the product to the user's wishlist
                        $wishlist_insert_query = "INSERT INTO wishlist (user_id, product_id) VALUES ($user_id, $product_id)";

                        if (mysqli_query($conn, $wishlist_insert_query)) {
                            echo '<p>Product added to wishlist successfully!</p>';
                        } else {
                            echo '<p>Failed to add product to wishlist.</p>';
                        }
                    }
                }
            }
        }

        mysqli_close($conn);
        ?>

        <a href="product_catalog.php" class="btn btn-primary">Continue Shopping</a>
        <a href="wishlist.php" class="btn btn-primary">View Wishlist</a>
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

