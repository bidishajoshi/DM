<?php
session_start();
include 'config.php';

/* Check login */
if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

/* Check temple */
if(!isset($_GET['temple_id'])){
    echo "Invalid booking";
    exit();
}

$temple_id = $_GET['temple_id'];

/* Fetch services */
$services = mysqli_query(
    $conn,
    "SELECT * FROM services WHERE temple_id='$temple_id'"
);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Select Service</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="navbar">
    <h2>🛕 Select Service</h2>
</div>

<div class="container">

<form method="post" action="booking.php">
    <input type="hidden" name="temple_id" value="<?php echo $temple_id; ?>">

<?php
if(mysqli_num_rows($services) > 0){
    while($row = mysqli_fetch_assoc($services)){
?>
    <div class="card">
        <input type="radio" name="service_id"
               value="<?php echo $row['service_id']; ?>" required>
        <strong><?php echo $row['service_name']; ?></strong><br>
        Price: Rs. <?php echo $row['price']; ?>
    </div>
<?php
    }
?>
    <br>
    <button type="submit" class="btn">Confirm Booking</button>

<?php
} else {
    echo "<p>No services available for this temple.</p>";
}
?>

</form>

</div>
</body>
</html>
