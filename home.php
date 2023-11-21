<?php
// Include the database connection file
require_once "db_connection.php";

// Fetch 10 newly added products
$newProductsQuery = "SELECT * FROM products ORDER BY product_id DESC LIMIT 10";
$newProductsResult = mysqli_query($conn, $newProductsQuery);
$newProducts = mysqli_fetch_all($newProductsResult, MYSQLI_ASSOC);

// Fetch 4 best selling products
$bestSellingQuery = "SELECT p.product_id, p.image_url, p.product_name, b.brand_name, c.category_name, SUM(oi.quantity) AS total_sales
                    FROM orderitems oi
                    INNER JOIN products p ON oi.product_id = p.product_id
                    INNER JOIN brands b ON p.brand_id = b.brand_id
                    INNER JOIN categories c ON p.category_id = c.category_id
                    GROUP BY p.product_id
                    ORDER BY total_sales DESC
                    LIMIT 4";
$bestSellingResult = mysqli_query($conn, $bestSellingQuery);
$bestSellingProducts = mysqli_fetch_all($bestSellingResult, MYSQLI_ASSOC);


?>

<!DOCTYPE html>
<html>
<head>
    <title>TECH HEAVEN - Home</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
      body {
    background-color: black;
    font-family: Arial, sans-serif;
}

.container {
    max-width: auto;
    margin: 0 auto;
    padding-bottom: 50px;
}

.product-list {
    margin-top: 30px;
    padding-bottom: 50px;
    max-height: auto;
}

.card {
    border: none;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    transition: box-shadow 0.3s ease;
}

.card:hover {
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
}

.card-img-top {
    height: 400px;
    object-fit: cover;
    background-color: black;
}

.card-body {
    padding: 15px;
    height: 300px;
    background-color: black;
    color: white;
}

.card-title {
    font-size: 18px;
    font-weight: bold;
    margin-bottom: 10px;
}

.card-text {
    font-size: 14px;
    color: #888;
    margin-bottom: 5px;
}

.btn {
    padding: 5px 10px;
    font-size: 14px;
    text-transform: uppercase;
    margin-top: 10px;
}

.btn-success {
    background-color: #28a745;
    border-color: #28a745;
}

.btn-success:hover {
    background-color: #218838;
    border-color: #1e7e34;
}

.footer {
    background-color: #f8f9fa;
    padding: 20px 0;
    text-align: center;
    color: #333;
}

.footer a {
    color: #333;
}

.footer a:hover {
    color: #28a745;
}

h2{
    color: white;
}

h1{
    color: teal;
    background-color: black;
    text-align: center;
    margin-bottom: 20px;
    margin-top: 20px;
    font-style: bold;
    font-size: 400%;
}




        
        
        
        
        
        
    </style>
</head>

<body>
    <!-- Navigation bar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="home.php">Home</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="product_catalog.php">Product Catalog</a>
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

    <div class="container">
        <h1>TECH HEAVEN</h1>

        

        <!-- Newly Added Products -->
        <h2>Newly Added Products</h2>
        <div class="row product-list">
            <?php foreach ($newProducts as $product) { ?>
                <div class="col-md-3">
                    <div class="card">
                        <img src="<?php echo $product['image_url']; ?>" class="card-img-top" alt="<?php echo $product['product_name']; ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $product['product_name']; ?></h5>
<!--                            <p class="card-text"><?php echo $brand['brand_name']; ?></p>
                            <p class="card-text"><?php echo $category['category_name']; ?></p>--> <div class="mt-auto">
                                                        <a href="product_details.php?product_id=<?php echo $product['product_id']; ?>" class="btn btn-success">Product Details</a>

                        <a href="add_to_wishlist.php?product_id=<?php echo $product['product_id']; ?>" class="btn btn-success">Add To Wishlist</a>
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
        

        <!-- Best Selling Products -->
        <h2>Best Selling Products</h2>
        <div class="row product-list">
            <?php foreach ($bestSellingProducts as $product) { ?>
                <div class="col-md-3">
                    <div class="card">
                         <img src="<?php echo $product['image_url']; ?>" class="card-img-top" alt="<?php echo $product['product_name']; ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $product['product_name']; ?></h5>
                            <p class="card-text"><?php echo $product['brand_name']; ?></p>
                            <p class="card-text"><?php echo $product['category_name']; ?></p>
                            <p class="card-text">Total Sales: <?php echo $product['total_sales']; ?></p>
                            <div class="mt-auto">
                                                        <a href="product_details.php?product_id=<?php echo $product['product_id']; ?>" class="btn btn-success">Product Details</a>

                        <a href="add_to_wishlist.php?product_id=<?php echo $product['product_id']; ?>" class="btn btn-success">Add To Wishlist</a>
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

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h3>Location</h3>
                    <p1>123 Tech Street, City, Country</p1>
                </div>
                <div class="col-md-4">
                    <h3>Opening Hours</h3>
                    <p1>Monday - Friday: 9am - 6pm</p1>
                    
                    <p1>Saturday: 9am - 3pm</p1>
                </div>
                <div class="col-md-4">
                    <h3>Social Accounts</h3>
                    <ul class="list-unstyled">
                        <li><a href="#">Facebook</a></li>
                        <li><a href="#">Twitter</a></li>
                        <li><a href="#">Instagram</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

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
