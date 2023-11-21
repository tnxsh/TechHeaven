<?php
session_start();
include "db_connection.php";

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: adminlogin.php");
    exit();
}

// Handle the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_name = $_POST['product_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $brand_id = $_POST['brand_id'];
    $category_id = $_POST['category_id'];
    
    // Check if the brand exists, otherwise add it to the database
    if ($brand_id == "new_brand") {
        $brand_name = $_POST['new_brand_name'];
        $query = "INSERT INTO brands (brand_name) VALUES ('$brand_name')";
        mysqli_query($conn, $query);
        $brand_id = mysqli_insert_id($conn);
    }
    
    // Check if the category exists, otherwise add it to the database
    if ($category_id == "new_category") {
        $category_name = $_POST['new_category_name'];
        $query = "INSERT INTO categories (category_name) VALUES ('$category_name')";
        mysqli_query($conn, $query);
        $category_id = mysqli_insert_id($conn);
    }

    // Upload product image
    $target_dir = "product_pic/";
    $target_file = $target_dir . basename($_FILES["product_image"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $allowedExtensions = ["jpg", "jpeg", "png", "gif"];

    if (in_array($imageFileType, $allowedExtensions)) {
        if (move_uploaded_file($_FILES["product_image"]["tmp_name"], $target_file)) {
            // File uploaded successfully, save the product details in the database
            $query = "INSERT INTO products (product_name, description, price, brand_id, category_id, image_url) VALUES ('$product_name', '$description', '$price', '$brand_id', '$category_id', '$target_file')";
            mysqli_query($conn, $query);
            header("Location: admin_manage_products.php");
            exit();
        } else {
            $error_message = "Failed to upload the product image.";
        }
    } else {
        $error_message = "Invalid file format. Only JPG, JPEG, PNG, and GIF files are allowed.";
    }
}

// Retrieve brand and category details from the database
$query = "SELECT * FROM brands";
$result_brands = mysqli_query($conn, $query);

$query = "SELECT * FROM categories";
$result_categories = mysqli_query($conn, $query);

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Product - Admin Dashboard</title>
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

        /* Styles for the admin dashboard */
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

        .form-group {
            margin-bottom: 20px;
        }

        .btn-primary {
            width: 100%;
        }
    </style>
</head>
<body>
    <!-- Navigation bar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="admin_manage_products.php">Manage Products</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="admin_dashboard.php">Admin Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="admin_manage_users.php">Manage Users</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="admin_view_orders.php">View Orders</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="admin_warrantyclaims.php">User Warranty Claims</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="admin_logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Add Product Form -->
    <div class="container">
        <h3>Add Product</h3>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Product Name:</label>
                <input type="text" name="product_name" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Description:</label>
                <textarea name="description" class="form-control" rows="5" required></textarea>
            </div>
            <div class="form-group">
                <label>Price (RM):</label>
                <input type="number" step="0.01" class="form-control" name="price" required>
            </div>
            <div class="form-group">
                <label>Brand:</label>
                <select name="brand_id" class="form-control" required>
                    <option value="">Select Brand</option>
                    <?php while ($row = mysqli_fetch_assoc($result_brands)): ?>
                        <option value="<?php echo $row['brand_id']; ?>"><?php echo $row['brand_name']; ?></option>
                    <?php endwhile; ?>
                    <option value="new_brand">Add New Brand</option>
                </select>
            </div>
            <div class="form-group" id="newBrandInput" style="display: none;">
                <label>New Brand Name:</label>
                <input type="text" name="new_brand_name" class="form-control">
            </div>
            <div class="form-group">
                <label>Category:</label>
                <select name="category_id" class="form-control" required>
                    <option value="">Select Category</option>
                    <?php while ($row = mysqli_fetch_assoc($result_categories)): ?>
                        <option value="<?php echo $row['category_id']; ?>"><?php echo $row['category_name']; ?></option>
                    <?php endwhile; ?>
                    <option value="new_category">Add New Category</option>
                </select>
            </div>
            <div class="form-group" id="newCategoryInput" style="display: none;">
                <label>New Category Name:</label>
                <input type="text" name="new_category_name" class="form-control">
            </div>
            <div class="form-group">
                <label>Product Image:</label>
                <input type="file" name="product_image" class="form-control-file" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Product</button>
            <?php if (isset($error_message)): ?>
                <div class="alert alert-danger mt-3"><?php echo $error_message; ?></div>
            <?php endif; ?>
        </form>
    </div>

    <script>
        // Show/hide new brand input based on selection
        const brandSelect = document.querySelector('select[name="brand_id"]');
        const newBrandInput = document.getElementById('newBrandInput');

        brandSelect.addEventListener('change', function() {
            if (this.value === 'new_brand') {
                newBrandInput.style.display = 'block';
            } else {
                newBrandInput.style.display = 'none';
            }
        });

        // Show/hide new category input based on selection
        const categorySelect = document.querySelector('select[name="category_id"]');
        const newCategoryInput = document.getElementById('newCategoryInput');

        categorySelect.addEventListener('change', function() {
            if (this.value === 'new_category') {
                newCategoryInput.style.display = 'block';
            } else {
                newCategoryInput.style.display = 'none';
            }
        });
    </script>
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
