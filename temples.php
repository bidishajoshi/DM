<?php
session_start();
include 'config.php';

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

$result = mysqli_query($conn, "SELECT * FROM temples");
?>

<!DOCTYPE html>
<html data-theme="light">
<head>
    <title>Available Temples</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>


<div class="navbar">
    <h2>🛕 Digital Mandir</h2>
    <div>
        <a href="my_bookings.php">My Bookings</a>
        <a href="logout.php">Logout</a>
        <button class="theme-toggle" title="Switch to Dark Mode"></button>
    </div>
</div>

<script src="theme-toggle.js"></script>

<div class="container">
    <h2>Available Temples</h2>
    
<div class="cards">
        <?php
        if(mysqli_num_rows($result) > 0){
            while($row = mysqli_fetch_assoc($result)){
                echo "<div class='temple-card'>";
                echo "<h2>".$row['name']."</h2>";
                echo "<p class='location'>".$row['location']."</p>";
                echo "<p>".$row['description']."</p>";
                echo "<a class='btn' href='services.php?temple_id=".$row['temple_id']."'>Book Service</a>&nbsp;&nbsp;";
               echo "<a class='btn donate-btn' href='donate.php?temple_id=".$row['temple_id']."'>
             Donate💰
          </a>";
          
                echo "</div>";
            }
        } else {
            echo "<p>No temples available.</p>";
        }
        ?>
    </div>
</div>


</body>
</html>
