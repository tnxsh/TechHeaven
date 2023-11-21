<?php
session_start();
include "db_connection.php";

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: adminlogin.php");
    exit();
}

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
    }
} else {
    // Redirect to manage products page if product_id is not provided
    header("Location: admin_manage_products.php");
    exit();
}


// Check if form is submitted for updating product details
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_name = $_POST['product_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $brand_id = $_POST['brand'];
    $category_id = $_POST['category'];

    // Update product details in the database
    $query = "UPDATE products SET product_name = '$product_name', description = '$description', price = '$price', brand_id = '$brand_id', category_id = '$category_id' WHERE product_id = $product_id";
    $result = mysqli_query($conn, $query);

    if ($result) {
        // Product details updated successfully
        header("Location: admin_manage_products.php");
        exit();
    } else {
        // Handle error when failed to update product details
        $error_message = "Failed to update product details.";
    }
}

// Retrieve all brands
$brand_query = "SELECT * FROM brands";
$brand_result = mysqli_query($conn, $brand_query);
$brands = mysqli_fetch_all($brand_result, MYSQLI_ASSOC);

// Retrieve all categories
$category_query = "SELECT * FROM categories";
$category_result = mysqli_query($conn, $category_query);
$categories = mysqli_fetch_all($category_result, MYSQLI_ASSOC);

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Product</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Styles for the navigation bar and edit product page */
        /* ... CSS styles ... */
    </style>
</head>
<body>
    <!-- Navigation bar -->
    <!-- ... Navigation bar code ... -->

    <!-- Edit product page -->
    <div class="container">
        <h3>Edit Product</h3>
        <?php if (isset($error_message)) { ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php } ?>
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF'] . '?product_id=' . $product_id; ?>">
            <div class="form-group">
                <label>Product Name:</label>
                <input type="text" name="product_name" class="form-control" value="<?php echo $product['product_name']; ?>">
            </div>
            <div class="form-group">
                <label>Description:</label>
                <textarea name="description" class="form-control"><?php echo $product['description']; ?></textarea>
            </div>
            <div class="form-group">
                <label>Price (RM):</label>
                <input type="number" name="price" class="form-control" value="<?php echo $product['price']; ?>">
            </div>
            <div class="form-group">
                <label>Brand:</label>
                <select name="brand" class="form-control">
                    <?php foreach ($brands as $brand) { ?>
                        <option value="<?php echo $brand['brand_id']; ?>" <?php if ($brand['brand_id'] == $product['brand_id']) echo "selected"; ?>><?php echo $brand['brand_name']; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group">
                <label>Category:</label>
                <select name="category" class="form-control">
                    <?php foreach ($categories as $category) { ?>
                        <option value="<?php echo $category['category_id']; ?>" <?php if ($category['category_id'] == $product['category_id']) echo "selected"; ?>><?php echo $category['category_name']; ?></option>
                    <?php } ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update Product</button>
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
