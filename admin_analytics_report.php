<?php
// Include the database connection file
require_once "db_connection.php";

// Get the selected brand and category filters
$selectedBrand = isset($_GET['brand']) ? $_GET['brand'] : '';
$selectedCategory = isset($_GET['category']) ? $_GET['category'] : '';

// Get the brand names
$brandQuery = "SELECT DISTINCT b.brand_id, b.brand_name
                FROM orderitems oi
                INNER JOIN products p ON oi.product_id = p.product_id
                INNER JOIN brands b ON p.brand_id = b.brand_id";
$brandResult = mysqli_query($conn, $brandQuery);

$brandOptions = "";
while ($row = mysqli_fetch_assoc($brandResult)) {
    $selected = ($selectedBrand == $row['brand_id']) ? 'selected' : '';
    $brandOptions .= '<option value="' . $row['brand_id'] . '" ' . $selected . '>' . $row['brand_name'] . '</option>';
}

// Get the category names
$categoryQuery = "SELECT DISTINCT c.category_id, c.category_name
                    FROM orderitems oi
                    INNER JOIN products p ON oi.product_id = p.product_id
                    INNER JOIN categories c ON p.category_id = c.category_id";
$categoryResult = mysqli_query($conn, $categoryQuery);

$categoryOptions = "";
while ($row = mysqli_fetch_assoc($categoryResult)) {
    $selected = ($selectedCategory == $row['category_id']) ? 'selected' : '';
    $categoryOptions .= '<option value="' . $row['category_id'] . '" ' . $selected . '>' . $row['category_name'] . '</option>';
}

// Generate the sales report query with filters
$salesQuery = "SELECT p.product_name, b.brand_name, c.category_name, SUM(oi.quantity) AS total_sales
                FROM orderitems oi
                INNER JOIN products p ON oi.product_id = p.product_id
                INNER JOIN brands b ON p.brand_id = b.brand_id
                INNER JOIN categories c ON p.category_id = c.category_id";

// Apply brand and category filters
if ($selectedBrand != '') {
    $salesQuery .= " WHERE p.brand_id = '$selectedBrand'";
}

if ($selectedCategory != '') {
    if ($selectedBrand != '') {
        $salesQuery .= " AND p.category_id = '$selectedCategory'";
    } else {
        $salesQuery .= " WHERE p.category_id = '$selectedCategory'";
    }
}

// Sorting functionality
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'default';
switch ($sort) {
    case 'highest':
        $salesQuery .= " GROUP BY p.product_id ORDER BY total_sales DESC";
        break;
    case 'lowest':
        $salesQuery .= " GROUP BY p.product_id ORDER BY total_sales ASC";
        break;
    default:
        $salesQuery .= " GROUP BY p.product_id";
        break;
}

$salesResult = mysqli_query($conn, $salesQuery);

// Prepare data for chart
$productNames = array();
$totalSales = array();

while ($row = mysqli_fetch_assoc($salesResult)) {
    $productNames[] = $row['product_name'];
    $totalSales[] = $row['total_sales'];
}
?>

<!-- HTML code continues... -->

<!DOCTYPE html>
<html>
<head>
    <title>Admin Analytics and Reporting</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .container {
            margin-top: 50px;
            color: white;
        }

        h2 {
            margin-bottom: 20px;
            color: white;
        }

        select {
            width: 200px;
            margin-right: 10px;
            color: white;
        }

        table {
            margin-top: 20px;
            color: white;
        }

        th {
            cursor: pointer;
            color: white;
        }

        body {
            background-color: black;
        }

        tbody {
            color: white;
        }

        select {
            color: black;
        }

        .chart-container {
            width: 100%;
            height: 400px;
            margin-top: 50px;
        }
    </style>
</head>

<body>
    <!-- Navigation code continues... --><nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="admin_analytics_report.php">Sales Report</a>
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
                <a class="nav-link" href="admin_manage_products.php">Manage Products</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="admin_view_orders.php">View Orders</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="admin_warrantyclaims.php">User Claim Request</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="admin_logout.php">Logout</a>
            </li>
        </ul>
    </div>
</nav>

    <div class="container">
        <h2>Sales Report</h2>

        <div>
            <label for="brand">Select Brand:</label>
            <select id="brand" name="brand">
                <option value="">All Brands</option>
                <?php echo $brandOptions; ?>
            </select>

            <label for="category">Select Category:</label>
            <select id="category" name="category">
                <option value="">All Categories</option>
                <?php echo $categoryOptions; ?>
            </select>

            <a href="?sort=highest" class="btn btn-link">Sort Highest Sales</a>
            <a href="?sort=lowest" class="btn btn-link">Sort Lowest Sales</a>
        </div>

      

<table class="table table-striped">
    <thead>
        <tr>
            <th>Product Name</th>
            <th>Brand</th>
            <th>Category</th>
            <th>Total Sales</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $allSalesResult = mysqli_query($conn, $salesQuery);
        while ($row = mysqli_fetch_assoc($allSalesResult)) :
        ?>
            <tr>
                <td><?php echo $row['product_name']; ?></td>
                <td><?php echo $row['brand_name']; ?></td>
                <td><?php echo $row['category_name']; ?></td>
                <td><?php echo $row['total_sales']; ?></td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>


        <div class="chart-container">
            <canvas id="salesChart"></canvas>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.5.1"></script>
    <script>
        
        
        
        $(document).ready(function () {
            // Handle brand and category filter change
            $('#brand, #category').change(function () {
                var brandId = $('#brand').val();
                var categoryId = $('#category').val();
                window.location.href = 'admin_analytics_report.php?brand=' + brandId + '&category=' + categoryId;
            });

            // Chart initialization
            var ctx = document.getElementById('salesChart').getContext('2d');
            var salesChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode($productNames); ?>,
                    datasets: [{
                        label: 'Total Sales',
                        data: <?php echo json_encode($totalSales); ?>,
                        backgroundColor: 'rgba(75, 192, 192, 0.6)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            precision: 0
                        }
                    }
                }
            });
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
