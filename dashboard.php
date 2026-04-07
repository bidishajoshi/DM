<head>
    <link rel="stylesheet" href="style.css">
</head>
<?php
session_start();
if(!isset($_SESSION['user'])){
    header("Location: login.php");
}
?>

<div class="form-box">
<h2>Welcome to Digital Mandir</h2>

<a href="temples.php" class="btn">View Temples</a><br><br>
<a href="my_bookings.php" class="btn">My Bookings</a><br><br>
<a href="logout.php" class="btn">Logout</a>
</div>
