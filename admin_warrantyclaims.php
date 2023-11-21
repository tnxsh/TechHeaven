<?php
session_start();
include "db_connection.php";

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: adminlogin.php");
    exit();
}

// Retrieve warranty claims
$query = "SELECT wc.claim_id, wc.user_id, u.username, wc.product_id, p.product_name, wc.claim_type, wc.claim_reason, wc.claim_status, wc.prove_image
          FROM WarrantyClaims wc
          INNER JOIN Users u ON wc.user_id = u.user_id
          INNER JOIN Products p ON wc.product_id = p.product_id";

$result = mysqli_query($conn, $query);

// Fetch the warranty claims
$claims = [];
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $claims[] = $row;
    }
}

// Handle claim cancellation or approval
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $claim_id = $_POST['claim_id'];
    $action = $_POST['action'];

    if ($action == 'cancel') {
        // Update the claim status to "Cancelled"
        $updateQuery = "UPDATE WarrantyClaims SET claim_status = 'Cancelled' WHERE claim_id = $claim_id";
        mysqli_query($conn, $updateQuery);
    } elseif ($action == 'approve') {
        // Update the claim status to "Approved"
        $updateQuery = "UPDATE WarrantyClaims SET claim_status = 'Approved' WHERE claim_id = $claim_id";
        mysqli_query($conn, $updateQuery);
    }
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Warranty Claims</title>
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

        /* Styles for the admin warranty claims page */
        body {
            background-color: black;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            margin-top: 50px;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h3 {
            text-align: center;
            margin-bottom: 20px;
        }

        .claim {
            margin-bottom: 20px;
        }

        .claim img {
            max-width: 200px;
            max-height: 200px;
        }

        .claim-status {
            font-weight: bold;
        }

        .btn-cancel {
            margin-right: 10px;
        }

        .btn-approve {
            margin-right: 10px;
        }
    </style>
</head>

<body>
    <!-- Navigation bar -->
   <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="admin_warrantyclaims.php">User Warranty Claims</a>
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
                <a class="nav-link" href="admin_analytics_report.php">Sales Report</a>
            </li>
           
            <li class="nav-item">
                <a class="nav-link" href="admin_logout.php">Logout</a>
            </li>
        </ul>
    </div>
</nav>


    <!-- Admin Warranty Claims -->
    <div class="container">
        <h3>Admin Warranty Claims</h3>

       <?php foreach ($claims as $claim) : ?>
            <div class="claim">
                <p>User ID: <?php echo $claim['user_id']; ?></p>
                <p>Username: <?php echo $claim['username']; ?></p>
                <p>Product ID: <?php echo $claim['product_id']; ?></p>
                <p>Product Name: <?php echo $claim['product_name']; ?></p>
                <p>Claim Type: <?php echo $claim['claim_type']; ?></p>
                <p>Claim Reason: <?php echo $claim['claim_reason']; ?></p>
                <p>Claim Status: <span class="claim-status"><?php echo $claim['claim_status']; ?></span></p>
                <img src="<?php echo $claim['prove_image']; ?>" alt="Prove Image">
                <?php if ($claim['claim_status'] == 'Pending') : ?>
                    <form method="POST">
                        <input type="hidden" name="claim_id" value="<?php echo $claim['claim_id']; ?>">
                        <button class="btn btn-danger btn-cancel" type="submit" name="action" value="cancel">Cancel</button>
                        <button class="btn btn-success btn-approve" type="submit" name="action" value="approve">Approve</button>
                    </form>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>

        <?php if (empty($claims)) : ?>
            <p>No warranty claims found.</p>
        <?php endif; ?>
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
