<?php
session_start();
include '../config.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

/* Count pending bookings */
$bookingRow = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total FROM bookings WHERE status='Pending'"
    )
);
$bookingCount = $bookingRow['total'];

/* Count donations */
$donationRow = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total FROM donations"
    )
);
$donationCount = $donationRow['total'];

/* Total notifications */
$totalNotifications = $bookingCount + $donationCount;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<div class="form-box">

    <!-- HEADER -->
    <div class="dashboard-header">
        <h2>Admin Dashboard</h2>

        <!-- SINGLE notification bell -->
       <a href="view_notifications.php" class="bell">
    🔔
    <span class="bell-badge"><?= $totalNotifications ?></span>
</a>

    </div>

    <a href="add_temples.php" class="btn">Add Temple</a><br><br>
    <a href="view_temples.php" class="btn">View and Delete Temples</a><br><br>
    <a href="add_services.php" class="btn">Add Services</a><br><br>
    <a href="admin_logout.php" class="btn">Logout</a>

</div>

</body>
</html>
