<?php
session_start();
include 'config.php';

/* Check login */
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

/* Validate data */
if(!isset($_POST['temple_id']) || !isset($_POST['service_id'])){
    echo "Invalid booking";
    exit();
}

$user_id = $_SESSION['user_id'];
$service_id = $_POST['service_id'];

/* Insert booking */
$query = "INSERT INTO bookings (user_id, service_id, status, booking_date)
          VALUES ('$user_id', '$service_id', 'Pending', NOW())";

mysqli_query($conn, $query);

/* Redirect */
echo "<script>
        alert('Booking Successful');
        window.location='my_bookings.php';
      </script>";
?>
