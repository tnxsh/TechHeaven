<?php
session_start();
include "db_connection.php";

// Retrieve brands from the database
$brand_query = "SELECT * FROM brands";
$brand_result = mysqli_query($conn, $brand_query);
$brands = mysqli_fetch_all($brand_result, MYSQLI_ASSOC);

// Retrieve categories from the database
$category_query = "SELECT * FROM categories";
$category_result = mysqli_query($conn, $category_query);
$categories = mysqli_fetch_all($category_result, MYSQLI_ASSOC);

// Filter and search functionality
$brand_filter = isset($_GET['brand']) ? $_GET['brand'] : '';
$category_filter = isset($_GET['category']) ? $_GET['category'] : '';
$keyword_search = isset($_GET['search']) ? $_GET['search'] : '';

// Prepare the query for retrieving products
$query = "SELECT * FROM products";

// Apply filters and search conditions
if (!empty($brand_filter)) {
    $query .= " WHERE brand_id = $brand_filter";
}

if (!empty($category_filter)) {
    if (strpos($query, 'WHERE') !== false) {
        $query .= " AND category_id = $category_filter";
    } else {
        $query .= " WHERE category_id = $category_filter";
    }
}

if (!empty($keyword_search)) {
    if (strpos($query, 'WHERE') !== false) {
        $query .= " AND (product_name LIKE '%$keyword_search%' OR description LIKE '%$keyword_search%')";
    } else {
        $query .= " WHERE (product_name LIKE '%$keyword_search%' OR description LIKE '%$keyword_search%')";
    }
}

if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        $search_query = "INSERT INTO user_activity (user_id, searches, activity_time, activity_date) VALUES ($user_id, '$keyword_search', NOW(), CURDATE())";
        mysqli_query($conn, $search_query);
    }


// Retrieve products from the database
$result = mysqli_query($conn, $query);
$products = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Add a product to the cart
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_to_cart'])) {
        $product_id = $_POST['product_id'];
        $quantity = 1; // Default quantity is 1

        // Retrieve product details from the database
        $product_query = "SELECT * FROM products WHERE product_id = $product_id";
        $product_result = mysqli_query($conn, $product_query);
        $product = mysqli_fetch_assoc($product_result);

        // Check if the product exists and quantity is valid
        if ($product && $quantity > 0) {
            // Create an array representing the item to add to the cart
            $item = array(
                'product_id' => $product['product_id'],
                'product_name' => $product['product_name'],
                'quantity' => $quantity,
                'unit_price' => $product['price']
            );

            // Add the item to the cart session
            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = array();
            }
            $_SESSION['cart'][$product_id] = $item;

            // Show success message
            $success_message = "Product added to cart successfully!";
        }
    }
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Product Catalog</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Styles for the product catalog page */
        /* ... CSS styles ... */
        body{
            background-color: black;
        }
        h3{
            color: white;
        }
    </style>
</head>
<body>
    
    
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="product_catalog.php">Product Catalog</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="home.php">Home</a>
            </li>
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

    <!-- Product catalog page -->
    <div class="container">
        <h3>Product Catalog</h3>

        <!-- Filter options -->
        <form class="form-inline mb-4">
            <div class="form-group mr-2">
                <select class="form-control" name="brand">
                    <option value="">All Brands</option>
                    <?php foreach ($brands as $brand) { ?>
                        <option value="<?php echo $brand['brand_id']; ?>" <?php if ($brand_filter == $brand['brand_id']) echo 'selected'; ?>><?php echo $brand['brand_name']; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group mr-2">
                <select class="form-control" name="category">
                    <option value="">All Categories</option>
                    <?php foreach ($categories as $category) { ?>
                        <option value="<?php echo $category['category_id']; ?>" <?php if ($category_filter == $category['category_id']) echo 'selected'; ?>><?php echo $category['category_name']; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group mr-2">
                <input type="text" class="form-control" name="search" placeholder="Search" value="<?php echo $keyword_search; ?>">
            </div>
            <button type="submit" class="btn btn-primary">Apply</button>
        </form>

        <!-- Product listing -->
        <div class="row">
    <?php foreach ($products as $product) { ?>
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <img class="card-img-top" src="<?php echo $product['image_url']; ?>" alt="Product Image">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title"><?php echo $product['product_name']; ?></h5>
                    <p class="card-text"><?php echo $product['description']; ?></p>
                    <p class="card-text">Price: RM<?php echo $product['price']; ?></p>
                    <div class="mt-auto">
                        <a href="add_to_wishlist.php?product_id=<?php echo $product['product_id']; ?>" class="btn btn-success">Add To Wishlist</a>
                        <a href="product_details.php?product_id=<?php echo $product['product_id']; ?>" class="btn btn-success">Product Details</a>
                        <form method="post" action="cart.php">
                            <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                            <!-- ... -->
                            <button type="submit" class="btn btn-success" name="add_to_cart">Add to Cart</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
</div>
        </div>

        <!-- Pagination -->
        <!-- ... Pagination code ... -->
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
