<?php
session_start();
include '../config.php';  // adjust path if needed

// Check if admin is logged in
if(!isset($_SESSION['admin_id'])){
    header("Location: admin_login.php");
    exit();
}

// Handle status update via GET
if(isset($_GET['action']) && isset($_GET['booking_id'])){
    $booking_id = $_GET['booking_id'];
    $action = $_GET['action']; // Confirmed, Pending, Cancelled

    $valid_actions = ['Confirmed', 'Pending', 'Cancelled'];
    if(in_array($action, $valid_actions)){
        mysqli_query($conn, "UPDATE bookings SET status='$action' WHERE booking_id='$booking_id'");
    }

    header("Location: view_bookings.php");
    exit();
}

// Fetch all bookings
$query = "
    SELECT b.booking_id, b.booking_date, b.status,
           u.name AS user_name,
           t.name AS temple_name,
           s.service_name, s.price
    FROM bookings b
    JOIN users u ON b.user_id = u.user_id
    JOIN services s ON b.service_id = s.service_id
    JOIN temples t ON s.temple_id = t.temple_id
    ORDER BY b.booking_date DESC
";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin - View Bookings</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        body { font-family: Arial, sans-serif; }
        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 20px 0;
           
        }
        .top-bar .links a {
             text-decoration: none;
            padding: 8px 15px;
            background-color: #007BFF;
            color: white;
            border-radius: 5px;
            margin-left: 10px;
        }
        .top-bar .links a:hover { text-decoration: underline; }

        table { border-collapse: collapse; width: 70%; float: left; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        th { background-color: #f2f2f2; }
        .btn { padding: 5px 10px; border-radius: 4px; text-decoration: none; color: white; margin-right: 5px; }
        .confirm { background-color: #28a745; }  /* green */
        .pending { background-color: #ffc107; }  /* yellow */
        .cancel { background-color: #dc3545; }   /* red */

        .container { display: flex; }
        
    </style>
</head>
<body>

<div class="navbar">
    <h2>Admin Panel - User Bookings</h2>
</div>

<div class="top-bar">
    <h3>Bookings</h3>
    <div class="links">
        <a href="admin_dashboard.php">Dashboard</a>
        <a href="admin_logout.php">Logout</a>
    </div>
</div>

<div class="container">

    <!-- Bookings Table on left -->
    <div class="table-container">
    <?php if(mysqli_num_rows($result) > 0){ ?>
        <table>
            <tr>
                <th>Booking ID</th>
                <th>User</th>
                <th>Temple</th>
                <th>Service</th>
                <th>Price (Rs.)</th>
                <th>Booking Date</th>
                <th>Status</th>
                <th>Action</th>
            </tr>

            <?php while($row = mysqli_fetch_assoc($result)){ ?>
            <tr>
                <td><?php echo $row['booking_id']; ?></td>
                <td><?php echo htmlspecialchars($row['user_name']); ?></td>
                <td><?php echo htmlspecialchars($row['temple_name']); ?></td>
                <td><?php echo htmlspecialchars($row['service_name']); ?></td>
                <td><?php echo $row['price']; ?></td>
                <td><?php echo $row['booking_date']; ?></td>
                <td><?php echo $row['status']; ?></td>
                <td>
                    <!-- Action buttons -->
                    <a href="view_bookings.php?booking_id=<?php echo $row['booking_id']; ?>&action=Confirmed" class="btn confirm">Confirm</a>
                    <a href="view_bookings.php?booking_id=<?php echo $row['booking_id']; ?>&action=Pending" class="btn pending">Pending</a>
                    <a href="view_bookings.php?booking_id=<?php echo $row['booking_id']; ?>&action=Cancelled" class="btn cancel">Cancel</a>
                </td>
            </tr>
            <?php } ?>
        </table>
    <?php } else { ?>
        <p>No bookings found.</p>
    <?php } ?>
    </div>

</div>
</body>
</html>
