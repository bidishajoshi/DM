<?php
session_start();
include 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

/* ---------- PAGINATION SETUP ---------- */
$recordsPerPage = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

$offset = ($page - 1) * $recordsPerPage;

/* Count total bookings for this user */
$countQuery = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM bookings WHERE user_id = '$user_id'"
);
$totalRow = mysqli_fetch_assoc($countQuery);
$totalRecords = $totalRow['total'];
$totalPages = ceil($totalRecords / $recordsPerPage);

/* Fetch bookings with LIMIT */
$query = "
    SELECT b.booking_id, b.booking_date, b.status,
           t.name AS temple_name, s.service_name, s.price
    FROM bookings b
    JOIN services s ON b.service_id = s.service_id
    JOIN temples t ON s.temple_id = t.temple_id
    WHERE b.user_id = '$user_id'
    ORDER BY b.booking_date DESC
    LIMIT $offset, $recordsPerPage
";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Bookings</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: "Segoe UI", sans-serif;
            background: linear-gradient(135deg, #dfd2e7, #e6dbec);
            color: #333;
            margin: 0;
            padding: 0;
        }

        .navbar {
            background-color: #6a1b9a;
            color: #fff;
            padding: 15px 20px;
            text-align: center;
            font-size: 22px;
            font-weight: bold;
        }

        .container {
            max-width: 1000px;
            margin: 30px auto;
            padding: 20px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #4a148c;
            margin-bottom: 20px;
        }

        /* TABLE STYLING */
    table {
            width: 90%;
            margin: 30px auto;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }
        th {
            background: #6a1b9a;
            color: white;
        }

        tr:hover {
            background-color: #f3e5f5;
        }

        /* STATUS BADGE */
        td:last-child {
            font-weight: bold;
        }

        /* ACTION BUTTONS */
        .actions {
            text-align: center;
            margin-top: 20px;
        }

        .actions .btn {
            display: inline-block;
            padding:10px 10px;
            margin: 2px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            color: #fff;
            transition: 0.3s ease;
            font-size: 16px;
        }

        .actions .btn:hover {
            transform: translateY(-2px);
        }

        .btn-home {
            background-color: #6a1b9a;
        }

        .btn-home:hover {
            background-color: #4a148c;
        }

        .btn-logout {
            background-color: #c62828;
        }

        .btn-logout:hover {
            background-color: #8e0000;
        }

        /* PAGINATION */
        .pagination {
            text-align: center;
            margin-top: 20px;
        }

        .pagination a {
            display: inline-block;
            padding: 8px 14px;
            margin: 3px;
            border: 1px solid #d1c4e9;
            border-radius: 6px;
            text-decoration: none;
            color: #6a1b9a;
            transition: all 0.2s ease;
        }

        .pagination a.active {
            background-color: #6a1b9a;
            color: #fff;
            border-color: #6a1b9a;
        }

        .pagination a:hover {
            background-color: #ede7f6;
        }

        /* RESPONSIVE TABLE */
        @media (max-width: 768px) {
            table, th, td {
                font-size: 14px;
            }
            .actions .btn {
                padding: 8px 16px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>

<div class="navbar">🛕 My Bookings</div>

<div class="container">

<?php if (mysqli_num_rows($result) > 0) { ?>
    <table border=1>
        <tr>
            <th>Booking ID</th>
            <th>Temple Name</th>
            <th>Service</th>
            <th>Price (Rs.)</th>
            <th>Booking Date</th>
            <th>Status</th>
        </tr>

        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?php echo $row['booking_id']; ?></td>
            <td><?php echo htmlspecialchars($row['temple_name']); ?></td>
            <td><?php echo htmlspecialchars($row['service_name']); ?></td>
            <td><?php echo $row['price']; ?></td>
            <td><?php echo $row['booking_date']; ?></td>
            <td>
                <?php 
                $status = $row['status'];
                if($status == 'Pending') echo "<span style='color: #ff9800;'>$status</span>";
                elseif($status == 'Approved') echo "<span style='color: #4caf50;'>$status</span>";
                elseif($status == 'Cancel') echo "<span style='color: #f44336;'>$status</span>";
                else echo $status;
                ?>
            </td>
        </tr>
        <?php } ?>
    </table>

   

<?php } else { ?>
    <p style="text-align:center; color:#777;">You have no bookings yet.</p>
<?php } ?>

<!-- ACTION BUTTONS -->
<div class="actions">
    <a href="index.php" class="btn btn-home">🏠 Back to Home</a>
    <a href="logout.php" class="btn btn-logout">🔓 Logout</a>
</div>

</div>
 <!-- PAGINATION BELOW TABLE -->
    <?php if ($totalPages > 1) { ?>
    <div class="pagination">
        <?php if ($page > 1) { ?>
            <a href="?page=<?php echo $page - 1; ?>">« Previous</a>
        <?php } ?>

        <?php for ($i = 1; $i <= $totalPages; $i++) { ?>
            <a href="?page=<?php echo $i; ?>" class="<?php echo ($i == $page) ? 'active' : ''; ?>">
                <?php echo $i; ?>
            </a>
        <?php } ?>

        <?php if ($page < $totalPages) { ?>
            <a href="?page=<?php echo $page + 1; ?>">Next »</a>
        <?php } ?>
    </div>
    <?php } ?>
</body>
</html>
