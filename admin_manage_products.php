<?php
session_start();
include "db_connection.php";

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: adminlogin.php");
    exit();
}

// Retrieve all products from the database
$query = "SELECT products.*, brands.brand_name, categories.category_name
          FROM products
          INNER JOIN brands ON products.brand_id = brands.brand_id
          INNER JOIN categories ON products.category_id = categories.category_id";
$result = mysqli_query($conn, $query);

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Products - Admin Dashboard</title>
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
            max-width: 900px;
            margin: 0 auto;
            margin-top: 100px;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .table {
            margin-top: 20px;
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
                <a class="nav-link" href="admin_analytics_report.php">Sales Report</a>
            </li>
           
            <li class="nav-item">
                <a class="nav-link" href="admin_logout.php">Logout</a>
            </li>
        </ul>
    </div>
</nav>
   

    <!-- Admin dashboard -->
    <div class="container">
        <h3>Manage Products</h3>
        <a href="add_products.php" class="btn btn-primary">Add Product</a>
        <table class="table">
            <thead>
                <tr>
                    <th>Product ID</th>
                    <th>Product Name</th>
                    <th>Description</th>
                    <th>Price (RM)</th>
                    <th>Brand</th>
                    <th>Category</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
    <tr>
        <td><?php echo $row['product_id']; ?></td>
        <td><?php echo $row['product_name']; ?></td>
        <td><?php echo $row['description']; ?></td>
        <td><?php echo 'RM ' . $row['price']; ?></td>
        <td><?php echo $row['brand_name']; ?></td>
        <td><?php echo $row['category_name']; ?></td>
        <td>
            <img src="<?php echo $row['image_url']; ?>" alt="Product Image" style="max-width: 100px; height: auto;">
        </td>
        <td>
            <a href="admin_edit_product.php?product_id=<?php echo $row['product_id']; ?>" class="btn btn-sm btn-primary">Edit</a>
            <a href="admin_delete_product.php?product_id=<?php echo $row['product_id']; ?>" class="btn btn-sm btn-danger">Delete</a>
        </td>
    </tr>
<?php endwhile; ?>

            </tbody>
        </table>
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
