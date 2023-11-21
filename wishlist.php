<!DOCTYPE html>
<html>
<head>
    <title>Wishlist</title>
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
        <h3>Wishlist</h3>

        <?php
        session_start();
        include "db_connection.php";

        // Check if the user is logged in
        if (!isset($_SESSION['user_id'])) {
            echo '<p>You need to be logged in to view your wishlist. <a href="userlogin.php">Login</a></p>';
        } else {
            // Retrieve the user ID from the session
            $user_id = $_SESSION['user_id'];

            // Retrieve the products in the user's wishlist
            $wishlist_query = "SELECT p.product_id, p.product_name, p.price FROM products p
                INNER JOIN wishlist w ON p.product_id = w.product_id
                WHERE w.user_id = $user_id";
            $wishlist_result = mysqli_query($conn, $wishlist_query);

            if (mysqli_num_rows($wishlist_result) == 0) {
                echo '<p>Your wishlist is empty.</p>';
            } else {
                echo '<table class="table">';
                echo '<thead><tr><th>Product Name</th><th>Price</th><th>Action</th></tr></thead>';
                echo '<tbody>';

                while ($row = mysqli_fetch_assoc($wishlist_result)) {
                    $product_id = $row['product_id'];
                    $product_name = $row['product_name'];
                    $price = $row['price'];

                    echo '<tr>';
                    echo '<td>' . $product_name . '</td>';
                    echo '<td>RM' . $price . '</td>';
                    echo '<td><a href="remove_from_wishlist.php?product_id=' . $product_id . '" class="btn btn-danger">Remove</a></td>';
                    echo '</tr>';
                }

                echo '</tbody>';
                echo '</table>';
            }
        }

        mysqli_close($conn);
        ?>

        <a href="product_catalog.php" class="btn btn-primary">Continue Shopping</a>
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

