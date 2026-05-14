<?php
session_start();
include '../config.php'; // adjust path according to your folder structure

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Fetch all donations with user and temple info
$query = "
    SELECT d.donor_name, d.amount, d.payment_method, d.donated_at,
           u.name AS user_name,
           t.name AS temple_name
    FROM donations d
    JOIN users u ON d.user_id = u.user_id
    JOIN temples t ON d.temple_id = t.temple_id
    ORDER BY d.donated_at DESC
";


$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin - View Donations</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h2 {
            text-align: center;
        }
        .top-bar {
            width: 90%;
            margin: 0 auto 20px auto;
            display: flex;
            justify-content: flex-end;

        }
        .top-bar a {
            text-decoration: none;
            padding: 8px 15px;
            background-color: #007BFF;
            color: white;
            border-radius: 5px;
            margin-left: 10px;
        }
        table {
            width: 90%;
            margin: 0 auto;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #333;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #c0aaaa;
        }
        tr:nth-child(even){
            background-color: #d2aeae;
        }
    </style>
</head>
<body>

<div class="top-bar">
    <div class="links">
        <a href="admin_dashboard.php">Dashboard</a>
        <a href="admin_logout.php">Logout</a>
    </div>
</div>

<h2>All Donations</h2>

<table>
    <tr>
        <th>User</th>
        <th>Temple</th>
        <th>Donor Name</th>
        <th>Amount (Rs.)</th>
        <th>Payment Method</th>
        <th>Date & Time</th>
    </tr>

    <?php while($row = mysqli_fetch_assoc($result)) { ?>
    <tr>
        <td><?php echo htmlspecialchars($row['user_name']); ?></td>
        <td><?php echo htmlspecialchars($row['temple_name']); ?></td>
        <td><?php echo htmlspecialchars($row['donor_name']); ?></td>
        <td><?php echo htmlspecialchars($row['amount']); ?></td>
        <td><?php echo htmlspecialchars($row['payment_method']); ?></td>
        <td><?php echo htmlspecialchars($row['donated_at']); ?></td>
    </tr>
    <?php } ?>
</table>

</body>
</html>
