<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Digital Mandir</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

<?php
session_start();
if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}
?>

<div class="navbar">
    <h2>🛕 Digital Mandir</h2>
    <div>
        <a href="index.php">Home</a>
        <a href="logout.php" class="btn" style="padding: 5px 15px; font-size: 0.8rem;">Logout</a>
    </div>
</div>

<div class="container1">
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION['user']); ?>!</h2>
    
    <div class="cards">
        <div class="card">
            <i class="fas fa-gopuram" style="font-size: 2.5rem; color: var(--primary); margin-bottom: 1rem;"></i>
            <h3>Explore Temples</h3>
            <p>Discover divine destinations and their unique services.</p>
            <br>
            <a href="temples.php" class="btn">View Temples</a>
        </div>

        <div class="card">
            <i class="fas fa-calendar-check" style="font-size: 2.5rem; color: var(--primary); margin-bottom: 1rem;"></i>
            <h3>My Bookings</h3>
            <p>Check the status of your upcoming and past temple services.</p>
            <br>
            <a href="my_bookings.php" class="btn">My Bookings</a>
        </div>

        <div class="card">
            <i class="fas fa-hands-helping" style="font-size: 2.5rem; color: var(--primary); margin-bottom: 1rem;"></i>
            <h3>Services</h3>
            <p>Access various religious services and offerings online.</p>
            <br>
            <a href="services.php" class="btn">View Services</a>
        </div>
    </div>
</div>

<div class="footer">
    © 2025 Digital Mandir | Final Year BCA Project
</div>

</body>
</html>

