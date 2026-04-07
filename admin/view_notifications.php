<?php
session_start();
include '../config.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

/* UPDATE BOOKING STATUS */
if (isset($_GET['id']) && isset($_GET['status'])) {
    $booking_id = (int)$_GET['id'];
    $status = $_GET['status'];

    if (in_array($status, ['Confirmed', 'Pending', 'Cancel'])) {
        mysqli_query(
            $conn,
            "UPDATE bookings SET status='$status' WHERE booking_id=$booking_id"
        );
    }

    header("Location: view_notifications.php");
    exit();
}

/* PAGINATION */
$limit = 5;

/* BOOKINGS */
$bpage = isset($_GET['bpage']) ? (int)$_GET['bpage'] : 1;
if ($bpage < 1) $bpage = 1;
$boffset = ($bpage - 1) * $limit;

$bcount = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM bookings")
)['total'];

$btotalPages = ceil($bcount / $limit);

$bookings = mysqli_query($conn,"
    SELECT b.*, u.name AS user_name, t.name AS temple_name, s.service_name, s.price
    FROM bookings b
    JOIN users u ON b.user_id = u.user_id
    JOIN services s ON b.service_id = s.service_id
    JOIN temples t ON s.temple_id = t.temple_id
    ORDER BY b.booking_date DESC
    LIMIT $limit OFFSET $boffset
");

/* DONATIONS */
$dpage = isset($_GET['dpage']) ? (int)$_GET['dpage'] : 1;
if ($dpage < 1) $dpage = 1;
$doffset = ($dpage - 1) * $limit;

$dcount = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM donations")
)['total'];

$dtotalPages = ceil($dcount / $limit);

$donations = mysqli_query($conn,"
    SELECT d.*, u.name AS user_name, t.name AS temple_name
    FROM donations d
    JOIN users u ON d.user_id = u.user_id
    JOIN temples t ON d.temple_id = t.temple_id
    ORDER BY d.donated_at DESC
    LIMIT $limit OFFSET $doffset
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Notifications</title>
<style>
body { font-family: Arial, sans-serif; }

.navbar {
    background: #6a1b9a;
    color: white;
    padding: 10px 20px;
    display: flex;
    justify-content: space-between;
}

.navbar a {
    background: #4CAF50;
    color: white;
    padding: 6px 12px;
    text-decoration: none;
    border-radius: 4px;
    margin-left: 8px;
}

/* 🔥 SIDE BY SIDE LAYOUT */
.notification-container {
    display: flex;
    gap: 20px;
    padding: 20px;
}

.notification-box {
    width: 50%;
    border: 1px solid #ccc;
    padding: 15px;
    border-radius: 5px;
    background: #fff;
}

table {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
}

th, td {
    border: 1px solid #999;
    padding: 6px;
}

th { background: #f2f2f2; }

.btn-action {
    padding: 4px 8px;
    color: white;
    text-decoration: none;
    border-radius: 4px;
    font-size: 11px;
    display: inline-block;
    margin-bottom: 3px;
}

.confirm { background: #28a745; }
.pending { background: #ffc107; color: #000; }
.cancel  { background: #dc3545; }

.pagination {
    text-align: center;
    margin-top: 10px;
}

.pagination a {
    padding: 5px 9px;
    background: #eee;
    margin: 2px;
    text-decoration: none;
    border-radius: 4px;
}

.pagination a.active {
    background: #6a1b9a;
    color: white;
}
</style>
</head>

<body>

<div class="navbar">
    <h2>🔔 Notifications</h2>
    <div>
        <a href="admin_dashboard.php">Dashboard</a>
        <a href="admin_logout.php">Logout</a>
    </div>
</div>

<div class="notification-container">

<!-- LEFT: BOOKINGS -->
<div class="notification-box">
<h3>📅 Bookings</h3>

<table>
<tr>
<th>User</th>
<th>Temple</th>
<th>Service</th>
<th>Price</th>
<th>Date</th>
<th>Status</th>
<th>Action</th>
</tr>

<?php while($b = mysqli_fetch_assoc($bookings)) { ?>
<tr>
<td><?= $b['user_name'] ?></td>
<td><?= $b['temple_name'] ?></td>
<td><?= $b['service_name'] ?></td>
<td><?= $b['price'] ?></td>
<td><?= $b['booking_date'] ?></td>
<td><?= $b['status'] ?></td>
<td>
<a class="btn-action confirm" href="?id=<?= $b['booking_id'] ?>&status=Confirmed">Confirm</a>
<a class="btn-action pending" href="?id=<?= $b['booking_id'] ?>&status=Pending">Pending</a>
<a class="btn-action cancel"  href="?id=<?= $b['booking_id'] ?>&status=Cancel">Cancel</a>
</td>
</tr>
<?php } ?>
</table>

<div class="pagination">
<?php for($i=1;$i<=$btotalPages;$i++): ?>
<a class="<?= ($i==$bpage)?'active':'' ?>" href="?bpage=<?= $i ?>&dpage=<?= $dpage ?>">
<?= $i ?>
</a>
<?php endfor; ?>
</div>
</div>

<!-- RIGHT: DONATIONS -->
<div class="notification-box">
<h3>💰 Donations</h3>

<table>
<tr>
<th>User</th>
<th>Temple</th>
<th>Donor</th>
<th>Amount</th>
<th>Payment</th>
<th>Date</th>
</tr>

<?php while($d = mysqli_fetch_assoc($donations)) { ?>
<tr>
<td><?= $d['user_name'] ?></td>
<td><?= $d['temple_name'] ?></td>
<td><?= $d['donor_name'] ?></td>
<td><?= $d['amount'] ?></td>
<td><?= $d['payment_method'] ?></td>
<td><?= $d['donated_at'] ?></td>
</tr>
<?php } ?>
</table>

<div class="pagination">
<?php for($i=1;$i<=$dtotalPages;$i++): ?>
<a class="<?= ($i==$dpage)?'active':'' ?>" href="?dpage=<?= $i ?>&bpage=<?= $bpage ?>">
<?= $i ?>
</a>
<?php endfor; ?>
</div>
</div>

</div>
</body>
</html>
