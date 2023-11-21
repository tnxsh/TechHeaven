<?php
session_start();
include "db_connection.php";

// Check if product_id is provided in the URL
if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];

    // Retrieve product details from the database
    $query = "SELECT * FROM products WHERE product_id = $product_id";
    $result = mysqli_query($conn, $query);
    $product = mysqli_fetch_assoc($result);

    if (!$product) {
        // Handle error when product is not found
        $error_message = "Product not found.";
    } else {
        // Retrieve brand and category details
        $brand_id = $product['brand_id'];
        $category_id = $product['category_id'];

        $brand_query = "SELECT * FROM brands WHERE brand_id = $brand_id";
        $brand_result = mysqli_query($conn, $brand_query);
        $brand = mysqli_fetch_assoc($brand_result);

        $category_query = "SELECT * FROM categories WHERE category_id = $category_id";
        $category_result = mysqli_query($conn, $category_query);
        $category = mysqli_fetch_assoc($category_result);
        
        if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
            $product_name = $product['product_name'];
            $seen_products_query = "INSERT INTO user_activity (user_id, seen_products, activity_time, activity_date) VALUES ($user_id, '$product_name', NOW(), CURDATE())";
            mysqli_query($conn, $seen_products_query);
        }

        // Retrieve reviews for the product
        $review_query = "SELECT * FROM reviews WHERE product_id = $product_id";
        $review_result = mysqli_query($conn, $review_query);
        $reviews = mysqli_fetch_all($review_result, MYSQLI_ASSOC);
    }
} else {
    // Redirect to the product catalog page if product_id is not provided
    header("Location: product_catalog.php");
    exit();
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Product Details</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Styles for the product details page */
        /* ... CSS styles ... */
        body{
            background-color: black;
        }
        h3{
            color: white;
        }
        h4{
            color: white;
        }
        h5{
            color: white;
        }
        p{
            color: wheat;
        }
        p1{
            color: black;
            size: 4px;
            
        }
        
        
    </style>
</head>
<body>
    <!-- Navigation bar -->
    <!-- ... Navigation bar code ... -->
    
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="product_catalog.php">Product Catalog</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="cart.php">Cart</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="wishlist.php">Wish List</a>
            </li>
           
           
            <li class="nav-item">
                <a class="nav-link" href="userdashboard.php">Profile</a>
            </li>
        </ul>
    </div>
</nav>

    <!-- Product details page -->
    <div class="container">
        <h3>Product Details</h3>
        <?php if (isset($error_message)) { ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php } else { ?>
            <div class="row">
                <div class="col-md-6">
                    <img class="img-fluid" src="<?php echo $product['image_url']; ?>" alt="Product Image">
                </div>
                <div class="col-md-6">
                    <h5><?php echo $product['product_name']; ?></h5>
                    <p><?php echo $product['description']; ?></p>
                    <p>Price: RM<?php echo $product['price']; ?></p>
                    <p>Brand: <?php echo $brand['brand_name']; ?></p>
                    <p>Category: <?php echo $category['category_name']; ?></p>
                     <a href="add_to_wishlist.php?product_id=<?php echo $product['product_id']; ?>" class="btn btn-success">Add To Wishlist</a>
                  <form method="post" action="cart.php">
    <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
    <!-- ... -->
    <button type="submit" class="btn btn-success" name="add_to_cart">Add to Cart</button>

                </div>
            </div>

            <?php if ($reviews) { ?>
                <h4>Product Reviews</h4>
                <?php foreach ($reviews as $review) { ?>
                    <div class="card">
                        <div class="card-body">
                            
                            <p1 class="card-text">Rating: <?php echo $review['rating']; ?></p>
                                                        <p1 class="card-text">Review: <?php echo $review['comment']; ?></p>

                            <p1 class="card-text">Posted on: <?php echo $review['review_date']; ?></p>
                        </div>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <p>No reviews available for this product.</p>
            <?php } ?>
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
